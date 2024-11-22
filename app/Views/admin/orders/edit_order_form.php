<?php
$order = [
    'billing_address' => '',
    'shipping_address' => '',
    'order_date' => date('Y-m-d'),
    'order_type' => 'shop_order',
    'order_meta' => [],
    'status' => 'pending',
    'customer_user' => '',
    'edit_mode' => 1
];

$order_items = false;
if(!empty($order_data)) {
    $order = [
        'billing_address' => $order_data['billing_address']['billing_address_1'].' '.$order_data['billing_address']['billing_address_2'],
        'shipping_address' => $order_data['shipping_address']['shipping_address_1'].' '.$order_data['shipping_address']['shipping_address_2'],
        'order_date' => date('Y-m-d', strtotime($order_data['order_date'])),
        'order_type' => 'shop_order',
        'order_meta' => $order_data['order_meta'],
        'status' => $order_data['status'],
        'customer_user' => $order_data['customer_user'],
        'edit_mode' => 1
    ];
    $order_items = $order_data['order_items'];
}
function product_info_template($data=[], $productsModel=[]) {
    // pr($data,false);
    $pid = $data['item_meta']['product_id'];
    $selected_variations = [];
    $order_item_id = !empty($data['order_item_id']) ? $data['order_item_id'] : 0;

    if(!empty($data['item_meta']['variations'])) {
        $pid = $data['item_meta']['product_id'];
        $variations = json_decode($data['item_meta']['variations'],true);
        foreach($variations as $key=>$value) {
            if(strstr($key,'attribute_')) {
                $selected_variations[] = $value;
            }
        }
    }

    $qty = !empty($data['item_meta']['quantity']) ? $data['item_meta']['quantity'] : 1;

    ob_start();
    ?>

    <tr class="prod_row">
        <td align="top" width="100">
            <div class="input_field">
                <label>Select product</label>
                <div class="rel">
                    <select name="products" style="width: 100%" onchange="get_product_info(this)" value="<?php echo $pid ?>" data-qty="<?php echo $qty ?>" required class="option_products product_autocomplete">
                        <option value="0">..</option>
                        <?php if($pid) {
                            ?>
                        <option value="<?php echo $pid ?>" selected><?php echo $data['product_name'] ?></option>
                        <?php
                        }?>
                    </select>
                </div>
            </div>
        </td>
        <?php
            $variation_check = $data['item_meta']['variation'];
            if(!empty($variation_check)){
                ?>
                    
                    <td class="option_variation input_field" align="top"></td>
                
                <?php
            }else{
                echo '<td></td>';
            }
        ?>
        
        <td class="input_field" width="70" align="top"><?php echo currency_symbol ?><span class="option_cost" style="padding-left: 2px;">0</span></td>
        <td class="input_field" align="top"><span class="option_qty"></span></td>
        <td class="input_field" width="70" align="top"><?php echo currency_symbol ?><span class="option_total" style="padding-left: 2px;">0</span></td>
        <td class="text-center"><a href="" onclick="removeItem(this); return false"><i class="lni lni-cross-circle color-base"></i> </a> </td>
    </tr>
    <?php
    return ob_get_clean();
}
$selected_variations = [];
$subscription_plans = [];
if(!empty($order_items)) {
    foreach($order_items as $item) {
        if (!empty($item['item_meta']['variations'])) {
            $pid = $item['item_meta']['product_id'];
            foreach (json_decode($item['item_meta']['variations'],true) as $key => $value) {
                if (strstr($key, 'attribute_')) {
                    $selected_variations[$pid][$key] = $value;
                }
            }
        }
        if(!empty($item['item_meta']['subscription'])) {
            $pid = $item['item_meta']['product_id'];
            $subscription_plans[$pid]['subscription'] = json_decode($item['item_meta']['subscription'],true);
        }
    }
}
?>

<script>
    let selected_variations = <?php echo json_encode($selected_variations); ?>;
    let product_subscription_plans = <?php echo json_encode($subscription_plans); ?>;
</script>

<div id="products" class="table-box">
    

    <?php 
        $order_meta = $order_data['order_meta'];
        if(!empty($order_meta['order_comments'])) {
            ?>
            <label for="order_comments">Order Notes
            <input type="text" name="order_comments" value="<?=$order_meta['order_comments']?>" id="order_comments">

            </label>
            <?php
        }
    ?>

    <label>Product Information</label>
    <table width="100%" class="table layout-fixed">
        <thead>
        <tr class="text-left">
            <th width="35%">Product(s)</th>
            <th width="35%">Variation</th>
            <th>Cost</th>
            <th>Qty</th>
            <th>Subtotal</th>
            <th width="50"></th>
        </tr>
        </thead>
        <tbody id="productList">
        <?php
        if(!empty($order_items)) {
            foreach($order_items as $item) {
                if($item['item_type'] === "line_item") {
                    echo product_info_template($item);
                }
            }
        }
        ?>
        </tbody>
    </table>

    <br>

    <a href="#" onclick="add_product_row();return false" class="btn btn-primary btn-sm">+ Add Product</a>

    <br>
    <br>

    <?php
     $order_meta = $order_data['order_meta'];

     function discount_input($input_name, $value, $type_value)
     {
         ?>
         <div class="d-inline-block" style="width: 100px">
             <div class="input-group input_field">
                 <input type="number" name="<?php echo $input_name ?>" step="0.01" min="0" value="<?php echo $value; ?>">
             </div>
         </div>
         <div class="d-inline-block input-group inline-checkbox input_field">
             <label>
                 <input type="radio" name="<?php echo $input_name ?>-type" <?php echo $type_value == 'percent' ? 'checked' : '' ?> value="percent">
                 <span>%</span>
             </label>
         </div>
         <div class="d-inline-block input-group inline-checkbox input_field">
             <label>
                 <input type="radio" name="<?php echo $input_name ?>-type" <?php echo $type_value == 'fixed' ? 'checked' : '' ?> value="fixed">
                 <span>=</span>
             </label>
         </div>
         <?php
     }

    ?>

    <div class="row">
        <div class="col-md-4"></div>
        <!-- <div class="col-md-8">
           <div class="pull-right">
               (in development)
               <table class="table layout-fixed text-left" style="width: 30em;">
                   <tbody>

                   <tr>
                       <th width="140">Item subtotal</th>
                       <td><span><?php echo currency_symbol ?></span><span id="item-subtotal"><?php echo ($order_meta['product_total']); ?></span></td>
                   </tr>

                   <tr>
                       <th width="140">Store discount</th>
                       <td>
                           <?php discount_input('store-discount',@$order_meta['global_discount'],@$order_meta['global_discount_type']) ?>
                       </td>
                   </tr>

                   <tr>
                       <th width="140">Wholesale discount</th>
                       <td>
                           <?php discount_input('wholesale-discount',@$order_meta['wholesale_discount'],@$order_meta['wholesale_discount_type']) ?>
                       </td>
                   </tr>
                   <tr>
                       <th width="140">User discount</th>
                       <td>
                           <?php discount_input('user-discount',@$order_meta['user_discount'],@$order_meta['user_discount_type']) ?>
                       </td>
                   </tr>

                   <tr>
                       <th width="120">Shipping discount</th>
                       <td>
                           <?php discount_input('shipping-discount',@$order_meta['shipping_discount'],@$order_meta['shipping_discount_type']) ?>
                       </td>
                   </tr>

                   <tr>
                       <th>Shipping</th>
                       <td id="shipping-input" width="220">
                           <select id="select-order-shipping" name="order-shipping" class="select2" disabled data-search="false">
                               <option value="0" data-amount="0">Select shipping method</option>

                           </select>
                       </td>
                   </tr>

                   <tr>
                       <?php
                       if(get_setting('enable_product_coupons')) {
                           $curr_date = date('Y-m-d h:i:s');
                           ?>
                           <th width="120">Coupon</th>
                           <td id="coupon-input">
                               <div class="input-group input_field">
                                   <div class="rel">
                                       <div>
                                           <select onchange="applyCoupon(this)" name="coupon" data-search="false" class="select2" style="width: 200px;">
                                               <option value="0">Select coupon</option>
                                               <?php
                                               foreach($coupons as $coupon) {
                                                   if($coupon->has_expiration && !date_between($curr_date,$coupon->valid_from,$coupon->valid_to)) {
                                                       continue;
                                                   }
                                                   $selected = '';
                                                   if(!empty($order_meta['coupon_id'])) {
                                                       if($coupon->id === $order_meta['coupon_id']) {
                                                           $selected = 'selected';
                                                       }
                                                   }
                                                   ?>
                                                   <option <?php echo $selected ?> data-type="<?php echo $coupon->type ?>" data-amount="<?php echo $coupon->amount ?>" value="<?php echo $coupon->id ?>"><?php echo $coupon->code ?></option>
                                                   <?php
                                               } ?>
                                           </select>
                                       </div>

                                   </div>
                               </div>
                           </td>
                       <?php } ?>
                   </tr>

                   <tr>
                       <th width="120">Coupon discount</th>
                       <td><span><?php echo currency_symbol ?></span><span id="item-subtotal">0.00</span></td>
                   </tr>

                   <tr>
                       <th width="120">Tax class</th>
                       <td>
                           <div class="input-group input_field">
                               <input type="number" step="0.01" min="0" id="order-discount" onchange="apply_discount(this)" class="form-control" style="width: 200px" value="<?php echo !empty($order_meta['cart_discount']) ? $order_meta['cart_discount']:0 ?>" name="discount">
                           </div>
                       </td>
                   </tr>

                   <tr>
                       <th width="120">Order total</th>
                       <td>
                           <span><?php echo currency_symbol ?></span><span id="order-subtotal">0.00</span>
                           <input type="hidden" name="order-subtotal" class="form-control" id="order-subtotal-input" value="0">
                       </td>
                   </tr>
                   </tbody>
               </table>
               <div class="mt-8"></div>
               <div class="pull-right">
                   <button type="button" class="btn btn-primary btn-sm">Recalculate</button>
               </div>
               <?php init_subscription_form_script() ?>
           </div>
        </div> -->

        <!-- working for perc and fixed  -->
        <div class="col-md-8">
            <div class="pull-right">
                (in development)
                <table class="table layout-fixed text-left" style="width: 30em;">
                    <tbody>
                        <tr>
                            <th width="140">Item subtotal</th>
                            <td>
                                <span><?php echo currency_symbol ?></span>
                                <span id="item-subtotal"><?php echo ($order_meta['product_total']); ?></span>
                            </td>
                        </tr>

                        <tr>
                            <th width="140">Store discount</th>
                            <td>
                                <?php discount_input('store-discount', @$order_meta['global_discount'], @$order_meta['global_discount_type']); ?>
                            </td>
                        </tr>

                        <tr>
                            <th width="140">Wholesale discount</th>
                            <td>
                                <?php discount_input('wholesale-discount', @$order_meta['wholesale_discount'], @$order_meta['wholesale_discount_type']); ?>
                            </td>
                        </tr>
                        <tr>
                            <th width="140">User discount</th>
                            <td>
                                <?php discount_input('user-discount', @$order_meta['user_discount'], @$order_meta['user_discount_type']); ?>
                            </td>
                        </tr>

                        <tr>
                            <th width="120">Shipping discount</th>
                            <td>
                                <?php discount_input('shipping-discount', @$order_meta['shipping_discount'], @$order_meta['shipping_discount_type']); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Shipping</th>
                            <td id="shipping-input" width="220">
                                <select id="select-order-shipping" name="order-shipping" class="select2" disabled data-search="false">
                                    <option value="0" data-amount="0">Select shipping method</option>

                                </select>
                            </td>
                        </tr>
                        <tr>
                            <?php
                            if(get_setting('enable_product_coupons')) {
                                $curr_date = date('Y-m-d h:i:s');
                                ?>
                                <th width="120">Coupon</th>
                                <td id="coupon-input">
                                    <div class="input-group input_field">
                                        <div class="rel">
                                            <div>
                                                <select onchange="applyCoupon(this)" name="coupon" data-search="false" class="select2" style="width: 200px;">
                                                    <option value="0">Select coupon</option>
                                                    <?php
                                                    foreach($coupons as $coupon) {
                                                        if($coupon->has_expiration && !date_between($curr_date,$coupon->valid_from,$coupon->valid_to)) {
                                                            continue;
                                                        }
                                                        $selected = '';
                                                        if(!empty($order_meta['coupon_id'])) {
                                                            if($coupon->id === $order_meta['coupon_id']) {
                                                                $selected = 'selected';
                                                            }
                                                        }
                                                        ?>
                                                        <option <?php echo $selected ?> data-type="<?php echo $coupon->type ?>" data-amount="<?php echo $coupon->amount ?>" value="<?php echo $coupon->id ?>"><?php echo $coupon->code ?></option>
                                                        <?php
                                                    } ?>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </td>
                            <?php } ?>
                        </tr>

                        <tr>
                            <th width="120">Coupon discount</th>
                            <td><span><?php echo currency_symbol ?></span><span id="item-subtotal">0.00</span></td>
                        </tr>

                        <tr>
                            <th width="120">Tax class</th>
                            <td>
                                <div class="input-group input_field">
                                    <input type="number" id="order-discount" step="0.01" min="0" id="order-discount" onchange="apply_discount(this)" class="form-control" style="width: 200px" value="<?php echo !empty($order_meta['cart_discount']) ? $order_meta['cart_discount']:0 ?>" name="discount">
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th>Order total</th>
                            <td>
                                <span><?php echo currency_symbol ?></span>
                                <span id="order-subtotal">0.00</span>
                                <input type="hidden" name="order-subtotal" class="form-control" id="order-subtotal-input" value="0">
                            </td>
                        </tr>

                    </tbody>
                </table>
                <div class="mt-8"></div>
                <div class="pull-right">
                    <button type="button" class="btn btn-primary btn-sm" onclick="recalculateTotal()">Recalculate</button>
                </div>
            </div>
        </div> 
        


      

    </div>
</div>

<?php
if(!empty($order_data)) {
    ?>
    <input type="hidden" name="order_id" value="<?php echo $order_data['order_id'] ?>">
    <?php
}
?>

<input type="hidden" name="current_url" value="<?php echo current_url() ?>">

<button type="submit" name="submit" value="1" class="btn-primary btn"><?php echo !empty($order_data) ? 'Save changes':'Add Order' ?> </button>


<template id="product_list_row">
    <?php echo product_info_template([], $productsModel) ?>
</template>

<?php
    $sub_plans = get_setting('subscription_plans',true);
    if(!empty($sub_plans)) {
        $sub_plans = $sub_plans[0];
    }
?>

<script>

    $('[name="shipping_country"], [name="shipping_postcode"], [name="shipping_city"]').each(function() {
        $(this).on('change', function() {
            const country = $('[name="shipping_country"]').val();
            const postcode = $('[name="shipping_postcode"]').val();
            const city = $('[name="shipping_city"]').val();

            let formData = new FormData();
            formData.append('country', country);
            formData.append('postcode', postcode);
            formData.append('city', city);

            $('#select-order-shipping.select2-hidden-accessible').select2('destroy');
            $('#select-order-shipping').prop('disabled',true);

            if(country && postcode && city) {
                fetch('<?php echo admin_url() ?>ajax/address-tax-ajax',{
                    method: "POST",
                    body: formData
                }).then(res=>res.json()).then((res)=>{
                    if(res) {
                        let selected = '';
                        $('#select-order-shipping').html('');
                        res.forEach((tax,idx)=>{

                            <?php if(!empty($order_data)) {
                            ?>
                            selected = tax.name === '<?php echo $order_meta['order_shipping_title'] ?>' ? 'selected':'';
                            <?php
                            } ?>
                            $('#select-order-shipping').append(`<option ${selected} value="${idx}" data-amount="${tax.method_amount}">${tax.name} (<?php echo currency_symbol ?>${tax.method_amount})</option>`);
                        });
                        select2_init();
                        $('#select-order-shipping').prop('disabled',false);
                        setTimeout(()=>{
                            $('[name="coupon"]').trigger('change');
                        },500);
                    }
                })
            }
        });
    }).promise().done(function() {

    });

    let resetIndex = ()=> {
        console.log('reset');
        $('#productList').children().each(function(idx) {
            $(this).find('[name]').each(function() {
                const name = $(this).attr('name').split('[')[0];
                $(this).attr('name',name+'['+idx+']');
            });
        });
    }

    let t;

    let removeItem = (item)=> {
        item.closest('tr').remove();
       // calcSubtotal();
        resetIndex();
    }

    let add_product_row = ()=> {
        const template = document.getElementById('product_list_row').content.cloneNode(true);
        document.getElementById('productList').appendChild(template);

       select2_init();
        resetIndex();
        product_autocomplete();
        return false;
    }

    let selectedProducts = [];

    let get_product_info = async (product)=> {
        const pid = product.value;
        const url = '<?php echo admin_url() ?>ajax/get_product_info_ajax/'+pid;
        let option_list = product.closest('tr').querySelector('.option_variation');
        const rowIdx = $(product.closest('tr')).index();
        const parent = $(product).closest('tr');
        const prodQty = product.getAttribute('data-qty');

        parent.find('.option_cost').html('');
        parent.find('.option_total').html('');
        parent.find('.option_qty').html('');
        parent.find('.option_variation').html('');

        let subtotal = 0;

        await fetch(url).then(res=>res.json()).then(res=>{
            if(res && typeof res.attributes !== "undefined") {
                let price = parseFloat(res.price);
                price = price.toFixed(2);
                let rid = '_'+Math.random().toString().substring(5);

                let option_list_html = '<table id='+rid+' width="100%" class="inner-table table layout-fixed">';
                if(res.attributes.length) {
                    let variation_list_options = [];
                    for(let key in res.attributes) {
                        const attr = res.attributes[key];
                        if(parseInt(attr.attribute_variation)) {
                            const label = attr.label;
                            let k = label.toLowerCase();
                            k = 'attribute_'+k.replaceAll(' ','-',k);
                            let rid = '_'+Math.random().toString().substring(5);

                            option_list_html += `<tr>
                            <td style="vertical-align: middle;" width="100"><label style="min-width: 60px">${label}</label></td>
                            <td><select id="${rid}" onchange="get_variation_info(this)" class="variation_list form-control select2" style="margin: 0 0 10px; width: 100%" data-search="false" name="variation_${k}" required>
                                <option value="" selected>Select ${label}</option></select></td>
                            </tr>`;

                            for(let v in attr.value) {
                                let selected = '';
                                if(typeof selected_variations[pid] !== "undefined") {
                                    for(let i in selected_variations[pid]) {
                                        if(i === k) {
                                            if(selected_variations[pid][i] === attr.value[v]) {
                                                selected = 'selected';
                                            }
                                        }
                                    }
                                }
                                variation_list_options.push({id: attr.value[v], input:'#'+rid, selected: selected, value: attr.value[v]});
                            }
                        }
                    }

                    option_list_html += '</table>';

                    option_list.innerHTML = option_list_html;

                    if(variation_list_options.length) {
                        variation_list_options.forEach((option)=>{
                            option_list.querySelector(option.input).innerHTML += `<option value="${option.id}" ${option.selected}>${option.value}</option>`;
                        });
                        $('.variation_list').each(function() {
                            $(this).trigger('change');
                        });
                    }
                }else {

                    let stock = '';
                    if(res.stock_managed === "1") {
                        stock = res.stock;
                    }
                    parent.find('.option_cost').html(price);
                    parent.find('.option_total').html(price+'<input type="hidden" value="'+price+'" name="subtotal[]" class="productSubtotal">');
                    parent.find('.option_qty').html(`<input type="number" style="width: 70px;text-align: center;" value="${prodQty}" onchange="change_subtotal(this)" min="1" name="qty[]" max="${stock}">`);

                    subtotal += price;
                }

                clearTimeout(t);

                t = setTimeout(()=>{
                    select2_init();
                    resetIndex();
                   // calcSubtotal();
                },500);

            }

            // product.closest('tbody').querySelector('.option_variation').innerHTML = ``;
        });
    }

    let var_timeout;

    let get_variation_info = (select)=> {
        clearTimeout(var_timeout);
        var_timeout = setTimeout(()=>{
            const parent = $(select).closest('.prod_row');
            let product_id = parent.find('.option_products').val();
            let validated = true;
            let variation_url = '';
            parent.find('.option_cost').html('');
            parent.find('.option_total').html('');
            parent.find('.option_qty').html('');

            const prodQty = parent.find('.option_products').data('qty');

            select.closest('.option_variation > table').querySelectorAll('select').forEach((select)=>{
                if(!select.value) {
                    validated = false;
                }
                let _name = select.name.split('[')[0];
                variation_url += "variations["+_name+"]="+select.value+"&";
            });

            variation_url += `product_id=${product_id}&type=product&quantity=1`;



            if(validated) {
                fetch('<?php echo site_url() ?>shop/product/getvariation?'+variation_url).then(res=>res.json()).then((res)=>{
                    let stock = '';
                    parent.find('.option_variation').find('.subscription_box_wrapper').remove();
                    parent.find('.option_variation').find('.shop_subscription_form').remove();

                    if(res.values) {
                        if(res.values.stock_status === "instock") {
                            stock = res.values.stock;
                        }
                        let price = parseFloat(res.calculated_price);
                        price = price.toFixed(2);

                        /*if(typeof res.values.product_level_subscription !== "undefined" && res.values.product_level_subscription === "yes") {
                            const subplan = typeof product_subscription_plans[product_id].subscription !== "undefined" ? JSON.stringify(product_subscription_plans[product_id].subscription) : '';
                            const subformurl = `<?php echo site_url() ?>ajax/shop_subscription_form?price=${price}&qty=${prodQty}&plan=${subplan}&fieldname=subscription[${product_id}]`;

                        fetch(subformurl).then(res=>res.text()).then(res=>{
                            parent.find('.option_variation').find('.subscription_box_wrapper').remove();
                            parent.find('.option_variation').append(`<section class="shop_subscription_form">${res}</section>`);

                            if(parent.find('[name="subscription[enable]"]:checked').val()) {
                                price = parent.find('.subscription_box_fields').data('price');
                                price = price.toFixed(2);
                            }

                            parent.find('[name="subscription[enable]"]').on('click', function() {
                                product_subscription_plans[product_id].subscription.enable = this.checked;
                                get_variation_info(select);
                            });

                            parent.find('.option_cost').html(price);
                            parent.find('.option_total').html(price+'<input type="hidden" value="'+price+'" name="subtotal[]"  class="productSubtotal">');
                            parent.find('.option_qty').html(`<input type="number" class="input_subtotal" style="width: 70px;text-align: center;" value="${prodQty}" onchange="change_subtotal(this)" min="1" name="qty[]" max="${stock}">`);
                        });
                    }else {

                    }*/

                        parent.find('.option_cost').html(price);
                        parent.find('.option_total').html(price+'<input type="hidden" value="'+price+'" name="subtotal[]" class="productSubtotal">');
                        parent.find('.option_qty').html(`<input type="number" class="input_subtotal" style="width: 70px;text-align: center;" value="${prodQty}" onchange="change_subtotal(this)" min="1" name="qty[]" max="${stock}">`);

                        $('.input_subtotal').trigger('change');
                    }

                   // calcSubtotal();
                    resetIndex();
                });
            }
        },50);
    }

    const shippingAmount = ()=> {
        let shippings = document.querySelector('#shipping-input select');
        let shippingVal = shippings.options.length ? parseFloat(shippings.options[shippings.selectedIndex].getAttribute('data-amount')) : 0;
        return parseFloat(shippingVal);
    }

    let apply_discount = (input) => {
        let subtotal = parseFloat(document.querySelector('#item-subtotal').innerHTML);
        let shipping_amt = subtotal + shippingAmount();
        let discount = shipping_amt - input.value;
        document.querySelector('#order-subtotal-input').value = discount.toFixed(2);
        document.querySelector('#order-subtotal').innerHTML = discount.toFixed(2);
        //$('[name="coupon"]').val(0).trigger('change');
    }

    let change_subtotal = (input)=> {
        let parent = $(input).closest('tr');
        let price = parseFloat(parent.find('.option_cost').text());
        let subtotal = price*input.value;

        subtotal = subtotal.toFixed(2);
        parent.find('.option_total').html(subtotal+'<input type="hidden" value="'+subtotal+'" name="subtotal[]"  class="productSubtotal">');
        //calcSubtotal();
    }

    let calcSubtotal = ()=> {
        let prodSubtotal = 0;

        let shipVal = shippingAmount();
        const couponAmt = document.querySelector('#order-discount') ? parseFloat(document.querySelector('#order-discount').value) : 0;

        document.querySelectorAll('.productSubtotal').forEach((ipt)=>{
            prodSubtotal += parseFloat(ipt.value);
        });

        console.log(prodSubtotal);

        let subtotal = (prodSubtotal + shipVal);

        let discount = 0;
        if(parseFloat($('select[name="coupon"]').val())) {
            let coupon = $('select[name="coupon"]').children('option').eq($('select[name="coupon"]')[0].selectedIndex)[0];
            const discType = coupon.getAttribute('data-type');
            const discAmount = coupon.getAttribute('data-amount') || 0;

            if(discAmount) {
                if(discType === "percent") {
                    discount = (subtotal*discAmount) / 100;
                }else {
                    discount = discAmount;
                }
            }
            document.querySelector('#order-discount').value = discount;
            subtotal = subtotal - discount;
        }

        document.querySelector('#item-subtotal').innerHTML = prodSubtotal.toFixed(2);

        document.querySelector('#order-subtotal').innerHTML = (subtotal).toFixed(2);
        document.querySelector('#order-subtotal-input').value = (subtotal).toFixed(2);
    }

    let applyCoupon = (select)=> {

        select.closest('#coupon-input').classList.add('processing');

        let shipping_cost = $('[name="order-shipping"]').find('option[value="'+$('[name="order-shipping"]').val()+'"]').attr('data-amount');
        shipping_cost = parseFloat(shipping_cost);
        const subtotal = parseFloat(document.querySelector('#item-subtotal').innerHTML) + shipping_cost;
        document.querySelector('#order-discount').value = 0;

        if(parseFloat(select.value)) {
            fetch('<?php echo admin_url() ?>ajax/coupon-info-json/'+select.value).then(res=>res.json()).then((res)=>{
                select.closest('#coupon-input').classList.remove('processing');
                const dc_amount = parseFloat(res.amount);

                let total;
                if(res.type === "percent") {
                    total = (subtotal/100)*dc_amount;
                }else {
                    total = subtotal - dc_amount;
                }

                document.querySelector('#order-discount').value = total;

                notification('Coupon applied with <?php echo currency_symbol ?>'+(total).toFixed(2)+' discount');

               // calcSubtotal();
            })
        }else {
            select.closest('#coupon-input').classList.remove('processing');
            document.querySelector('#order-subtotal').innerHTML = subtotal;
            document.querySelector('#order-subtotal-input').value = subtotal;
        }
       // calcSubtotal();
    }

    <?php
    if(!empty($order_items)) {
    ?>
        $(function() {
            setTimeout(()=>{
                $('.option_products').each(function() {
                    $(this).trigger('change');
                });
                $('[name="shipping_country"]').trigger('change');

            },800);
        })
    <?php
    }
    ?>
</script>

<style>
    table.table tbody tr td, table.table tbody tr th {
        vertical-align: top;
    }
    .subscription_box_wrapper .variations tr td,
    .subscription_box_wrapper .variations tr th {
        border: none;
    }
    .subscription_box_wrapper {
        border-top: 1px solid #eee;
        margin-top: 0.8em;
        padding-top: 0.8em;
    }
    .subscription_plan_price {
        padding-bottom: 0.8em;
        display: block;
    }
</style>
<script>
    // Function to recalculate order total
    // document.querySelector('#recalculate-btn').addEventListener('click', function () {
    //     // Get field values
    //     const productTotal = parseFloat(document.querySelector('#product-total').textContent) || 0;
    //     const storeDiscount = parseFloat(document.querySelector('#store-discount-input').value) || 0;
    //     const wholesaleDiscount = parseFloat(document.querySelector('#wholesale-discount-input').value) || 0;
    //     const userDiscount = parseFloat(document.querySelector('#user-discount-input').value) || 0;
    //     const shippingDiscount = parseFloat(document.querySelector('#shipping-discount-input').value) || 0;
    //     const taxClass = parseFloat(document.querySelector('#tax-class-input').value) || 0;
    //     const shippingCost = parseFloat(document.querySelector('#select-order-shipping').selectedOptions[0].dataset.amount) || 0;

    //     // Calculate total
    //     const totalDiscount = storeDiscount + wholesaleDiscount + userDiscount + shippingDiscount;
    //     const subtotal = productTotal - totalDiscount;
    //     const tax = (subtotal * taxClass) / 100;
    //     const orderTotal = subtotal + tax + shippingCost;

    //     // Update order total on the page
    //     document.querySelector('#order-subtotal').textContent = orderTotal.toFixed(2);
    //     document.querySelector('#order-subtotal-input').value = orderTotal.toFixed(2);
    // });

    // working code for perc and fixed
    document.addEventListener('DOMContentLoaded', function () {
        const itemSubtotalElement = document.getElementById('item-subtotal');
        const orderSubtotalElement = document.getElementById('order-subtotal');
        const orderSubtotalInput = document.getElementById('order-subtotal-input');
        const discountFields = [
            'store-discount',
            'wholesale-discount',
            'user-discount',
            'shipping-discount',
        ];

        // Function to recalculate order total
        function recalculateTotal() {
            let itemSubtotal = parseFloat(itemSubtotalElement.textContent) || 0;
            let totalDiscount = 0;

            discountFields.forEach((field) => {
                const discountInput = document.querySelector(`input[name="${field}"]`);
                const discountType = document.querySelector(`input[name="${field}-type"]:checked`);

                if (discountInput && discountType) {
                    const discountValue = parseFloat(discountInput.value) || 0;
                    const discountTypeValue = discountType.value;

                    if (discountTypeValue === 'percent') {
                        totalDiscount += (itemSubtotal * discountValue) / 100;
                    } else if (discountTypeValue === 'fixed') {
                        totalDiscount += discountValue;
                    }
                }
            });

            // Update order total
            const orderTotal = Math.max(0, itemSubtotal - totalDiscount); // Ensure no negative total
            orderSubtotalElement.textContent = orderTotal.toFixed(2);
            orderSubtotalInput.value = orderTotal.toFixed(2);
        }

        // Attach event listeners to discount fields
        discountFields.forEach((field) => {
            const discountInput = document.querySelector(`input[name="${field}"]`);
            const discountTypeRadios = document.querySelectorAll(`input[name="${field}-type"]`);

            if (discountInput) {
                discountInput.addEventListener('input', recalculateTotal);
            }

            if (discountTypeRadios) {
                discountTypeRadios.forEach((radio) =>
                    radio.addEventListener('change', recalculateTotal)
                );
            }
        });

        // Initial calculation
        recalculateTotal();
    });

    // document.addEventListener('DOMContentLoaded', function () {
    //     const itemSubtotalElement = document.getElementById('item-subtotal');
    //     const orderSubtotalElement = document.getElementById('order-subtotal');
    //     const orderSubtotalInput = document.getElementById('order-subtotal-input');
    //     const discountFields = [
    //         'store-discount',
    //         'wholesale-discount',
    //         'user-discount',
    //         'shipping-discount',
    //     ];

    //     // Function to recalculate order total
    //     function recalculateTotal() {
    //         let itemSubtotal = parseFloat(itemSubtotalElement.textContent) || 0;
    //         let totalDiscount = 0;

    //         discountFields.forEach((field) => {
    //             const discountInput = document.querySelector(`input[name="${field}"]`);
    //             const discountType = document.querySelector(`input[name="${field}-type"]:checked`);

    //             if (discountInput && discountType) {
    //                 const discountValue = parseFloat(discountInput.value) || 0;
    //                 const discountTypeValue = discountType.value;

    //                 if (discountTypeValue === 'percent') {
    //                     totalDiscount += (itemSubtotal * discountValue) / 100;
    //                 } else if (discountTypeValue === 'fixed') {
    //                     totalDiscount += discountValue;
    //                 }
    //             }
    //         });

    //         // Update order total
    //         const orderTotal = Math.max(0, itemSubtotal - totalDiscount); // Ensure no negative total
    //         orderSubtotalElement.textContent = orderTotal.toFixed(2);
    //         orderSubtotalInput.value = orderTotal.toFixed(2);
    //     }

    //     // Attach the recalculateTotal function to the button click
    //     const recalculateButton = document.querySelector('button[onclick="recalculateTotal()"]');
    //     if (recalculateButton) {
    //         recalculateButton.addEventListener('click', recalculateTotal);
    //     }
    // });


</script>