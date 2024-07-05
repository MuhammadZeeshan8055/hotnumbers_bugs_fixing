<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;200&display=swap">

    <style>
        div {font-family: 'Lato', sans-serif;}

        body {
            font-family: 'Lato', sans-serif;
            padding: 1em 6em 3em;
            font-size: 14px;
        }
        html { margin: 0px;}
        h1 {
            font-size: 20px;
        }
        h5 {
            font-size: 14px;
            margin-bottom: 15px;
        }
        p {
            margin-top:0;
            margin-bottom: 0;
            font-size: 12px;
            font-weight: 400;
            line-height: 18px;
        }
        td {
            font-size: 12px;
            font-weight: 400;
        }
        tr {
            vertical-align: top;
        }
        th {
            text-align: left;
            font-size: 13px;
            padding: 5px 8px;
        }
        #site-logo {
            padding-top: 25px;
            width: 150px;
            margin-left: 18px;
        }
        thead tr, .order_receipt_subtotal tbody th {
            background-color: #000;
            color: #fff;
            padding: 5px;
            vertical-align: middle !important;
        }

        #site-logo {
            margin-top: 1em;
        }

        .address-meta p {
            line-height: 18px;
            margin: 0;
        }

        .address-meta h5 {
            margin-bottom: 0;
        }

        .order_receipt tbody tr {
            border-bottom: 1px solid #eee;
        }
        .order_receipt tbody th {
            font-weight: 400;
        }
        .order_receipt tbody td {
            padding: 5px;
            font-size: 13px;
        }
        .order_receipt_subtotal {
            float: right;
            width: 230px !important;
        }
        .order_receipt_subtotal tbody th {
            width: 100px;
            font-size: 13px;
            font-weight: 400;
        }
        .order_receipt_subtotal tbody td {
            padding: 5px 10px;
            font-size: 13px;
        }
        .page_break { page-break-before: always; }

        .item-meta {
            padding-top: 5px;
            padding-bottom: 5px;
        }
        .item-meta p {
            font-size: 11px;
        }
    </style>

</head>
<body>
<div class="header">

    <?php
    if(!empty($slips)) {

        foreach($slips as $i=>$slip) {
            $totals = [
                'item_total' => 0,
                'tax_total' => 0,
            ];
        ?>

        <table width="100%" border="0">
            <tr>
                <td width="60%" align="top" style="vertical-align: middle">
                    <img id="site-logo" width="165" type="image/png" src="<?php echo asset('images/slip-logo.png') ?>"></td>
                <td align="top">
                    <div class="address-meta">
                        <h5><?php echo @$setting['title'] ?></h5>

                        <p><?php echo @$setting['site_address_1'] ?> <?php echo @$setting['site_address_2'] ?> <?php echo @$setting['post_code'] ?></p>
                        <p><?php echo !empty($setting['contact_number']) ? 'T '.$setting['contact_number']:'' ?> | E:</p>
                        <p><?php echo @$setting['contact_email'] ?> </p>
                    </div>
                </td>
            </tr>
        </table>
        <div style="margin-top: 2.5em"></div>
        <h1>PACKING SLIP</h1>
        <div style="margin-top: 1em"></div>
        <?php

                    $shipping_address = $slip['shipping_address'];
                    $meta = $slip['order_meta'];
                    $order_items = $slip['order_items'];
                    $order_id = $slip['order_id'];
                    $shipping_method = '';
                    $customer = $slip['customer'];

                    foreach($order_items as $item) {
                        $item_meta = $item['item_meta'];
                        if(!empty($item_meta['line_subtotal']) && $item['item_type'] === "line_item") {
                            $totals['item_total'] += $item_meta['line_subtotal'];
                        }
                        if(!empty($item_meta['cost']) && $item['item_type'] === "shipping") {
                            $totals['tax_total'] += $item_meta['cost'];
                        }
                        if(!empty($item_meta['tax_amount']) && $item['item_type'] === "tax") {
                            $totals['tax_total'] += $item_meta['tax_amount'];
                        }
                    }

                    ?>
                <div>
             <table width="100%">
                <tr>
                    <td>
                        <table width="100%">
                            <tr>
                                <td>
                                    <p><?php echo @$shipping_address['shipping_first_name'] ?> <?php echo @$shipping_address['shipping_last_name'] ?></p>
                                    <p><?php echo @$shipping_address['shipping_address_1'].' '.@$shipping_address['shipping_address_2'] ?></p>
                                    <p><?php echo @$shipping_address['shipping_city'] ?></p>
                                    <p><?php echo @$shipping_address['shipping_state'] ?></p>
                                    <p><?php echo @$shipping_address['shipping_postcode'] ?></p>
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
                        <br>

                        <?php if(!empty($slip['order_items'])) {?>
                        <table class="order_receipt table" cellpadding="0" cellspacing="0" width="100%" style="text-align: left">
                            <thead>
                                <tr>
                                    <th width="80%">Product</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($slip['order_items'] as $item) {
                                    $product_name = @$item['product_name'];

                                    $meta = @$item['item_meta'];
                                    $meta_values = !empty($meta['variation']['values']) ? $meta['variation']['values'] : [];
                                    if($item['item_type'] === "line_item") {
                                    ?>
                                <tr>
                                    <td>
                                       <p> <?php echo $product_name ?></p>
                                        <div class="item-meta">
                                            <?php if(!empty($meta_values['sku'])) {
                                                ?>
                                                <p><b>SKU:</b> <?php echo $meta_values['sku'] ?></p>
                                                <?php
                                            }?>
                                            <?php  if(!empty($meta['variations'])){
                                                    foreach(json_decode($meta['variations'],true) as $key=>$variation) {
                                                        $key = str_replace('attribute_','',$key);
                                                        $key = str_replace('_',' ',$key);
                                                        $key = ucfirst($key);
                                                        if(is_array($variation)) {
                                                            $variation = implode(', ',$variation);
                                                        }
                                                ?>
                                                <p><b><?php echo $key ?>: </b><?php echo $variation ?></p>
                                             <?php }
                                            }?>
                                        </div>
                                    </td>
                                    <td><?php echo $meta['quantity'] ?></td>
                                </tr>
                                <?php }
                                }?>

                            </tbody>
                        </table>
                        <?php } ?>

                        <?php
//                            echo view('checkout/order_receipt',[
//                                    'order'=>$slip,
//                                    'hide_price' => true,
//                                    'hide_total' => true,
//                                    'hide_subtotals'=>true
//                            ]);
                        ?>
                    </td>
                </tr>
             </table>
        <?php
            if(count($slips) > 1) {
                ?>
                <div style="float: right; width: 320px">
                    <table width="100%" class="order_receipt order_receipt_subtotal table">
                        <tr>
                            <td>Item total:</td>
                            <td style="text-align: right"><?php echo _price($totals['item_total']) ?></td>
                        </tr>
                        <tr>
                            <td>Item tax:</td>
                            <td style="text-align: right"><?php echo _price($totals['tax_total']) ?></td>
                        </tr>
                        <tr>
                            <th><div style="width: 120px">Packing slip total:</div></th>
                            <td style="text-align: right"><?php echo _price($totals['item_total']+$totals['tax_total']) ?></td>
                        </tr>
                    </table>
                </div>
                <?php
            }
            if(count($slips)-1 > $i) {
            ?>
            <div class="page_break"></div>
           <?php
            }
        }
     ?>


            <div style="clear: both"></div>
                </div>

        <?php
    }
    ?>

    <div style="margin-top: 8em"></div>
</div>
</body>
</html>


