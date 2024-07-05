<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\FetchCategories;
use CodeIgniter\Model;


class Shop extends BaseController
{
    private $productModel;
    private $media;

    public function __construct()
    {
        $this->productModel = model('ProductsModel');
        $this->media = model('Media');
    }


    //// fetch shop categories data
    public function index()
    {
        $get_categories = $this->productModel->get_shop_categories('c.*',1,'',1);

        foreach($get_categories as $cat) {
            if(!empty($cat)) {
                if($this->productModel->role_category_permission($cat->id)) {
                    $active_cat_products = $this->productModel->products_by_category($cat->id,'AND p.status="publish"','p.title',1);
                    if (!empty($active_cat_products)) {
                        $img = json_decode($cat->img, true);
                        if (is_array($img)) {
                            $img = $img[0];
                        } else {
                            $img = $cat->img;
                        }
                        $image_src = $this->media->get_media_src($img, '', 'large');
                        if (!$image_src) {
                            $image_src = base_url('assets/images/placeholder.jpg');
                        }
                        $data['loop_data'][] = [
                            'title' => $cat->name,
                            'image' => $image_src,
                            'url' => 'shop/category/' . $cat->slug
                        ];
                    }

                }

            }
        }
        $data['title'] = 'Shop';
        $data['list_type'] = 'category';

        return view('shop/shop',$data);
    }

    public function shop_by_category($category='') {
        $master = model('MasterModel');

        $get_category = $master->getRow('tbl_categories',['slug'=>$category,'group_name'=>'product_cat']);

        $data = [];
        if($get_category){
            $data['title'] = $get_category['name'];
            $cat_id = $get_category['id'];
            $get_products = $this->productModel->products_by_category($cat_id,'AND p.status="publish"','p.title,p.ID, p.slug, p.img, p.title, p.price',1);

            foreach($get_products as $product) {
                if($this->productModel->role_product_permission($product['ID'])) {
                    $img = json_decode($product['img'], true);
                    if (!empty($img)) {
                        if (is_array($img)) {
                            $img = $img[0];
                        } else {
                            $img = $product['img'];
                        }
                        $image_src = $this->media->get_media_src($img);
                    } else {
                        $image_src = false;
                    }


                    if (!$image_src) {
                        $image_src = base_url('assets/images/placeholder.jpg');
                    }
                    $data['loop_data'][] = [
                        'title' => $product['title'],
                        'image' => $image_src,
                        'url' => 'shop/product/' . $product['slug']
                    ];
                }
            }

            $data['caption'] = $get_category['description'];

        }

        $data['list_type'] = 'product';

        return view('shop/shop',$data);
    }

    public function product_page($product_slug='') {
        $data = [];

        $status = 'publish';

        if(is_admin()) {
            $status = 'any';
        }

        $data['media'] = model('Media');

        $data['product'] = $this->productModel->product_by_slug($product_slug,'*',$status);
        $pid = $data['product']->id;

        if($this->productModel->role_product_permission($pid)) {

            if ($data['product']) {
                $data['category'] = $this->productModel->product_categories($pid);
                $data['variations'] = $this->productModel->product_variation($pid);
            } else {
                $data['category'] = false;
            }
            $data['media'] = model('Media');
            $data['productModel'] = model('ProductsModel');

            if (!empty($data['category'])) {
                $category = $data['category'][0];
                $cat_products = $this->productModel->category_products($category['id'], 1, " AND pc.product_id!='$pid' AND prod.status='publish' LIMIT 4 ");
                $data['related_products'] = $cat_products;
            }

        }else {
            $data['product'] = false;
        }

        return view('shop/shop_single',$data);
    }

    public function get_product_variation() {
        $get_variations = $this->request->getVar();


        $error = [];
        if(!empty($get_variations)) {
            //$get_variations = array_filter($get_variations);
            $prod_variations = false;

            $quantity = !empty($get_variations['quantity']) ? $get_variations['quantity'] : 1;

            $productModel = model('ProductsModel');

            if (!empty($get_variations['product_id'])) {

                $product = $productModel->product_by_id($get_variations['product_id'],'*','any');

                if($product) {
                    if(!empty($product->stock_managed) && $product->stock_managed === "yes") {
                        if(empty($product->stock) || $product->stock_status === "outofstock") {
                            $error['error'] = 'Product is out of stock';
                        }
                        elseif($quantity > $product->stock && $product->stock_managed) {
                            $error['error'] = 'Quantity is more than available stock';
                        }
                    }
                    if($product->status !== "publish" && !is_admin()) {
                        $error['error'] = 'Product is inactive';
                    }
                }

                if(!empty($get_variations['variations'])) {

                    $variation_list = $get_variations['variations'];

                    $prod_variations = $this->productModel->product_variation($get_variations['product_id'], $variation_list);

                    if(!empty($prod_variations['values'])) {
                        $variation_vals = $prod_variations['values'];
                        if(!empty($variation_vals['manage_stock']) && $variation_vals['manage_stock']==="yes") {
                            if(empty($variation_vals['stock']) || $variation_vals['stock_status'] === "outofstock") {
                                $error['error'] = "Out of stock";
                            }
                            else if($quantity > $variation_vals['stock']) {
                                $error['error'] = "Quantity is more than available stock";
                            }
                        }else {
                            $variation_vals['manage_stock'] = 'no';
                        }
                    }
                }

                $variation_json = [];

                $calculated_price = 0;

                if (!empty($prod_variations)) {
                    $variation_json = $prod_variations;
                    if(!empty($variation_json['values'])) {
                        $values = $variation_json['values'];
                        $product_price = empty($values['sale_price']) ?  $values['regular_price'] :  $values['sale_price'];
                        $calculated_price = $quantity ? $product_price * $quantity : $product_price;
                        $calculated_price = $productModel->product_reduced_price($calculated_price);
                        $variation_json['calculated_price'] = number_format($calculated_price, 2);
                        $variation_json['success'] = 1;
                        $variation_json['error'] = "";

                        $original_price = '';
                        if(!empty($values['sale_price'])) {
                            $original_price = '<small class="strike-through">'._price($values['regular_price']).'</small>';
                        }
                        $variation_json['calculated_price_html'] = '<span class="display-price">'.$original_price._price($variation_json['calculated_price']).'</span>';
                    }
                }else {
                    $product = $this->productModel->product_by_id($get_variations['product_id']);
                    if($product) {
                        //$variation_json = (array)$product;
                        $product_price  = !empty($product->sale_price) ? $product->sale_price : $product->price;
                        $calculated_price = $product_price * $quantity;
                        $calculated_price = $productModel->product_reduced_price($calculated_price);
                    }
                }

                $variation_json['calculated_price'] = (float)$calculated_price;
                $variation_json['regular_price'] = (float)$product->price;
                $variation_json['sale_price'] = (float)$product->sale_price;
                $variation_json['stock_quantity'] = (float)$product->stock;
                $variation_json['stock_status'] = $product->stock_status;
                $variation_json['success'] = 1;
                $variation_json['error'] = "";
                $original_price = '';
                if($variation_json['sale_price']) {
                    $original_price = '<small class="strike-through">'._price($product->price).'</small>';
                }
                $variation_json['calculated_price_html'] = '<span class="display-price">'.$original_price._price($variation_json['calculated_price']).'</span>';

                if(!empty($error)) {
                    $variation_json['error'] = $error['error'];
                    $variation_json['success'] = 0;
                }

                if(!empty($get_variations['html']) && $get_variations['html'] == 'true') {
                    if($variation_json['success']) {
                        $values = $variation_json['values'];
                        if(!empty($values['product_level_subscription']) && $values['product_level_subscription'] == 'yes') {
                            //echo view('shop/shop_subscription_form',['product_price'=>$variation_json['calculated_price'],'product_quantity'=>$quantity]);
                            ?>
                                <div class="shop_subscription_form" data-price="<?php echo $variation_json['calculated_price'] ?>" data-qty="<?php echo $quantity ?>"></div>
                                <script>init_shop_subscription_form()</script>
                                <?php init_subscription_form_script() ?>
                            <?php
                        }
                    ?>

                    <div>
                        <div class="woocommerce-variation-title">Total price:</div>
                        <div class="woocommerce-variation-box">
                            <div class="woocommerce-variation-description"></div>
                            <div class="woocommerce-price">
                                <?php if($values['manage_stock'] == 'yes') { ?>
                                    <div class="woocommerce-variation-availability">
                                        <p id="item-stock-availability" class="<?php echo ($values['stock'] > 0) ? 'in-stock':'out-stock' ?>"><?php echo ($values['stock'] > 0) ? 'In Stock':'Out of stock' ?></p>
                                    </div>
                                <?php } ?>

                                <div class="woocommerce-variation-price">
                                    <span class="price">
                                        <span class="woocommerce-Price-amount amount">
                                            <span id="item-price"><?php echo $variation_json['calculated_price_html'] ?></span>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                }else {
                    echo json_encode($variation_json);
                }
            }
        }
    }

    public function getAddressAutoComplete($str='', $id='') {
        $user = model("UserModel");
        if($id) {
            $getRes = $user->getaddressdata($id);
        }else {
            $getRes = $user->postCodeLookup($str);
        }

        echo json_encode($getRes);
    }

}
