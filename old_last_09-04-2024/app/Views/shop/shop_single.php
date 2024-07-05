<?php echo view( 'includes/header');?>

<style>
    .row > * {
        flex-shrink: 0;
        width: 100%;
        max-width: 100%;
        padding-right: unset;
        padding-left: unset;
        margin-top: unset;
    }
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
            $single_cat = $category[0];
            ?>
            <nav class="woocommerce-breadcrumb">
                <a href="<?php echo base_url() ?>">Home</a>
                <a href="<?php echo base_url('shop/category/'.$single_cat['slug']) ?>">&nbsp;&#47;&nbsp;<?php echo $single_cat['name']?></a> <span>&nbsp;&#47;&nbsp;<?php echo $product->title?></span>
            </nav>
            <!--- shop Container ---->
            <div class="shop_container">
                <section class="category_description">
                    <div class="container text-center">
                        <div class="pull-left">  <h1><?php echo $product->title ?></h1>
                            <?php if(is_admin()) { ?>
                                <script>
                                    $('header .hamburger').after(`<a href="<?php echo site_url('admin/products/add/'. $product->id) ?>" class="btn button f16" style="display: inline-block;vertical-align: top;margin: 1px 8px;border-radius: 0;border: 0;">Edit Product</a>`);
                                </script>
                            <?php } ?>
                        </div>
                        <div class="pull-right">
                            <?php if(!empty($product->address)) { ?>
                            <img style="width: 14px;margin-right: 5px;margin-top: -4px;" src="<?php echo base_url('./assets/images/') ?>/location.png">
                            <span id="region"><?php echo !empty($product->address) ? $product->address : '' ?> &nbsp;</span></div>
                        <?php } ?>
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
                                    $attributes = !empty($product->attributes) ? json_decode($product->attributes,true) : [];
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

                                    if(is_array($price) && !empty(array_filter($price))) {
                                        $p = $price[0];
                                        $discount_price = $productModel->product_reduced_price($p);
                                        if($discount_price) {
                                            $p = $discount_price;
                                        }

                                        $price_text = '<span class="woocommerce-Price-currencySymbol">From '._price(number_format($p,2)).'</span>';
                                    }else {
                                        $discount_price = $productModel->product_reduced_price($price);
                                        if($discount_price) {
                                            $price = $discount_price;
                                        }
                                        $price_text = '<span class="woocommerce-Price-currencySymbol">'._price(number_format($price,2)).' per item</span>';
                                    }
                                    ?>
                                    <div class="add_to_cart_form">
                                        <div class="header">
                                            <h3 class="pull-left">Purchase</h3>

                                            <div class="pull-right">
                                                <?php echo $price_text; ?>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="box_content">
                                            <div class="woo_discount_rules_variant_table" data-id="37542"></div>

                                            <form class="variations_form cart validate" action="" method="post" enctype="multipart/form-data">

                                                <table class="variations" cellspacing="0" style="">
                                                    <tbody>
                                                    <?php
                                                    $is_variation = false;
                                                    $err = false;
                                                    if(!empty($attributes)) {

                                                        // pr($attributes,false);

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
                                                                        ?>
                                                                        <select class="form-control" name="variations[attribute_<?php echo $label_id ?>]" required data-error="Please select <?php echo $label ?>">
                                                                            <option selected value="">Select an option</option>
                                                                            <?php  foreach($attribute['value'] as $variation) {
                                                                                $var_id = strtolower($variation);
                                                                                $var_id = str_replace(' ','-',$label_id);
                                                                                $name = 'attribute_'.$var_id;
                                                                                ?>
                                                                                <option value="<?php echo $variation ?>"><?php echo $variation ?></option>
                                                                            <?php } ?>
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
                                                    else if($product->stock_managed && empty($product->stock)) {
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

                                                    if(!$err && empty($product->sold_individually)) {
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
                                                    <div class="single_variation_wrap">

                                                        <div class="woocommerce-variation-add-to-cart">

                                                            <div id="single_variation_data" class="woocommerce-variation single_variation" <?php if($is_variation) {echo 'style="display:none"';} ?>>

                                                            </div>

                                                            <button type="submit" class="single_add_to_cart_button button alt">Add to basket</button>


                                                        </div>
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
                                    if(this.value && this.value !== "0" && form.checkValidity()) {
                                        const inputs = $('table.variations').find('input,select,textarea');
                                        $('.add_to_cart_form').addClass('loading');

                                        $.get('<?php echo base_url('shop/product/getvariation') ?>?' + inputs.serialize() + '&html=true', function (res) {

                                            $('#single_variation_data').html(res);
                                            $('#single_variation_data').slideDown();
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
                                    }
                                })
                            });
                        })
                    </script>

                    <style>
                        #single_variation_data:has(#subscription_toggle:checked) .subscribe_check_options {
                            display:table-row !important;
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
                                                <img width="300" height="200" src="<?php echo $image_url ?>" class="thumbnail" alt="<?php echo $product['title'] ?>" loading="lazy"></a>
                                            <a href="<?php echo site_url('shop/product/'.$product['slug']) ?>">
                                                <div class="details">
                                                    <div class="content">
                                                        <h2 class="woocommerce-loop-product__title"><?php echo $product['title'] ?></h2>
                                                        <span class="price">
                                                    <span class="amount">From <span><?php echo currency_symbol ?></span><?php echo (int)$product['price'] ?></span>
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
