<?php echo view( 'includes/header');?>

<style>
    .header-caption {
        padding: 40px 0 22px;
        text-align: center;
        max-width: 90%;
        margin: auto;
        display: table;
        font-size: 1.2em;
    }
    .stock.out-stock {
        color: var(--red);
        font-size: 16px;
    }
</style>


<!--main body ---->

<div class="underbanner"  style="background-image:url(<?php echo base_url('./assets/images/shop/banner.jpg') ?>);" ></div>

<!-- wrapper content area --->
<div id="shop" class="woocommerce wrapper content-area">
    <div class="container home-container" role="main">

        <?php

        if(!empty($product))  {
            $single_cat = !empty($category[0]) ? $category[0] : [];
            ?>
            <nav class="woocommerce-breadcrumb">
                <a href="<?php echo base_url() ?>">Home</a>
                <a href="<?php echo base_url('shop/category/'.@$single_cat['slug']) ?>">&nbsp;&#47;&nbsp;<?php echo @$single_cat['name']?></a> <span>&nbsp;&#47;&nbsp;<?php echo $product->title?></span>
            </nav>
            <!--- shop Container ---->
            <div class="shop_container">
                <section class="category_description">
                    <div class="container text-center">
                        <div class="row">
                            <div class="col-md-<?php echo !empty($product->address) ? 6:12 ?> text-left">
                                <h1><?php echo $product->title ?></h1>
                                <?php
                                if($product->status !== 'publish') {

                                    if($product->stock_status=="outofstock"){
                                        $show_status="out of stock";
                                    }elseif($product->stock_status=="instock"){
                                        $show_status="in stock";
                                    }

                                    ?>
                                    <div class="notices-wrapper">
                                        <div><i style="position: relative; top: 2px" class="lni lni-warning"></i> Product status is <?php echo $show_status?></div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <?php if(is_admin()) { ?>
                                    <script>
                                        $('header .hamburger').after(`<a href="<?php echo site_url('admin/products/add/'. $product->id) ?>" class="btn button f16" style="display: inline-block;vertical-align: top;margin: 1px 8px;border-radius: 0;border: 0;">Edit Product</a>`);
                                    </script>
                                <?php } ?>
                            </div>

                            <?php if(!empty($product->address)) {
                                $address = $product->address;
                                $address_link = false;
                                if(preg_match('/\'([^\']+)\'/', $address, $m)) {
                                    $address_link = $m[1];
                                }
                                if($address_link) {
                                    $address = str_replace("'".$address_link."'",'<a target="_blank" href="https://maps.google.com/?q='.$address_link.'">'.$address_link.'</a>', $address); //Replace with google map link
                                }
                                ?>
                            <div class="col-md-6 align-items-end d-flex pb-10">
                                    <div class="text-right w-100">
                                        <img style="width: 14px;margin-right: 5px;margin-top: -4px;" src="<?php echo base_url('./assets/images/') ?>/location.png">

                                        <a href="https://maps.google.com/maps?q=<?=$address?>"><span id="region"><?php echo !empty($address) ? $address : '' ?> &nbsp;</span></a>
                                    </div>
                            </div>
                            <?php } ?>

                        </div>
                        <div class="clear"></div>
                    </div>
                </section>

                <div class="body woocommerce wrapper">
                    <div class="container">



                        <?php if($product->img) {
                            $imgs = json_decode($product->img,true);
                            ?>
                            <div class="content-carousel">
                                <?php
                                foreach($imgs as $img) {
                                    $src = $media->get_media_src($img,'','large');
                                    ?>
                                    <div class="product_image">
                                        <div class="gallery-imgs">
                                            <img src="<?php echo $src ?>">
                                        </div>
                                    </div>
                                <?php }
                                ?>
                            </div>
                            <?php
                        }?>

                    </div>

                    <div class="clear"></div>

                    <div class="product_content">
                        <div class="container">
                            <div class="cont">
                                <article class="flex-block">
                                    <h4 class="p_title"><?php echo $product->title ?> </h4>
                                    <p><?php echo $product->description ?></p>

                                    <?php
                                    $attributes = !empty($product->attributes) && $product->type == "variable" ? json_decode($product->attributes,true) : [];
                                    ?>

                                    <table class="attributes">
                                        <tbody>
                                        <?php foreach($attributes as $attribute){
                                            if(!empty($attribute['attribute_visibility']) && $attribute['attribute_visibility']==1) {
                                                ?>
                                                <tr>
                                                    <th align="top" width="100"><?php echo $attribute['label'] ?></th>
                                                    <td align="top"><?php echo !is_array($attribute['value']) ? $attribute['value'] : implode(' | ',$attribute['value']) ?></td>
                                                </tr>
                                            <?php }
                                        }?>
                                        </tbody>
                                    </table>

                                </article>
                                <article class="flex-block">
                                    <?php
                                    $price = $productModel->product_price($product->id);
                                    $purchaseable = true; 

                                    

                                    $wholesaler_discount_value=0;
                                    
                                    if(is_wholesaler()){
                                        // Call the function to get the discount value of wholesale
                                        $discount_array = global_wholesale_discount_value();

                                        if (is_array($discount_array) && isset($discount_array['role_discount'])) {
                                            $wholesaler_discount_value = $discount_array['role_discount'];
                                                            
                                        } else {
                                            $wholesaler_discount_value=0;
                                        }
                                    }
                                                    

                                    if(is_array($price) && !empty(array_filter($price)) && $product->type == "variable") {
                                        $p = $price[0];
                                        $discount_price = $productModel->product_reduced_price($p);
                                        if($discount_price) {
                                            $p = $discount_price;
                                        }
                                        $price_text = '<span class="woocommerce-Price-currencySymbol">From '._price(number_format($p,2)).'</span>';
                                    }
                                    else if($product->type != "variable") {
                                        $discount_html = '';
                                        if($product->sale_price) {
                                            $old_price = $price;
                                            $price = $product->sale_price;
                                            $discount_html = '<strike class="discount">'._price($old_price).'</strike>';
                                        }
                                        $discount_price = $productModel->product_reduced_price($price);
                                        if($discount_price) {
                                            $price = $discount_price;

                                            if(is_wholesaler()){
                                                $wholesale_discount_price = ($price * $wholesaler_discount_value) / 100;
                                                $final_price=$price-$wholesale_discount_price;
                                                $discount_html = '<strike class="discount">'._price($price).'</strike>';

                                                $price_text = '<span class="woocommerce-Price-currencySymbol">'.$discount_html.' '._price(number_format($final_price,2)).' per item</span>';
                                            }else{
                                                $final_price = $price;
                                                $price_text = '<span class="woocommerce-Price-currencySymbol">'.$discount_html.' '._price(number_format($final_price,2)).' per item</span>';
                                            }

                                        }
                                        // $price_text = '<span class="woocommerce-Price-currencySymbol">'.$discount_html.' '._price(number_format($final_price,2)).' per item</span>';
                                    }

                                    if(!$price) {
                                        $price_text = '';
                                    }

                                    $in_stock = empty($attributes) ? in_stock($product) : true;
                                    $has_quantity = true;
                                    $head_text = 'Purchase';
                                    $button_text = 'Add To Basket';
                                    $button_link = false;

                                    if($product->type == "external") {
                                        $has_quantity = false;
                                        $button_text = !empty($product->button_text) ? $product->button_text : 'View Product';
                                        $button_link = $product->external_url;
                                    }

                                    ?>
                                    <div class="add_to_cart_form">
                                        <div class="header">
                                            <h3 class="pull-left">
                                                <?php 
                                                    if(($product->type=='external') && !empty($product->override_text)){
                                                        echo $product->override_text; 
                                                    }else{
                                                        echo $head_text; 
                                                    }
                                                ?>
                                            </h3>

                                            <div class="pull-right">
                                                <?php echo $in_stock ? $price_text : _price(0); ?>
                                            </div>
                                            <div class="clear"></div>
                                        </div>

                                        <?php if($in_stock) { ?>
                                            <div class="box_content">
                                            <div class="woo_discount_rules_variant_table"></div>

                                            <form class="variations_form cart validate" action="" method="post" enctype="multipart/form-data">

                                                <table class="variations" cellspacing="0">
                                                    <tbody>
                                                    <?php
                                                    $is_variation = false;
                                                    $err = false;

                                                    if(!empty($attributes) && $product->type == "variable") {

                                                        $var_list = [];
                                                        if($product->type == "variable" && !empty($variation_arr)) {
                                                            foreach($variation_arr as $variation) {
                                                                foreach($variation['keys'] as $id=>$k) {
                                                                    $k = empty($k) ? 'any': $k;
                                                                    $var_list[$id][] = $k;
                                                                }
                                                            }
                                                        }


                                                        foreach($attributes as $attribute) {
                                                            $label = $attribute['label'];
                                                            $label_id = strtolower($label);
                                                            $label_id = str_replace(' ','-',$label_id);

                                                            if(!empty($attribute['attribute_variation'])) {
                                                                $is_variation = true;
                                                                ?>
                                                                <tr class="variation_input" data-product="<?php echo $label_id ?>">
                                                                    <td width="130" class="label">
                                                                        <div style="width: 100%">
                                                                            <label for="<?php echo $label_id ?>"><?php echo ucfirst($label) ?></label>

                                                                            <?php if($label_id === 'weight' || $label_id === 'grind') { ?>
                                                                            <span class="popup_open" data-popup="#popup_<?php echo $label_id ?>">
                                                                            <img src="<?php echo site_url('assets/images/question-white.png') ?>" width="100"></span>
                                                                            <?php } ?>

                                                                            <input type="hidden" name="type" value="product">
                                                                        </div>
                                                                    </td>

                                                                    <td style="width: 60%" class="value relative">
                                                                        <?php

                                                                            if(!empty($attribute['value'])) {

                                                                                // Find the label with "first_value_default": "1"
                                                                                $labelWithDefault = null;
                                                                            
                                                                                if (isset($attribute['first_value_default']) && $attribute['first_value_default'] === '1') {
                                                                                    $labelWithDefault = $attribute['label'];
                                                                                }

                                                                                ?>
                                                                                <select class="form-control variation_select" name="variations[attribute_<?php echo $label_id ?>]" required data-error="Please select <?php echo $label ?>">
                                                                                    <?php if ($label != $labelWithDefault) { ?>
                                                                                        <option selected value="">Select an option</option>
                                                                                    <?php } ?>
                                                                                    <?php
                                                                                    foreach($attribute['value'] as $i=>$variation) {
                                                                                        $var_id = strtolower($variation);
                                                                                        $var_id = str_replace(' ','-',$label_id);
                                                                                        $name = 'attribute_'.$var_id;
                                                                                        $var_val = $var_list[$name];

                                                                                        if(in_array($variation,$var_val) || in_array('any',$var_val)) {
                                                                                        ?>
                                                                                        <option value="<?php echo $variation ?>"><?php echo $variation ?></option>
                                                                                        <?php
                                                                                        }

                                                                                    }?>
                                                                                </select>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                        }

                                                    }

                                                    else if(empty($product->stock) && $product->stock_managed == 'yes') {
                                                        $err = 'Out of stock';
                                                    }else {
                                                    ?>
                                                        <script>
                                                            $(function() {
                                                                setTimeout(()=>{
                                                                    $('#input_qunatity').trigger('change');
                                                                },100);
                                                            })
                                                        </script>
                                                        <?php
                                                    }

                                                    if(!$err && empty($product->sold_individually) && $has_quantity) {
                                                        ?>

                                                        <tr>
                                                            <td width="150" class="label">Quantity: </td>
                                                            <td width="60%">
                                                                <input id="input_qunatity" type="number" class="input-text qty text" step="1" min="1"  name="quantity" value="1" title="Qty" size="4" placeholder="" inputmode="numeric">
                                                                <div id="status_message" class="text-right error_message"></div>
                                                            </td>
                                                        </tr>

                                                    <?php }
                                                    ?>

                                                    <tr>
                                                        <td>
                                                            <?php
                                                            if($product->sold_individually) {
                                                            ?>
                                                            <input type="hidden" name="quantity" value="1"></td>
                                                        <?php
                                                        }
                                                        ?>
                                                        <input type="hidden" name="product_id" value="<?php echo $product->id ?>"></td>
                                                    </tr>

                                                    </tbody>
                                                </table>


                                                <?php if(!$err) {
                                                    ?>
                                                    <div class="pt-25"></div>

                                                    <div class="single_variation_wrap d-table w-100">
                                                        <div class="woocommerce-variation-add-to-cart">
                                                            <div id="single_variation_data" class="woocommerce-variation single_variation" <?php if($is_variation) {echo 'style="display:none"';} ?>>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="add_to_basket" class="row" <?php if($is_variation) { echo 'style="display:none"';} ?>>
                                                        <div class="col-md-6 d-flex align-items-center justify-content-start">
                                                            <?php
                                                            /*if(is_logged_in() && $product->subscription) { ?>
                                                            <a href="<?php echo site_url('coffee-club-subscription?product='.$product->id) ?>" class="button bg-transparent text-decoration-underline ml-0">Subscribe & Save</a>
                                                            <?php }*/ ?>
                                                        </div>
                                                        <?php if($purchaseable) {

                                                            ?>
                                                        <div class="col-md-6 justify-content-end d-flex">

                                                            
                                                            <!-- <div class="woocommerce-variation-add-to-cart">
                                                                <?php 
                                                                  
                                                                if(!$button_link) { ?>
                                                                    <button type="submit" class="single_add_to_cart_button button alt"><?php echo $button_text ?></button>
                                                                <?php 
                                                                }else {
                                                                ?>
                                                                    <a href="<?php echo $button_link ?>" class="single_add_to_cart_button button alt" style="border-color: #fff;" target="_blank"><?php echo $button_text ?></a>
                                                                <?php
                                                                }
                                                                    
                                                                ?>
                                                            </div> -->


                                                            <?php 
                                                                   
                                                                if(!$button_link) { 


                                                                     if($product->type ==  "variable"){
                                                                    
                                                                        $product_id=$product->id;
                                                                        $check = check_manage_stock_for_variations($product_id);
                
                                                                        // Decode the JSON string
                                                                        $checkvariations = json_decode($check['variation'], true);
                                                                        
                                                                        if (is_array($checkvariations)) {
                                                                            $manage_stock = 'no'; // Initialize default value
                                                                            $stock_status = 'Out of Stock'; // Default stock status
                                                                    
                                                                            foreach ($checkvariations as $variation) {
                                                                                if ($variation['values']['manage_stock'] == 'yes') {
                                                                                    $manage_stock = 'yes';
                                                                                }
                                                                                
                                                                                if ($variation['values']['stock_status'] == 'instock') {
                                                                                    $stock_status_class = 'stock stock_available';
                                                                                    $stock_status = 'In Stock';
                                                                                    break; // Exit the loop if 'In Stock' is found
                                                                                }
                                                                            }
                                                                        }
                                                                    }

                                                                    if($product->type ==  "variable" && $product->stock_managed=='no' && $manage_stock=='no' && $product->stock_status=='outofstock'){
                                                                        
                                                                    
                                                                ?>
                                                                
                                                                            <p>Out of Stock</p>
                                                                
                                                                <?php 
                                                                    }else{
                                                                ?>
                                                                            <button type="submit" class="single_add_to_cart_button button alt"><?php echo $button_text ?></button>
                                                                
                                                                <?php
                                                                    }
                                                                }else {
                                                                ?>
                                                                    <a href="<?php echo $button_link ?>" class="single_add_to_cart_button button alt" style="border-color: #fff;" target="_blank"><?php echo $button_text ?></a>
                                                                <?php
                                                                }
                                                                    
                                                                ?>

                                                            

                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                <?php }else
                                                {
                                                    ?>
                                                    <div style="text-align: right">
                                                        <h4><?php echo $err ?></h4>
                                                    </div>
                                                    <?php
                                                }?>



                                            </form>

                                        </div>
                                        <?php }
                                        else {
                                            ?>
                                            <div class="box_content">
                                                <p>This product is currently out of stock and unavailable.</p>
                                            </div>
                                            <?php
                                        }?>

                                        <div class="clear"></div>
                                    </div>
                                </article>
                            </div>
                            <br>
                            <hr>

                            <?php if($product->additional_desc) { ?>
                                <div class="product_info">
                                    <h4>Additional Information</h4>
                                    <p></p>
                                    <p><?php echo $product->additional_desc ?></p>
                                    <p></p>
                                </div>
                            <?php }?>

                        </div>
                    </div>



                    <script>
                        $(function() {
                            $('table.variations').find('input,select,textarea').each(function() {
                                const form = this.closest('form');
                                $(this).on('change', function() {
                                    //console.log(form.checkValidity());
                                    if(form.checkValidity()) {
                                        const inputs = $('table.variations').find('input,select,textarea');
                                        $('.add_to_cart_form').addClass('loading');

                                        $.get('<?php echo base_url('shop/product/getvariation') ?>?' + inputs.serialize() + '&html=true', function (res) {

                                            if(res.length) {
                                                $('#single_variation_data').html(res);
                                                $('#single_variation_data').slideDown();
                                                $('#add_to_basket').slideDown();

                                                // Extract error message
                                                var errorMessage = $('.woocommerce-variation-box .error-message').text();
                                                
                                                if(errorMessage=='Out of stock'){
                                                    $('.single_add_to_cart_button').prop('disabled', true);
                                                }else{
                                                    $('.single_add_to_cart_button').prop('disabled', false);
                                                }
                                            }
                                            else {
                                                <?php if($product->type == "variable") { ?>
                                                $('#single_variation_data').html('');
                                                $('#single_variation_data').slideUp();
                                                $('#add_to_basket').slideUp();
                                                <?php } ?>

                                            }

                                            $('.add_to_cart_form').removeClass('loading');

                                            /*$('.single_add_to_cart_button').prop('disabled', false);
                                            $('#item-stock-availability').removeClass('out-stock in-stock');
                                            $('#item-price').text(0);
                                            $('.add_to_cart_form').removeClass('loading');
                                            $('#status_message').text('');
                                            $('.single_add_to_cart_button').prop('disabled',false);

                                            if (res) {
                                                let res_data = JSON.parse(res);
                                                if(res_data.success) {
                                                    if (typeof res_data.values !== "undefined") {
                                                        if (res_data.stock_managed) {
                                                            if (parseInt(res_data.stock_quantity, 10) > 0) {
                                                                $('#item-stock-availability').addClass('in-stock').text('In Stock');
                                                            } else {
                                                                $('#item-stock-availability').addClass('out-stock').text('Out of Stock');
                                                                $('.single_add_to_cart_button').prop('disabled', true);
                                                            }
                                                        } else {
                                                            $('#item-stock-availability').addClass('in-stock').text('In Stock');
                                                        }
                                                        $('#item-price').html(res_data.calculated_price_html);
                                                        $('#single_variation_data').slideDown();
                                                    } else {
                                                        $('#single_variation_data').slideUp();
                                                    }

                                                }else {
                                                    $('#item-price').html(res_data.calculated_price_html);
                                                    $('#single_variation_data').slideDown();
                                                    $('#item-stock-availability').addClass('out-stock').html('<span class="text-red"><i class="icon icon-attention-circled"></i> '+res_data.error+'</span>');
                                                    $('.single_add_to_cart_button').prop('disabled',true);
                                                }
                                            } else {
                                                $('#item-stock-availability').addClass('out-stock').text('Out of Stock');
                                                $('.single_add_to_cart_button').prop('disabled', true);
                                                $('#item-price').text(0);
                                                $('#single_variation_data').slideUp();
                                            }*/
                                        });

                                    }else {
                                        $('#single_variation_data').slideUp();
                                        $('#item-price').text(0);
                                        $('#add_to_basket').slideUp();
                                    }
                                })
                            });
                        })
                    </script>

                    <style>
                        #single_variation_data:has(#subscription_toggle:checked) .subscribe_check_options {
                            display:table-row !important;
                        }
                        .swal2-container .single_add_to_cart.swal2-toast[class].error {
                            min-width: 320px;
                        }
                    </style>

                    <div class="bottom_arrow"><img src="<?php echo base_url('assets/images/shop') ?>/icon-coffee-small.png" width="32"></div>
                </div>

                <?php
                if(!empty($related_products)) {

                    ?>
                    <div class="product_footer">

                        <div class="container">

                            <section class="related products">

                                <h2>Related products</h2>

                                <ul class="products columns-4">

                                    <?php foreach($related_products as $product) {
                                        $image = json_decode($product['img'],true);
                                        $image_url = '';
                                        if(!empty($image)) {
                                            $image_url = $media->get_media_src($image[0]);
                                        }
                                        ?>
                                        <li class="product post-<?php echo $product['id'] ?> <?php echo $product['img'] ? 'has-post-thumbnail':'' ?>">
                                            <a href="<?php echo site_url('shop/product/'.$product['slug']) ?>">
                                            
                                            <!-- <img width="300" height="200" src="<?php echo $image_url ?>" class="thumbnail" alt="<?php echo $product['title'] ?>" loading="lazy"></a> -->

                                            <img 
                                            width="300" 
                                            height="200" 
                                            src="<?php 
                                                $return = base_url().'/assets/images/placeholder.jpg';
                                                echo !empty($image_url) ? $image_url : base_url('public/res.php?src='.$return.'&w=300&h=300'); ?>" 
                                            class="thumbnail" 
                                            alt="<?php echo $product['title']; ?>" 
                                            loading="lazy">
                                             
                                            <a href="<?php echo site_url('shop/product/'.$product['slug']) ?>">
                                                <div class="details">
                                                    <div class="content">
                                                        <h2 class="woocommerce-loop-product__title"><?php echo $product['title'] ?></h2>
                                                        <span class="price">
                                                            <?php
                                                                if($product['type']=='variable'){
                                                                    $variation_price=get_variation_starting_price($product['id']);

                                                                    
                                                                    $variations = json_decode($variation_price['variation'], true);

                                                                    $regular_prices = [];

                                                                    foreach ($variations as $variation) {
                                                                        $regular_prices[] = (float) $variation['values']['regular_price'];
                                                                    }

                                                                    $smallest_price = min($regular_prices);
                                                                                                                                    
                                                            ?>
                                                                <span class="amount">From <span><?php echo currency_symbol ?></span><?php echo number_format($smallest_price, 2) ?></span>
                                                            <?php
                                                                }else{
                                                            ?>
                                                                <span class="amount">From <span><?php echo currency_symbol ?></span><?php echo number_format((float)$product['price'], 2) ?></span>
                                                            <?php
                                                                }
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php }?>

                                </ul>

                            </section>



                            <hr style="padding-bottom: 0;padding-top: 0;margin-top: -0.5em;">
                        </div>
                    </div>
                    <?php
                }

                echo view('shop/popup_grind_info');
                echo view('shop/popup_weight_info');
                ?>


            </div>
        <?php }
        else {
            ?>
            <div class="container">
                <h2>Product not found</h2>
            </div>
            <?php
        }?>
    </div>

</div>

<?php echo view('includes/footer');?>
