
<tr class="cart-subtotal">
    <th style="max-width:320px">Subtotal</th>
    <td data-title="Subtotal"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"></span><?php echo _price($cart['product_total'])?></bdi></span></td>
</tr>

<?php
$curr_page = $page;
if(!empty($shipping_methods)) {
    $checked_shipping_price = 0;
    $shipping_cost = $cart['shipping_cost'];

    if(!empty($cart['shipping_discount'])) {
        $shipping_cost -= $cart['shipping_discount'];
    }

    if(!empty($cart['shipping_free_products'])) {
        $free_ship_count = !empty($cart['shipping_free_products']) ? count($cart['shipping_free_products']) : 0;
        if($free_ship_count) {
            $shipping_total = $cart['shipping_cost'] + $cart['shipping_tax'];
            $free_ship_total = $shipping_total * $free_ship_count;
            $shipping_cost -= $free_ship_total;
        }
    }

    if(!empty($cart['shipping_cost_item_count']) && $shipping_cost > 0) {
        ?>
        <tr class="woocommerce-shipping-totals shipping" align="top">
            <th>Shipping</th>
            <td data-title="Shipping">
                <ul id="shipping_method" class="woocommerce-shipping-methods">
                    <?php
                    $method_vat = 0;
                    $shipping_id = $cart['shipping_id'];
                    $vat_price = 0;
                    foreach($shipping_methods as $i=>$method) {
                        $method_name = $method['name'];
                        $vat_price = !empty($method['tax_amount']) ? $method['tax_amount'] : 0;
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
                        $tax_name = '';
                        $method_amount = number_format($method_amount,2);
                        ?>
                        <li>
                            <label for="shipping_method_<?php echo $i; ?>">
                                <input type="radio" name="shipping_method" id="shipping_method_<?php echo $i; ?>" data-amount="<?php echo $method_amount ?>" value="<?php echo $i ?>" class="shipping_method" data-vat="<?php echo $vat_price ?>" data-tax-name="<?php echo $tax_name ?>" <?php echo $checked ? 'checked="checked"':'' ?>>
                                <?php echo $method_name ?>: <span class="woocommerce-Price-amount amount"><bdi>
                                                        <?php echo currency_symbol.$method_amount ?></bdi></span>

                            </label>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
                if($curr_page == "cart") {
                    shipping_calculator_html();
                }

                ?>
            </td>
        </tr>
        <?php
    }

    if(!empty($cart['shipping_discount'])) {
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
    }

    if(!empty($cart['shipping_free_products']) && $shipping_cost > 0 && !empty($free_ship_total)) {
        ?>
        <tr class="woocommerce-shipping-totals shipping" align="top">
            <th width="200">Shipping discount</th>
            <td data-ship-count="<?php echo $free_ship_count ?>"><?php echo currency_symbol ?><span id="free_shipping_price"><?php echo $free_ship_total ?></span></td>
        </tr>
        <?php
    }
}

if(get_setting('enable_product_coupons')) { ?>
    <tr class="coupon">
        <th>Coupon:</th>
        <td>
            <div class="d-inline-block" style="max-width: 200px;vertical-align: top">
                <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" style="font-size: 16px;padding: 6px 15px;margin-bottom: 0;" placeholder="Coupon code">
                <div id="coupon_code_message" style="color:var(--red);font-size: 12px;padding: 0 10px;position: relative;"></div>
            </div>
            <div class="d-inline-block" style="vertical-align: top">
                <button id="apply_coupon" type="button" class="btn btn-sm btn-primary" style="font-size: 12px;margin: 0" name="apply_coupon" value="Apply coupon">Apply coupon</button>
            </div>
        </td>
    </tr>
    <?php
}



if(get_setting('price_with_tax') === "exclusive") {
    $cartModel = model('CartModel');
    $cart_tax = $cartModel->get_cart_tax();
    if(!empty($cart_tax)) {
        ?>
        <tr class="shipping_tax">
            <th>Tax:</th>
            <td>
                <?php echo $cart_tax['amount'].'%' ?>
            </td>
        </tr>
        <?php
    }
}
if(!empty($cart['coupon_code'])) {
    $coupon = $ProductsModel->getCouponByCode($cart['coupon_code']);
    if($coupon) {
        $sign = $coupon['type'] == 'percent' ? '%' : '';
        ?>
        <tr class="cart-subtotal">
            <th>Discount</th>
            <td>
                <div><span id="discount-amount" data-discount="<?php echo $coupon['amount'] ?>" data-type="<?php echo $coupon['type'] ?>"><?php echo $coupon['amount'].$sign ?> </span>
                    <a href="?remove_discount" id="remove_discount_code" class="color-red" style="text-decoration: underline; font-size: 12px; padding-left: 15px">Clear</a></div>
                <div id="coupon_applied_message" style="color:var(--red);font-size: 12px;padding: 0 10px;position: relative;"></div>
            </td>
        </tr>
        <?php
    }
}
else {
    $curr_user_role = current_user_role();
    $user_role_discounts = get_setting('user_discount',true);

    $global_discount = get_setting('global_discount',true);
    if(!empty($global_discount['price'])) {
        $sign = $global_discount['type'] == 'percent' ? '%' : '';
        ?>
        <tr class="cart-subtotal">
            <th>Discount</th>
            <td><span id="discount-amount" data-discount="<?php echo $global_discount['price'] ?>" data-type="<?php echo $global_discount['type'] ?>"><?php echo $global_discount['price'].$sign ?> </span>
            </td>
        </tr>
        <?php
    }else {
        if($user_role_discounts) {
            $role_discount = [];
            foreach ($user_role_discounts as $discount) {
                if ($discount['role_id'] == $curr_user_role['id']) {
                    $role_discount = $discount;
                    break;
                }
            }

            if(!empty($role_discount['role_discount'])) {
                $sign = $role_discount['role_discount_type'] == 'percent' ? '%' : '';
                ?>
                <tr class="cart-subtotal">
                    <th width="250"><?php echo $curr_user_role['name'] ?> discount</th>
                    <td><span id="discount-amount" data-discount="<?php echo $role_discount['role_discount'] ?>" data-type="<?php echo $role_discount['role_discount_type'] ?>"><?php echo $role_discount['role_discount'].$sign ?> </span>
                    </td>
                </tr>
                <?php
            }
        }
    }

}
?>

<tr class="order-total">
    <th>Total</th>
    <td data-title="Total"><strong><span class="woocommerce-Price-amount amount"><bdi>
                                                    <span style="margin-right: -4px"><?php echo currency_symbol ?></span>
                                                    <span id="total_shipping_amount"><?php echo (number_format($cart['subtotal'],2));?></span></bdi></span></strong>

        <span id="shipping-vat" style="font-size: 15px;margin-left: 5px;"><?php if($cart['total_tax']) {echo '(includes '.currency_symbol.number_format($cart['total_tax'],2).' VAT)';} ?></span>

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
                let shipping_cost = parseFloat($(this).data('amount'),10);
                let total = <?php echo !empty($cart['product_total']) ? floatval(($cart['product_total'])) : 0; ?>;
                $('#coupon_code_message').text('');

                let discount = 0;

                let subtotal = (total + shipping_cost);

                let ship_count = $('[data-ship-count]').data('ship-count');

                if(ship_count) {
                    let ship_count_discount = ship_count*shipping_cost;
                    $('#free_shipping_price').html(ship_count_discount);

                    subtotal = (subtotal - ship_count_discount);
                }

                <?php
                if(!empty($cart['shipping_discount']) && !empty($cart['shipping_options'])) {
                    ?>
                    let ship_discount = <?php echo json_encode($cart['shipping_options']) ?>;
                    const discount_value = parseFloat(ship_discount.value);
                    if(ship_discount.type === 'percent') {
                        const pct = (discount_value/100)*shipping_cost;
                        subtotal -= pct;
                    }else {
                        subtotal = (subtotal - discount_value) > 0 ? subtotal - discount_value : 0;
                    }
                    <?php
                }
                ?>

                <?php if(!empty($cart['discount_amount'])) {
                ?>
                let discount_amount = $('#discount-amount').data('discount');
                let discount_type = $('#discount-amount').data('type');
                if(discount_amount) {
                    if(discount_type === "percent") {
                        discount = (discount_amount/100) * subtotal;
                    }else {
                        discount = subtotal - discount_amount;
                    }
                    subtotal -= discount;
                }
                <?php
                } ?>

                if($(this).data('vat')) {
                    const  vat_amt = $(this).data('vat');
                    //let vat_calc =  ((val/100)*vat_amt);

                    let tax_name = $(this).data('tax-name');

                    // subtotal += vat_calc;
                    // vat_calc = vat_calc.toFixed(2);

                    $('#shipping-vat').text('(includes <?php echo currency_symbol ?>'+vat_amt+' '+tax_name+')');

                }else {
                    $('#shipping-vat').text('');
                }

                $('#total_shipping_amount').text((subtotal).toFixed(2));
            });
        </script>
    </td>
</tr>


<?php
function shipping_calculator_html() {
    $input_arr = [
        'calc_state' => ['','State'],
        'calc_city' => ['','City'],
        'calc_postcode' => ['','Postcode']
    ];
    if(is_logged_in()) {
        $UserModel = model('UserModel');
        if(empty(get_setting('tax_based_on')) || get_setting('tax_based_on') == "billing") {
            $billing = $UserModel->get_billing_address();
            $calc_country = $billing['billing_country'];
            $input_arr = [
                'calc_country' => [$billing['billing_country'],'Country'],
                'calc_state' => [$billing['billing_state'],'State'],
                'calc_city' => [$billing['billing_city'],'City'],
                'calc_postcode' => [$billing['billing_postcode'],'Postcode']
            ];
        }
        if(get_setting('tax_based_on') == "shipping") {
            $shipping = $UserModel->get_shipping_address();
            $calc_country = $shipping['shipping_country'];
            $input_arr = [
                'calc_country' => [$shipping['shipping_country'],'Country'],
                'calc_state' => [$shipping['shipping_state'],'State'],
                'calc_city' => [$shipping['shipping_city'],'City'],
                'calc_postcode' => [$shipping['shipping_postcode'],'Postcode']
            ];

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