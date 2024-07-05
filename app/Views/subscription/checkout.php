
<!--header-------------->
<?php echo view('includes/header.php');?>
<!---headder end-------->

<div class="underbanner" style="background: url('<?php echo base_url('./assets/images/coffee-club-subscription/banner.jpg') ?>') no-repeat;  "></div>
<div class="woocommerce wrapper content-area">
    <div class="container" role="main">

        <nav class="woocommerce-breadcrumb">
            <a href="<?php echo base_url() ?>">Home</a>
            &nbsp;&#47;&nbsp;
            <a href="#">Coffee Subscriptions</a>
            &nbsp;&#47;&nbsp;
            <span>The Coffee Club</span>
        </nav>
        <form style="width: 100%" action="<?php echo base_url('coffee-club-subscription/submit') ?>" class="checkout woocommerce-checkout"
              enctype="multipart/form-data" id="checkout" method="post" name="checkout">

        <div class="shop_container coffee-club">
            <br>
            <br>
            <!--header box -->
            <div class="header">
                <div>
                    <div class="row-fluid">
                        <div class="col-9 col-xs-12 fl" >
                            <h3 style="text-transform: none;padding-left: 8px" class="f28">Checkout</h3>
                        </div>

                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <!--header box end -->

            <div class="subscription_form">
                <div class="content">
                    <div class="row-fluid">
                        <div class="col-12 col-sm-12 flexbox fl options" >
                    <div class="border" style="padding: 1em;">


                            <?php echo view('checkout/checkout_fields') ?>

                            <div class="clearfix"></div>


                    </div>
                        </div>
                    </div>
                </div>
            </div>



            <br>
            <br>
            <!--header box -->
            <div class="header">
                <div>
                    <div class="row-fluid">
                        <div class="col-9 col-xs-12 fl" >
                            <h3 style="text-transform: none;margin-left: 8px" class="f28">Subscription Review</h3>
                        </div>

                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <!--header box end -->

            <?php
            if(!is_logged_in()) {
                ?>
                <article class="col-8 col-sm-12 options">
                    <p><strong>You must be logged in to purchase this product!</strong></p>
                </article>

                <div class="clearfix"></div>
                <?php
            }
            ?>

            <?php
             if(empty($variation) || empty($variation_data)) {
                 ?>
                 <article class="col-8 col-sm-12 options">
                     <p><strong>You must be logged in to purchase this product!</strong></p>
                 </article>
                <?php
             }
            ?>

            <div class="body <?php echo !is_logged_in() ? 'disabled':'' ?>">
                <table width="100%" class="shop_table shop_table_responsive">
                    <tr>
                        <th>Subscription Type</th>
                        <td>
                            <?php echo !empty($subscription_type) ? ucfirst($subscription_type) : '' ?>
                            <input type="hidden" name="subscription-type" value="<?php echo !empty($subscription_type) ? ($subscription_type) : '' ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>Duration</th>
                        <td><?php echo !empty($subscription_duration) ? ucfirst($subscription_duration) : '' ?>
                            <input type="hidden" name="subscription_duration" value="<?php echo !empty($subscription_duration) ? ($subscription_duration) : '' ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>Coffee</th>
                        <td><?php echo !empty($product->title) ? ucfirst($product->title) : '' ?>
                        </td>
                    </tr>

                    <?php
                    $i = 4;

                    foreach($variation as $id=>$key) {
                        $id = ucfirst($id);
                        ?>
                        <tr>
                            <th><?php echo $id ?></th>
                            <td><?php echo $key; ?></td>
                        </tr>
                        <?php $i++; }
                    ?>



                    <?php
                    if (!empty($shipping_methods)) {
                        $shipping_methods = json_decode($shipping_methods->value);
                        ?>
                        <tr class="woocommerce-shipping-totals shipping">
                            <th>Shipping</th>
                            <td data-title="Shipping">
                                <ul class="woocommerce-shipping-methods" id="shipping_method">
                                    <?php
                                    foreach ($shipping_methods->name as $i => $method_name) {
                                        $method_amount = $shipping_methods->amount[$i];
                                        $vat_price = $shipping_methods->vat[$i];
                                        $method_amount = number_format($method_amount,2);
                                        ?>
                                        <li>
                                            <input type="radio" name="shipping_method"
                                                   id="shipping_method_<?php echo $i; ?>"
                                                   data-amount="<?php echo $method_amount ?>"
                                                   value="<?php echo $i ?>"
                                                   class="shipping_method" required
                                                   data-vat="<?php echo $vat_price ?>">
                                            <label for="shipping_method_<?php echo $i; ?>"><?php echo $method_name ?>
                                                : <span class="woocommerce-Price-amount amount"><bdi><span
                                                                class="woocommerce-Price-currencySymbol"><?php echo currency_symbol ?></span><?php echo $method_amount ?></bdi></span>
                                            </label>
                                        </li>
                                        <?php
                                    } ?>

                                </ul>
                            </td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <th>Subscription price</th>
3                        <td><span class="color-red"  style="font-weight: bolder"><?php echo currency_symbol ?><span id="total_shipping_amount"><?php echo $variation_data['values']['regular_price'] ?></span></span>   <span id="shipping-vat" style="font-size: 15px;margin-left: 5px;"></span> </td>
                    </tr>

                </table>
            </div>

            <script>
                $(document).on('change','[name="shipping_method"]', function() {
                    const val = parseFloat($(this).data('amount'),10);
                    const total = <?php echo $variation_data['values']['regular_price']; ?>;
                    const discount = <?php echo !empty($cart['product_total']) ? floatval($cart['discount_amount']) : 0 ?>;
                    let subtotal = total + val - discount;
                    $('#total_shipping_amount').text((subtotal).toFixed(2));
                    if($(this).data('vat')) {
                        const vat_amt = $(this).data('vat');
                        let vat_calc = val - ( val / ( ( vat_amt / 100 ) + 1 ) );
                        vat_calc = vat_calc.toFixed(2);
                        $('#shipping-vat').text('(includes £'+vat_calc+' VAT)');
                    }else {
                        $('#shipping-vat').text('');
                    }
                });
            </script>

            <br>
            <br>

            <!--header box -->
            <div class="header">
                <div>
                    <div class="row-fluid">
                        <div class="col-9 col-xs-12 fl" >
                            <h3 style="text-transform: none;margin-left: 8px" class="f28">Make Payment</h3>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <!--header box end -->

            <div class="border" style="padding: 1em">
                <div id="order_review_footer">

                    <div class="woocommerce-terms-and-conditions-wrapper">
                        <div class="woocommerce-privacy-policy-text"><p>Your personal data will be used to process your
                                order, support your experience throughout this website, and for other purposes described
                                in our <a href="<?php echo base_url() ?>privacy-policy/"
                                          class="woocommerce-privacy-policy-link" target="_blank">privacy policy</a>.
                            </p>
                        </div>
                        <p class="form-row validate-required">
                            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox"
                                   style="padding-top: 0">
                                <input style="display: inline-block;width: auto;margin-right: 5px;" type="checkbox"
                                       class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
                                       required name="terms" id="terms">
                                I have read and agree to the website
                                <a href="<?php echo base_url() ?>terms-conditions-retail/"
                                   class="woocommerce-terms-and-conditions-link" target="_blank">terms and
                                    conditions</a>
                            </label>
                            <input type="hidden" name="terms-field" value="1">
                        </p>
                    </div>

                </div>

                <div style="display:table">
                    <div class="payment_box">
                        <input type="hidden" name="checkout_place_order" value="1">
                        <input type="hidden" name="type" value="subscription">
                        <input type="hidden" name="variation" value='<?php echo json_encode($variation) ?>'>
                        <input type="hidden" name="product_id" value="<?php echo $product->id ?>">

                        <button type="submit" name="proceed" value="1" style="margin-top: 1em;">Complete Order</button>
                        <div>
                            <?php
                            /*<p style="margin-bottom: 0;padding-bottom: 15px;">Pay with your credit card via Stripe.<br>
                                <span style="font-size: 14px; opacity: 0.5;">*All fields are required</span></p>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-row">
                                        <label>Expiry Date<span class="required">*</span></label>
                                        <input id="exp_date" placeholder="MM / YY" value="02 / 25" class="input-text" onkeyup="formatDate(this.value);" onblur="validDate(this.value);" maxlength="7" required>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-row">
                                        <label>Card Number<span class="required">*</span></label>
                                        <input id="card-number" placeholder="1234 1234 1234 1234" class="input-text" required>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-row">
                                        <label>Card Code (CVC)<span class="required">*</span></label>
                                        <input id="cvc" type="number" placeholder="CVC" class="input-text" value="222" maxlength="3" required>
                                    </div>
                                </div>
                            </div>*/ ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        </form>

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    </div>
</div>




<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


