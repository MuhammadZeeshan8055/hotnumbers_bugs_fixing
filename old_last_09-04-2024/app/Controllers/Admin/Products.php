<?php

namespace App\Controllers\Admin;

use App\Models\Media;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\MasterModel;


class Products extends BaseController
{
    private $master;
    protected $uri;
    private $data;

    protected $table = "tbl_products";

    public function __construct()
    {

        $this->uri = service('uri');
        $this->master = new MasterModel();
        $this->data['page'] = "product";

        // $this->data['page'] = "books";

    }

    public function index()
    {
        $productModel = model('ProductsModel', true, $db);

        $query = "SELECT * FROM ".$this->table;
        $category = '';

        $categories = $productModel->get_shop_categories();

        $this->data['categories'] = $categories;
        $this->data['category'] = $category;
        $this->data['product_rows'] = $this->master->query($query);
        $this->data['productModel'] = $productModel;
        $this->data['content'] = ADMIN . '/products/index';

        if(!empty($_GET['get_table'])) {

            $media = model('Media');

            $rows = ['p.id','p.img','p.title','p.description','p.additional_desc','p.price','p.status','p.slug','p.stock','p.stock_status','p.total_sales','p.stock_threshold','p.stock_managed','p.sort_order'];

            $sort_cols = [
                '',
                'p.id',
                'p.title',
                'p.description',
                'p.price',
                'c.name',
                'p.stock',
                'p.total_sales',
                'p.status'
            ];

            $extquery = ' WHERE 1=1 ';


            $table_q = $this->table.' AS p';
            $table_q = $table_q.' JOIN tbl_product_categories AS tc ON tc.product_id=p.id JOIN tbl_categories AS c ON c.id=tc.category_id';

            if(!empty($_GET['filter_by_category'])) {
                $catid = $_GET['filter_by_category'];
                //$join = " JOIN tbl_product_categories AS tc ON tc.product_id=id";
                $extquery .= "  AND tc.category_id='$catid' ";
            }

            if(!empty($_GET['filter_by_status'])) {
                $extquery .= "AND p.status='".$_GET['filter_by_status']."'";
            }

            $extquery .= ' GROUP BY p.id';
           // print_r($table_q);
            $output = datatable_query($table_q,$rows,$sort_cols,'',$extquery);

            $records = $output['records'];
            unset($output['records']);

            foreach($records as $i=>$row) {
                $imgs = json_decode($row['img'],true);
                $img = $row['img'];
                if(is_array($imgs) && !empty($imgs[0])) {
                    $img = $imgs[0];
                }
                $image = $media->get_media_src($img,'','thumbnail');
                $cat_names = [];

                $cats = $productModel->product_categories($row['id']);

                if(!empty($cats)) {
                    foreach($cats as $cat) {
                        $cat_names[] = $cat['name'];
                    }
                }

                $cat_names = array_unique($cat_names);

                //pr($row,false);

                $desc = [limit(strip_tags($row['description']), 35),limit(strip_tags($row['additional_desc']), 35)];
                $desc = array_filter($desc);
                if(is_array($desc)){
                    $desc = implode(',',$desc);
                }

                $price = $productModel->product_price($row['id']);

                $stock = intval($row['stock']);
                $sales = $row['total_sales'];
                $low_stock = $row['stock_threshold'];

                $stock_status = 'stock stock_available';
                if($row['stock_managed'] || $row['stock_managed'] == 'yes') {
                    if($low_stock >= $stock && $stock > 0) {
                        $stock_status = 'stock stock_low';
                        $stock = '<div title="Low stock"><i class="icon-exclamation"></i> '.$stock.'</div>';
                    }else {
                        if(!$stock || $row['stock_status'] == "outofstock") {
                            $stock_status = 'stock stock_outofstock';
                            $stock = '<div title="Empty stock">'.$stock.'</div>';
                        }
                    }
                }


                if(is_array($price)) {
                    $price = 'From '._price(number_format((float)$price[0],2));
                }else {
                    $price = _price(number_format($price,2));
                }

                $view_url = site_url().'shop/product/'.$row['slug'];
                $edit_url = site_url().ADMIN.'/products/add/'.$row['id'];

                $action_btns = [
                    '<a class="edit_row btn btn-sm save text-center d-block"  style="margin-bottom:4px" href="'.$edit_url.'">Edit </a> &nbsp;&nbsp;',
                ];

                if($row['status'] === 'trash') {
                    $action_btns[] = '<a class="del_row edit_row btn-sm btn save text-center d-block bg-black" style="margin-bottom:4px" data-tooltip title="Delete product permanently" onclick="del_item(\'' . base_url(ADMIN . '/products/delete/') . '/' . $row['id'] . '\')"
href="javascript:void(0)"></i> <i class="lni lni-close"></i></a> &nbsp;';
                }else {
                    $action_btns[] = '<a class="del_row edit_row btn-sm btn save text-center d-block bg-black" style="margin-bottom:4px" data-tooltip title="Move to bin" onclick="del_item(\'' . base_url(ADMIN . '/products/trash/') . '/' . $row['id'] . '\')"
href="javascript:void(0)"></i> <i class="lni lni-trash-can"></i></a> &nbsp;';
                }

                $output['data'][] = [
                    '<div class="input_field inline-checkbox"><label><input type="checkbox" class="checkrow" name="product-row[]" value="'.$row['id'].'"></label></div>',
                    $row['id'],
                    '<div><a href="'.$edit_url.'">'.stripslashes($row['title']).'</a>
                    <a href="'.$view_url.'" target="_blank" title="View Product" class="preview-open" data-id="44464"><i class="lni lni-eye"></i></a></div>',
                    $desc,
                    $price,
                    implode(', ',$cat_names),
                    '<div class="'.$stock_status.'">'.$stock.'</div>',
                    $sales,
                    '<span class="status-'.$row['status'].'">'.ucfirst($row['status']).'</span>',
                    '<div class="text-center" style="width: 150px;">'.implode('',$action_btns).'</div>'
                ];
            }

            echo json_encode($output);

            exit;
        }

        _render_page(ADMIN . '/index', $this->data);
    }

    public function product_sortorder() {
        if( empty($_POST) ){ $_POST = json_decode(file_get_contents('php://input'), true); }
        $product_id = $_POST['product_id'];
        $order = $_POST['order'];
        $this->master->query("UPDATE tbl_products SET sort_order='$order' WHERE id='$product_id'");
        echo json_encode(['success'=>1]);
        exit;
    }

    public function trash_products($ids='') {
        $productModel = model('ProductsModel');
        foreach(explode(',',$ids) as $prodID) {
            $productModel->change_status($prodID,'trash');
        }
        notice_success('Products trashed successfully');
        if(empty($_GET['ref'])) {
            ?>
            <script>
                window.close();
            </script>
            <?php
        }else {
            ?>
            <script>
                history.back();
            </script>
            <?php
        }
    }

    public function add()
    {
        if (!empty($this->request->getPost())) {

            $data = $this->request->getPost();

            $data['stock_managed'] = $this->request->getPost('stock_managed') === 'yes'?'yes':'no';

            $data['img'] = !empty($this->request->getPost('product_images')) ? implode(',',$this->request->getPost('product_images')) : '';

            $data['slug'] = toSlugUrl($data['title']);

            $attributes = [];
            if(!empty($data['attributes'])) {
                $attributes = ($data['attributes']);
                foreach ($attributes as $i => $attribute) {
                    $attribute['value'] = explode(' | ', $attribute['value']);
                    $attributes[$i] = $attribute;
                }
            }

            $dbdata = [
                'title'=>$data['title'],
                'slug'=>$data['product_url'],
                'description'=>$data['description'],
                'additional_desc'=>$data['additional_desc'],
                'address'=>$data['address'],
                'price'=>$data['price'],
                'sale_price'=>$data['sale_price'],
                'stock_managed'=>!empty($data['stock_managed']) ? $data['stock_managed'] : 'no',
                'stock_status'=>!empty($data['stock_status_input']) ? $data['stock_status_input'] : '',
                'stock_threshold'=>!empty($data['stock_threshold']) ? $data['stock_threshold'] : 0,
                'img'=>!empty($data['product_images']) ? json_encode($data['product_images']) : '',
                'stock'=>!empty($data['stock']) ? $data['stock'] : '0',
                'sold_individually'=>!empty($data['sold_individually']) ? $data['sold_individually'] : '0',
                'free_shipping'=>!empty($data['free_shipping']) ? $data['free_shipping'] : '0',
                'type'=>$data['product_type'],
                'sku'=>$data['sku'],
                'status'=>$data['status'],
                'attributes'=>json_encode($attributes),
                'subscription'=>!empty($data['subscribe']) ? $data['subscribe'] : 0
            ];


            $id = $this->master->insertData($this->table, $dbdata, 'id', $data['id']);

            $cats = $this->request->getPost('category');

            $this->master->delete_data('tbl_product_categories','product_id',$id);

            if(!empty($cats)) {
                foreach($cats as $cat) {
                    $this->master->insertData('tbl_product_categories',['product_id'=>$id,'category_id'=>$cat]);
                }
            }


            $variation_data = [];

            if(!empty($data['variations'])) {
                foreach($data['variations'] as $variation) {
                    $variation_data[] = ($variation);
                }
            }

            $var_data = [
                'product_id'=>$id,
                'variation'=>json_encode($variation_data)
            ];

            $getvar = $this->master->getRow('tbl_product_variations',['product_id'=>$id]);

            if(!empty($variation_data) && $getvar) {
                $this->master->insertData('tbl_product_variations', $var_data, 'product_id', $data['id']);
            }elseif(!empty($variation_data)) {
                $this->master->insertData('tbl_product_variations', $var_data);
            }

            $msg = 'Successfully Updated';

            return redirect()->to(base_url(ADMIN . '/products/add/' . $id))->with('msg', $msg);
        }

        $prodcut_id = (int)$this->uri->getSegment(4);

        $media = model('Media');
        $this->data['productModel'] = $productModel = model('ProductsModel');
        $this->data['page'] = "product";

        $this->data['product_row'] = (array)$productModel->product_by_id($prodcut_id,'*','any');

        $this->data['tax_classes'] = tax_rates();

        $this->data['categories'] = $this->master->getRows('tbl_categories', ['status' => 1,'group_name'=>'product_cat']);
        $this->data['content'] = ADMIN . "/products/add_product";

        if(!empty($this->data['product_row'])) {
            $this->data['product_variations'] = $productModel->product_variations($prodcut_id,'all');
            if(!empty($_GET['get_attribute_variation'])) {
                $product = $this->data['product_row'];
                $attributes = !empty($product['attributes']) ? json_decode($product['attributes'],true) : [];
                $attr = $_GET['attribute'];
                $data = [];
                foreach($attributes as $k=>$attribute) {
                    if($attribute['label'] == $attr && $attribute['attribute_variation'] == 1) {
                        $data = $attribute;
                        break;
                    }
                }
                echo json_encode($data);
                exit;
            }
            $prod_cat_ids = [];
            $cats = $productModel->product_categories($prodcut_id);
            foreach($cats as $cat) {
                $prod_cat_ids[] = $cat['id'];
            }
            $this->data['prod_cat_ids'] = $prod_cat_ids;

            $this->data['media'] = $media;
        }else {
            $this->data['prod_cat_ids'] = [];
            $this->data['status'] = 'instock';
        }



        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function update_variations($pid=0) {
        $data = $this->request->getPost();
        if(!empty($data['attributes']) && $pid) {
            $post_attrs = $data['attributes'];
            $new_attrs = [];
            foreach($post_attrs as $attr) {
                $attr['value'] = explode(' | ',$attr['value']);
                $new_attrs[] = $attr;
            }
            $attributes = json_encode($new_attrs);
            $this->master->insertData('tbl_products',['attributes'=>$attributes],'id',$pid);
            echo 1;
        }else {
            echo 0;
        }
    }

    public function delete(int $id)
    {
        if ($id > 0) {
            $this->master->delete_data($this->table, 'id', $id);
            $this->res['ok'] = '1';
            $this->res['id'] = $id;
            echo json_encode($this->res);
            exit;
        }
    }

    public function trash_product(int $id)
    {
        if ($id > 0) {
            $productModel = model('ProductsModel');
            $productModel->change_status($id,'trash');
            $this->res['ok'] = '1';
            $this->res['id'] = $id;
            echo json_encode($this->res);
            exit;
        }
    }

    public function coupon_list() {
        $this->data['page'] = "coupons";
        $this->data['coupons'] = $this->master->getRows('tbl_coupons');

        if(!empty($_GET['edit'])) {
            $this->data['coupon'] = $this->master->getRow('tbl_coupons',['id'=>(int)$_GET['edit']]);
        }

        if(!empty($_GET['delete'])) {
            $this->master->delete_data('tbl_coupons','id',$_GET['delete']);
            notice_success('Coupon deleted successfully');
            return redirect()->to(base_url('/' . ADMIN . '/coupons'));
        }

        _render_page('/' . ADMIN . '/products/coupons', $this->data);
    }

    public function coupon_add() {
        $coupon_code = $this->request->getPost('coupon_code');
        $coupon_amount = $this->request->getPost('coupon_amount');
        $coupon_type = $this->request->getPost('coupon_type');
        $has_expiration = $this->request->getPost('has_expiration');
        $coupon_valid_from = $this->request->getPost('coupon_valid_from');
        $coupon_valid_to = $this->request->getPost('coupon_valid_to');
        $coupon_usage_limit = $this->request->getPost('coupon_usage_limit');
        $cpn_update = $this->request->getPost('cpn_update');
        $status = $this->request->getPost('status');

        if(!$has_expiration) {
            $coupon_valid_from = '';
            $coupon_valid_to = '';
        }else {
            $coupon_valid_from_ = explode('/',$coupon_valid_from);
            $coupon_valid_from = $coupon_valid_from_[2].'-'.$coupon_valid_from_[1].'-'.$coupon_valid_from_[0];

            $coupon_valid_to_ = explode('/',$coupon_valid_to);
            $coupon_valid_to = $coupon_valid_to_[2].'-'.$coupon_valid_to_[1].'-'.$coupon_valid_to_[0];

            $coupon_valid_from = date('Y-m-d h:i:s',strtotime($coupon_valid_from));
            $coupon_valid_to = date('Y-m-d h:i:s',strtotime($coupon_valid_to));
        }

        $data = [
            'code' => $coupon_code,
            'amount'=>$coupon_amount,
            'type'=>$coupon_type,
            'valid_from'=>$coupon_valid_from,
            'valid_to'=>$coupon_valid_to,
            'use_limit'=>$coupon_usage_limit,
            'status'=>$status,
            'has_expiration'=>$has_expiration
        ];

        $this->master->insertData('tbl_coupons',$data,'id',$cpn_update);

        notice_success($cpn_update ? 'Coupon updated successfully' : 'Coupon added successfully');

        return redirect()->to(base_url('/' . ADMIN . '/coupons'));
    }

    public function coupon_toggle() {
        $def = get_setting('enable_product_coupons');
        $db_data = [
          'title'=>'enable_product_coupons',
          'value' => !$def ? 1 : 0
        ];
        $notice = 'Coupons ';
        $notice .= !$def ? 'enabled' : 'disabled';
        $this->master->insertOrUpdate('tbl_settings',$db_data,'title','enable_product_coupons');
        notice_success($notice);
        return redirect()->back();
    }


    public function show_hide_products() {
        $roles = shop_roles();
        $productModel = model('ProductsModel');

        $this->data['page'] = 'show-hide-products';
        $this->data['user_roles'] = $roles;
        $this->data['products'] = $productModel->get_all_data('id,title','');
        $this->data['categories'] = $productModel->get_shop_categories();

        if($this->request->getPost('user_role_categories')) {

            $meta_data = $this->request->getPost();

            foreach($meta_data as $key=>$datas) {

                if(is_array($datas)) {

                    foreach($datas as $roleid=>$data) {

                        if(!empty($data['data'])) {
                            $mode_key = $key.'_mode';
                            $mode = $data['mode'];
                            $exists = $this->master->query("SELECT meta_id FROM tbl_user_role_meta WHERE role_id='$roleid' AND meta_key='$key'",true,true);
                            $mode_exists = $this->master->query("SELECT meta_id FROM tbl_user_role_meta WHERE role_id='$roleid' AND meta_key='$mode_key'",true,true);

                            $data_list = implode(',',$data['data']);

                            if(empty($exists)) {
                                $this->master->insertData('tbl_user_role_meta',['role_id'=>$roleid,'meta_key'=>$key,'meta_value'=>$data_list]);
                            }else {
                                $meta_id = $exists['meta_id'];
                                $this->master->insertData('tbl_user_role_meta',['role_id'=>$roleid,'meta_key'=>$key,'meta_value'=>$data_list],'meta_id',$meta_id);
                            }

                            if(empty($mode_exists)) {
                                $this->master->insertData('tbl_user_role_meta',['role_id'=>$roleid,'meta_key'=>$mode_key,'meta_value'=>$mode]);
                            }else {
                                $mid = $mode_exists['meta_id'];
                                $this->master->insertData('tbl_user_role_meta',['role_id'=>$roleid,'meta_key'=>$mode_key,'meta_value'=>$mode],'meta_id',$mid);
                            }
                        }else {
                            $this->master->query("DELETE FROM tbl_user_role_meta WHERE role_id='$roleid' AND meta_key='$key'");
                        }
                    }
                }


            }

            notice_success('Settings updated successfully');

            _redirect(current_url());
        }

        $this->data['productModel'] = $productModel;

//        $this->data['user_role_products'] = $productModel->user_role_products();
//        $this->data['user_role_categories'] = $productModel->user_role_categories();

        $this->data['content'] = ADMIN . '/products/show-hide-products';
        _render_page(ADMIN . '/index', $this->data);
    }

    public function shopping_settings() {
        $this->data['page'] = 'shop-settings';
        $this->data['content'] = ADMIN . '/products/shop-settings';
        _render_page(ADMIN . '/index', $this->data);
    }



}
