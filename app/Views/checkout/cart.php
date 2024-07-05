<?php echo view('includes/header.php');?>
<!--- banner -->
<div class="underbanner" style="background: url('<?php echo base_url('./assets/images/coffee-club-subscription/banner.jpg') ?>') no-repeat;  "></div>

<div id="woocommerce-cart-form-div-wrap" class="wrapper">
    <div id="woocommerce-cart-form-div" class="container">
        <?php
        if(!empty($cart['products'])) {

          //  pr($cart);

            ?>
            <div class="woocommerce">
                <h1 class="align_center pagetitle">Basket</h1>

                <!--Cart Desktop View-->
                <table class="shop_table woocommerce-cart-form cart woocommerce-cart-form__contents" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="product-remove" style="width: 2%;">&nbsp;</th>
                        <th class="product-thumbnail" style="width: 16%;">&nbsp;</th>
                        <th class="product-name" style="width: 22%;">Product</th>
                        <th class="product-price" style="width: 20%;">Price</th>

                        <th width="100" class="product-quantity text-center" style="width: 10%;">Quantity</th>
                        <th width="150" class="product-subtotal text-center" style="width: 20%;">Total</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    $product_total = 0;
                    $cart_product_list = [];

                    $sub_plan = get_setting('subscription_plans',true);
                    if(!empty($sub_plan)) {
                        $sub_plan = $sub_plan[0];
                    }
                    $hasError = false;

                    foreach($cart['products'] as $i=>$item) {

                        $product = $ProductsModel->product_by_id($item['product_id'],'*','any');
                        $max_stock = !empty($product->stock) ? 'max="'.$product->stock.'"' : '';
                        $price = $item['price'];
                        $qty = $item['quantity'];

                        $img = !empty($item['img']) ? json_decode($item['img'],true) : 0;
                        if(!empty($img[0])) {
                            $img = $img[0];
                        }

                        $img_src = $media->get_media_src($img,'','thumbnail');
                        $product_variations = !empty($item['variations']) ? $item['variations'] : [];
                        $variation_id = 0;
                        $variation_display = [];

                       if($product->type === "club_subscription") {
                           $available = 1;
                       }else {
                           $available = $ProductsModel->stock_availability($item['product_id'],$item['quantity'], $product_variations);
                       }


                        $error_msg = '';
                        if(!empty($available['error'])) {
                            $error_msg = $available['error'];
                            $hasError = true;
                        }

                        if(!empty($item['attributes'])) {
                            foreach($item['attributes'] as $key=>$value) {
                                $variation_display[$key] = $value;
                            }
                        }

                        if(!empty($item['variations'])) {
                            foreach($item['variations'] as $k=>$v) {
                                $variation_display[$k] = $v;
                            }
                        }

                        if(empty($item['type'])) {
                            $item['type'] = 'product';
                        }

                        if(empty($item['type'])) {
                            $item['type'] = 'product';
                        }

                        $post_type = $item['type'];

                        if($product->sold_individually) {
                            $item['quantity'] = 1;
                        }

                        ?>
                        <tr class="woocommerce-cart-form__cart-item cart_item cart-box">

                            <td class="product-remove">
                                <a href="" class="remove remove_item" onclick="remove_cart_item(this);return false" aria-label="Remove this item" data-item="<?php echo $i; ?>">×</a>
                            </td>

                            <td class="product-thumbnail">
                                <a href="<?php echo base_url('shop/product/'.$product->slug) ?>"><img height="225" src="<?php echo $img_src ?>" class="attachment-woocommerce_thumbnail thumbnail" alt="" loading="lazy" style="width: 150px"></a>
                            </td>

                            <td class="product-name" data-title="Product">
                                <a href="<?php echo base_url('shop/product/'.$product->slug) ?>"><?php echo $product->title ?></a>
                                <?php /*if(!empty($item['free_shipping'])) { ?>
                                <div style="font-weight: 400;"> <small class="d-inline-flex align-items-center"><i class="lni lni-delivery"></i> &nbsp; Free delivery</small></div>
                                <?php }*/ ?>

                                <div style="margin-top: 1.2em;"></div>
                                <?php

                                foreach($variation_display as $k=>$p_var) {
                                    $k = str_replace('variation_','',$k);
                                    $k = str_replace('attribute_','',$k);
                                    $k = str_replace('_',' ',$k);
                                    $k = str_replace('-',' ',$k);
                                    $k = ucfirst($k);
                                    if(is_array($p_var)) {
                                        $p_var = implode(', ',$p_var);
                                    }

                                    if(!empty($p_var)) {
                                    ?>
                                    <dl class="variation" style="margin-bottom: 0;">
                                        <dd><b><?php echo $k ?>:</b> <?php echo ucfirst($p_var) ?></dd>
                                    </dl>
                                    <?php
                                    }
                                }
                                ?>

                                <div class="error_message"><?php echo $error_msg ?></div>

                            </td>

                            <td class="product-price" data-title="Price">
                                    <span class="woocommerce-Price-amount amount"><bdi><span class="product-price-text"><?php echo $item['item_price_html'] ?></span></bdi>
                                    </span>
                            </td>

                            <td class="product-quantity text-center" data-title="Quantity">
                                <?php
                                if($post_type === "product") {
                                    if(!$product->sold_individually) { ?>
                                        <div class="quantity">
                                            <input type="number" class="input-text qty text text-center" step="1" min="1" max="<?php echo $max_stock ?>" name="cart[<?php echo $item['product_id'] ?>][qty]" value="<?php echo $item['quantity'] ?>" title="Qty" size="4" placeholder="" inputmode="numeric" onchange="updateCart(<?php echo $i ?>,this);">
                                        </div>
                                    <?php }
                                    if($post_type === "product" && $product->sold_individually) { ?>
                                        <div class="quantity">
                                            <?php echo $item['quantity'] ?>
                                        </div>
                                    <?php }
                                    elseif($product->sold_individually) {
                                        ?>
                                        <div class="quantity">1</div>
                                        <?php
                                    }
                                }else {
                                    ?>
                                    <input disabled value="1" type="number" class="input-text qty text text-center">
                                    <?php
                                }

                                ?>
                            </td>

                            <td class="product-subtotal text-center" data-title="Total">
                                <span class="woocommerce-Price-amount amount"><bdi><span class="product-total-text"><?php echo $item['display_price_html'] ?></span></bdi></span>
                            </td>
                        </tr>
                        <?php
                        $product_total += $price * $item['quantity'];
                    }
                    ?>

                    </tbody>
                </table>


                <!--Cart Mobile View-->
                <div class="shop_table woocommerce-cart-form cart cart-mobile woocommerce-cart-form__contents">

                    <?php
                    $product_total = 0;

                    foreach($cart['products'] as $i=>$item) {
                        $product = $ProductsModel->product_by_id($item['product_id'],'*','any');
                        $max_stock = !empty($product->stock) ? 'max="'.$product->stock.'"' : '';
                        $price = $item['price'];

                        $img = !empty($item['img']) ? json_decode($item['img'],true)[0] : 0;

                        $img_src = $media->get_media_src($img,'','thumbnail');

                        $product_variations = [];
                        $variation_id = 0;
                        $variation_display = [];
                        if(!empty($item['variations'])) {
                            foreach($item['variations'] as $k=>$v) {
                                $variation_display[$k] = $v;
                            }
                            $product_variations = $item['variations'];
                        }

                        if($product->sold_individually) {
                            $item['quantity'] = 1;
                        }
                        ?>
                        <div class="cart-box">

                            <div class="product-thumbnail">
                                <a href="<?php echo base_url('shop/product/'.$product->slug) ?>">
                                    <img width="300" height="225" src="<?php echo $img_src ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail thumbnail" alt="" loading="lazy">
                                </a>
                            </div>
                            <div class="content">
                                <a href="" class="remove remove_item" onclick="remove_cart_item(this);return false" aria-label="Remove this item" data-item="<?php echo $i; ?>">×</a>
                                <h3> <a href="<?php echo base_url('shop/product/'.$product->slug) ?>"><?php echo $product->title ?></a></h3>

                                <?php


                                foreach($variation_display as $k=>$p_var) {

                                    $k = str_replace('variation_','',$k);
                                    $k = str_replace('attribute_','',$k);
                                    $k = str_replace('_',' ',$k);
                                    $k = ucfirst($k);

                                    if(is_array($p_var)) {
                                        $p_var = implode(', ',$p_var);
                                    }
                                    ?>
                                    <dl class="variation" style="margin-bottom: 0;">
                                        <dt class="variation"><?php echo $k ?>: <?php echo ucfirst($p_var) ?></dt>
                                    </dl>
                                    <?php

                                }
                                ?>
                                <div style="margin-top: 0.5em;"></div>
                                <?php if(!$product->sold_individually) { ?>
                                    <div class="quantity">
                                        <h4>Quantity: </h4>
                                        <input type="number" class="input-text qty text text-center" step="1" min="1" max="<?php echo $max_stock ?>" name="cart[<?php echo $item['product_id'] ?>][qty]" value="<?php echo $item['quantity'] ?>" title="Qty" size="4" placeholder="" inputmode="numeric" onchange="updateCart(<?php echo $i ?>,this);" style="max-width: 100px;">
                                    </div>
                                <?php } ?>


                                <h4 class="price">Price: <bdi><span class="product-total-text"><?php echo $item['display_price_html'] ?></span></bdi></h4>

                            </div>
                        </div>

                        <?php
                    } ?>
                </div>

                <br>

                <div id="cart-collaterals-wrap">
                    <div id="cart-collaterals" class="cart-collaterals">

                        <form class="woocommerce-cart-form" action="<?php echo base_url('cart/checkout') ?>" method="post">
                            <div class="cart_totals">
                                <h2>Basket totals</h2>
                                <div class="relative">
                                    <table cellspacing="0" id="order_totals_review" class="shop_table">
                                        <tbody>
                                        <?php echo view('checkout/checkout_table_basket_totals',['page'=>$page]) ?>
                                        </tbody>
                                    </table>

                                </div>

                                <div class="wc-proceed-to-checkout">
                                    <button id="proceed_to_cart" <?php echo $hasError ? 'disabled':'' ?> type="submit" class="button btn-sm">Proceed to checkout</button>
                                </div>


                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <?php
        }else {
            ?>
            <div class="woocommerce">

                <h1 class="align_center pagetitle"></h1>

                <div class="cart-empty woocommerce-info">Your basket is currently empty.</div>
                <div class="return-to-shop">
                    <a class="button wc-backward" href="<?php echo base_url('shop') ?>"  style="position: absolute; text-decoration: none !important;">Return to shop</a>
                </div>
            </div>
            
            <?php
        } ?>



    </div>
</div>



<script>
    jQuery(function() {
        let t;
        updateCart = function(cartID,ele) {
            clearTimeout(t);
            $('.shop_table').addClass('loading');
            t = setTimeout(()=>{
                const url = '<?php echo base_url('ajax/updatequantity') ?>';
                let price = $(ele).closest('tr').find('.product-price-text').text();
                price = parseFloat(price);

                $.post(url,{item_id:cartID,quantity:ele.value}, function(data) {
                    if(data) {
                        const json = JSON.parse(data);
                        let price = json.price.toFixed(2);

                        if(json.error) {
                            $(ele).closest('.cart_item').find('.error_message').html(json.error);
                            $('#proceed_to_cart').prop('disabled',true);
                        }else {
                            $('#proceed_to_cart').prop('disabled',false);
                            $(ele).closest('.cart_item').find('.error_message').text("");
                        }

                        reload_element('.woocommerce-cart-form, #cart-collaterals-wrap', function() {
                            $('.shop_table').removeClass('loading');
                        })
                      //  $('#cart-collaterals-wrap').load(location.href + ' #cart-collaterals');
                    }
                });
            },120);
        }
    });
</script>




<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


