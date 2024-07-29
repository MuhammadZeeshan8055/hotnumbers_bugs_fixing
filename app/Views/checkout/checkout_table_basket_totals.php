
<tr class="cart-subtotal">
    <th style="width: 245px">Subtotal</th>
    <td data-title="Subtotal"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"></span><?php echo _price($cart['product_total'])?></bdi></span></td>
</tr>

<?php
$curr_page = $page;
$cartModel = model('CartModel');

$cart = $cartModel->get_cart();
$total_discount = 0;

$product_total = $cart['product_total'];

//pr($cart, false);

if(!empty($cart['global_discount'])) {
    $discount_amount = $cart['global_discount'];
    $discount_text = $cart['global_discount_text'];
    $discount_type = $cart['global_discount_type'];
    $total_discount += $discount_amount;
    ?>
    <tr class="cart-subtotal">
        <th>Shop Discount</th>
        <td>
            <div><span>-<?php echo _price($discount_amount) ?></span>
        </td>
    </tr>
    <?php
}

if(!empty($cart['wholesale_discount'])) {
    $discount_amount = $cart['wholesale_discount'];
    $discount_text = $cart['wholesale_discount_text'];
    $discount_type = $cart['wholesale_discount_type'];
    $total_discount += $discount_amount;
    ?>
    <tr class="cart-subtotal">
        <th>Wholesale Discount</th>
        <td>
            <div><span>-<?php echo _price($discount_amount) ?></span>
        </td>
    </tr>
    <?php
}

if(!empty($cart['user_discount'])) {
    $discount_amount = $cart['user_discount'];
    $discount_text = $cart['user_discount_text'];
    $discount_type = $cart['user_discount_type'];
    $total_discount += $discount_amount;
    ?>
    <tr class="cart-subtotal">
        <th>User Discount</th>
        <td>
            <div><span>-<?php echo _price($discount_amount) ?></span></div>
        </td>
    </tr>
    <?php
}

if(get_setting('enable_product_coupons')) { ?>
    <tr class="coupon">
        <th>Coupon</th>
        <td>
            <div class="d-inline-block" style="max-width: 200px;vertical-align: top">
                <input type="text" name="coupon_code" class="input-text prevent-enter" id="coupon_code" value="" style="font-size: 16px;padding: 6px 15px;margin-bottom: 0;" placeholder="Coupon code">
                <div id="coupon_code_message" style="color:var(--red);font-size: 12px;padding: 0 10px;position: relative;"></div>
            </div>
            <div class="d-inline-block" style="vertical-align: top">
                <button id="apply_coupon" type="button" class="btn btn-sm btn-primary" style="font-size: 12px;margin: 0" name="apply_coupon" value="Apply coupon">Apply coupon</button>
            </div>
        </td>
    </tr>
    <?php
}

if(!empty($cart['coupon_discount_text'])) {
    $coupon_amount = $cart['coupon_discount'];
    $coupon_type = $cart['coupon_type'];
    $coupon_discount_text = $cart['coupon_discount_text'];

    $total_discount += $coupon_amount;
    ?>
    <tr class="cart-subtotal">
        <th>Coupon Discount</th>
        <td>
            <div><span id="discount-amount" data-discount="<?php echo $coupon_amount ?>" data-type="<?php echo $coupon_type ?>">
                     <div><span>-<?php echo _price($coupon_amount) ?></span> <a href="?remove_discount" id="remove_discount_code" class="color-red" style="text-decoration: underline; font-size: 12px; padding-left: 15px">Remove</a></div></div>
                </span>

            <div id="coupon_applied_message" style="color:var(--red);font-size: 12px;padding: 0 10px;position: relative;"></div>
        </td>
    </tr>
    <?php
}



if(!empty($shipping_methods)) {
    $checked_shipping_price = 0;
    $shipping_cost = $cart['shipping_cost'];

//    if(!empty($cart['shipping_discount'])) {
//        $shipping_cost -= $cart['shipping_discount'];
//    }
//
//    if(!empty($cart['shipping_free_products'])) {
//        $product_count = count($cart['products']);
//        $free_ship_count = !empty($cart['shipping_free_products']) ? count($cart['shipping_free_products']) : 0;
//        if($free_ship_count) {
//            $shipping_total = $cart['shipping_cost'] + $cart['shipping_tax'];
//            $free_ship_total = $shipping_total - (($shipping_total / $product_count) * ($product_count - $free_ship_count));
//            //$shipping_cost -= $free_ship_total;
//        }
//    }

    //pr($cart, false);

    if(!empty($cart['shipping_discount']) && !empty($cart['shipping_rule'])) {
        $shipping_options = $cart['shipping_rule'];
        /*if($shipping_options['option'] == 'discount') {
            ?>
            <tr class="woocommerce-shipping-totals shipping" align="top">
                <th width="200">Shipping discount</th>
                <td>-<?php if($shipping_options['type'] === 'percent') {echo $shipping_options['value'].'%';}else {echo $shipping_options['value'];} ?></td>
            </tr>
           <?php
        }*/
    }

    if(!empty($cart['has_shipping'])) {
        $free_ship_count = !empty($cart['shipping_free_products']) ? count($cart['shipping_free_products']) : 0;
        $product_count = count($cart['products']);
        ?>
        <tr class="woocommerce-shipping-totals shipping" align="top">
            <th>Shipping</th>
            <td data-title="Shipping">
                <?php if(empty($cart['free_shipping'])) {
                    ?>
                    <ul id="shipping_method" class="woocommerce-shipping-methods">
                        <?php
                        $method_vat = 0;

                        $shipping_id = $cart['shipping_id'];
                        $vat_price = !empty($cart['total_tax']) ? $cart['total_tax'] : 0;
                        $tax_name = !empty($cart['tax_name']) ? $cart['tax_name'] : '';
                        foreach($shipping_methods as $i=>$method) {
                            $method_name = $method['name'];
                            $method_amount = floatval($method['calculated_value']);
                            if(!empty($shipping_id)) {
                                    $checked = $shipping_id == $i;
                                    $checked_shipping_price = $method_amount;
                            }else {
                                $checked = $i==0;
                                if($i === 0) {
                                    $checked_shipping_price = $method_amount;
                                }
                            }
                            if($cart['shipping_discount']) {
                                $method_amount = free_ship_amount($method_amount,$product_count,$free_ship_count);
                            }
                            if(!empty($cart['shipping_rule'])) {
                                if($cart['shipping_rule']['option'] == 'discount') {
                                    if($cart['shipping_rule']['type'] == 'percent') {
                                        $method_amount = percent_reduce($method_amount, $cart['shipping_rule']['value']);
                                    }
                                    if($cart['shipping_rule']['type'] == 'fixed') {
                                        $method_amount = $method_amount - $cart['shipping_rule']['value'];
                                    }
                                }
                            }
                            $method_amount_text = _price($method_amount);
                            ?>
                            <li>
                                <label for="shipping_method_<?php echo $i; ?>">
                                    <input type="radio" name="shipping_method" id="shipping_method_<?php echo $i; ?>" data-amount="<?php echo $method_amount ?>" value="<?php echo $i ?>" class="shipping_method" data-vat="<?php echo $vat_price ? number_format($vat_price, 2) : '' ?>" data-tax-name="<?php echo $tax_name ?>" <?php echo $checked ? 'checked="checked"':'' ?>>
                                    <?php echo $method_name ?>: <span class="woocommerce-Price-amount amount"><bdi><?php echo $method_amount_text ?></bdi></span>
                                </label>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                        if(!empty($cart['shipping_discount']) && !empty($cart['shipping_rule']) && get_setting('show_shipping_discount')) {
                            $shipping_options = $cart['shipping_rule'];
                            ?>
                            <p>Shipping discount: -<?php if($shipping_options['type'] === 'percent') {echo $shipping_options['value'].'%';}else {echo $shipping_options['value'];} ?></p>
                                <?php
                        }
                    ?>
                    <?php
                    if($curr_page == "cart") {
                        shipping_calculator_html();
                    }
                }
                else {
                    ?>
                    <p>Free shipping</p>
                <?php
                }
                ?>
            </td>
        </tr>
        <?php
    }

    /*if(!empty($cart['shipping_discount'])) {
        $shipping_options = $cart['shipping_options'];
        if($shipping_options['option'] === 'discount') {
           ?>
            <tr class="woocommerce-shipping-totals shipping" align="top">
                <th width="200">Shipping discount</th>
                <td><?php if($shipping_options['type'] === 'percent') {echo $shipping_options['value'].'%';}else {echo $shipping_options['value'];} ?></td>
            </tr>
           <?php
        }
        if($shipping_options['option'] === 'add_cost') {
            ?>
            <tr class="woocommerce-shipping-totals shipping" align="top">
                <th width="200">Additional Shipping Cost</th>
                <td><?php if($shipping_options['type'] === 'percent') {echo $shipping_options['value'].'%';}else {echo $shipping_options['value'];} ?></td>
            </tr>
            <?php
        }
    }*/

    /*if(!empty($cart['shipping_discount'])) {
        ?>
        <tr class="woocommerce-shipping-totals shipping" align="top">
            <th width="200">Shipping discount</th>
            <td data-ship-count="<?php echo $free_ship_count ?>"><?php echo currency_symbol ?><span id="free_shipping_price"><?php echo number_format($cart['shipping_discount'],  2) ?></span></td>
        </tr>
        <?php
    }*/
}

$cart_tax = $cartModel->get_cart_tax();

if(!empty($cart_tax) && $cart['total_tax'] && get_setting('display_tax_price') === "excluding_tax") {
    ?>
    <tr class="shipping_tax">
        <th>Tax:</th>
        <td>
            <?php
            echo _price($cart['total_tax']);
            ?>
        </td>
    </tr>
    <?php
}

?>

<tr class="order-total">
    <th>Total</th>
    <td data-title="Total">
        <strong>
            <span class="woocommerce-Price-amount amount">
                <bdi>
                    <span style="margin-right: -4px"><?php echo currency_symbol ?></span>
                    <span id="total_shipping_amount">
                        <?php
                        if(!empty($cart['has_shipping'])) {
                            $grand_total= $cart['cart_total'];
                        }else{
                            $grand_total= $cart['cart_total']-$cart['shipping_cost'];
                        }
                            echo number_format($grand_total, 2); 
                        ?>
                    </span>
                </bdi>
            </span>
        </strong>

        <span id="shipping-vat" style="font-size: 15px;margin-left: 5px;"><?php if($cart['total_tax'] && get_setting('display_tax_price') == 'including_tax') {echo '(includes '._price($cart['total_tax']).' '.$cart['tax_name'].')';} ?></span>

        <script>
            jQuery(function() {
                $(document).on('click','#apply_coupon', function(e) {
                    e.preventDefault();
                    const code = $('#coupon_code').val();
                    $('#coupon_code_message').text('');
                    const url = '<?php echo base_url('cart/applycode') ?>';
                    $('#order_totals_review').addClass('loading');
                    $.post(url, 'code='+code, function(res) {
                        let result = JSON.parse(res);
                        if(result && result.success) {
                            $('#coupon_code_message').text(result.message);
                            setTimeout(()=>{
                                location.reload();
                            },1500);
                        }else {
                            $('#coupon_code_message').text(result.message);
                        }
                        //$('#coupon_code_message').text(result.message);
                        $('#order_totals_review').removeClass('loading');
                    });
                });

                $(document).on('click','#remove_discount_code', function(e) {
                    e.preventDefault();
                    $('#coupon_applied_message').text('');
                    const url = '<?php echo site_url('cart?remove_discount=1') ?>';
                    message('Remove applied coupon?',{
                        showConfirmButton:true,
                        showCancelButton: true
                    }).then((e)=>{
                        if(e.isConfirmed) {
                            $('#order_totals_review').addClass('loading');
                            $.get(url, function(res) {
                                if(res) {
                                    $('#order_totals_review').removeClass('loading');
                                    $('#coupon_applied_message').text('Coupon removed');
                                    setTimeout(()=>{
                                        location.reload();
                                    },1500);
                                }
                            });
                        }
                    });
                });
            });

            $(document).on('change','[name="shipping_method"]', function() {
                <?php /*let shipping_cost = parseFloat($(this).data('amount'),10);
                <?php
                $prod_total = !empty($cart['product_total']) ? $cart['product_total']+$cart['total_tax'] - floatval($cart['discount_amount']) : 0;
                if($prod_total < 0) {
                    $prod_total = 0;
                }
                ?>
                let total = <?php echo $prod_total ?>;
                $('#coupon_code_message').text('');
                let prod_count = <?php echo count($cart['products']); ?>;
                //$shipping_total - (($shipping_total / $product_count) * ($product_count - $free_ship_count));
                let discount = 0;

                console.log(total);

                let subtotal = (total + shipping_cost);
                let free_ship_count = $('[data-ship-count]').data('ship-count');

                if(free_ship_count) {
                    let ship_count_discount = (shipping_cost / prod_count) * (prod_count - free_ship_count);
                    ship_count_discount = ship_count_discount.toFixed(2);
                    $('#free_shipping_price').html(ship_count_discount);
                    subtotal = (subtotal - ship_count_discount);
                }



                if($(this).data('vat')) {
                    const  vat_amt = $(this).data('vat');
                    //let vat_calc =  ((val/100)*vat_amt);

                    let tax_name = $(this).data('tax-name');

                    // subtotal += vat_calc;
                    // vat_calc = vat_calc.toFixed(2);

                    <?php if(get_setting('display_tax_price') == 'including_tax') { ?>
                    $('#shipping-vat').text('(includes <?php echo currency_symbol ?>'+vat_amt+' '+tax_name+')');
                    <?php } ?>

                }else {
                    $('#shipping-vat').text('');
                }

                $('#total_shipping_amount').text((subtotal).toFixed(2));*/ ?>

                $('#cart-collaterals > *').addClass('loading');
                const ship_method = this.value;

                $.post('<?php echo site_url() ?>ajax/switch_shipping_method',{"shipping_method":ship_method}, function(res) {
                    $('#cart-collaterals').load(location.href+' #cart-collaterals > *', function() {
                        $('#cart-collaterals > *').removeClass('loading');
                    });
                });
            });
        </script>
    </td>
</tr>


<?php
function shipping_calculator_html() {
    $input_arr = [
        'calc_state' => ['','State'],
        'calc_city' => ['','City'],
        'calc_postcode' => ['','Postcode'],
        'calc_country' => ['','Country'],
    ];
    $calc_country = '';
    if(is_logged_in()) {
        $UserModel = model('UserModel');
        if(empty(get_setting('tax_based_on')) || get_setting('tax_based_on') == "billing") {
            $billing = $UserModel->get_billing_address();
            if(!empty($billing['billing_country'])) {
                $calc_country = $billing['billing_country'];
                $input_arr = [
                    'calc_country' => [@$billing['billing_country'],'Country'],
                    'calc_state' => [@$billing['billing_state'],'State'],
                    'calc_city' => [@$billing['billing_city'],'City'],
                    'calc_postcode' => [@$billing['billing_postcode'],'Postcode']
                ];
            }

        }
        if(get_setting('tax_based_on') == "shipping") {
            $shipping = $UserModel->get_shipping_address();
            if(!empty($shipping['shipping_country'])) {
                $calc_country = $shipping['shipping_country'];
                $input_arr = [
                    'calc_country' => [@$shipping['shipping_country'],'Country'],
                    'calc_state' => [@$shipping['shipping_state'],'State'],
                    'calc_city' => [@$shipping['shipping_city'],'City'],
                    'calc_postcode' => [@$shipping['shipping_postcode'],'Postcode']
                ];
            }
        }
        set_session('tax_class_address', $input_arr);
    }else {
        set_session('tax_class_address', []);
    }

    if(get_setting('enable_shipping_calculator')) {

        if(is_logged_in()) {
            ?>
            <a href="#" class="color-red" onclick="$('#shipping_calculator').slideToggle('fast');return false">Calculate shipping</a>
            <div id="shipping_calculator" style="display: none">
                <div class="input_field">
                    <select class="select2 w-auto" style="min-width: 300px" name="calc_country">
                        <?php foreach(get_selling_countries() as $code=>$country) {
                            $selected =  $calc_country === $code ? 'selected':'';
                            ?>
                            <option <?php echo $selected ?> value="<?php echo $code ?>"><?php echo $country ?></option>
                            <?php
                        }?>
                    </select>
                </div>
                <?php foreach($input_arr as $key=>$value) { ?>
                    <div class="input_field">
                        <input type="text" class="w-auto" style="min-width: 300px" name="<?php echo $key ?>" value="<?php echo $value[0] ?>" placeholder="<?php echo $value[1] ?>">
                    </div>
                <?php } ?>

                <div class="text-left">
                    <button type="submit" name="shipment_calculator_change" value="1" class="btn btn-sm">Update</button>
                </div>
            </div>

            <?php
        }
    }
}
?>