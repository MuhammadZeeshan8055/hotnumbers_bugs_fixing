<?php
    if(!empty($order_id)) {

        $order = model('OrderModel');
        $get_order = $order->get_order($order_id);
        if($get_order) {
            $date_created = date('F d, Y',strtotime($get_order['date_created']));
            ?>
            <h3 style="color: #d62034; display: block; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;">[Order# <?php echo $order_id ?>] (<?php echo $date_created ?>)</h3>
            <div style="margin-bottom: 40px;">
            <table style="width: 100%">
                <thead>
                    <tr>
                        <th style="color: #414141; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;">Product</th>
                        <th style="color: #414141; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;">Quantity</th>
                        <th style="color: #414141; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left;">Price</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $subtotal = 0;

                foreach($get_order['products'] as $product) {
                        $data = json_decode($product['variations'],true);
                        $variations = [];

                        foreach($data as $k=>$d) {
                            if(strstr($k,'variation_')) {
                                $key = str_replace('variation_','',$k);
                                $key = str_replace('_',' ',$key);
                                $key = ucfirst($key);
                                $variations[$key] = $d;
                            }
                        }
                    ?>
                    <tr>
                        <td style="color: #414141; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap: break-word;">
                            <?php echo $product['product_name'] ?>
                            <?php if(!empty($variations)) { ?>
                                <ul class="wc-item-meta" style="font-size: small; margin: 1em 0 0; padding: 0; list-style: none;">
                                    <?php foreach($variations as $k=>$variation) { ?>
                                        <li style="margin: 0.5em 0 0; padding: 0;">
                                            <strong class="wc-item-meta-label" style="float: left; margin-right: .25em; clear: both;">
                                                <?php echo $k ?>:</strong> <p style="margin: 0;"><?php echo $variation ?></p>
                                        </li>
                                    <?php }?>
                                </ul>
                            <?php }?>
                        </td>
                        <td style="color: #414141; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
                            <?php echo $product['product_qty'] ?>
                        </td>
                        <td style="color: #414141; border: 1px solid #e5e5e5; padding: 12px; text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
                            £<?php echo $product['product_qty']*$product['product_price'] ?>
                        </td>
                    </tr>
                    <?php $subtotal += ($product['product_qty']*$product['product_price']); }

                $total = $subtotal + $product['shipping_cost'];
                ?>

                </tbody>
                <tfoot>
                    <tr>
                        <th class="td" scope="row" colspan="2" style="color: #414141; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;">Subtotal:</th>
                        <td class="td" style="color: #414141; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo currency_symbol ?></span><?php echo $subtotal ?></span></td>
                    </tr>
                    <tr>
                        <th class="td" scope="row" colspan="2" style="color: #414141; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;">Shipping:</th>
                        <td class="td" style="color: #414141; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo currency_symbol ?></span><?php echo $get_order['shipping_cost'] ?></span></td>
                    </tr>


                    <tr>
                        <th class="td" scope="row" colspan="2" style="color: #414141; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;">Payment method:</th>
                        <td class="td" style="color: #414141; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"></span><?php echo $get_order['payment_method'] ?></span></td>
                    </tr>

                    <tr>
                        <th class="td" scope="row" colspan="2" style="color: #414141; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;">Total:</th>
                        <td class="td" style="color: #414141; border: 1px solid #e5e5e5; vertical-align: middle; padding: 12px; text-align: left; border-top-width: 4px;"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo currency_symbol ?></span><?php echo $total ?></span></td>
                    </tr>
                </tfoot>
            </table>
            </div>



            <table id="addresses" cellspacing="0" cellpadding="0" border="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding: 0;">
                <tbody>

                    <tr>
                        <td valign="top" width="50%" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border: 0; padding: 0;">
                            <h2 style="color: #d62034; display: block; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;">Billing address</h2>

                            <address class="address" style="padding: 12px 12px; font-size: 16px; color: #414141; line-height: 2; min-height: 123px; border: 1px solid #e5e5e5;">
                               <?php
                               echo implode('<br>',[$get_order['billing_address1'],$get_order['billing_address2'],$get_order['billing_address3']]);
                               ?>
                                <p style="margin: 0 0 16px;"><?php echo $get_order['billing_email'] ?></p>
                            </address>

                        </td>

                        <td valign="top" width="50%" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding: 0;">
                            <h2 style="color: #d62034; display: block; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 1.3; margin: 0 0 18px; text-align: left;">Shipping address</h2>

                            <address class="address" style="padding: 12px 12px; font-size: 16px; line-height: 2;   min-height: 130px; color: #414141; border: 1px solid #e5e5e5;">
                                <?php
                                if(!empty($get_order['shipping_different_address'])){
                                echo implode('<br>',[$get_order['shipping_address1'],$get_order['shipping_address2']]);
                                ?>
                                <p style="margin: 0 0 16px;"><?php echo $get_order['shipping_email'] ?></p>
                                    <?php }
                                else {
                                    ?>
                                    <p style="margin: 0 0 16px;">Same as billing</p>
                                    <?php
                                }?>
                            </address>
                        </td>
                    </tr>

                </tbody>
            </table>

            <?php
        }
    }
?>