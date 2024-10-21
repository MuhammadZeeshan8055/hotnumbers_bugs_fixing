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

    public function get_price() {
        $productId = $this->request->getPost('product_id');
        $targetVariation = $this->request->getPost('variation');
    
        // Get the variation product price
        $values = get_variation_product_price($productId);
    
        // Decode the JSON string in the 'variation' key
        $variations = json_decode($values['variation'], true);
    
        // Search through variations to find the one that matches weight, size, or quantity
        foreach ($variations as $variation) {
            // Check if targetVariation matches 'attribute_weight', 'attribute_size', or 'attribute_quantity'
            if (
                (isset($variation['keys']['attribute_weight']) && $variation['keys']['attribute_weight'] == $targetVariation) ||
                (isset($variation['keys']['attribute_size']) && $variation['keys']['attribute_size'] == $targetVariation) ||
                (isset($variation['keys']['attribute_quantity']) && $variation['keys']['attribute_quantity'] == $targetVariation)
            ) {
                // If matching variation is found, print the price
                echo $variation['values']['regular_price'];
                return; // Stop after finding the match
            }
        }
    
        // If no matching variation is found, display a message
        echo "No matching variation found.";
    }





    // public function get_price() {
    //     $productId = '3488';
    //     $targetVariation = '250g'; // The variation you want to find
    
    //     // Get the variation product price
    //     $values = get_variation_product_price($productId);
    
    //     // Decode the JSON string in the 'variation' key
    //     $variations = json_decode($values['variation'], true);
    
    //     // Search through variations to find the one that matches the target variation
    //     foreach ($variations as $variation) {
    //         if ($variation['keys']['attribute_weight'] == $targetVariation) {
    //             // If matching variation is found, print the price
    //             echo $variation['values']['regular_price'];
    //             return; // Stop after finding the match
    //         }
    //     }
    
    //     // If no matching variation is found, display a message
    //     echo "No matching variation found.";
    // }
    

    //// fetch shop categories data
    // public function index()
    // {
    //     $get_categories = $this->productModel->get_shop_categories('c.*',1,'',1);

    //     foreach($get_categories as $cat) {
    //         if(!empty($cat)) {
    //             if($this->productModel->role_category_permission($cat->id)) {
    //                 $active_cat_products = $this->productModel->products_by_category($cat->id,'AND p.status="publish"','p.title',1);
    //                 if (!empty($active_cat_products)) {
    //                     $img = json_decode($cat->img, true);
    //                     if (is_array($img)) {
    //                         $img = $img[0];
    //                     } else {
    //                         $img = $cat->img;
    //                     }
    //                     $image_src = $this->media->get_media_src($img, '', 'large');
    //                     if (!$image_src) {
    //                         $image_src = base_url('assets/images/placeholder.jpg');
    //                     }
    //                     $data['loop_data'][] = [
    //                         'title' => $cat->name,
    //                         'image' => $image_src,
    //                         'url' => 'shop/category/' . $cat->slug
    //                     ];
    //                 }

    //             }

    //         }
    //     }
    //     $data['title'] = 'Shop';
    //     $data['list_type'] = 'category';

    //     return view('shop/shop',$data);
    // }

    public function index()
    {
        $get_categories = $this->productModel->get_shop_categories('c.*', 1, '', 1);
        $data = [];
        
        foreach ($get_categories as $cat) {
            if (!empty($cat)) {
                if ($this->productModel->role_category_permission($cat->id)) {
                    // Fetch category products
                    $active_cat_products = $this->productModel->products_by_category($cat->id, 'AND p.status="publish"', 'p.title, p.ID, p.type, p.attributes, p.stock_managed, p.stock, p.sold_individually, p.slug, p.img, p.title, p.price', 0, 1);

                    if (!empty($active_cat_products)) {
                        // Category Image
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

                        // Add category data
                        $category_data = [
                            'cid' => $cat->id,
                            'title' => $cat->name,
                            'image' => $image_src,
                            'url' => 'shop/category/' . $cat->slug,
                            'products' => []
                        ];

                        // Add product data for each category
                        foreach ($active_cat_products as $product) {
                            if ($this->productModel->role_product_permission($product['ID'])) {

                                $product_data = $this->productModel->product_by_id($product['ID'], '*', 'any');
       

                                $prod_img = json_decode($product['img'], true);
                                if (is_array($prod_img)) {
                                    $prod_img = $prod_img[0];
                                } else {
                                    $prod_img = $product['img'];
                                }
                                $product_image_src = $this->media->get_media_src($prod_img);
                                if (!$product_image_src) {
                                    $product_image_src = base_url('assets/images/placeholder.jpg');
                                }

                                // Append product data to category
                                $category_data['products'][] = [
                                    'id' => $product['ID'],
                                    'type' => $product['type'],
                                    'attributes' => $product['attributes'],
                                    'stock_managed' => $product['stock_managed'],
                                    'sold_individually' => $product['sold_individually'],
                                    'stock' => $product['stock'],
                                    'title' => $product['title'],
                                    'image' => $product_image_src,
                                    'url' => 'shop/product/' . $product['slug'],
                                    'price' => $product['price'],
                                    'stock_status'=> $product['stock_status']
                                ];
                            }
                        }

                        // Store category data including its products
                        $data['loop_data'][] = $category_data;
                    }
                }
            }
        }

        $data['title'] = 'Shop';
        $data['list_type'] = 'category';

        return view('shop/shop', $data);
    }


    public function shop_by_category($category='') {
        $master = model('MasterModel');

        $get_category = $master->getRow('tbl_categories',['slug'=>$category,'group_name'=>'product_cat']);

        $data = [];
        if($get_category){
            $data['title'] = $get_category['name'];
            $cat_id = $get_category['id'];
            $get_products = $this->productModel->products_by_category($cat_id,'AND p.status="publish"','p.title,p.ID, p.slug, p.img, p.title, p.price',0,1);

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

        // $status = 'publish';

        // if(is_admin()) {
        //     $status = 'any';
        // }

        $status = 'any';

        $data['media'] = model('Media');

        $data['product'] = $this->productModel->product_by_slug($product_slug,'*',$status,'','',1);

        //pr($data['product']);

        if(!empty($data['product'])) {
            $pid = $data['product']->id;
            $data['variation_arr'] = [];

            if($this->productModel->role_product_permission($pid)) {
                if ($data['product']) {
                    $data['category'] = $this->productModel->product_categories($pid);
                    $data['variations'] = $this->productModel->product_variation($pid);
                    $data['variation_arr'] = $this->productModel->product_variations($pid);
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

                    if(!empty($get_variations['variations'])) {
                        $variation_list = $get_variations['variations'];
                        $available = $this->productModel->stock_availability($get_variations['product_id'], $quantity, $variation_list);
                        if(!empty($available['error'])) {
                            $error['error'] = $available['error'];
                        }
                        $prod_variations = $available['variations'];
                        $prod_variations = $this->productModel->product_variation($get_variations['product_id'], $variation_list);
                        if(!empty($prod_variations['values'])) {
                            $variation_vals = $prod_variations['values'];
                            if(!empty($variation_vals['manage_stock']) && $variation_vals['manage_stock']==="yes") {
                                $error['error'] = '';
                                if(empty($variation_vals['stock']) || $variation_vals['stock_status'] === "outofstock") {
                                    $error['error'] = "Out of stock";
                                }
                                else if($quantity > $variation_vals['stock']) {
                                    $error['error'] = "Quantity is more than available stock";
                                }
                            }
                            else {
                                $variation_vals['manage_stock'] = 'no';
                            }
                        }
                    }



                    if($product->status !== "publish" && !is_admin()) {
                        $error['error'] = 'Product is inactive';
                    }

                }



                if (!empty($prod_variations)) {
                    $variation_json = $prod_variations;
                    if(!empty($variation_json['values'])) {
                        $values = $variation_json['values'];
                        $regular_price = $values['regular_price'];
                        $sale_price = !empty($values['sale_price']) ? $values['sale_price'] : 0;

                        $product_price = empty($values['sale_price']) ?  $values['regular_price'] :  $values['sale_price'];
                        $calculated_price = $quantity && $product_price ? $product_price * $quantity : $product_price;

                        $calculated_price = $productModel->product_reduced_price($calculated_price);
                        $variation_json['calculated_price'] = $calculated_price ? number_format($calculated_price, 2) : 0;

                        $original_price = '';
                        if(!empty($values['sale_price']) && $values['sale_price'] != $values['regular_price']) {
                            $original_price = '<span class="strike-through">'._price($values['regular_price']).'</span>';
                        }
                        $variation_json['calculated_price_html'] = '<span class="display-price">'.$original_price._price($variation_json['calculated_price']).'</span>';
                    }
                }
                else {
                    if($product) {
                        //$variation_json = (array)$product;
                        $product_price  = !empty($product->sale_price) ? $product->sale_price : $product->price;
                        $calculated_price = $product_price * $quantity;
                        $calculated_price = $productModel->product_reduced_price($calculated_price);
                        $regular_price = $product->price;
                        $sale_price = !empty($product->sale_price) ? $product->sale_price : 0;
                    }
                }

                if($product->tax_status === "taxable" && get_setting("shop_price_with_tax") === "inclusive") {
                    $prod_tax = $productModel->tax_price($product, $prod_variations);
                    $calculated_price += $prod_tax;
                    $regular_price += $prod_tax;
                }

                $variation_json['calculated_price'] = $calculated_price;
                $variation_json['regular_price'] = $regular_price;
                $variation_json['sale_price'] = $sale_price;
                $variation_json['stock_quantity'] = (float)$product->stock;
                $variation_json['stock_status'] = $product->stock_status;
                $variation_json['success'] = 1;
                $variation_json['error'] = "";
                $original_price = '';

                if($sale_price && $regular_price!=$sale_price) {
                    $original_price = '<span class="strike-through">'._price($regular_price).'</span> ';
                }

                if(is_wholesaler()){
                    // Call the function to get the discount value of wholesale
                    $discount_array = global_wholesale_discount_value();

                    if (is_array($discount_array) && isset($discount_array['role_discount'])) {
                        $wholesaler_discount_value = $discount_array['role_discount'];
                        $wholesale_discount_price = ($calculated_price * $wholesaler_discount_value) / 100;
                        $final_price=$calculated_price-$wholesale_discount_price;
                                        
                    } else {
                        $wholesaler_discount_value=0;
                    }
                }
                if(is_wholesaler()){
                    $original_price = '<span class="strike-through">'._price($calculated_price).'</span> ';
                    $variation_json['calculated_price_html'] = $original_price._price($final_price); 
                }else{
                    $variation_json['calculated_price_html'] = $original_price._price($calculated_price); 
                }

                // $variation_json['calculated_price_html'] = $original_price._price($calculated_price); 

                if(!empty($error)) {
                    $variation_json['error'] = $error['error'];
                    $variation_json['success'] = 0;
                }

                if(!empty($get_variations['html']) && $get_variations['html'] == 'true') {
                    if(!empty($variation_json['values'])) {
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
                                <?php
                                if(empty($variation_json['error'])) {
                                if(!empty($values['manage_stock']) && $values['manage_stock'] == 'yes') { ?>
                                    <div class="woocommerce-variation-availability">
                                        <p id="item-stock-availability" class="<?php echo ($values['stock'] > 0) ? 'in-stock':'out-stock' ?>"><?php echo ($values['stock'] > 0) ? 'In Stock':'Out of stock' ?></p>
                                    </div>
                                <?php }
                                ?>

                                <div class="woocommerce-variation-price">
                                    <span class="price">
                                        <span class="woocommerce-Price-amount amount">
                                            <span id="item-price"><?php echo $variation_json['calculated_price_html'] ?></span>
                                        </span>
                                    </span>
                                </div>
                                <?php }
                                else {
                                    ?>
                                <p class="error-message"><?php echo $variation_json['error'] ?></p>
                                <div class="woocommerce-variation-price">
                                    <span class="price">
                                        <span class="woocommerce-Price-amount amount">
                                            <span id="item-price"><?php echo $variation_json['calculated_price_html'] ?></span>
                                        </span>
                                    </span>
                                </div>
                                <?php
                                }?>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                }
                else {
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

    public function set_refund_status() {
        $post = $this->request->getPost();
        if(!empty($post['order_id'])) {
            $order_id = $post['order_id'];
            $refund_amount = $post['refund_amount'];
            $refund_stock = $post['refund_stock'];
            $refund_reason = $post['refund_reason'];
            $orderModel = model('OrderModel');
            $productModel = model('ProductsModel');
            $order = $orderModel->get_order_by_id($post['order_id']);

            $orderModel->add_order_meta($order_id,'refund_reason',$refund_reason);

            if($refund_stock) {
                foreach($order['order_items'] as $item) {
                    if($item['item_type'] === "line_item") {
                        $qty = $item['item_meta']['quantity'];
                        $product_id = $item['item_meta']['product_id'];
                        $productModel->gain_stock($product_id, $qty);
                    }
                }
            }

            $orderModel->change_order_status($order_id,'refund');

            $get_refund = $orderModel->order_meta($order_id,'order_refund');
            if(empty($get_refund)) {
                $get_refund = [];
            }else {
                $get_refund = json_decode($get_refund['order_refund'],true);
            }
            $get_refund[] = [time() => $refund_amount];
            $get_refund = json_encode($get_refund);
            $orderModel->add_order_meta($order_id,'order_refund',$get_refund);

            $note_text = _price($refund_amount)." refunded to customer";
            if($refund_stock) {
                $note_text .= " and items were restocked";
            }
            $orderModel->add_note($order_id,$note_text);

            $orderModel->refund_order_email($order_id);
        }

        return redirect()->back();
    }

}
