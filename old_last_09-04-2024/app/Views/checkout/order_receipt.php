<?php if(!empty($order)) {
    $orders = $order;
    if(!empty($order['order_id'])) {
        $orders = [$order];
    }
    $productModel = model('ProductsModel');
    ?>

    <table class="order_receipt table" cellpadding="0" cellspacing="0" width="100%" style="text-align: left">

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

        $shipping_method = '';
        $discount = '';
        $free_shipping_count = 0;
        $shipping_discount = 0;
        $order_total = 0;
        $order_subtotal = 0;
        $item_total = 0;

        $variation_map = [
            'Weight' => 'weight',
            'Grind' => 'grind',
            'Subscription Type' => 'subscription-type'
        ];

        foreach($orders as $order) {
            $order_meta = $order['order_meta'];
            $order_items = $order['order_items'];

            $shipping_method = !empty($order_meta['order_shipping_title']) ? $order_meta['order_shipping_title'] : '';

            foreach($order_items as $item) {
                $item_meta = $item['item_meta'];
                if($item['item_type'] === "shipping") {
                    $shipping_method = $item['product_name'];
                }
            }

            $shipping_cost = !empty($order_meta['order_shipping']) ? $order_meta['order_shipping'] : 0;
            $vat = !empty($order_meta['order_tax']) ? number_format($order_meta['order_tax'],2) : 0;
            $discount = !empty($order_meta['cart_discount']) ? number_format($order_meta['cart_discount'],2) : '';
            $order_subtotal += !empty($order_meta['order_total']) ? $order_meta['order_total'] : '';


            if(!empty($order_meta['shipping_discount'])) {
                $shipping_discount = $order_meta['shipping_discount'];
            }

            foreach($order['order_items'] as $item) {

                $product_name = $item['product_name'];
                $meta = @$item['item_meta'];
                $qty = @$meta['quantity'];
                $item_price = @$meta['item_price_html'];
                $item_subtotal = @$meta['display_price_html'];

                $item_total += !empty($meta['price']) ? $meta['price'] : 0;

                $productID = @$meta['product_id'];
                $sku = '';

                if(!empty($meta['free_shipping'])) {
                    $free_shipping_count++;
                }

                $sub_settings = get_setting('subscriptionForm',true);

                $variation_list = [];

                if($item['item_type']==="line_item") {
                    ?>
                    <tr style="text-align: left">

                        <?php if(empty($hide_product_name)) {
                            ?>
                            <th align="left" style="text-align: left">
                                <?php echo $product_name.' '.$productID ?>
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
                                        $key = str_replace('_',' ',$key);
                                        $key = ucfirst($key);
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
                            </td>
                            <?php
                        }?>
                    </tr>
                    <?php
                }
            }
        }

        ?>
        </tbody>
    </table>
    <br>

    <?php
    
    if(empty($hide_subtotals)) {
        ?>
        <div class="clearfix"></div>

        <div>
            <div class="pull-right fr" style="display: flex; justify-content: right">
                <table width="300" cellpadding="0" cellspacing="0" class="table order_receipt order_receipt_subtotal" style="width: fit-content;">
                    <tr align="top" style="vertical-align: top;text-align: left">
                        <td>Total: </td>
                        <td style="text-align: right">
                            <?php echo empty($item_total) ? _price(number_format($order_total,2)) : _price(number_format($item_total,2)) ?>
                        </td>
                    </tr>
                    <?php
                    $shipping_total = 0;
                    if(!empty($shipping_cost) && $shipping_method && ($shipping_cost - $shipping_discount) > 0)  { ?>
                        <tr align="top" style="vertical-align: top;text-align: left">
                            <td>Shipping: </td>
                            <td style="text-align: right">
                                <div style="font-size: 14px"><?php echo $shipping_method ?></div>
                                <div><?php
                                        if($shipping_discount) {
                                            echo '<strike>'._price($shipping_cost+$vat).'</strike> '._price($shipping_cost - $shipping_discount);
                                        }else {
                                            echo _price($shipping_cost+$vat);
                                        }
                                        if($vat) {
                                            echo '<span style="font-size: 14px;margin-left: 5px;">(includes '._price($vat).' VAT)</span>';
                                        }
                                        $shipping_total = $shipping_cost;
                                     ?></div>
                            </td>
                        </tr>

                    <?php }
                    ?>

                    <?php

                    if(!empty($orders['order_meta']['shipping_discount'])) {
                        ?>
                        <tr align="top" style="vertical-align: top;text-align: left">
                            <td>Discount: </td>
                            <td style="padding-bottom: 10px; text-align: right"><?php echo _price($orders['order_meta']['shipping_discount']) ?></td>
                        </tr>
                        <?php
                    }

                    if($free_shipping_count) {
                        $free_shipping_discount = $free_shipping_count*$shipping_total;
                        ?>
                        <tr align="top" style="vertical-align: top;text-align: left">
                            <td>Shipping discount: </td>
                            <td style="padding-bottom: 10px; text-align: right"><?php echo _price($free_shipping_discount) ?></td>
                        </tr>
                        <?php
                    }
                    ?>

                    <tr align="top" style="vertical-align: top; text-align: left; border-top: 1px solid #eee;">
                        <th>Subtotal: </th>
                        <td style=" padding-top: 10px; text-align: right"><?php echo _price(number_format($order_subtotal,2)); ?></td>
                    </tr>

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