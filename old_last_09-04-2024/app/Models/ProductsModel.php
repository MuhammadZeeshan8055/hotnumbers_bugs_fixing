<?php

namespace App\Models;
use CodeIgniter\Model;


class ProductsModel extends Model {


    protected $table = 'tbl_products';


    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_data($select='*',$query='limit 10',$return = false)
    {
        $query = $this->db->query("SELECT $select FROM ".$this->table." $query");
        if($return) {
            return $query;
        }else {
            return $query->getResult();
        }

    }

    public function get_products($fields='*',$query='') {
        return $this->get_all_data($fields,"where status='publish' $query");
    }

    public function get_shop_categories($fields='c.*',$status=1,$query='',$user_role_filter=0) {

        $sql = "SELECT $fields FROM tbl_categories AS c ";

        if($status) {
            $sql .= " JOIN tbl_product_categories AS pc ON pc.category_id=c.id JOIN tbl_products AS p ON p.id=pc.product_id ";
        }

        if($user_role_filter) {
            $userModel = model('UserModel');
            $curr_user_role = current_user_role();
            $role_id = $curr_user_role['id'];
            $user_role_metas = $userModel->get_role_meta($role_id);

            $role_cats = '';
            foreach($user_role_metas as $role_meta) {
                $k = $role_meta->meta_key;
                $v = $role_meta->meta_value;
                if($k === "user_role_categories") {
                    $role_cats = $v;
                    break;
                }
            }

            if(!empty($role_cats)) {
                foreach($user_role_metas as $role_meta) {
                    $k = $role_meta->meta_key;
                    $v = $role_meta->meta_value;
                    if($k === "user_role_categories_mode") {
                        if($v === "show") {
                            $sql .= " LEFT JOIN tbl_user_role_meta AS usr_role ON FIND_IN_SET(c.id,usr_role.meta_value)";
                            $query .= " AND c.id IN ($role_cats)";
                        }
                        if($v === "hide") {
                            $sql .= " LEFT JOIN tbl_user_role_meta AS usr_role ON FIND_IN_SET(c.id,usr_role.meta_value)";
                            $query .= " AND c.id NOT IN ($role_cats)";
                        }
                    }
                }
            }


        }

        $sql .= " where 1=1 ";

        $sql .= " AND c.group_name='product_cat' AND c.status=$status $query GROUP BY c.id ORDER BY c.sort_order";

        $fetch_category= $this->db->query($sql);
        return $fetch_category->getResult();
    }

    public function product_by_category_slug($category_slug,$fields='*',$status='publish',$extraQuery='') {
        $post_status = $status != 'any' ? "product.status='$status'" : '';

        $sql = "SELECT $fields FROM ".$this->table." AS product 
       JOIN tbl_product_categories AS pc ON pc.product_id=product.id
       JOIN tbl_categories AS cat ON cat.id=pc.category_id
       LEFT JOIN tbl_product_variations AS variation ON variation.product_id=product.id
       WHERE cat.slug='$category_slug' AND $post_status ";

        if($status == "publish") {
            $sql .= " AND ((product.stock_managed=1 AND product.stock > 0) OR product.stock_status!='outofstock')";
        }
        $sql .= " GROUP BY product.id ";

        $sql .= " $extraQuery ";

        $query = $this->db->query($sql);
        return $query->getResult();
    }

    public function get_category_by_id($cat_id=0,$status=1) {
        $_status = $status != 'any' ? "AND status=$status" : '';
        $fetch_category= $this->db->query("SELECT * FROM tbl_categories where id='$cat_id' $_status");
        return $fetch_category->getRow();
    }

    public function product_categories($pid,$fields='category.*',$status=1) {
        $_status = $status != 'any' ? "AND category.status=$status" : '';
        $fetch_category= $this->db->query("SELECT $fields FROM tbl_categories AS category JOIN tbl_product_categories AS pc ON pc.category_id=category.id where pc.product_id=$pid $_status")->getResultArray();
        return $fetch_category;
    }

    public function category_products($cid,$status=1,$query='') {
        $_status = $status != 'any' ? "AND category.status=$status" : '';

        if($status !== "any") {
            $query = " AND (prod.stock_managed=1 AND stock_status='instock') ".$query;
        }

        $sql = "SELECT category.*,prod.* FROM tbl_categories AS category JOIN tbl_product_categories AS pc ON pc.category_id=category.id JOIN tbl_products AS prod ON prod.id=pc.product_id where pc.category_id=$cid $_status $query";

        $fetch_category = $this->db->query($sql)->getResultArray();

        return $fetch_category;
    }

    public function get_attributes($pid) {
        $product = $this->product_by_id($pid,'*','any');
        $attrs = [];
        if(!empty($product->attributes)) {
            $attrs = json_decode($product->attributes,true);
        }
        return $attrs;
    }

    public function products_by_category($cid,$query='',$fields='p.*', $filter_by_stock_status=0) {
        $fields .= ',p.id,p.stock_managed,p.stock_status,p.stock';

        $fields = trim($fields,',');

        $q = "SELECT $fields FROM tbl_categories AS category JOIN tbl_product_categories AS pc ON pc.category_id=category.id JOIN tbl_products AS p ON p.id=pc.product_id WHERE category.id='$cid' $query GROUP BY p.id";

        $fetch_query= $this->db->query($q)->getResultArray();

        $prod_list = [];

        if($filter_by_stock_status) {
            if (!empty($fetch_query)) {
                foreach($fetch_query as $prod) {
                    if($prod['stock_managed'] === 'yes') {
                        if($prod['stock_status'] === 'instock' && $prod['stock'] > 0) {
                            $prod_list[] = $prod;
                        }
                    } else {
                        $prod_list[] = $prod;
                    }
                }
            }
        }else {
            $prod_list = $fetch_query;
        }

        return $prod_list;
    }

    public function products_by_category_slug($slug,$query='') {
        $fetch_query= $this->db->query("SELECT p.* FROM tbl_categories AS category JOIN tbl_product_categories AS pc ON pc.category_id=category.id JOIN tbl_products AS p ON p.id=pc.product_id WHERE category.slug='$slug' $query GROUP BY p.id")->getResultArray();
        return $fetch_query;
    }

    public function subscription_products($query='',$subscription=1) {
        $fetch_query= $this->db->query("SELECT p.* FROM tbl_categories AS category JOIN tbl_product_categories AS pc ON pc.category_id=category.id JOIN tbl_products AS p ON p.id=pc.product_id WHERE p.subscription='$subscription' $query GROUP BY p.id")->getResultArray();
        return $fetch_query;
    }

    public function product_by_slug($product_slug,$fields='*',$status='publish',$extraQuery='',$multiple=false) {

        $slugs = '';
        foreach(explode(',',$product_slug) as $slug) {
            $slugs .= "slug='$slug' OR ";
        }
        $slugs = trim($slugs," OR ");

        $sql = "SELECT $fields FROM tbl_products WHERE $slugs";

        if($status != 'any') {
            $sql .= " AND status='$status'";
        }

        $sql .= $extraQuery;

        $query = $this->db->query($sql);
        if(!$multiple){
            return $query->getRow();
        }else {
            return $query->getResultArray();
        }
    }

    public function product_variation($product_id=0,$where=[]) {

        $sql = "SELECT id,variation FROM tbl_product_variations WHERE product_id='$product_id' ";
        //$product = $this->product_by_id($product_id,'price','any');
        $row = $this->db->query($sql)->getRow();

        if(!empty($row)) {
            $values = [];
            if(!empty($where)) {
                $variations = json_decode($row->variation, true);
                foreach($variations as $variation) {
                    if(!empty($variation['keys'])) {
                        $var_keys = $variation['keys'];
                        $var_keys = array_filter($var_keys);
                        if(!array_diff($var_keys,$where)) {
                            $values = $variation;
                        }
                    }
                }
            }

            if(!empty($values)) {
                $values['id'] = $row->id;
            }
        }

        return $values;
    }

    public function product_variations($product_id=0) {
        $sql = "SELECT variation.id,variation.variation,product.attributes,JSON_UNQUOTE(JSON_EXTRACT(variation,'$.stock_quantity')) FROM tbl_product_variations AS variation JOIN tbl_products AS product ON product.id=variation.product_id WHERE product.id='$product_id'";

        $res = [];
        $results = $this->db->query($sql)->getResultArray();

        //$product = $this->product_by_id($product_id,'price','any');
        $output = [];
        if(!empty($results)) {
            $keys = [];
            foreach($results as $result) {
                $attributes = !empty($result['attributes']) ? json_decode($result['attributes'], true) : [];
                $variations = !empty($result['variation']) ? json_decode($result['variation'], true) : [];
                if(!empty($attributes)) {

                    foreach($attributes as $attribute) {
                        $attr_id = strtolower($attribute['label']);
                        $attr_id = str_replace(' ','-',$attr_id);
                        $attr_id = 'attribute_'.$attr_id;
                        if(!empty($attribute['attribute_variation'])) {
                            $keys[] = $attr_id;
                        }
                    }
                }

                $new_keys = [];
                foreach($keys as $k) {
                    $new_keys[$k]='';
                }
                if(!empty($variations)) {
                    foreach($variations as $variation) {
                        if(!empty($variation['keys'])) {
                            $var_keys = !empty($variation['keys']) ? $variation['keys'] : '';
                            $values = !empty($variation['values']) ? $variation['values'] : '';
                            foreach($var_keys as $k=>$v) {
                                $new_keys[$k]=$v;
                            }
                            $output[] = [
                                'keys' => $new_keys,
                                'values' => $values,
                            ];
                        }

                    }
                }

            }
        }

        return $output;
    }

    public function product_reduced_price($price='') {
        $value = $price;
        if($price) {
            $global_rate = get_setting('product_reduce_price',true);
            if(!empty($global_rate['price'])) {
                if($global_rate['type'] === "percent") {
                    $value = percent_reduce($price, $global_rate['price']);
                }else {
                    $value = $price - $global_rate['price'];
                }
            }
            else {

            }
        }
        return $value;
    }

    public function product_price($product_id=0) {
        $product = $this->product_by_id($product_id,'price','any');

        $prices = [];
        $price = 0;
        if(!empty($product)) {
            $price = $product->price;
            $variations = $this->product_variations($product_id);
            if(!empty($variations)) {
                foreach($variations as $variation) {
                    foreach($variation as $v) {
                        if(!empty($v['regular_price'])) {
                            $prices[] = $v['regular_price'];
                        }
                    }
                }
                if(!empty($prices)) {
                    sort($prices);
                    $price = $prices;
                }
            }
        }

        return $price;
    }

    public function product_variation_by_id($variation_id=0) {
        $sql = "SELECT * FROM tbl_product_variations WHERE id='$variation_id'";
        return $this->db->query($sql);
    }

    public function product_by_id($product_id,$fields='*',$status='publish') {
        $sql = "SELECT $fields FROM tbl_products WHERE id='{$product_id}'";
        if($status != 'any') {
            $sql .= " AND status='$status'";
        }
        $query = $this->db->query($sql);
        return $query->getRow();
    }

    public function product_availability($product_id=0) {
        $product = $product = $this->product_by_id($product_id,'status,stock,stock_managed','any');
        $availability = true;
        if($product->status !== "publish") {
            $availability = false;
        }

        if(!empty($product->stock_managed) && ($product->stock_managed === 'yes' || $product->stock_managed == 1) && !$product->stock) {
            $availability = false;
        }
        return $availability;
    }

    public function getCouponByID($id) {
        $master = model('MasterModel');
        if($id) {
            $coupon = $master->getRow("tbl_coupons",['id'=>$id]);
            if($coupon) {
                return $coupon;
            }
        }
    }

    public function getCouponByCode($code) {
        $master = model('MasterModel');
        if($code) {
            $coupon = $master->getRow("tbl_coupons",['code'=>$code]);
            if($coupon) {
                return $coupon;
            }
        }
    }

    public function reduce_stock($product_id, $stock_deduct=0) {
        $master = model('MasterModel');
        $master->query("UPDATE tbl_products SET stock = stock-$stock_deduct WHERE id='$product_id' AND stock_managed=1 AND stock_status='instock'");
        $get_prod = $master->query("SELECT stock, stock_status FROM tbl_products WHERE id='$product_id'",true,true);
        if(!empty($get_prod)) {
            if($get_prod['stock'] == 0) {
                $master->query("UPDATE tbl_products SET stock_status = 'outofstock' WHERE id='$product_id' AND stock_managed=1");
            }
        }
    }

    public function in_stock($product_id, $filter_by_user_role=0) {
        $product = $this->product_by_id($product_id);
        $allowed = false;
        if(!empty($product)) {
            if($product->stock_managed === 'yes' || $product->stock_managed == 1) {
                if($product->stock_status == 'instock' && $product->stock > 0) {
                    $allowed = true;
                }
            }
            elseif($product->stock_managed == 'no' || $product->stock_managed == 0) {
                $allowed = true;
            }

            if($allowed) {
                if($filter_by_user_role) {
                    $cats = $this->product_categories($product_id);
                    $has_allowed = false;
                    foreach($cats as $cat) {
                        $allowed_cat = $this->role_category_permission($cat['id']);
                        if($allowed_cat) {
                            $has_allowed = true;
                            break;
                        }
                    }
                    if($has_allowed) {
                        $allowed = $this->role_product_permission($product_id);
                    }else {
                        $allowed = false;
                    }
                }
            }
        }

        return $allowed;
    }

    public function change_status($product_id, $status) {
        $master = model('MasterModel');
        $master->query("UPDATE tbl_products SET status = '$status' WHERE id='$product_id'");
    }

    public function add_sale($product_id, $sale_count=0) {
        $master = model('MasterModel');
        $master->query("UPDATE tbl_products SET total_sales = total_sales+$sale_count WHERE id='$product_id' AND stock_managed=1 AND stock_status='instock'");
    }

    public function annual_stats($time='') {
        $start_time = date('Y-m-d H:i:s', strtotime("-".$time));

        $master = model('MasterModel');
        $db_records = $master->query("SELECT o.order_date, item.product_name, o.order_id, item.order_item_id FROM tbl_orders AS o JOIN tbl_order_items AS item ON item.order_item_id=o.order_id WHERE item.item_type='line_item' AND o.order_date >= '$start_time' GROUP BY o.order_id ORDER BY o.order_date");

        $output = [];

        foreach($db_records as $record) {

            $year = date('Y',strtotime($record->order_date));
            $month =  date('m',strtotime($record->order_date));
            $output[$year][$month][] = $record;
        }

        return $output;
    }

    public function user_role_products($role_id=0) {
        $master = model('MasterModel');
        $sql = "SELECT role.id AS role_id,meta.meta_key,role.role,role.name,meta.meta_value, (SELECT meta_value FROM tbl_user_role_meta WHERE meta_key='user_role_products_mode' AND role_id=meta.role_id) AS role_products_mode FROM tbl_user_role_meta AS meta JOIN tbl_user_roles AS role ON role.id=meta.role_id WHERE meta_key='user_role_products'";
        if($role_id) {
            $sql .= " AND role.id=$role_id";
            return $master->query($sql,true,true);
        }else {
            return $master->query($sql,true);
        }

    }

    public function user_role_categories($role_id=0, $catID=0) {
        $master = model('MasterModel');
        $sql = "SELECT role.id AS role_id,meta.meta_key,role.role,role.name,meta.meta_value, (SELECT meta_value FROM tbl_user_role_meta WHERE meta_key='user_role_categories_mode' AND role_id=meta.role_id) AS role_category_mode FROM tbl_user_role_meta AS meta JOIN tbl_user_roles AS role ON role.id=meta.role_id WHERE meta_key='user_role_categories'";

        if($catID) {
            $sql .= " AND FIND_IN_SET($catID,meta.meta_value)";
        }

        if($role_id) {
            $sql .= " AND role.id=$role_id";
            return $master->query($sql,true,true);
        }else {
            return $master->query($sql,true);
        }

    }

    public function role_category_permission($cat_id=0) {
        $role = current_user_role();
        $is_allowed = 1;
        if($cat_id) {
            $get_user_role_perms = $this->user_role_categories($role['id']);

            if(!empty($get_user_role_perms)) {
                $meta_value = !empty($get_user_role_perms['meta_value']) ? explode(',',$get_user_role_perms['meta_value']) : [];
                $show_mode = $get_user_role_perms['role_category_mode'];

                if($show_mode === "show" && !in_array($cat_id,$meta_value)) {
                    $is_allowed = 0;
                }
                if($show_mode === "hide" && in_array($cat_id,$meta_value)) {
                    $is_allowed = 0;
                }
            }
        }

        return $is_allowed;
    }

    public function role_product_permission($prod_id=0) {
        $role = current_user_role();
        $is_allowed = 1;
        if($prod_id) {
            $get_user_role_perms = $this->user_role_products($role['id']);
            if(!empty($get_user_role_perms)) {
                $meta_value = !empty($get_user_role_perms['meta_value']) ? explode(',',$get_user_role_perms['meta_value']) : [];
                $show_mode = $get_user_role_perms['role_products_mode'];
                if($show_mode === "show" && !in_array($prod_id,$meta_value)) {
                    $is_allowed = 0;
                }
                if($show_mode === "hide" && in_array($prod_id,$meta_value)) {
                    $is_allowed = 0;
                }
            }
        }
        return $is_allowed;
    }

    public function get_sku($prod_id=0, $get_variation=[]) {
        $product = $this->product_by_id($prod_id);
        $sku = '';

        if(!empty($product)) {
            $sku = $product->sku;
            if(!empty($get_variation)) {
                $sku = '';
                $variation = $this->product_variation($prod_id, $get_variation);
                if(!empty($variation['values']['sku'])) {
                    $sku = $variation['values']['sku'];
                }
            }
        }


        return $sku;
    }

}

