<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;200&display=swap">
    <style>
        body {
            font-family: 'Lato', sans-serif;
            padding: 1em 6em 3em;
            font-size: 14px;
            margin: 0;
            position: relative;
            min-height: 100%;
            padding-bottom: 2.5em; /* Space for footer */
        }
        h1 {
            font-size: 20px;
            margin: 0;
        }
        h5 {
            font-size: 14px;
            margin: 15px 0;
        }
        p {
            margin: 0;
            font-size: 12px;
            line-height: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            font-size: 12px;
            padding: 5px;
            text-align: left;
        }
        thead th {
            background-color: #000;
            color: #fff;
        }
        .page_break { page-break-before: always; }
        .item-meta p {
            font-size: 11px;
        }
        .address-meta p {
            line-height: 18px;
        }
        .order_receipt tbody tr {
            border-bottom: 1px solid #eee;
        }
        .order_receipt_subtotal {
            float: right;
            width: 230px;
        }
        .order_receipt_subtotal th, .order_receipt_subtotal td {
            font-size: 13px;
        }

        /* Footer Style */
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            font-size: 12px; 
            float: left;
        }

        
    </style>
</head>
<body>
<div class="header">
    <?php if (!empty($slips)) { 
        foreach ($slips as $i => $slip) { 
            $totals = ['item_total' => 0, 'tax_total' => 0];
    ?>
        <div>
            <!-- Packing Slip Header -->
            <table>
                <tr>
                    <td width="60%">
                        <img id="site-logo" width="165" src="<?php echo asset('images/slip-logo.png'); ?>">
                    </td>
                    <td>
                        <div class="address-meta">
                            <h5><?php echo @$setting['title']; ?></h5>
                            <p><?php echo @$setting['site_address_1'] . ' ' . @$setting['site_address_2'] . ' ' . @$setting['post_code']; ?></p>
                            <p><?php echo !empty($setting['contact_number']) ? 'T ' . $setting['contact_number'] : ''; ?> | E:</p>
                            <p><?php echo @$setting['contact_email']; ?></p>
                        </div>
                    </td>
                </tr>
            </table>
            <h1>PACKING SLIP</h1>
            <div style="margin-top: 1em;"></div>

            <!-- Shipping and Order Details -->
            <?php 
                $shipping_address = $slip['shipping_address'];
                $meta = $slip['order_meta'];
                $order_items = $slip['order_items'];
                $order_id = $slip['order_id'];
                $customer = $slip['customer'];

                foreach ($order_items as $item) {
                    $item_meta = $item['item_meta'];
                    if (!empty($item_meta['line_subtotal']) && $item['item_type'] === "line_item") {
                        $totals['item_total'] += $item_meta['line_subtotal'];
                    }
                    if (!empty($item_meta['cost']) && $item['item_type'] === "shipping") {
                        $totals['tax_total'] += $item_meta['cost'];
                    }
                    if (!empty($item_meta['tax_amount']) && $item['item_type'] === "tax") {
                        $totals['tax_total'] += $item_meta['tax_amount'];
                    }
                }
            ?>
            <table>
                <tr>
                    <td>
                        <p><?php echo @$shipping_address['shipping_first_name'] . ' ' . @$shipping_address['shipping_last_name']; ?></p>
                        <p><?php echo @$shipping_address['shipping_address_1'] . ' ' . @$shipping_address['shipping_address_2']; ?></p>
                        <p><?php echo @$shipping_address['shipping_city']; ?></p>
                        <p><?php echo @$shipping_address['shipping_state']; ?></p>
                        <p><?php echo @$shipping_address['shipping_postcode']; ?></p>
                    </td>
                                <td>
                                    <div class="pull-right fr" style="float: right;">
                                        <table width="100%" align="top" cellspacing="2" cellpadding="2">
                                            <tr>
                                                <td width="100">Order Number:</td>
                                                <td><?php
                                                    //$inv_prefix = $customer->role_id == 'wholesale_customer' ? 'HNW':'HNR';
                                                    $inv_prefix = 'HNR';
                                                    echo !empty($order_id) ? _order_number($order_id, $inv_prefix): '' ?></td>
                                            </tr>

                                            <tr>
                                                <td width="100">Order Date:</td>
                                                <td><?php echo !empty($meta['order_date']) ? date(env('date_format'),strtotime($meta['order_date'])) : '' ?></td>
                                            </tr>
                                            <?php if(!empty($meta['purchase_order_number'])) { ?>
                                                <tr style="padding-bottom: 5px">
                                                    <td>Purchase order#:</td>
                                                    <td><?php echo $meta['purchase_order_number'] ?></td>
                                                </tr>
                                            <?php } ?>

                                            <tr>
                                                <td>Shipping Method:</td>
                                                <td><?php echo !empty($meta['order_shipping_title']) ? $meta['order_shipping_title'] : "" ?></td>
                                            </tr>

                                        </table>
                                    </div>
                                </td>
                </tr>
            </table>
           <br>
            <?php if (!empty($meta['order_comments'])) { ?>
                <p>Order Note: <?php echo !empty($meta['order_comments']) ? $meta['order_comments'] : ''; ?></p>
            <?php } ?>
            <br>

            <!-- Order Items -->
            <?php if (!empty($slip['order_items'])) { ?>
            <table class="order_receipt">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($slip['order_items'] as $item) { 
                        $product_name = @$item['product_name'];
                        $meta = @$item['item_meta'];
                        $product_id = $meta['product_id'];
                        $category_name=order_product_category($product_id);
                        $meta_values = !empty($meta['variation']['values']) ? $meta['variation']['values'] : [];
                        if ($item['item_type'] === "line_item") { ?>
                    <tr>
                        <td>
                            <p><?php echo $product_name . (!empty($category_name) ? " - " . $category_name : ""); ?></p>
                            <div class="item-meta">
                                <?php if (!empty($meta_values['sku'])) { ?>
                                    <p><b>SKU:</b> <?php echo $meta_values['sku']; ?></p>
                                <?php } 
                                if (!empty($meta['variations'])) {
                                    foreach (json_decode($meta['variations'], true) as $key => $variation) {
                                        $key = ucfirst(str_replace(['attribute_', '_'], [' ', ' '], $key));
                                        if (is_array($variation)) $variation = implode(', ', $variation);
                                ?>
                                    <p><b><?php echo $key; ?>:</b> <?php echo $variation; ?></p>
                                <?php } } ?>
                            </div>
                        </td>
                        <td><?php echo $meta['quantity']; ?></td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
            <?php } ?>
            <?php
            if(count($slips) > 1) {
                ?>

            <!-- Subtotal -->
            <div style="float: right; width: 320px;">
                <table class="order_receipt_subtotal">
                    <tr>
                        <td>Item total:</td>
                        <td style="text-align: right;"><?php echo _price($totals['item_total']); ?></td>
                    </tr>
                    <tr>
                        <td>Item tax:</td>
                        <td style="text-align: right;"><?php echo _price($totals['tax_total']); ?></td>
                    </tr>
                    <tr>
                        <th>Packing slip total:</th>
                        <td style="text-align: right;"><?php echo _price($totals['item_total'] + $totals['tax_total']); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php  } ?>
    </div>

    <!-- Footer displayed on every page -->
    <div class="footer">
        <p>Customer: <?php echo @$shipping_address['shipping_first_name'] . ' ' . @$shipping_address['shipping_last_name'].', '. ' Order Id: ' . $order_id;?> </p>
    </div>

    <?php if (count($slips) - 1 > $i) { ?>
        <div class="page_break"></div>
    <?php } } } ?>
</div>
</body>
</html>
