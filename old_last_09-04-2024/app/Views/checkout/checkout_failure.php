<!--header-------------->
<?php echo view('includes/header.php');?>
<!---headder end-------->

<!--- banner -->
<div class="underbanner" style="background: url('<?php echo base_url('./assets/images/coffee-club-subscription/banner.jpg') ?>') no-repeat;  "></div>

<div class="wrapper">
    <div id="woocommerce-cart-form-div" class="container" style="top: 72px; ">

        <div class="woocommerce">
            <h1 style="margin-top: 0.5em" class="align_center">Order Complete</h1>

            <div class="text-center">Thank you. Your order<b>#<?php echo $order_id ?></b> has been received. <a id="printlink" href="#" onclick="window.print()" style="float: right;color: #d62135;">Print</a> </div>

            <br>
            <br>
            <div class="order-complete-message" style="width: 800px; margin: auto">

                <table class="table" cellpadding="5" cellspacing="5">
                    <?php
                    $products = $order['products'];
                    $shipping_cost = $order['shipping_cost'];
                    $vat = $order['tax'];
                    $subtotal = 0;
                    $discount = $order['discount_amount'];
                    $shipping_method = $order['shipping_method'];
                    foreach($products as $item) {
                        $order_data = json_decode($item['variations'],true);
                        $variations = [];
                        foreach($order_data as $k=>$od) {
                            if(strstr($k,'variation_')) {
                                $key = str_replace('variation_','',$k);
                                $key = str_replace('_',' ',$key);
                                $key = ucfirst($key);
                                $variations[$key] = $od;
                            }
                        }
                        $subtotal += $item['product_price']*$item['product_qty'];
                        ?>
                    <tr>
                        <th><?php echo $item['product_name'] ?></th>
                        <td>
                            <?php foreach($variations as $k=>$variation) { ?>
                             <div><b><?php echo $k ?>: </b> <?php echo $variation ?></div>
                            <?php }?>
                        </td>
                        <td>
                            <b>Price:</b> <?php echo _price($item['product_price']) ?>
                        </td>
                        <td>
                            <b>Quantity:</b> <?php echo $item['product_qty'] ?>
                        </td>
                        <td>
                            <b>Total:</b> <?php echo _price($item['product_price']*$item['product_qty']) ?>
                        </td>
                    </tr>
                    <?php }?>
                </table>
                <br>

                <div class="clearfix"></div>


                <div class="pull-right">
                    <table width="300" cellpadding="10" cellspacing="5">
                        <tr align="top" style="vertical-align: top">
                            <th>Date: </th>
                            <td>
                                <?php echo date('d F, Y',strtotime($order['date_created'])) ?>
                            </td>
                        </tr>
                        <tr align="top" style="vertical-align: top">
                            <th>Shipping: </th>
                            <td>
                                <div style="font-size: 14px"><?php echo $shipping_method ?></div>
                                <div><?php echo _price($shipping_cost+$vat); if($vat) {echo '<span style="font-size: 14px;margin-left: 5px;
}">(includes '._price($vat).' VAT)</span>';} ?></div>
                            </td>
                        </tr>
                        <?php if($discount) {
                            ?>
                            <tr align="top" style="vertical-align: top">
                                <th>Discount: </th>
                                <td style="padding-bottom: 10px"><?php echo _price($discount) ?></td>
                            </tr>
                        <?php
                            $subtotal = $subtotal-$discount;
                        } ?>

                        <tr align="top" style="vertical-align: top; border-top: 1px solid #eee;">
                            <th style=" padding-top: 10px">Subtotal: </th>
                            <td style=" padding-top: 10px"><?php echo _price(number_format($subtotal+$shipping_cost+$vat,2)); ?></td>
                        </tr>
                    </table>
                </div>

                <div class="clearfix"></div>

                <br>
                <br>
                <br>
            </div>
        </div>

        <style media="print">
            .header1,
            html body .underbanner,
            #footer,
            #printlink {
                display: none !important;
            }
        </style>
    </div>
</div>


<!------------footer ------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end ------------------->