<?php
if(!empty($order)) {
    if(!empty($order['order_id'])) {
        $orders = [$order];
    }
    $productModel = model('ProductsModel');

    ?>

    <table class="order_receipt table" cellpadding="10" cellspacing="5" width="100%" style="text-align: left">

        <thead>
        <tr>
            <?php if(empty($hide_product_name)) {
                ?>
                <th>Products</th>
            <?php } ?>

            <?php if(empty($hide_variation)) {
                ?>
                <th></th>
            <?php } ?>
            <?php if(empty($hide_price)) {
                ?>
                <th>Price</th>
            <?php } ?>
            <?php if(empty($hide_qty)) {
                ?>
                <th>Quantity</th>
            <?php } ?>
            <?php if(empty($hide_total)) {
                ?>
                <th>Total</th>
            <?php } ?>
        </tr>
        </thead>

        <tbody>
        <?php

        $payment_method=$order['payment_method'];
        $transaction_id=$order['transaction_id'];
        $shipping_method = '';
        $discount = '';
        $free_shipping_count = 0;
        $shipping_discount = 0;
        $order_total = 0;
        $order_subtotal = 0;
        $item_total = 0;
        $is_club_subscription = false;
        $is_subscription = false;
        $has_subscription = false;

        $variation_map = [
            'Weight' => 'weight',
            'Grind' => 'grind',
            'Subscription Type' => 'subscription-type'
        ];

        $customer_coffee = '';

        $price_with_tax = '';
        $display_tax_price = '';
        $tax_inc_text = '';
        $tax_name = '';

        foreach($orders as $order) {
            $order_meta = $order['order_meta'];
            $order_items = $order['order_items'];

            $shipping_method = isset($order_meta['order_shipping_title']) ? $order_meta['order_shipping_title'] : '';

            foreach($order_items as $item) {
                $item_meta = $item['item_meta'];
                if($item['item_type'] === "shipping") {
                    $shipping_method = $item['product_name'];
                }
            }

            $price_with_tax = isset($order_meta['price_with_tax']) ? $order_meta['price_with_tax'] : '';
            $display_tax_price = isset($order_meta['display_tax_price']) ? $order_meta['display_tax_price'] : _price(0);
            $tax_name = isset($order_meta['tax_name']) ? $order_meta['tax_name'] : 'VAT';

            if($price_with_tax === "exclusive" && $display_tax_price === "including_tax") {
                $tax_inc_text = '<div style="font-size: 14px"><small>(inc. '.$tax_name.')</small></div>';
            }
            if($price_with_tax === "inclusive" && $display_tax_price === "excluding_tax") {
                $tax_inc_text = '<div style="font-size: 14px"><small>(ex. '.$tax_name.')</small></div>';
            }

            $shipping_cost = isset($order_meta['order_shipping']) ? $order_meta['order_shipping'] : 0;
            $vat = isset($order_meta['order_tax']) ? ($order_meta['order_tax']) : 0;
            $discount = isset($order_meta['cart_discount']) ? ($order_meta['cart_discount']) : 0;

            $order_subtotal += isset($order_meta['order_total']) ? $order_meta['order_total'] : 0;

            if(!empty($order_meta['shipping_discount'])) {
                $shipping_discount = $order_meta['shipping_discount'];
            }

            //pr($order);

            $has_subscription = $order_meta['has_subscription'];

            foreach($order['order_items'] as $item) {
                $product_name = $item['product_name'];
                $meta = @$item['item_meta'];
                $qty = @$meta['quantity'];
                $item_price = @$meta['item_price_html'];
                $item_subtotal = @$meta['display_price_html'];

                $item_total += !empty($meta['price']) ? $meta['price'] : 0;

                $productID = @$meta['product_id'];
                $sku = '';     
                if(!empty($meta['type']) && $meta['type'] == 'club_subscription') {
                    $is_subscription = true;
                }

                if(!empty($meta['subscription'])) {
                    $is_subscription = true;
                }

                if(!empty($meta['free_shipping'])) {
                    $free_shipping_count++;
                }

                $sub_settings = get_setting('subscriptionForm',true);

                $variation_list = [];

                if($item['item_type']==="line_item") {
                    ?>
                    <tr style="text-align: left">

                        <?php if(empty($hide_product_name)) {

                            if(!isset($show_link)) {
                                $show_link = strstr(current_url(),'orders/view') || strstr(current_url(),'admin/subscription');
                            }

                            ?>
                            <th align="left" style="text-align: left">
                                <?php if($show_link) {
                                    ?>
                                <a class="color-base" target="_blank" href="<?php echo admin_url() ?>products/add/<?php echo $productID ?>">
                                <?php
                                }?>
                                <?php echo $product_name ?>
                                <?php if($show_link) {
                                    ?>
                                </a>
                                    <?php
                                }?>
                                <div style="padding-top: 8px"><small><?php echo !empty($sku) ? 'SKU: '.$sku : ''; ?></small></div>
                            </th>
                            <?php
                        }?>
                        <?php
                        if(empty($hide_variation)) {
                            ?>
                            <td align="left" style="text-align: left">
                                <?php
                                if(!empty($meta['variations'])){
                                    foreach(json_decode($meta['variations'],true) as $key=>$variation) {
                                        $key = str_replace('attribute_','',$key);
                                        $key = str_replace('_',' ',$key);
                                        $key = ucfirst($key);
                                        if(is_array($variation)) {
                                            $variation = implode(', ',$variation);
                                        }
                                        ?>
                                        <div class="mb-8"><b><?php echo $key ?>: </b> <?php echo $variation ?></div>
                                        <?php
                                    }
                                }
                                ?>
                            </td>
                            <?php
                        }?>
                        <?php if(empty($hide_price)) {
                            ?>
                            <td align="left" style="text-align: left">
                                <?php echo $item_price ?>
                                <?php echo $tax_inc_text ?>
                            </td>
                            <?php
                        }?>
                        <?php if(empty($hide_qty)) {
                            ?>
                            <td align="left" style="text-align: left">
                                <?php echo $qty ?>
                            </td>
                            <?php
                        }?>
                        <?php if(empty($hide_total)) {
                            ?>
                            <td align="left" style="text-align: left">
                                <?php echo $item_subtotal ?>
                                <?php //echo $tax_inc_text ?>
                            </td>
                            <?php
                        }?>
                    </tr>
                    <?php
                }
            }

            if($has_subscription && !empty($order_meta['customer_coffee_selection'])) {
                $customer_coffee = $order_meta['customer_coffee_selection'];
            }else {
                $customer_coffee = $order['order_items'][0]['item_meta']['product_id'];
            }
        }

        //pr($order);

        ?>
        </tbody>
    </table>

    <?php

    if(empty($hide_subtotals)) {


        ?>
        <div class="clearfix"></div>

        <div style="margin-top: 15px">
            <div class="fl pull-left">
                <?php if(!empty($order_meta['purchase_order_number'])) {
                    ?>
                    <div>Purchase order number: <?php echo $order_meta['purchase_order_number'] ?></div>
                    <?php
                }?>
                <?php if($has_subscription && !empty($coffee_products)) { ?>
                    <div class="mt-40">
                        <form method="post" class="ajaxsubmit" action="<?php echo admin_url() ?>orders/edit/<?php echo $order['order_id'] ?>">
                            <div class="input_field">
                                <label>Coffee Selected for Customer:</label>
                                <div>
                                    <?php
                                        if($order['status']=='processing'){
                                    ?>
                                        <select class="select2" name="customer_coffee" onchange="this.form.submitbtn.disabled = false; this.form.submitbtn.click()">
                                            <?php foreach($coffee_products as $product) {
                                                $selected = $customer_coffee === $product['title'] ? 'selected':'';
                                                ?>
                                                <option <?php echo $selected ?> value="<?php echo $product['title'] ?>"><?php echo $product['title'] ?></option>
                                                <?php
                                            }?>
                                        </select>
                                    <?php
                                        }elseif($order['status']=='completed'){
                                    ?>
                                        <select class="select2" name="customer_coffee" onchange="this.form.submitbtn.disabled = false; this.form.submitbtn.click()">
                                            <?php 
                                            // Find the selected coffee product
                                            foreach($coffee_products as $product) {
                                                if ($customer_coffee === $product['title']) {
                                                    ?>
                                                    <option selected value="<?php echo $product['title'] ?>">
                                                        <?php echo $product['title'] ?>
                                                    </option>
                                                    <?php
                                                    break; // Exit loop once the selected product is found
                                                }
                                            }
                                            ?>
                                        </select>
                                    <?php
                                        }else{
                                    ?>
                                        <select class="select2" name="customer_coffee" onchange="this.form.submitbtn.disabled = false; this.form.submitbtn.click()">
                                            <?php foreach($coffee_products as $product) {
                                                $selected = $customer_coffee === $product['title'] ? 'selected':'';
                                                ?>
                                                <option <?php echo $selected ?> value="<?php echo $product['title'] ?>"><?php echo $product['title'] ?></option>
                                                <?php
                                            }?>
                                        </select>

                                    <?php
                                        }
                                    ?>
                                   
                                </div>
                                <div hidden>
                                    <button type="submit" name="submitbtn" value="1"></button>
                                </div>
                            </div>
                        </form>
                    </div>
            <?php } ?>
            </div>

            <div class="pull-right fr" style="display: flex; justify-content: right">
                <table cellpadding="10" cellspacing="5" class="table order_receipt order_receipt_subtotal" style="width: <?php echo !empty($subtotal_width) ? $subtotal_width : 'fit-content' ?>;">
                    <tr align="top" style="vertical-align: top;text-align: left">
                        <td>Subtotal: </td>
                        <td style="text-align: right">
                            <?php echo empty($item_total) ? _price($order_total) : _price($item_total) ?>
                        </td>
                    </tr>


                    <?php

                    if(!empty($order_meta['global_discount'])) {
                        ?>
                        <tr align="top" style="vertical-align: top;text-align: left">
                            <td>Store Discount: </td>
                            <td style="padding-bottom: 10px; text-align: right">-<?php echo _price($order_meta['global_discount']) ?></td>
                        </tr>
                        <?php
                    }

                    if(!empty($order_meta['wholesale_discount'])) {
                        ?>
                        <tr align="top" style="vertical-align: top;text-align: left">
                            <td>Wholesale Discount: </td>
                            <td style="padding-bottom: 10px; text-align: right">-<?php echo _price($order_meta['wholesale_discount']) ?></td>
                        </tr>
                        <?php
                    }

                    if(!empty($order_meta['shipping_discount'])) {
                        ?>
                        <tr align="top" style="vertical-align: top;text-align: left">
                            <td>Shipping Discount: </td>
                            <td style="padding-bottom: 10px; text-align: right">-<?php echo _price($order_meta['shipping_discount']) ?></td>
                        </tr>
                        <?php
                    }

                    if(!empty($order_meta['coupon_discount'])) {
                        ?>
                        <tr align="top" style="vertical-align: top;text-align: left">
                            <td>Coupon Discount: </td>
                            <td style="padding-bottom: 10px; text-align: right">-<?php echo _price($order_meta['coupon_discount']) ?></td>
                        </tr>
                        <?php
                    }

                    if(!empty($order_meta['user_discount_text'])) {
                        ?>
                        <tr align="top" style="vertical-align: top;text-align: left">
                            <td>User Discount: </td>
                            <td style="padding-bottom: 10px; text-align: right">-<?php echo _price($order_meta['user_discount_text']) ?></td>
                        </tr>
                        <?php
                    }

                    if(!empty($order_meta['discount_text'])) {
                        ?>
                        <tr align="top" style="vertical-align: top;text-align: left">
                            <td>Discount: </td>
                            <td style="padding-bottom: 10px; text-align: right">-<?php echo $order_meta['discount_text'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>

                    <?php
                    if($order_meta['has_shipping']!= 0 ){
                        $shipping_total = 0;
                    ?>
                    <tr align="top" style="vertical-align: top;text-align: left">
                        <td>Shipping: </td>
                        <td style="text-align: right">
                            <?php

                            if($shipping_cost) { ?>
                                <div style="font-size: 14px"><?php echo $shipping_method ?></div>
                                <div><?php
                                    if($shipping_discount) {
                                        echo '<strike>'._price($shipping_cost).'</strike> '._price($shipping_cost - $shipping_discount);
                                    }else {
                                        echo _price($shipping_cost);
                                    }
                                    $shipping_total = $shipping_cost;
                                    ?></div>
                            <?php }else {
                                ?>
                                <p>Free Shipping</p>
                                <?php
                            }?>
                        </td>
                    </tr>

                    <?php
                    }
                    $tax_name = !empty($order_meta['tax_name']) ? $order_meta['tax_name'] : 'VAT';
                    if($vat && $display_tax_price === "excluding_tax") {
                        ?>
                        <tr>
                            <td><?php echo $tax_name ?></td>
                            <td style=" padding-top: 10px; text-align: right"><?php echo _price($vat); ?></td>
                        </tr>
                        <?php
                    } ?>

                    <tr align="top" style="vertical-align: top; text-align: left; border-top: 1px solid #eee;">
                        <th style="text-align: left">Total: </th>
                        <td style=" padding-top: 10px; text-align: right">
                            <?php 
                                echo _price($order_subtotal); 
                            ?>
                            <?php
                                if($order_meta['has_shipping']) { 
                                    if($vat && $display_tax_price === "including_tax") {
                                        echo '<div><small>(includes '._price($vat).' '.$tax_name.')</small></div>';
                                    } 
                                }
                            ?>
                        </td>
                    </tr>
                    <?php
                    if(!empty($payment_method)) {
                        ?>
                        <tr align="top" style="vertical-align: top;text-align: left">
                            <td>Payment Method: </td>
                            <td style="padding-bottom: 10px; text-align: right"><?php echo $payment_method ?></td>
                        </tr>
                        <?php
                    }
                    ?>

                    <?php
                     if(!empty($order_meta['order_refund'])) {
                         $refunds = json_decode($order_meta['order_refund'],true);
                         ?>
                         <tr style="vertical-align: top; text-align: left; border-top: 1px solid #eee;">
                             <th style="text-align: left">Order refunds:</th>
                             <td style="text-align: right; line-height: 1.8">
                         <?php
                         $refund_balance = $order_subtotal;
                        foreach($refunds as $refund) {
                            $k = key($refund);
                            $v = $refund[$k];
                            ?>
                            <?php echo date("d/m/Y h:i A",$k); ?>: <?php echo _price($v) ?><br>
                                <?php
                            $refund_balance -= $v;
                        }
                        ?>
                             </td>
                         </tr>
                         <tr>
                             <th style="text-align: left">Refund balance:</th>
                             <td style="text-align: right; line-height: 1.8"><?php echo _price($refund_balance) ?></td>
                         </tr>
                             <?php
                     }

                     if(!empty($order_meta['order_comments'])) {
                         ?>
                         <tr>
                             <th style="text-align: left">Order note:</th>
                             <td style="text-align: right; line-height: 1.8"><p><?php echo $order_meta['order_comments'] ?></p></td>
                         </tr>
                             <?php
                     }
                    ?>

                </table>
            </div>
            <div style="clear: both"></div>
        </div>



        <style>
            table.order_receipt {
                border: 1px solid #eee;
                border-collapse: collapse;
            }
            table.order_receipt td, table.order_receipt th {
                border: 1px solid #eee;
            }
        </style>

        <div class="clearfix"></div>

    <?php } ?>
    <br>

<?php } ?>