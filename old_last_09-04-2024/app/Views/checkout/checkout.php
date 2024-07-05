<!--header-------------->
<?php
$header_scripts = '';
ob_start();
if(env('braintree.enable')) {
    ?>
    <script type="text/javascript" src="https://js.braintreegateway.com/web/dropin/1.39.0/js/dropin.min.js"></script>
    <script type="text/javascript" src="https://js.braintreegateway.com/web/3.87.0/js/client.min.js"></script>
    <script type="text/javascript" src="https://js.braintreegateway.com/web/3.87.0/js/data-collector.min.js"></script>
    <?php
}
if(env('squareup.enable')) {
    $payment_methods = get_setting('payment_method',true);
    if(!empty($payment_methods['squareup']['mode']) && $payment_methods['squareup']['mode'] === "sandbox") {
        ?>
        <script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
        <?php
    }else {
        ?>
        <script type="text/javascript" src="https://js.squareup.com"></script>
        <?php
    }
}
$header_scripts = ob_get_clean();

echo view('includes/header', ['header_scripts'=>$header_scripts]); ?>
<!---headder end-------->

<!--- banner -->
<div class="underbanner"
     style="background: url('<?php echo base_url('./assets/images/coffee-club-subscription/banner.jpg') ?>') no-repeat;  "></div>


<div id="woocommerce-cart-form-div" class="container">
    <?php

    if (!empty($cart)) {

        ?>
        <div class="woocommerce" style="padding-bottom: 0em">
            <h1 class="align_center">Checkout</h1>



            <form action="<?php echo base_url('cart/cart-process') ?>" class="checkout validate woocommerce-checkout"
                  enctype="multipart/form-data" id="checkout" method="post" name="checkout">

                <div>
                    <div class="content">
                        <div class="row-fluid">
                            <div class="col-12 col-sm-12 flexbox fl options" >
                                <div class="border" style="padding: 1em;width: 100%">
                                    <?php echo view('checkout/checkout_fields') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <br>
                <br>

                <h3 id="order_review_heading">Your order</h3>
                <div class="relative">
                    <div class="woocommerce-checkout-review-order" id="order_totals_review">
                        <table class="shop_table woocommerce-checkout-review-order-table">
                            <thead>
                            <tr>
                                <th class="product-name" style="width: 30%">Product</th>
                                <th class="product-total">Subtotal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $sub_plan = get_setting('subscription_plans',true);
                            if(!empty($sub_plan)) {
                                $sub_plan = $sub_plan[0];
                            }

                            foreach ($cart['products'] as $item) {
                                $product = $ProductsModel->product_by_id($item['product_id']);
                                $variation_db = [];
                                $price = $item['price'];
                                $item_type = $product->post_type;
                                $product_variations = [];
                                $variation_display = [];

                                if(!empty($item['attributes'])) {
                                    foreach($item['attributes'] as $key=>$value) {
                                        $key = str_replace('_', ' ', $key);
                                        $key = str_replace('-', ' ', $key);
                                        $product_variations[$key] = $value;
                                    }
                                }

                                $product_type = !empty($product->type) ? $product->type : 'product';

                                if($product->sold_individually) {
                                    $item['quantity'] = 1;
                                }

                                if (!empty($item['variation'])) {

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

                                    if(!empty($sub_plan['plan_enable']) && !empty($item['subscription'])) {
                                        $item_sub_plan = $item['subscription'];
                                        $plan_discount = percent_reduce($item['price'],$sub_plan['discount_percent'],true);
                                    }

                                    foreach ($item['variation'] as $k => $value) {
                                        if (strstr($k, 'variation_')) {
                                            $key = str_replace('variation_', '', $k);
                                            $key = str_replace('_', ' ', $key);
                                            $key = str_replace('-', ' ', $key);
                                            $key = ucfirst($key);
                                            $product_variations[$key] = $value;
                                            $variation_db[$k] = $value;
                                            $variation_display[$key] = $value;
                                        }
                                    }

                                    $variation = $item['variation'];

                                    if (!empty($variation) && !is_array($variation)) {
                                        $variation = json_decode($variation, true);
                                    } else {
                                        $variation = false;
                                    }
                                }
                                ?>
                                <tr class="cart_item">
                                    <td class="product-name">
                                        <?php echo $product->title ?>&nbsp; <strong class="product-quantity">×&nbsp;<?php echo $item['quantity'] ?></strong>
                                        <?php if(!empty($item_type) && $item_type !== 'product') {
                                            ?>
                                            <h6 class="color-red"><?php echo ucfirst($item_type) ?></h6>
                                            <?php
                                        }?>
                                        <div style="margin-bottom: 10px"></div>
                                        <?php
                                        foreach($variation_display as $k=>$p_var) {
                                            $k = str_replace('variation_','',$k);
                                            $k = str_replace('attribute_','',$k);
                                            $k = str_replace('_',' ',$k);
                                            $k = str_replace('-',' ',$k);
                                            $k = ucfirst($k);
                                            ?>
                                            <dl class="variation" style="margin-bottom: 0;">
                                                <dd><b><?php echo $k ?>:</b> <span><?php echo ucfirst($p_var) ?></span></dd>
                                            </dl>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td class="product-total">
                                        <span class="woocommerce-Price-amount amount"></span>
                                        <bdi>
                                            <span class="woocommerce-Price-amount amount"><?php echo $item['display_price_html'] ?></span>
                                        </bdi>
                                    </td>
                                </tr>
                            <?php }
                            ?>

                            </tbody>
                            <tfoot>
                            <?php echo view('checkout/checkout_table_basket_totals',['page'=>$page]) ?>
                            </tfoot>
                        </table>

                    </div>
                </div>

                <div id="order_review_footer">

                    <div class="woocommerce-terms-and-conditions-wrapper">
                        <div class="woocommerce-privacy-policy-text"><p>Your personal data will be used to process your
                                order, support your experience throughout this website, and for other purposes described
                                in our <a href="<?php echo base_url() ?>/privacy-policy/"
                                          class="woocommerce-privacy-policy-link" target="_blank">privacy policy</a>.
                            </p>
                        </div>
                        <p class="form-row validate-required">
                            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox input_button checkbox"
                                   style="padding-top: 0">
                                <input style="display: inline-block;width: auto;margin-right: 5px;" type="checkbox"
                                       class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
                                       required name="terms" id="terms">
                                I have read and agree to the website
                                <a href="<?php echo base_url() ?>/terms-conditions-retail/"
                                   class="woocommerce-terms-and-conditions-link" target="_blank">terms and
                                    conditions</a>
                            </label>
                            <input type="hidden" name="terms-field" value="1">
                        </p>
                    </div>

                    <style>
                        .woocommerce-form__label-for-checkbox .error_message {
                            position: absolute;
                        }
                    </style>

                    <br>

                    <script>
                        const form = document.getElementById('checkout');
                    </script>


                    <?php

                    if($cart['subtotal'] > 0 || !empty($cart['has_subscription'])) {
                        $payment_config = get_setting('payment_method', true);
                        if(env('braintree.enable')) {
                            echo view("checkout/braintree_payment_box");
                        }
                        if(env('squareup.enable')) {
                            echo view("checkout/squareup_payment_box");
                        }
                        if(env('directcheckout.enable')) {
                            echo view("checkout/direct_checkout_box");
                        }
                    }else {
                        echo view("checkout/direct_checkout_box");
                    }
                    ?>


                </div>

            </form>



            <div class="woocommerce-loading-message">
                <h3>Please wait while we are processing your order</h3>
                <div><img src="<?php echo base_url('assets/images/loader-2.svg') ?>"></div>
                <div style="height: 100px"></div>
            </div>
        </div>

        <script>

            function formatDate(sValue) {
                if (sValue.length == 2 || sValue.length == 5)
                    document.querySelector('#exp_date').value = sValue + " / ";
            }

            function validDate(dValue) {
                var result = false;
                dValue = dValue.split(' / ');
                var pattern = /^\d{2}$/;

                if (dValue[0] < 1 || dValue[0] > 12)
                    result = true;

                if (!pattern.test(dValue[0]) || !pattern.test(dValue[1]))
                    result = true;

                if (dValue[2])
                    result = true;

                if (result) {
                    //alert("Please enter a valid date in MM / YY format.");
                }
            }
        </script>


    <?php } ?>

    <div class="payment_logos" style="padding-bottom: 2em">
        <img src="<?php echo base_url('./assets/images/shop/mastercard.jpg') ?>" alt=""/>
        <img src="<?php echo base_url('./assets/images/shop/visa.jpg') ?>" alt=""/>
    </div>
</div>
<!------------footer ---------------------------------------->
<?php echo view('includes/footer.php'); ?>
<!--------------- footer end -------------------------------->
