<?php

namespace App\Controllers\Admin;

use App\Libraries\Paypal_lib;
use App\Libraries\Pass_hash_lib;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\MasterModel;
use CodeIgniter\Model;


class SqlSync extends BaseController
{
    protected $uri;
    protected $table = "tbl_products";
    private $master;
    private $data;

    public function __construct()
    {

        $this->uri = service('uri');
        $this->master = new MasterModel();
        $this->data['page'] = "shop";
    }

    private function sync_wp_media($meta_value,$folder='products') {
        error_reporting(0);

        $medias = $this->query_array("SELECT ID,post_title, guid, post_date FROM a5jqqtl_posts WHERE ID IN ('" . $meta_value . " LIMIT 1')");

        $imagesIDs = [];

        foreach ($medias as $media) {
            $title = $media['post_title'];
            $ID = $media['ID'];
            $date_created = $media['post_date'];

            $guid_ = str_replace('hotnumberswholesale.uk','hotnumberscoffee.co.uk',$media['guid']);
            $guid = explode('/', $guid_);
            $filename = end($guid);

            $guid__ = str_replace('https://hotnumberscoffee.co.uk/wp-content/uploads/','../../../hotnum-images/',$guid_);

            $folder = str_replace('../../../hotnum-images/','',$guid__);
            $folder_exp = explode('/',$folder);

            $folder1 = $folder_exp[0];
            $folder2 = $folder_exp[1];

//            $folder1 = date('Y');
//            $folder2 = date('m');

            if(!is_dir(IMAGEUPLOADPATH.'/'.$folder1)) {
                mkdir(IMAGEUPLOADPATH.'/'.$folder1);
            }

            if(!is_dir(IMAGEUPLOADPATH.'/'.$folder1.'/'.$folder2)) {
                mkdir(IMAGEUPLOADPATH.'/'.$folder1.'/'.$folder2);
            }

            if(copy($guid__,IMAGEUPLOADPATH.'/'.$folder1.'/'.$folder2.'/'.$filename))
            {
                echo 'Copied: '.$filename;

            }else {
                echo 'Failed: '.$filename;
            }

            $path = IMAGEUPLOADPATH . '/'.$folder1.'/'.$folder2.'/'.$filename;
//            $media_exists = file_exists($path);
//
//            if (!$media_exists) {
//                $src = file_get_contents($media['guid']);
//                file_put_contents($path, $src);
//                echo '<div>Media Imported: '.$filename.'</div>';
//            }else {
//
//                echo '<div>Media exists: '.$filename.'</div>';
//            }

            echo '<div><img width="150" src="'.base_url('assets/images/site-images/'.$folder1.'/'.$folder2.'/'.$filename).'"></div>';


            $ext = explode('.', $filename);
            $ext = end($ext);
            $ext = strtolower($ext);
            $size = filesize($path);

            $folder_path_name = $folder1.'/'.$folder2.'/'.$filename;

            $file_exists = $this->master->getRow('tbl_files', ['path' => $folder_path_name]);

            if (!empty($file_exists)) {
                $fileid = $file_exists['id'];
            } else {
                $fileid = $this->master->insertData('tbl_files', ['id'=>$ID,'name' => $title, 'path' => $folder_path_name, 'type' => "image/$ext", 'size' => $size, 'created_at'=>$date_created]);
            }

            $imagesIDs[] = $fileid;
        }
        return $imagesIDs;
    }

    private function sync_wp_media_folder($post_id = 0) {
        $medias = $this->query_array("SELECT * FROM `a5jqqtl_posts` WHERE `post_type`='attachment' AND ID='$post_id'");

        foreach($medias as $media) {
            $this->sync_wp_media($media['ID']);
        }

    }

    private function query_array($query='') {
        error_reporting(0);
        $db = 'hotnumbers_wp';
        $user = 'root';
        $pass = '';

        $db_wp = mysqli_connect('localhost',$user,$pass,$db);

        $query = mysqli_query($db_wp,$query);

        $output = [];

        while($q = mysqli_fetch_array($query)) {
            $output[] = $q;
        }

        return $output;
    }

    private function sync_db_categories($term_id=0)  {

        $cats_db = "SELECT * FROM a5jqqtl_term_relationships AS relation JOIN a5jqqtl_term_taxonomy as taxonomy ON taxonomy.term_id=relation.term_taxonomy_id JOIN a5jqqtl_terms as term ON term.term_id=taxonomy.term_id WHERE term.term_id='$term_id'";

        $cat_q = $this->query_array($cats_db);

        foreach($cat_q as $cat) {
            $cat_exists = $this->master->getRows('tbl_categories',['id'=>$cat['term_id']]);

            $db_data = [
              'id' => $cat['term_id'],
              'parent' => $cat['parent'],
              'name' => $cat['name'],
              'description' => $cat['description'],
              'img' => '',
              'slug' => $cat['slug'],
              'group_name' => $cat['taxonomy'],
              'sort_order'=>0,
              'status' => 1
            ];

            $term_metas = $this->query_array("SELECT * FROM a5jqqtl_termmeta WHERE term_id='".$cat['term_id']."'");

            foreach($term_metas as $meta) {
                $val = $meta['meta_value'];
                if($meta['meta_key'] === "order") {
                    $db_data['sort_order'] = $val;
                }
                if($meta['meta_key'] === "thumbnail_id") {
                    $thumbs = $this->sync_wp_media($val);
                    $db_data['img'] = implode(',',$thumbs);
                }
            }

            if($cat_exists) {
                $this->master->insertData('tbl_categories', $db_data, 'id',$cat['term_id']);
                echo "Category updated: ".$cat['name'];
            }else {
                $this->master->insertData('tbl_categories', $db_data);
                echo "Category added: ".$cat['name'];
            }
        }

    }

    public function sync_db_products($postID=0) {
        error_reporting(0);
        $db = 'hotnumbers_wp';
        $user = 'root';
        $pass = '';
        $db_wp = mysqli_connect('localhost',$user,$pass,$db);
        $sql = "SELECT * FROM a5jqqtl_posts WHERE post_type='product' AND ID='".$postID."' ORDER BY ID";
        $wp_posts = mysqli_query($db_wp,$sql);

        while ($post = mysqli_fetch_array($wp_posts)) {
            echo "<h3>Importing: ".$post['post_title']."</h3>";
            $pid = $post['ID'];

            $data = [
                'id' => $pid,
                'title' => $post['post_title'],
                'slug' => $post['post_name'],
                'description' => $post['post_excerpt'],
                'additional_desc' => $post['post_content'],
                'created_at' => $post['post_date'],
                'address' => '',
                'price' => 0,
                'stock_managed' => '',
                'status'=>$post['post_status'],
                'stock_status'=>''
            ];

            $metas = mysqli_query($db_wp, "SELECT * FROM a5jqqtl_postmeta WHERE post_id='" . $pid . "' ORDER BY meta_id");

            $images = [];
            $meta_prices = [];
            $meta_array = [];

            while ($meta = mysqli_fetch_array($metas)) {
                $meta_array[] = $meta;
                if ($meta['meta_key'] == "_price") {
                    $meta_prices[$meta['post_id']][] = $meta;
                }
            }

            $sql = "SELECT * FROM a5jqqtl_posts AS post WHERE post.post_parent='$pid' AND post.post_type='product_variation'";
            $variations = mysqli_query($db_wp,$sql);

            $attr_sql = mysqli_query($db_wp,"SELECT * FROM a5jqqtl_postmeta AS meta WHERE meta.post_id='$pid' AND meta.meta_key='_product_attributes'");

            $attr_res = mysqli_fetch_assoc($attr_sql);

            $attr_res_values = unserialize($attr_res['meta_value']);

            $attr_res_values_keys = !empty($attr_res_values) ? array_keys($attr_res_values) : [];

            $variations_arr = [];

            $i=0;

            while($variation = mysqli_fetch_array($variations)) {

                $var_pid = $variation['ID'];
                $var_meta_sql = "SELECT * FROM a5jqqtl_postmeta AS meta WHERE meta.post_id='$var_pid'";
                $var_meta_db = mysqli_query($db_wp,$var_meta_sql);
                $attr_keys = [];
                $attr_vals = [];

                $k = $attr_res_values_keys[$i];
                $j = 0;

                while($var = mysqli_fetch_array($var_meta_db)) {

                    if (strstr($var['meta_key'], 'attribute_')) {
                        $attr_keys[$var['meta_key']] = $var['meta_value'];
                    }

                    $var['meta_key'] = trim($var['meta_key'],'_');
                    $attr_vals[$var['meta_key']] = $var['meta_value'];

                    $j++;
                }

                $post_excerpts = explode(', ',$variation['post_excerpt']);



                foreach($post_excerpts as $arr) {
                    $parts = explode(': ',$arr);
                    $k = strtolower($parts[0]);
                    $k = str_replace(' ','-',$k);
                    $v = $parts[1];
                    $attr_keys['attribute_'.$k] = $v;
                }

                if(!empty($attr_keys)) {
                    $variations_arr[$i]['keys'] = $attr_keys;
                }

                if(!empty($attr_vals)) {
                    $variations_arr[$i]['values'] = $attr_vals;
                }

                //$variations_arr[$k] = [];
                $i++;
            }


            $query = "SELECT t.name, t.term_id, tt.description,tt.taxonomy,relation.object_id,post.post_title,termmeta.meta_key,termmeta.meta_value FROM a5jqqtl_term_taxonomy AS tt 
    JOIN a5jqqtl_terms as t ON t.term_id=tt.term_id 
    JOIN a5jqqtl_term_relationships AS relation ON relation.term_taxonomy_id=t.term_id
    JOIN a5jqqtl_posts AS post ON relation.object_id=post.ID
    JOIN a5jqqtl_termmeta AS termmeta ON termmeta.term_id=t.term_id
WHERE relation.object_id='" . $post['ID'] . "' GROUP BY tt.term_taxonomy_id";

            $categories = mysqli_query($db_wp, $query);

            $prod_categories = [];

            while ($category = mysqli_fetch_array($categories)) {

                $cat_exists = $this->master->getRow('tbl_categories', ['id' => $category['term_id']]);
                $cat_id = 0;

                $slug = strtolower($category['name']);
                $slug = str_replace(' ','-',$slug);

                $cat_data = [
                    'id'=>$category['term_id'],
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'status' => 1,
                    'group_name' => $category['taxonomy'],
                    'slug'=>$slug
                ];

                if($category['meta_key'] == 'thumbnail_id') {
                    $thumbs = $this->sync_wp_media($category['meta_value']);
                    $cat_data['img'] = implode(',',$thumbs);
                }

                if (empty($cat_exists)) {

                   $cat_id = $this->master->insertData('tbl_categories', $cat_data);

                    if($cat_id) {
                        echo '<div>Imported Category: '.$category['name'].'</div>';
                    }
                } else {
                    $cat_id = $cat_exists['id'];
                    $this->master->insertData('tbl_categories',$cat_data,'id',$cat_id);
                    echo '<div>Category re-synced: '.$category['name'].'</div>';
                }
                //$data['category'] = $cat_id;
                $prod_categories[] = $cat_id;
            }

            $prod_categories = array_unique($prod_categories);

            foreach ($meta_array as $meta) {

                if ($meta['meta_key'] == "_product_attributes") {
                    $values = unserialize($meta['meta_value']);
                    foreach ($values as $value) {
                        if ($value['name'] == "Region") {
                            $data['address'] = $value['value'];
                        }
                    }
                }

                if ($meta['meta_key'] == "_price") {
                    $data['price'] = $meta['meta_value'];
                }

                if ($meta['meta_key'] == "_manage_stock") {
                    $data['stock_managed'] = $meta['meta_value'] == 'yes' ? 1 : 0;
                }

                if ($meta['meta_key'] == "_stock") {
                    $data['stock'] = $meta['meta_value'];
                }

                if ($meta['meta_key'] == "_stock_status") {
                    $data['stock_status'] = $meta['meta_value'] === "instock" ? "instock":'outofstock';
                }

                if ($meta['meta_key'] == "_sku") {
                    $data['sku'] = $meta['meta_value'];
                }

                if ($meta['meta_key'] == "_low_stock_amount") {
                    $data['stock_threshold'] = $meta['meta_value'];
                }

                if ($meta['meta_key'] == "total_sales") {
                    $data['total_sales'] = $meta['meta_value'];
                }

                if ($meta['meta_key'] == "_product_attributes") {
                    $meta_prices = $meta_prices[$meta['post_id']];
                    $values = unserialize($meta['meta_value']);
                    foreach ($values as $value) {
                        $vals =  explode('|',$value['value']);
                        $v = [];
                        foreach($vals as $val) {
                            $v[] = trim($val);
                        }
                        $attrs = [
                            'label' => $value['name'],
                            'value' => $v,
                            'attribute_variation'=>$value['is_variation'],
                            'attribute_visibility'=>$value['is_visible']
                        ];
                        $data['attributes'][] = $attrs;
                    }
                }
                if ($meta['meta_key'] == "_product_image_gallery" || $meta['meta_key'] == "_thumbnail_id") {
                    $images[] = $this->sync_wp_media($meta['meta_value']);
                }
            }

            if(!empty($data['attributes'])) {
                $data['attributes'] = json_encode($data['attributes']);
            }

            $imgs = [];
            foreach($images as $image) {
                if(is_array($image)) {
                    foreach($image as $i) {
                        $imgs[] = $i;
                    }
                }
            }

            $data['img'] = json_encode($imgs);

            $chkID = $this->master->getRow('tbl_products',['title'=>$data['title']]);

            if(empty($chkID)) {
                $insertID = $this->master->insertData('tbl_products', $data);
            }else {
                $this->master->insertData('tbl_products', $data,'id',$chkID['id']);
                $insertID = $chkID['id'];
            }

            if(!empty($prod_categories)) {
                foreach($prod_categories as $catid) {
                    $cat_exists = $this->master->getRow('tbl_product_categories',['product_id'=>$insertID,'category_id'=>$cat_id]);
                    if(!$cat_exists) {
                        $this->master->insertData('tbl_product_categories',['product_id' => $insertID, 'category_id' => $catid]);
                    }
                }
                echo '<div>Categories assigned: '.count($prod_categories).'</div>';
            }

            $this->master->delete_data('tbl_product_variations','product_id',$insertID);

            $variation_json = json_encode($variations_arr);
            $this->master->insertData('tbl_product_variations', ['product_id' => $insertID, 'variation' => $variation_json]);
            echo '<div>Variation added</div>';


            echo '<div>Product no. '.$insertID.' imported successfully</div><br><hr><br>';
        }
    }

    public function sync_db_blogs($postID=0) {
        error_reporting(0);
        $db = 'hotnumbers_wp';
        $user = 'root';
        $pass = '';

        $db_wp = mysqli_connect('localhost',$user,$pass,$db);

       // mysqli_query($db_wp,"DELETE FROM tbl_posts WHERE post_type='post'");

        $wp_posts = mysqli_query($db_wp,"SELECT * FROM a5jqqtl_posts WHERE post_type='post' AND ID='".$postID."' ORDER BY ID");

        while ($post = mysqli_fetch_array($wp_posts)) {

            $post_data = $post['post_date'];
            $guid = $post['post_name'];

            $metas = mysqli_query($db_wp, "SELECT * FROM a5jqqtl_postmeta WHERE post_id='" . $post['ID'] . "' ORDER BY meta_id");

            $meta_array = [];

            $thumbnail_id = 0;

            $cat_ids = [];

            $cats_db = "SELECT term.name,term.slug,taxonomy.description FROM a5jqqtl_term_relationships AS relation JOIN a5jqqtl_term_taxonomy as taxonomy ON taxonomy.term_id=relation.term_taxonomy_id JOIN a5jqqtl_terms as term ON term.term_id=taxonomy.term_id WHERE relation.object_id='" . $post['ID'] . "'";

            $cat_q = mysqli_query($db_wp,$cats_db);

            while($cat = mysqli_fetch_array($cat_q)) {
                $cat_exists = $this->master->getRows('tbl_categories',['slug'=>$cat['slug']]);
                if(empty($cat_exists)) {
                    $cat_data = [
                        'name' => $cat['name'],
                        'description' => $cat['description'],
                        'status' => 1,
                        'parent' => 0,
                        'slug'=>$cat['slug']
                    ];

                    $cat_ids[] = $this->master->insertData('tbl_categories',$cat_data);
                }else {
                    foreach($cat_exists as $c) {
                        $cat_ids[] = $c->id;
                    }
                }
            }


            while ($meta = mysqli_fetch_array($metas)) {
                $meta_array[] = $meta;
                if($meta['meta_key'] == "_thumbnail_id") {
                    $thumbnail_id = $this->sync_wp_media($meta['meta_value']);
                }
            }

            $data = [
                'title'=>addslashes($post['post_title']),
                'slug'=>$guid,
                'category'=>implode(',',$cat_ids),
                'post_date' => $post_data,
                'scheduled_date' => $post_data,
                'post_status'=>$post['post_status'],
                'img'=>!empty($thumbnail_id) ? implode(',', $thumbnail_id) : "",
                'content'=>$post['post_content'],
                'featured_post'=>0
            ];

            $data = array_filter($data);

            $exists = $this->master->getRow('tbl_posts',['slug'=>$data['slug']]);

            if(empty($exists)) {

               $this->master->insertData('tbl_posts',$data);

                echo "<h3>Imported: " . $post['post_title'] . "</h3>";
            }else {

                $this->master->insertData('tbl_posts',$data,'post_id',$exists['post_id']);
                echo "<h3>Updated: " . $post['post_title'] . "</h3>";
            }

        }

    }

    public function sync_db_pages($postID) {
        $db = 'hotnumbers_wp';
        $user = 'root';
        $pass = '';
        $db_wp = mysqli_connect('localhost',$user,$pass,$db);
        $sql = "SELECT * FROM a5jqqtl_posts WHERE post_type='page' AND ID='".$postID."' ORDER BY ID";
        $wp_posts = mysqli_query($db_wp,$sql);

        while ($post = mysqli_fetch_array($wp_posts)) {
            echo "<h3>Importing: ".$post['post_title']."</h3>";

            $pid = $post['ID'];
            $data = [
                'title' => $post['post_title'],
                'slug' => $post['post_name'],
                'created_at' => $post['post_date'],
                'status' => $post['post_status']
            ];

            $metas = mysqli_query($db_wp, "SELECT * FROM a5jqqtl_postmeta WHERE post_id='" . $pid . "' ORDER BY meta_id");

            $meta_fields = [];
            while ($meta = mysqli_fetch_array($metas)) {
                $meta_array[] = $meta;
                $meta_value = $meta['meta_value'];
                if(strstr($meta_value,'field_')) {
                    $fields_db = mysqli_query($db_wp, "SELECT * FROM a5jqqtl_posts WHERE post_type='acf-field' AND post_name='$meta_value'");
                    if($fields_db) {
                        while ($field = mysqli_fetch_array($fields_db)) {
                            $meta_fields[] = $field;
                        }
                    }
                }
            }

            pr($meta_fields);
        }
    }

    public function sync_shop_orders($postID=0) {
        $db = 'hotnumbers_wp';
        $user = 'root';
        $pass = '';
        $db_wp = mysqli_connect('localhost',$user,$pass,$db);
        $sql = "SELECT * FROM a5jqqtl_posts AS post WHERE post.ID='".$postID."'";

        $wp_posts = $this->query_array($sql);

        foreach ($wp_posts as $order) {

            $ID = $order['ID'];

            $billing_address  = $this->query_array("SELECT meta_key,meta_value FROM a5jqqtl_postmeta WHERE post_id='".$ID."' AND `meta_key` LIKE '%_billing_%'");
            $shipping_address  = $this->query_array("SELECT meta_key,meta_value FROM a5jqqtl_postmeta WHERE post_id='".$ID."' AND `meta_key` LIKE '%_shipping_%'");

            $customer_id = $this->query_array("SELECT meta_value FROM a5jqqtl_postmeta WHERE post_id='".$ID."' AND `meta_key`='_customer_user'");
            $customer_ip_address = $this->query_array("SELECT meta_value FROM a5jqqtl_postmeta WHERE post_id='".$ID."' AND `meta_key`='_customer_ip_address'");
            $payment_method = $this->query_array("SELECT meta_value FROM a5jqqtl_postmeta WHERE post_id='".$ID."' AND `meta_key`='_payment_method'");
            $customer_user_agent = $this->query_array("SELECT meta_value FROM a5jqqtl_postmeta WHERE post_id='".$ID."' AND `meta_key`='_customer_user_agent'");
            $order_currency = $this->query_array("SELECT meta_value FROM a5jqqtl_postmeta WHERE post_id='".$ID."' AND `meta_key`='_order_currency'");

            $billing_arr = [];
            $shipping_arr = [];

            foreach($billing_address as $address) {
                $key = substr($address['meta_key'],1);
                $value = $address['meta_value'];
                $billing_arr[$key] = $value;
            }

            foreach($shipping_address as $address) {
                $key = substr($address['meta_key'],1);
                $value = $address['meta_value'];
                $shipping_arr[$key] = $value;
            }

            $order_data = [
                'order_id'=>$ID,
                'order_title'=>addslashes($order['post_title']),
                'order_date'=>$order['post_date'],
                'order_type' => $order['post_type'],
                'parent_id' => $order['post_parent'],
                'billing_address' => json_encode($billing_arr),
                'shipping_address' => json_encode($shipping_arr),
                'customer_user' => $customer_id[0]['meta_value'],
                'payment_method' => $payment_method[0]['meta_value'],
                'customer_ip_address' => $customer_ip_address[0]['meta_value'],
                'customer_user_agent' => $customer_user_agent[0]['meta_value'],
                'order_currency' => $order_currency[0]['meta_value'],
                'order_note' => addslashes($order['post_excerpt']),
                'order_password' => $order['post_password'],
                'status'=>str_replace('wc-','',$order['post_status']),
            ];

            $exists = $this->master->query("SELECT order_id FROM tbl_orders WHERE order_id=".$ID,true,true);

            if(empty($exists)) {
                $oid =  $this->master->insertData('tbl_orders',$order_data);
                echo "<h3>Order added: " . $order['ID'] . " </h3>";
            }
            else {
                $oid = $exists['order_id'];
                $this->master->insertOrUpdate('tbl_orders',$order_data,'order_id',$oid);

                echo "<h3>Order updated: " . $order['ID'] . "</h3>";
            }

            $post_comments = $this->query_array("SELECT * FROM a5jqqtl_comments WHERE comment_post_ID='".$ID."'");

            foreach($post_comments as $comment) {

                $cmt_data = [
                  'comment_ID'=>$comment['comment_ID'],
                  'comment_post_ID'=>$comment['comment_post_ID'],
                  'comment_author'=>$comment['comment_author'],
                  'comment_author_email'=>$comment['comment_author_email'],
                  'comment_author_url'=>$comment['comment_author_url'],
                  'comment_author_IP'=>$comment['comment_author_IP'],
                  'comment_date'=>$comment['comment_date'],
                  'comment_date_gmt'=>$comment['comment_date_gmt'],
                  'comment_content'=>urlencode($comment['comment_content']),
                  'comment_karma'=>$comment['comment_karma'],
                  'comment_approved'=>$comment['comment_approved'],
                  'comment_agent'=>$comment['comment_agent'],
                  'comment_type'=>$comment['comment_type'],
                  'comment_parent'=>$comment['comment_parent'],
                  'user_id'=>$comment['user_id'],
                ];

                $this->master->insertOrUpdate('tbl_comments',$cmt_data,'comment_ID',$comment['comment_ID']);

                $post_comment_metas = $this->query_array("SELECT * FROM a5jqqtl_commentmeta WHERE comment_id='".$comment['comment_ID']."'");
                $post_comment_metas = !empty($post_comment_metas) ? $post_comment_metas[0] : [];

                if(!empty($post_comment_metas)) {
                    $meta_data = [
                        'meta_id'=>$post_comment_metas['meta_id'],
                        'comment_id'=>$post_comment_metas['comment_id'],
                        'meta_key'=>$post_comment_metas['meta_key'],
                        'meta_value'=>$post_comment_metas['meta_value'],
                    ];

                    $this->master->insertOrUpdate('tbl_commentmeta',$meta_data,'meta_id',$meta_data['meta_id']);
                }

            }



            $db_meta = [];

            $meta_fields = [
                'customer_user',
                'payment_method',
                'customer_ip_address',
                'customer_user_agent',
                'order_currency',
                'billing_address',
                'shipping_address'
            ];

            $post_meta  = $this->query_array("SELECT meta_key,meta_value FROM a5jqqtl_postmeta WHERE post_id='".$ID."'");

            $billing_address = [];
            $shipping_address = [];

            $customer_user = 0;

            foreach($post_meta as $order_meta) {
                $key = $order_meta['meta_key'];
                $value = $order_meta['meta_value'];

                if($key[0] == "_") {
                    $key = substr($key,1);
                }

                if(in_array($key,$meta_fields)) {
                    $db_meta[$key] = $value;
                }

                $exists = $this->master->query("SELECT ometa_id FROM tbl_order_meta WHERE order_id='$oid' AND meta_key='$key'",true,true);

                if (empty($exists)) {
                    $mid = $this->master->insertData('tbl_order_meta', ['order_id'=>$oid,'meta_key'=>$key, 'meta_value'=>$value]);
                    echo "<h4>Order meta added: " . $key.'='.$value . "</h4>";
                } else {
                    $mid = $exists['ometa_id'];
                    //$mid = $this->master->insertData('tbl_order_meta', ['order_id'=>$oid,'meta_key'=>$key, 'meta_value'=>$value], 'ometa_id', $mid);
                   // echo "<h4>Order meta updated: " . $key.'='.$value . "</h4>";
                }
            }

            $db_meta['billing_address'] = json_encode($billing_address);
            $db_meta['shipping_address'] = json_encode($shipping_address);

          //  $this->master->insertOrUpdate('tbl_orders', $db_meta, 'order_id', $oid);

            $order_items = mysqli_query($db_wp,"SELECT * FROM a5jqqtl_woocommerce_order_items WHERE order_id='".$order['ID']."'");


            while($order_item = mysqli_fetch_array($order_items)) {

                $name = $order_item['order_item_name'];
                $value = $order_item['order_item_type'];

                if($order_item['order_item_type'] === "shipping") {
                    $exists = $this->master->query("SELECT meta_key FROM tbl_order_meta WHERE order_id='$oid' AND meta_key='order_shipping_title'");
                    if(!empty($exists)) {
                        $this->master->query("UPDATE tbl_order_meta SET meta_key='order_shipping_title', meta_value='$name' WHERE order_id='$oid' AND meta_key='order_shipping_title'");
                    }else {
                        $this->master->query("INSERT INTO tbl_order_meta SET meta_key='order_shipping_title', meta_value='$name', order_id='$oid'");
                    }
                    continue;
                }

                if($order_item['order_item_type'] === "tax") {
                    $exists = $this->master->query("SELECT meta_key FROM tbl_order_meta WHERE order_id='$oid' AND meta_key='order_tax_title'");
                    if(!empty($exists)) {
                        $this->master->query("UPDATE tbl_order_meta SET meta_key='order_tax_title', meta_value='$name' WHERE order_id='$oid' AND meta_key='order_tax_title'");
                    }else {
                        $this->master->query("INSERT INTO tbl_order_meta SET meta_key='order_tax_title', meta_value='$name', order_id='$oid'");
                    }
                    continue;
                }

                $db_data = [
                    'order_id'=>$oid,
                    'product_name'=>addslashes($order_item['order_item_name']),
                    'item_type'=>addslashes($order_item['order_item_type'])
                ];

                $exists = $this->master->query("SELECT order_item_id FROM tbl_order_items WHERE order_id='$oid' AND product_name='".$db_data['product_name']."' AND item_type='".$order_item['order_item_type']."'",true,true);

                if(empty($exists)) {
                    $item_id = $this->master->insertData('tbl_order_items',$db_data);
                    echo "<h3>Order item added: " . $order_item['order_item_name'] . "</h3>";
                }else {
                    $item_id = $exists['order_item_id'];
                    $this->master->insertData('tbl_order_items',$db_data,'order_item_id',$item_id);
                    echo "<h3>Order item updated: " . $order_item['order_item_name'] . "</h3>";
                }

                $item_meta = $this->query_array("SELECT * FROM a5jqqtl_woocommerce_order_itemmeta WHERE order_item_id='".$order_item['order_item_id']."'");

                foreach($item_meta as $meta) {

                    $key = $meta['meta_key'];
                    $value = addslashes($meta['meta_value']);

                    if($key[0] == "_") {
                        $key = substr($key,1);
                    }

                    if($key === "variation_id") {

                        $get_variation = $this->query_array("SELECT * FROM a5jqqtl_posts WHERE ID='".$value."' AND post_type='product_variation'");

                        if(!empty($get_variation)) {

                            $this->master->query("DELETE FROM tbl_order_item_meta WHERE item_id='$item_id' AND meta_key='variation'");

                            $variation_arr = [];
                                foreach($get_variation as $variation) {
                                $_excerpts = explode(', ',$variation['post_excerpt']);
                                foreach($_excerpts as $ex) {
                                    $ex_ = explode(': ',$ex);
                                    $variation_arr[$ex_[0]] = $ex_[1];
                                }
                            }

                            if(!empty($variation_arr)) {
                                $variation_arr = json_encode($variation_arr);
                                $this->master->insertData('tbl_order_item_meta',['item_id'=>$item_id,'meta_key'=>'variation','meta_value'=>$variation_arr]);
                                echo "<h4>Order variation added </h4>";
                            }

                        }

                        continue;
                    }

//                    if($key === "product_id" && !empty($value)) {
//
//                        $get_wp_product = mysqli_query($db_wp,"SELECT ID,post_name FROM a5jqqtl_posts WHERE ID='".$value."'");
//                        $get_wp_product_ = mysqli_fetch_assoc($get_wp_product);
//
//                        $site_product = $this->master->query("SELECT id FROM tbl_products WHERE slug='".$get_wp_product_['post_name']."'",true,true);
//                        $value = !empty($get_wp_product_['ID']) ? $get_wp_product_['ID'] : 0;
//                    }

                    $db_data = [
                        'item_id'=>$item_id,
                        'meta_key'=>$key,
                        'meta_value'=>$value
                    ];

                    $exists = $this->master->query("SELECT ometa_id FROM tbl_order_item_meta WHERE item_id='$item_id' AND meta_key='$key'",true,true);

                    if(empty($exists)) {

                        $ometaa_id = $this->master->insertData('tbl_order_item_meta',$db_data);
                          echo "<h3>Order item meta added: " . $ometaa_id . "</h3>";

                    }else if($exists['ometa_id']) {

                        $ometaa_id = $exists['ometa_id'];
                        $this->master->insertData('tbl_order_item_meta',$db_data,'ometa_id',$ometaa_id);
                         echo "<h3>Order item meta updated: " . $key . "</h3>";
                    }
                }
            }

            echo "<h3> --------------------------- </h3>";
        }
    }

    public function sync_db_users($postID=0) {

        $db = 'hotnumbers_wp';
        $user = 'root';
        $pass = '';
        $db_wp = mysqli_connect('localhost',$user,$pass,$db);
        $sql = "SELECT * FROM a5jqqtl_users WHERE ID='".$postID."' ORDER BY ID";
        $wp_posts = mysqli_query($db_wp,$sql);



        while ($user = mysqli_fetch_array($wp_posts)) {

            $user_id = $user['ID'];
            $sql = "SELECT * FROM a5jqqtl_usermeta WHERE user_id='".$user_id."'";

            $metas = mysqli_query($db_wp,$sql);

            $password = $user['user_pass'];

            $db_data = [
                'user_id'=>$user_id,
                'username'=>$user['user_login'],
                'display_name'=>$user['display_name'],
                'slug'=>$user['user_url'],
                'email'=>$user['user_email'],
                'password'=>$password,
                'status'=>$user['user_status'],
            ];

            $exists_db = $this->master->getRow('tbl_users',['email'=>$user['user_email']]);

            $roles = [];

            while($meta = mysqli_fetch_array($metas)) {
                $key = $meta['meta_key'];
                $key = str_replace('a5jQqtL_','',$key);
                $value = !empty($meta['meta_value']) ? ($meta['meta_value']) : '';

                if($key === "capabilities") {
                    $caps = unserialize($value);

                    foreach($caps as $k=>$cap) {
                        $rold_id = strtolower($k);
                        $rold_id = str_replace(' ','_',$rold_id);
                        if($rold_id === "wholesale_customers") {
                            $rold_id = "wholesale_customer";
                        }
                        $getCaps = $this->master->query("SELECT * FROM tbl_user_roles WHERE role='$rold_id'",true,true);
                        if(!empty($getCaps)) {
                            $roles[] = $getCaps['id'];
                        }else {
                            $name = str_replace('_',' ',$k);
                            $name = ucfirst($name);
                            $this->master->query("INSERT INTO tbl_user_roles SET role='".($rold_id)."', name='".$name."'");
                            $roles[] = $this->master->last_insert_id();
                        }
                    }
                }
            }

            $roles = array_unique($roles);

            $db_data['role'] = implode(',',$roles);


            if(empty($exists_db)) {
                $this->master->insertData('tbl_users',$db_data);
                $uid = $this->master->last_insert_id();
                echo "Added user ID: $uid <br>";
            }else {
               // $this->master->insertData('tbl_users',$db_data,'email',$exists_db['email']);
                $uid = $exists_db['user_id'];
                echo "Updated user ID: $uid <br>";
            }

            $metas = mysqli_query($db_wp,$sql);

            while($meta = mysqli_fetch_array($metas)) {
                $key = $meta['meta_key'];
                $key = str_replace('a5jQqtL_','',$key);

                if($key === "capabilities") {
                    continue;
                }

                $value = !empty($meta['meta_value']) ? addslashes($meta['meta_value']) : '';

                if($key) {
                    $exists_db = $this->master->getRow('tbl_user_meta', ['meta_key' => $key, 'user_id' => $uid]);

                    if (empty($exists_db)) {
                        $this->master->insertData('tbl_user_meta', ['meta_key' => $key, 'meta_value' => $value, 'user_id' => $uid]);
                        echo "Added user meta: $uid <br>";
                    } else {
                        $this->master->query("UPDATE tbl_user_meta SET meta_value='$value' WHERE user_id='$uid' AND meta_key='$key'");
                        echo "Updated user meta: $uid <br>";
                    }
                }
            }

        }
    }

    public function sync_order_addresses($postID=0) {

        $get_orders = $this->master->query("SELECT order_id,billing_address, shipping_address FROM `tbl_orders` WHERE order_id='$postID'");

        foreach($get_orders as $order) {
            $billing_address = json_decode($order->billing_address,true);
            $shipping_address = json_decode($order->shipping_address,true);
            $oid =$order->order_id;


            foreach($billing_address as $key=>$add) {
                $add = addslashes($add);
                $exists = $this->master->query("SELECT meta_key FROM tbl_order_meta WHERE order_id='$oid' AND meta_key='$key' AND meta_value='$add'");

                if(empty($exists)) {
                    $this->master->query("INSERT INTO tbl_order_meta SET order_id='$oid', meta_key='$key', meta_value='$add'");
                    echo 'Added '.$oid.'<br>';
                }else {
                    echo 'Skipping '.$oid.'<br>';
                }

            }

            foreach($shipping_address as $key=>$add) {
                $add = addslashes($add);
                $exists = $this->master->query("SELECT meta_key FROM tbl_order_meta WHERE order_id='$oid' AND meta_key='$key' AND meta_value='$add'");
                if(empty($exists)) {
                    $this->master->query("INSERT INTO tbl_order_meta SET order_id='$oid', meta_key='$key', meta_value='$add'");
                    echo 'Added '.$oid.'<br>';
                }else {
                    echo 'Skipping '.$oid.'<br>';
                }

            }



        }
    }


    public function index() {

        if(!empty($_GET['wp_post_id'])) {
            $post_ids = explode(',',$_GET['wp_post_id']);
            foreach($post_ids as $id) {
                $this->sync_shop_orders($id);
            }
            exit;
        }

        $db = 'hotnumbers_wp';
        $user = 'root';
        $pass = '';
        $db_wp = mysqli_connect('localhost',$user,$pass,$db); // OR ID=39511  AND ID=24373 12374 ID=2113

        //$sql = "SELECT ID FROM `a5jqqtl_posts` WHERE post_type='product'";
       // $sql .= " AND ID=2113";
        //ID=39511

        //$sql = "SELECT ID FROM `a5jqqtl_posts` WHERE post_type='post'";
        //ID = 9516 AND
        //ID = 41590 AND

        //Discount: 39170

        $sql = "SELECT ID FROM `a5jqqtl_posts` WHERE (post_type='shop_order' OR post_type='shop_subscription') AND ID IN (45397, 45379, 44788, 44260) ORDER BY ID DESC";

     //   $sql = "SELECT ID FROM `a5jqqtl_posts` WHERE  post_type='shop_order' ORDER BY ID DESC";

    //    $sql = "SELECT ID FROM `a5jqqtl_posts` WHERE  post_type='product' ORDER BY ID ASC";

       // $sql = "SELECT ID FROM `a5jqqtl_posts` WHERE  post_type='attachment' ORDER BY ID ASC";

       // $sql = "SELECT term_id as ID FROM `a5jqqtl_terms` ORDER BY term_id ASC";


      //  $sql = "SELECT ID FROM `a5jqqtl_users`";

     //   $get_orders = $this->master->query("SELECT order_id,billing_address, shipping_address FROM `tbl_orders` WHERE order_id > 38641");

        $list = [];

//        foreach($get_orders as $order) {
//            $list[] = $order->order_id;
//        }



        $wp_posts = mysqli_query($db_wp,$sql);

        while($post = mysqli_fetch_array($wp_posts)) {
            $list[] = $post['ID'];
        }

        ?>
        <script>
            let last_ids = <?php echo json_encode($list) ?>;
            let completed = 0;
            let max_threads = 5;

            const forLoop = async _ => {

                let c = 0;


                for(var i in last_ids) {

                    let id = ""+last_ids[i]+"";

                    c++;

                    if(c === max_threads) {
                        await fetch('?wp_post_id='+id).then(res=>res.text()).then((result)=>{
                            document.body.innerHTML = result;
                            //window.scrollTo(0, document.body.scrollHeight);
                            completed++;
                        });
                        c = 0;
                    }else {
                        fetch('?wp_post_id='+id).then(res=>res.text()).then((result)=>{
                            document.body.innerHTML = result;
                            //window.scrollTo(0, document.body.scrollHeight);
                            completed++;
                        });
                    }

                }



                alert("Sync Completed!");
            }
            forLoop();
        </script>
        <?php
    }
}