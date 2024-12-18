<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;200&display=swap');

        div {font-family: 'Lato', sans-serif;}

        body {
            font-family: 'Lato', sans-serif;
            padding: 0 2em 2em;
        }
        h1 {
            font-size: 22px;
        }
        h5 {
            font-size: 14px;
            margin-bottom: 15px;
        }
        p {
            margin-bottom: 8px;
            margin-top:0;
            font-size: 12px;
            font-weight: 400;
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
        thead tr, .order_receipt_subtotal tbody th {
            background-color: #000;
            color: #fff;
            padding: 5px;
            vertical-align: middle !important;
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
                    <img width="165" type="image/png" src="<?php echo asset('images/slip-logo.png') ?>"></td>
                <td align="top">
                    <h5><?php echo @$setting['title'] ?></h5>
                    <p><?php echo @$setting['site_address_1'] ?></p>
                    <p><?php echo @$setting['site_address_2'] ?></p>
                    <p>T: <?php echo @$setting['contact_number'] ?> | E:</p>
                    <p><?php echo @$setting['contact_email'] ?> </p>
                </td>
            </tr>
        </table>
        <div style="margin-top: 1em"></div>
        <h1>PACKING SLIP</h1>
        <div style="margin-top: 1em"></div>
        <?php
                    $shipping_address = $slip['shipping_address'];
                    $meta = $slip['order_meta'];
                    $order_items = $slip['order_items'];
                    $order_id = $slip['order_id'];
                    $shipping_method = '';

                    foreach($order_items as $item) {
                        $item_meta = $item['item_meta'];
                        if($item['item_type'] === "shipping") {
                            $shipping_method = $item['product_name'];
                        }
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
                        <h2>Order# <?php echo $order_id ?></h2>
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
                                    <div class="pull-right fr" style="float: right">
                                        <table width="100%" align="top" cellspacing="2" cellpadding="2">
                                            <tr>
                                                <td width="120">Order Date:</td>
                                                <td><?php echo date(env('date_full_format'),strtotime($meta['paid_date'])) ?></td>
                                            </tr>
                                            <tr>
                                                <td width="120">Payment Method:</td>
                                                <td><?php echo payment_method_map($meta['payment_method']) ?></td>
                                            </tr>
                                            <tr>
                                                <td width="120">Shipping Method:</td>
                                                <td><?php echo @$shipping_method ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>

                        </table>

                        <br>

                        <?php
                            echo view('checkout/order_receipt',[
                                    'order'=>$slip,
                                    'hide_price' => true,
                                    'hide_total' => true,
                                    'hide_subtotals'=>true
                            ]);
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


