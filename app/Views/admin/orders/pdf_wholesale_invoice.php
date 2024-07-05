<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;200&display=swap">

    <style>
        div, strong {font-family: 'Lato', sans-serif;}

        body {
            font-family: 'Lato', sans-serif;
            padding: 1em 3em 2em;
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
            padding-bottom: 3px;
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
            background-color: #fff;
            color: #000;
            padding: 5px;
            vertical-align: middle !important;
        }
        .order_receipt th, .order_receipt_subtotal thead tr > * {
            border-bottom: 1px solid #000;
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
        strong {
            font-weight: 800;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        .foot-note {
            position: absolute;
            bottom: 1em;
            left: 3em;
        }

        .foot-note p {
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
                    <td width="60%" align="top" style="vertical-align: middle; text-align: right">
                        <img id="site-logo" style="width: 120px" type="image/png" src="<?php echo asset('images/slip-logo.png') ?>">
                    </td>
                </tr>
            </table>
            <div style="margin-top: 2.5em"></div>

            <?php

            $billing_address = $slip['billing_address'];
            $slip_meta = $slip['order_meta'];
            $order_items = $slip['order_items'];
            $order_id = $slip['order_id'];
            $shipping_method = '';
            $customer = $slip['customer'];
            $price_has_tax = $slip_meta['price_with_tax'] == "exclusive";

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

            <div>
                <table width="100%">
                    <tr>
                        <td>
                            <div>
                                <div style="float: left">
                                    <h1 style="font-size: 35px;margin-top: 0; margin-bottom: 10px; font-weight: 400">INVOICE</h1>
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                <p><?php echo @$billing_address['billing_first_name'] ?> <?php echo @$billing_address['billing_last_name'] ?></p>
                                                <p><?php echo @$billing_address['billing_address_1'].' '.@$billing_address['billing_address_2'] ?></p>
                                                <p><?php echo @$billing_address['billing_city'] ?></p>
                                                <p><?php echo @$billing_address['billing_state'] ?></p>
                                                <p><?php echo @$billing_address['billing_postcode'] ?></p>
                                                <p><?php echo @$billing_address['billing_country'] ?></p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div style="float:right">
                                    <?php
                                    $inv_prefix = $customer->role_id == 'wholesale_customer' ? 'HNW':'HNR';
                                    $invoice_number = _order_number($order_id,$inv_prefix);
                                    $reference = $slip_meta['payment_method_title'];
                                    $vat_number =  @$setting['vat_number'];
                                    ?>
                                    <div>
                                        <div style="padding-right: 2em;display: inline-block; vertical-align: top">
                                            <div style="width: fit-content; float: left">
                                                <div style="padding-bottom: 5px">
                                                    <b>Invoice Date</b>
                                                    <p><?php echo _date($slip['order_date']) ?></p>
                                                </div>
                                                <div style="padding-bottom: 5px">
                                                    <b>Invoice Number</b>
                                                    <p><?php echo $invoice_number ?></p>
                                                </div>
                                                <div style="padding-bottom: 5px">
                                                    <b>Reference</b>
                                                    <p><?php echo $reference ?></p>
                                                </div>
                                                <div style="padding-bottom: 5px">
                                                    <b>VAT Number</b>
                                                    <p><?php echo $vat_number ?></p>
                                                </div>
                                                <?php if(!empty($slip_meta['purchase_order_number'])) { ?>
                                                <div style="padding-bottom: 5px">
                                                    <b>Purchase Order #</b>
                                                    <p><?php echo $slip_meta['purchase_order_number'] ?></p>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div style="display: inline-block; vertical-align: top">
                                            <div style="width: fit-content; float: left">
                                                <p><?php echo @$setting['title'] ?></p>
                                                <p><?php echo @$setting['site_address_1'] ?></p>
                                                <p><?php echo @$setting['site_address_2'] ?></p>
                                                <p><?php echo @$setting['site_address_town'] ?></p>
                                                <p><?php echo @$setting['post_code'] ?></p>
                                                <p>Tel: <?php echo @$setting['contact_number'] ?></p>
                                                <p>hotnumberscoffee.co.uk</p>
                                            </div>
                                        </div>
                                        <div style="clear: both"></div>
                                    </div>
                                </div>
                                <div style="clear: both"></div>
                            </div>

                            <br>
                            <br>
                            <br>

                            <?php if(!empty($slip['order_items'])) {?>
                                <table class="order_receipt table" cellpadding="0" cellspacing="0" width="100%" style="text-align: left" border="0">
                                    <thead>
                                    <tr>
                                        <th width="<?php echo $price_has_tax ? "100":"80" ?>%"><b>Product</b></th>
                                        <th><b>Quantity</b></th>
                                        <th class="text-center" width="80"><b>Unit Price</b></th>
                                        <?php if($price_has_tax) { ?>
                                        <th class="text-center" width="70"><b><?php echo $price_has_tax ?></b></th>
                                        <?php } ?>
                                        <th class="text-right" width="70"><b>Amount <?php echo env('DEFAULT_CURRENCY_CODE') ?></b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $product_total = 0;
                                    $vat_total = 0;
                                    foreach($slip['order_items'] as $item) {
                                        $product_name = @$item['product_name'];
                                        $meta = @$item['item_meta'];
                                        $meta_values = !empty($meta['variation']['values']) ? @$meta['variation']['values'] : [];



                                        if($item['item_type'] === "line_item") {
                                            $item_total = $meta['item_price'] * $meta['quantity'];
                                            $vat = !empty($meta['tax']) ? _price($meta['tax']) : 'No VAT';
                                            if($meta['tax']) {
                                                $vat_total += $meta['tax'];
                                            }

                                            $product_total += $item_total;
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
                                                <td class="text-center"><?php echo $meta['quantity'] ?></td>
                                                <td class="text-center"><?php echo _price($meta['item_price']) ?></td>
                                                <?php if($price_has_tax) { ?>
                                                 <td class="text-center"><?php echo $price_has_tax ? $vat : "" ?></td>
                                                <?php }?>
                                                <td><div class="text-center"><?php echo _price($item_total) ?></div></td>
                                            </tr>
                                        <?php }
                                    }?>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <?php if($price_has_tax) { ?>
                                            <td></td>
                                            <?php } ?>
                                            <td class="text-right"><p>Subtotal</p></td>
                                            <td class="text-center"><p><?php echo _price($product_total) ?></p></td>
                                        </tr>
                                        <?php if($price_has_tax) { ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <?php if($price_has_tax) { ?>
                                                <td style="padding-top: 15px;"></td>
                                            <?php } ?>
                                            <td style="border-bottom: 1px solid #000; padding-top: 15px;" class="text-right"><p>TOTAL VAT</p></td>
                                            <td style="border-bottom: 1px solid #000; padding-top: 15px;" class="text-center"><p><?php echo _price($vat_total) ?></p></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td></td>
                                            <td style="padding-top: 15px;"></td>
                                            <?php if($price_has_tax) { ?>
                                                <td style="padding-top: 15px;"></td>
                                            <?php } ?>
                                            <td class="text-right" style="padding-top: 12px"><b>TOTAL <?php echo env('DEFAULT_CURRENCY_CODE') ?></b></td>
                                            <td class="text-center" style="padding-top: 12px"><b><?php echo _price($product_total+$vat_total) ?></b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            <?php } ?>

                        </td>
                    </tr>
                </table>

                <br>

                <?php
                $due_date = date('d M Y',strtotime($slip['order_date'].' + 30 days'));
                ?>

                <div>
                    <?php if(!$slip_meta['paid_date']) { ?>
                    <p><b style="font-size: 16px">Due Date: <?php echo $due_date ?></b></p>
                    <?php }else {
                        ?>
                    <p><b style="font-size: 16px">Paid on: <?php echo _date($slip_meta['paid_date']) ?></b></p>
                    <?php
                    }?>

                    <p>VAT no: <?php echo $setting['vat_number'] ?></p>
                    <br>
                    <?php
                        if(!empty($setting['invoice_payment_details'])) {
                            foreach(explode("\n",$setting['invoice_payment_details']) as $detail) {
                                ?>
                            <p><?php echo $detail ?></p>
                                <?php
                            }
                        }
                    ?>

                    <p>Our payment terms are 30 days from date of invoice.</p>

                    <?php if(!$slip_meta['paid_date']) { ?>
                    <p><a href="">View and pay online now</a> </p>
                    <?php } ?>
                </div>
            </div>


            <?php
            if(count($slips)-1 > $i) {
                ?>
                <div class="page_break"></div>
                <?php
            }
            ?>
                <div style="clear: both"></div>
            </div>

            <div class="foot-note">
                <p><?php echo $setting['invoice_footer_text'] ?></p>
            </div>
                <?php
        }
        ?>

        <?php
    }
    ?>

    <div style="margin-top: 8em"></div>
</div>
</body>
</html>