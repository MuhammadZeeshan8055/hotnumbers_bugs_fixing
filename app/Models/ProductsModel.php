<?php

namespace App\Models;
use CodeIgniter\Model;


class ProductsModel extends Model {


    protected $table = 'tbl_products';


    public function __construct()
    {
        parent::__construct();

        $this->low_stock_email();
        $this->zero_stock_email();
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
            $sql .= " LEFT JOIN tbl_product_categories AS pc ON pc.category_id=c.id LEFT JOIN tbl_products AS p ON p.id=pc.product_id ";
        }

        if($user_role_filter) {

            $curr_user_role = current_user_role();
            $role_id = $curr_user_role['id'];

            $uid = is_logged_in();

            $role_categories = $this->user_role_categories($role_id);
            $role_products = $this->user_role_products($role_id);

            $user_cats_permissions = $this->user_category_permissions($uid);
            $user_prod_permissions = $this->user_product_permissions($uid);

            if(!empty($role_categories)) {
                $cat_ids = $role_categories['meta_value'];
                if($role_categories['role_category_mode'] == 'hide') {
                    $query .= " AND c.id NOT IN ($cat_ids)";
                }
                if($role_categories['role_category_mode'] == 'show') {
                    $query .= " AND c.id IN ($cat_ids)";
                }
            }

            if(!empty($role_products)) {
                $cat_ids = $role_products['meta_value'];
                if($role_products['role_product_mode'] == 'hide') {
                    $query .= " AND p.id NOT IN ($cat_ids)";
                }
                if($role_products['role_product_mode'] == 'show') {
                    $query .= " AND p.id IN ($cat_ids)";
                }
            }

            $disallowed_cats = [];
            $allowed_cats = [];
            if(!empty($user_cats_permissions)) {
                foreach($user_cats_permissions as $permission) {
                    $field = json_decode($permission['value'],true);
                    if($field['permission'] == 'disallow') {
                        $disallowed_cats[] = $field['category_id'];
                    }
                    if($field['permission'] == 'allow') {
                        $allowed_cats[] = $field['category_id'];
                    }
                }
                if(!empty($disallowed_cats)) {
                    $disallowed_cats_ = implode(',',$disallowed_cats);
                    $query .= " AND c.id NOT IN ($disallowed_cats_)";
                }
                if(!empty($allowed_cats)) {
                    $allowed_cats_ = implode(',',$allowed_cats);
                    $query .= " AND c.id IN ($allowed_cats_)";
                }
            }

            if(!empty($user_prod_permissions)) {
                $allowed_prods = [];
                $disallowed_prods = [];
                foreach($user_prod_permissions as $perm) {
                    $field = json_decode($perm['value'],true);
                    if($field['permission'] == 'disallow') {
                        $disallowed_prods[] = $field['product_id'];
                    }
                    if($field['permission'] == 'allow') {
                        $allowed_prods[] = $field['product_id'];
                    }
                }
                if(!empty($disallowed_prods)) {
                    $disallowed_prods_ = implode(',',$disallowed_prods);
                    $query .= " AND p.id NOT IN ($disallowed_prods_)";
                }
                if(!empty($allowed_prods)) {
                    $allowed_prods_ = implode(',',$allowed_prods);
                    $query .= " AND p.id IN ($allowed_prods_)";
                }
            }

            $uid = is_logged_in();

            if($uid) {
                $user_cats_permissions = $this->user_category_permissions($uid);
                $disallowed_cats = [];
                $allowed_cats = [];
                foreach($user_cats_permissions as $permission) {
                    $field = json_decode($permission['value'],true);
                    if($field['permission'] == 'disallow') {
                        $disallowed_cats[] = $field['category_id'];
                    }
                    if($field['permission'] == 'allow') {
                        $allowed_cats[] = $field['category_id'];
                    }
                }
                if(!empty($disallowed_cats)) {
                    $disallowed_cats_ = implode(',',$disallowed_cats);
                    $query .= " AND c.id NOT IN ($disallowed_cats_)";
                }
                if(!empty($allowed_cats)) {
                    $allowed_cats_ = implode(',',$allowed_cats);
                    $query .= " AND c.id IN ($allowed_cats_)";
                }

            }

        }



        $sql .= " where 1=1 ";

        $sql .= " AND c.group_name='product_cat' AND c.status=$status $query GROUP BY c.id ORDER BY c.sort_order";

        $fetch_category= $this->db->query($sql);

        $shop_result = $fetch_category->getResult();

        return $shop_result;
    }

    public function product_by_category_slug($category_slug,$fields='*',$status='publish',$extraQuery='') {
        $post_status = $status != 'any' ? "product.status='$status'" : '';

        $sql = "SELECT $fields FROM ".$this->table." AS product 
       LEFT JOIN tbl_product_categories AS pc ON pc.product_id=product.id
       LEFT JOIN tbl_categories AS cat ON cat.id=pc.category_id
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

    public function products_by_category($cid,$query='',$fields='p.*', $filter_by_stock_status=0, $filter_by_user=0) {

        $stock_available = " IF(((p.stock_managed = 'yes' OR p.stock_managed=1) AND stock_status='instock' AND stock > 0), 1, 0)";

        $fields .= ',p.id,p.stock_managed,p.stock_status,p.stock';

        if($filter_by_stock_status) {
            $query .= " AND ($stock_available = 1 OR (p.stock_managed != 'yes'))";
        }

        if($filter_by_user) {
            $uid = is_logged_in();

            $curr_user_role = current_user_role();
            $role_id = $curr_user_role['id'];

                $role_categories = $this->user_role_categories($role_id);
                $role_products = $this->user_role_products($role_id);

                $user_cats_permissions = $this->user_category_permissions($uid);
                $user_prod_permissions = $this->user_product_permissions($uid);


            if(!empty($role_categories)) {
                $cat_ids = $role_categories['meta_value'];
                if($role_categories['role_category_mode'] == 'hide') {
                    $query .= " AND category.id NOT IN ($cat_ids)";
                }
                if($role_categories['role_category_mode'] == 'show') {
                    $query .= " AND category.id IN ($cat_ids)";
                }
            }

            if(!empty($role_products)) {
                $cat_ids = $role_products['meta_value'];
                if($role_products['role_product_mode'] == 'hide') {
                    $query .= " AND p.id NOT IN ($cat_ids)";
                }
                if($role_products['role_product_mode'] == 'show') {
                    $query .= " AND p.id IN ($cat_ids)";
                }
            }

            $disallowed_cats = [];
            $allowed_cats = [];
            if(!empty($user_cats_permissions)) {

            foreach($user_cats_permissions as $permission) {
                $field = json_decode($permission['value'],true);
                if($field['permission'] == 'disallow') {
                    $disallowed_cats[] = $field['category_id'];
                }
                if($field['permission'] == 'allow') {
                    $allowed_cats[] = $field['category_id'];
                }
            }
            if(!empty($disallowed_cats)) {
                $disallowed_cats_ = implode(',',$disallowed_cats);
                $query .= " AND category.id NOT IN ($disallowed_cats_)";
            }
            if(!empty($allowed_cats)) {
                $allowed_cats_ = implode(',',$allowed_cats);
                $query .= " AND category.id IN ($allowed_cats_)";
            }
            }

            if(!empty($user_prod_permissions)) {
            $allowed_prods = [];
            $disallowed_prods = [];
            foreach($user_prod_permissions as $perm) {
                $field = json_decode($perm['value'],true);
                if($field['permission'] == 'disallow') {
                    $disallowed_prods[] = $field['product_id'];
                }
                if($field['permission'] == 'allow') {
                    $allowed_prods[] = $field['product_id'];
                }
            }
            if(!empty($disallowed_prods)) {
                $disallowed_prods_ = implode(',',$disallowed_prods);
                $query .= " AND p.id NOT IN ($disallowed_prods_)";
            }
            if(!empty($allowed_prods)) {
                $allowed_prods_ = implode(',',$allowed_prods);
                $query .= " AND p.id IN ($allowed_prods_)";
            }
            }

        }


        $fields = trim($fields,',');

        $q = "SELECT $fields FROM tbl_categories AS category JOIN tbl_product_categories AS pc ON pc.category_id=category.id JOIN tbl_products AS p ON p.id=pc.product_id WHERE category.id='$cid' $query  GROUP BY p.id";

        $fetch_query= $this->db->query($q)->getResultArray();

        $prod_list = $fetch_query;

        return $prod_list;
    }

    public function products_by_category_slug($slug,$query='', $fields='p.*') {
        $fields .= ',p.id';
        $fetch_query= $this->db->query("SELECT $fields FROM tbl_categories AS category JOIN tbl_product_categories AS pc ON pc.category_id=category.id JOIN tbl_products AS p ON p.id=pc.product_id WHERE category.slug='$slug' $query GROUP BY p.id")->getResultArray();
        return $fetch_query;
    }

    public function subscription_products($query='',$subscription=1) {
        $fetch_query= $this->db->query("SELECT p.* FROM tbl_categories AS category JOIN tbl_product_categories AS pc ON pc.category_id=category.id JOIN tbl_products AS p ON p.id=pc.product_id WHERE p.subscription='$subscription' $query GROUP BY p.id")->getResultArray();
        return $fetch_query;
    }

    public function product_by_slug($product_slug,$fields='product.*',$status='publish',$extraQuery='',$multiple=false, $filter_by_user=0) {

        $slugs = '';
        if($fields) {
            $fields .= ",product.type";
        }
        foreach(explode(',',$product_slug) as $slug) {
            $slugs .= "product.slug='$slug' OR ";
        }
        $slugs = trim($slugs," OR ");

        $sql = "SELECT $fields FROM tbl_products AS product LEFT JOIN tbl_product_categories AS pc ON pc.product_id=product.id WHERE $slugs";

        if($status != 'any') {
            $sql .= " AND product.status='$status'";
        }

        if($filter_by_user) {
            $uid = is_logged_in();

            $curr_user_role = current_user_role();
            $role_id = $curr_user_role['id'];

            $role_categories = $this->user_role_categories($role_id);
            $role_products = $this->user_role_products($role_id);

            if(!empty($role_categories)) {
                $cat_ids = $role_categories['meta_value'];
                if($role_categories['role_category_mode'] == 'hide') {
                    $sql .= " AND pc.category_id NOT IN ($cat_ids)";
                }
                if($role_categories['role_category_mode'] == 'show') {
                    $sql .= " AND pc.category_id IN ($cat_ids)";
                }
            }

            if(!empty($role_products)) {
                $cat_ids = $role_products['meta_value'];
                if($role_products['role_product_mode'] == 'hide') {
                    $sql .= " AND product.id NOT IN ($cat_ids)";
                }
                if($role_products['role_product_mode'] == 'show') {
                    $sql .= " AND product.id IN ($cat_ids)";
                }
            }

            $user_cats_permissions = $this->user_category_permissions($uid);
            $user_prod_permissions = $this->user_product_permissions($uid);

            $disallowed_cats = [];
            $allowed_cats = [];
            if(!empty($user_cats_permissions)) {
                foreach($user_cats_permissions as $permission) {
                    $field = json_decode($permission['value'],true);
                    if($field['permission'] == 'disallow') {
                        $disallowed_cats[] = $field['category_id'];
                    }
                    if($field['permission'] == 'allow') {
                        $allowed_cats[] = $field['category_id'];
                    }
                }
                if(!empty($disallowed_cats)) {
                    $disallowed_cats_ = implode(',',$disallowed_cats);
                    $sql .= " AND pc.category_id NOT IN ($disallowed_cats_)";
                }
                if(!empty($allowed_cats)) {
                    $allowed_cats_ = implode(',',$allowed_cats);
                    $sql .= " AND pc.category_id IN ($allowed_cats_)";
                }
            }

            if(!empty($user_prod_permissions)) {
                $allowed_prods = [];
                $disallowed_prods = [];
                foreach($user_prod_permissions as $perm) {
                    $field = json_decode($perm['value'],true);
                    if($field['permission'] == 'disallow') {
                        $disallowed_prods[] = $field['product_id'];
                    }
                    if($field['permission'] == 'allow') {
                        $allowed_prods[] = $field['product_id'];
                    }
                }
                if(!empty($disallowed_prods)) {
                    $disallowed_prods_ = implode(',',$disallowed_prods);
                    $sql .= " AND product.id NOT IN ($disallowed_prods_)";
                }
                if(!empty($allowed_prods)) {
                    $allowed_prods_ = implode(',',$allowed_prods);
                    $sql .= " AND product.id IN ($allowed_prods_)";
                }
            }
        }


        $sql .= $extraQuery;

        $query = $this->db->query($sql);

        if(!$multiple){
            $row = $query->getRow();
            if(!empty($row)) {
                $row->variations = [];
                if($row->type == 'variable') {
                    $row->variations = $this->product_variations($row->id);
                }

                return $row;
            }

        }else {
            return $query->getResultArray();
        }
    }

    public function product_variation($product_id=0,$where=[],$filter_price=1) {

        $sql = "SELECT id,variation FROM tbl_product_variations WHERE product_id='$product_id' ";
        //$product = $this->product_by_id($product_id,'price','any');
        $row = $this->db->query($sql)->getRow();

        if(!empty($row)) {
            $values = [];
            if(!empty($where)) {
                if(!empty($where['sizes'])) {
                    $where['sizes'] = implode(', ',$where['sizes']); //temp fix
                }
                $variations = json_decode($row->variation, true);

                foreach($variations as $variation) {
                    if(!empty($variation['keys'])) {
                        if($filter_price && !strlen(trim($variation['values']['regular_price']))) {
                            continue;
                        }
                        $var_keys = $variation['keys'];
                        $var_keys = array_filter($var_keys);

                        foreach(array_keys($variation['keys']) as $k) {
                            $v = $variation['keys'][$k];
                            if(empty($v)) {
                                $var_keys[$k] = $where[$k];     //Dummy fill to match Any Key
                            }
                        }

                        if($var_keys == $where) {
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

    public function product_variations($product_id=0, $price_filter=true) {
        $sql = "SELECT variation.id,variation.variation,product.attributes FROM tbl_product_variations AS variation JOIN tbl_products AS product ON product.id=variation.product_id WHERE product.id='$product_id'";

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

                            if($price_filter && !strlen($values['regular_price'])) {
                                continue;
                            }

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

        //pr($output);

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

    public function product_price($product_id=0, $variation = []) {
        $product = $this->product_by_id($product_id,'price,type','any');

        $prices = [];
        $price = '';
        if(!empty($product)) {
            if($product->type == "variable") {
                if(empty($variation)) {
                    $variations = $this->product_variations($product_id);
                    if (!empty($variations)) {
                        foreach ($variations as $variation) {
                            foreach ($variation as $v) {
                                if (!empty($v['sale_price'])) {
                                    $prices[] = $v['sale_price'];
                                }else if (!empty($v['regular_price'])) {
                                    $prices[] = $v['regular_price'];
                                }
                            }
                        }

                        if (!empty($prices)) {
                            sort($prices);
                            $price = $prices;
                        }
                    }
                }
            }else {
                $price = $product->price;
            }
        }

        return $price;
    }

    public function product_variation_by_id($variation_id=0) {
        $sql = "SELECT * FROM tbl_product_variations WHERE id='$variation_id'";
        return $this->db->query($sql);
    }

    public function stock_availability($product_id=0, $quantity=0, $variations=[]) {
        $error = '';
        $prod_variations = false;
         $product = $this->product_by_id($product_id);

        if(!empty($variations) && $product->type == "variable") {
            $prod_variations = $this->product_variation($product_id, $variations);
            if(!empty($prod_variations['values'])) {
                $variation_vals = $prod_variations['values'];
                if(!empty($variation_vals['manage_stock']) && $variation_vals['manage_stock']==="yes") {
                    
                    if(empty($variation_vals['stock']) || $variation_vals['stock_status'] === "outofstock") {
                        $error = "Out of stock";
                    }
                    else if($quantity > $variation_vals['stock']) {
                        $error = "Quantity is more than available stock";
                    }
                }
            }
        }else {
            if($product->stock_managed && $product->stock_managed == 'yes') {
                if(!$product->stock) {
                    $error = 'Product is out of stock';
                }else if($quantity > $product->stock) {
                    $error = 'Product quantity is more than available stock';
                }
            }
        }

        return ['error'=>$error, 'success'=>empty($error), 'variations'=>$prod_variations];
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
        $master->query("UPDATE tbl_products SET stock = stock-$stock_deduct WHERE id='$product_id' AND (stock_managed=1 OR stock_managed='yes')");
        $get_prod = $master->query("SELECT stock, stock_status FROM tbl_products WHERE id='$product_id'",true,true);
        if(!empty($get_prod)) {
            if($get_prod['stock'] == 0) {
                $master->query("UPDATE tbl_products SET stock_status = 'outofstock' WHERE id='$product_id' AND (stock_managed=1 OR stock_managed='yes')");
            }
        }
    }

    public function gain_stock($product_id, $stock_gain=0) {
        $master = model('MasterModel');
        $master->query("UPDATE tbl_products SET stock = stock+$stock_gain WHERE id='$product_id'");

        $master->query("UPDATE tbl_products SET stock_status = 'instock' WHERE id='$product_id' AND (stock_managed=1 OR stock_managed='yes')");
    }

    /**Returns tax amount applied to product*/
    public function tax_price($product, $variation=[]) {
        $tax_price = 0;
        $price = !empty($product->sale_price) ? $product->sale_price : $product->price;
        if($product->tax_class === "Standard") {
            $product->tax_class = 'standard'; //correction fix
        }
        $tax_class = $product->tax_class.'_tax_rate';

        if(!empty($variation['values'])) {
            $var_values = $variation['values'];
            $price = !empty($var_values['sale_price']) ? $var_values['sale_price'] : $var_values['regular_price'];
            $tax_class = !empty($var_values['tax_class']) ? $var_values['tax_class'] : $tax_class;
        }

        if(get_setting('price_with_tax') === "exclusive") {
            $tax_class_data = get_tax_price([],$tax_class);
            if(!empty($tax_class_data)) {
                if($tax_class_data['type'] == 'percent') {
                    $tax_price = percent_increase($price, $tax_class_data['amount'], true);
                }else {
                    $tax_price = $tax_class_data['amount'];
                }
            }
        }

        return $tax_price;
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
                    if(!empty($cats)) {
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
        }

        return $allowed;
    }

    public function change_status($product_id, $status) {
        $master = model('MasterModel');
        $master->query("UPDATE tbl_products SET status = '$status' WHERE id='$product_id'");
    }

    public function add_sale($product_id, $sale_count=0) {
        $master = model('MasterModel');
        $master->query("UPDATE tbl_products SET total_sales = total_sales+$sale_count WHERE id='$product_id'");
    }

    public function sales_stats($time='') {
        $start_time = date('Y-m-d H:i:s', strtotime("-".$time));

        $master = model('MasterModel');
        $query = "SELECT o.order_date, item.product_name, o.order_id, item.order_item_id FROM tbl_orders AS o JOIN tbl_order_items AS item ON item.order_id=o.order_id WHERE item.item_type='line_item' AND o.order_date >= '$start_time' GROUP BY o.order_id ORDER BY o.order_date";
        $db_records = $master->query($query);

        $output = [];

        foreach($db_records as $record) {
            $year = date('Y',strtotime($record->order_date));
            $month =  date('m',strtotime($record->order_date));
            $output[$year][$month][] = $record;
        }

        return $output;
    }



    public function user_role_products($role_id=0, $product_id=0) {
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

        if(isset($role_id)) {
            $sql .= " AND role.id=$role_id";

            $sql .= " GROUP BY role.id";

            return $master->query($sql,true,true);
        }else {
            $sql .= " GROUP BY role.id";
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

    public function user_category_permissions($user_id=0) {
        $masterModel = model('MasterModel');
        if(!$user_id) {
            $permissions = $masterModel->query("SELECT value FROM tbl_settings WHERE title LIKE 'user_cat_visibility%'",true);
        }else {
            $permissions = $masterModel->query("SELECT value FROM tbl_settings WHERE title='user_cat_visibility_$user_id'",true);
        }
        return $permissions;
    }

    public function user_product_permissions($user_id=0) {
        $masterModel = model('MasterModel');
        if(!$user_id) {
            $permissions = $masterModel->query("SELECT value FROM tbl_settings WHERE title LIKE 'user_prod_visibility%'",true);
        }else {
            $permissions = $masterModel->query("SELECT value FROM tbl_settings WHERE title='user_prod_visibility_$user_id'",true);
        }
        return $permissions;
    }

    public function low_stock_email() {
            $masterModel = model("MasterModel");
            $mailModel = model("MailModel");

            $products = $masterModel->query("SELECT id, title, slug, stock,stock_threshold FROM tbl_products WHERE (stock_managed=1 OR stock_managed='yes') AND stock > 0 AND stock_threshold >= stock AND status='publish' AND low_stock_email=0",true);

            if(!empty($products)) {
                foreach($products as $product) {
                    $product_id = $product['id'];

                    $admin_email = get_setting('website.admin_email');

                    $mailbody = $mailModel->get_parsed_content("product_low_stock",[
                        'product_id'=>$product['id'],
                        'product_name'=>$product['title'],
                        'slug'=>$product['slug'],
                        'product_stock'=>intval($product['stock']),
                        'product_threshold'=>$product['stock_threshold']
                    ]);
                    $mailModel->send_email($admin_email, $mailbody);

                    $masterModel->query("UPDATE tbl_products SET low_stock_email=1 WHERE id=$product_id");
                }
            }
    }

    public function zero_stock_email() {

            $masterModel = model("MasterModel");
            $mailModel = model("MailModel");

            $products = $masterModel->query("SELECT id, title, slug, stock,stock_threshold FROM tbl_products WHERE (stock_managed=1 OR stock_managed='yes') AND stock=0 AND stock_threshold >= stock AND status='publish' AND zero_stock_email=0",true);

            if(!empty($products)) {
                foreach($products as $product) {
                    $product_id = $product['id'];

                    $admin_email = get_setting('website.admin_email');

                    $mailbody = $mailModel->get_parsed_content("product_zero_stock", [
                        'product_id' => $product['id'],
                        'product_name' => $product['title'],
                        'slug' => $product['slug'],
                        'product_stock' => intval($product['stock']),
                        'product_threshold' => $product['stock_threshold']
                    ]);
                    $mailModel->send_email($admin_email, $mailbody);

                    $masterModel->query("UPDATE tbl_products SET zero_stock_email=1 WHERE id=$product_id");
                }
            }

    }

    public function admin_product_attribute_body($attribes=[]) {
        $datas = [];
        if (!empty($attribes)) {
            foreach ($attribes as $attribute) {
                $datas[] = $attribute;
            }
        }
        else {
            $datas[0] = [
                'label'=>'',
                'value'=>[],
                'attribute_visibility'=>0,
                'attribute_variation' => 0
            ];
        }

        foreach($datas as $i=>$data) {
            ?>
            <div class="table-box">
                <label>Attributes <?php echo $i ?>
                    <span class="pull-right"><a href="" class="color-base"><i class="lni lni-close"></i></a> </span>
                </label>
                <input type="text" placeholder="Name" name="attributes[<?php echo $i ?>][label]" value="<?php echo $data['label'] ?>" required>
                <textarea name="attributes[0][value]" placeholder="Value" class="mt-30"><?php echo implode(' | ',$data['value']) ?></textarea>
                <p class="caption">(separate attributes by "|")</p>

                <div class="flex-start mb-16" >
                    <div class="d-inline-block">
                        <div class="input_field checkbox">
                            <input type="checkbox" class="checkbox" name="attributes[<?php echo $i ?>][attribute_visibility]" value="1" <?php echo $data['attribute_visibility'] ? 'checked':'' ?>>
                            <label>Visible on the product page</label>
                        </div>
                    </div>
                    &nbsp;&nbsp;&nbsp;
                    <div class="d-inline-block">
                        <div class="input_field checkbox">
                            <input type="checkbox" class="used_for_variation" name="attributes[<?php echo $i ?>][attribute_variation]" value="1" value="1" <?php echo $data['attribute_variation'] ? 'checked':'' ?>>
                            <label>Used for variations</label>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

}

