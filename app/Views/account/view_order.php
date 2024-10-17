
<?php $session = session(); ?>

<?php echo view( 'includes/header');
?>


<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">



<div class="underbanner" style="background: url('<?php echo base_url('assets/images'); ?>/banner.jpg');"></div>

<!-- wrapper -->
<div class="wrapper">
        <!-- title -->
	<h1 class="pagetitle">My account</h1>
			<div class="container">
			    <div class="woocommerce">



                    <div class="clearfix"></div>

                    <?php include "menu.php"?>

                    <?php
                    if(!empty($order)) {

                        ?>
                     <!-- order -->
                    <div class="woocommerce-MyAccount-content">

                        <?php echo get_message(); ?>

                	    <?php
                        if(!empty($order['order_id'])) {
                            $order_id = $order['order_id'];
                            $status = $order['status'];
                            $date = _date($order['order_date']);
                            $products = $order['order_items'];
                            $order_meta = $order['order_meta'];
                            $shipping_cost = $order_meta['order_shipping'];
                            $vat = !empty($order_meta['order_tax']) ? number_format($order_meta['order_tax'],2) : 0;
                            $order_tax = !empty($order_meta['order_tax']) ? $order_meta['order_tax'] : 0;
                            $order_total = !empty($order_meta['order_total']) ? $order_meta['order_total'] : 0;
                            $payment_method = $order_meta['payment_method_title'];
                            $order_discount = $order_meta['cart_discount'];

                            ?>
                            <p><strong>Order#</strong> <span class="order-number"><?php echo _order_number($order_id); ?></span> placed on <span class="order-date"><?php echo $date; ?></span></p>
                            <p><strong>Status:</strong> <span><?php echo ucfirst(_order_status($status)) ?></span></p>

                            <!--- section 1 --->
                            <section class="woocommerce-order-details">
                                <div class="pull-left"><h3>Order details</h3></div>
                                <?php
                                    if($order['order_type'] === "shop_subscription") {
                                        ?>
                                        <div class="pull-right">
                                            <div class="d-flex">
                                                <?php if($status === "active") { ?>
                                                <a data-href="<?php echo base_url('account/orders/view_order') ?>/<?php echo _order_number($order_id) ?>?action=pause" data-confirm="Are you sure to pause this subscription?" class="button btn btn-primary d-inline-block" style="margin-right: 1em">Pause</a>
                                                <?php } ?>
                                                <?php if($status === "on-hold") { ?>
                                                    <a data-href="<?php echo base_url('account/orders/view_order') ?>/<?php echo _order_number($order_id) ?>?action=resume" data-confirm="Are you sure to pause this subscription?" class="button btn btn-primary d-inline-block" style="margin-right: 1em">Resume</a>
                                                <?php } ?>
                                                <?php if($status !== "cancelled") { ?>
                                                <a data-href="<?php echo base_url('account/orders/view_order') ?>/<?php echo _order_number($order_id) ?>?action=cancel" data-confirm="Are you sure to cancel this subscription?" class="button btn btn-primary d-inline-block">Cancel</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                ?>

                                <?php
                                $price_with_tax = !empty($order_meta['price_with_tax']) ? $order_meta['price_with_tax'] : '';
                                $display_tax_price = !empty($order_meta['display_tax_price']) ? $order_meta['display_tax_price'] : '';
                                $tax_name = !empty($order_meta['tax_name']) ? $order_meta['tax_name'] : 'VAT';
                                $tax_inc_text = '';

                                if($price_with_tax === "exclusive" && $display_tax_price === "including_tax") {
                                    $tax_inc_text = '<div style="font-size: 14px"><small>(inc. '.$tax_name.')</small></div>';
                                }
                                if($price_with_tax === "inclusive" && $display_tax_price === "excluding_tax") {
                                    $tax_inc_text = '<div style="font-size: 14px"><small>(ex. '.$tax_name.')</small></div>';
                                }
                                ?>

                                <div class="clearfix clear"></div>
                                <table class="shop_table order_details">
                                    <thead>
                                    <tr>
                                        <th class="" width="50%">Product</th>
                                        <th class="">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $product_total = 0;
                                    $product_subtotal = 0;
                                    $inactive_prods = [];
                                    if(!empty($products)) {
                                        foreach($products as $product) {
                                            $attributes = [];
                                            if($product['item_type'] === "line_item") {
                                                $meta = $product['item_meta'];
                                                $product_total = !empty($meta['display_price_html']) ? $meta['display_price_html'] : 0;
                                                $qty = !empty($meta['quantity']) ? $meta['quantity'] : 0;

                                                $product_subtotal += $meta['item_price'];
                                                $url = '';
                                                if(!empty($meta['product_id'])) {
                                                    $get_product = $productModel->product_by_id($meta['product_id']);
                                                    $error_state = '';

                                                    if($get_product->stock_status === 'outofstock' || $get_product->stock === 0 ) {
                                                        $error_state = 'Out of stock';
                                                        $inactive_prods[$meta['product_id']] = 0;
                                                    }
                                                    else if($get_product->status !== 'publish') {
                                                        $error_state = 'Inactive';
                                                        $inactive_prods[$meta['product_id']] = 0;
                                                    }
                                                    $url = $get_product->slug;
                                                }

                                                if(!empty($meta['variations'])){
                                                    foreach(json_decode($meta['variations'],true) as $key=>$variation) {
                                                        $key = str_replace('attribute_', '', $key);
                                                        $key = str_replace('_', ' ', $key);
                                                        $key = ucfirst($key);
                                                        $attributes[$key] = $variation;
                                                    }
                                                }

                                                if(!empty($meta['subscription-type'])) {
                                                    $attributes['Subscription type'] = $meta['subscription-type'];
                                                }

                                                if(!empty($meta['duration'])) {
                                                    $attributes['Duration'] = $meta['duration'];
                                                }


                                                ?>
                                                <tr class="woocommerce-table__line-item order_item">
                                                <td class="woocommerce-table__product-name product-name">
                                                    <?php if($url) { ?>
                                                    <a target="_blank" href="<?php echo site_url('shop/product/'.$url) ?>">
                                                        <?php } ?>
                                                    <?php echo $product['product_name'] ?>
                                                    <strong class="product-quantity"> &times;&nbsp;<?php echo $qty; ?>
                                                        <?php
                                                        if($product['item_type'] === "line_item") {
                                                            if($error_state) {
                                                                ?>
                                                                <small class="color-red"> &nbsp;(<?php echo $error_state ?>)</small>
                                                                <?php
                                                            }
                                                        }?>
                                                    </strong> <?php if($url) { ?></a> <?php } ?>

                                                    <div class="mt-2 f-16">
                                                        <?php foreach($attributes as $key=>$value) {
                                                            ?>
                                                            <div class="mb-1"><span style="font-weight: 600"><?php echo $key ?></span>: 
                                                                <?php 
                                                                    if($key=="Sizes"){
                                                                        echo $value[0]; 
                                                                    }else{
                                                                        echo $value; 
                                                                    }
                                                                ?>
                                                            </div>
                                                            <?php
                                                        }?>
                                                    </div>

                                                </td>


                                                <td class="woocommerce-table__product-total product-total" style="vertical-align: top">
                                                    <span class="woocommerce-Price-amount amount"><bdi><?php echo $product_total; ?></bdi></span>
                                                    <?php echo $tax_inc_text ?>
                                                </td>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </tr>
                                                <?php
                                            }

                                            ?>


                                    </tbody>
                                    <tfoot>

                                    <?php //pr($order_meta); ?>

                                    <tr>
                                        <th scope="row">Subtotal:</th>
                                        <td><span class="woocommerce-Price-amount amount"><?php echo _price($product_subtotal); ?></td>
                                    </tr>

                                    <?php if(!empty($order_meta['global_discount'])) {
                                        ?>
                                        <tr>
                                            <th scope="row">Store Discount:</th>
                                            <td>
                                                -<?php echo _price($order_meta['global_discount']) ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }?>

                                    <?php if(!empty($order_meta['wholesale_discount'])) {
                                        ?>
                                        <tr>
                                            <th scope="row">Wholesale Discount:</th>
                                            <td>
                                                -<?php echo _price($order_meta['wholesale_discount']) ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }?>

                                    <?php if(!empty($order_meta['coupon_discount'])) {
                                        ?>
                                        <tr>
                                            <th scope="row">Coupon Discount:</th>
                                            <td>
                                                -<?php echo _price($order_meta['coupon_discount']) ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }?>

                                    <?php if(!empty($order_meta['discount_text'])) {
                                        ?>
                                        <tr>
                                            <th scope="row">Discount:</th>
                                            <td>
                                                <?php echo $order_meta['discount_text'] ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }?>

                                    <tr>
                                        <th scope="row">Shipping:</th>
                                        <td>
                                            <div><?php
                                                if($shipping_cost) {
                                                    echo "<small>".$order_meta['order_shipping_title']."</small><br>";
                                                    echo _price($shipping_cost);
                                                }else {
                                                    ?>
                                                <p>Free Shipping</p>
                                                <?php
                                                }?></div>
                                        </td>
                                    </tr>

                                    <?php if($order_meta['total_tax'] && $display_tax_price === "excluding_tax") { ?>
                                    <tr>
                                        <th scope="row">Tax:</th>
                                        <td><span class="woocommerce-Price-amount amount"><?php echo _price($order_meta['total_tax']) ?></span>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                    <tr>
                                        <th scope="row">Total:</th>
                                        <td><span class="woocommerce-Price-amount amount"><?php echo _price($order_total) ?></span> <?php if($order_meta['total_tax'] && $display_tax_price === "including_tax") {echo '<div><small>(includes '._price($order_meta['total_tax']).' '.$tax_name.')</small></div>';} ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Payment method:</th>
                                        <td><?php echo $payment_method ?></td>
                                    </tr>

                                    <?php if(!empty($order_meta['purchase_order_number'])) { ?>
                                    <tr>
                                        <th scope="row">Purchase order number:</th>
                                        <td><?php echo $order_meta['purchase_order_number'] ?></td>
                                    </tr>
                                    <?php } ?>

                                    <?php if(!empty($order_meta['order_comments'])) { ?>
                                        <tr>
                                            <th scope="row">Order note:</th>
                                            <td><?php echo $order_meta['order_comments'] ?></td>
                                        </tr>
                                    <?php } ?>

                                    </tfoot>
                                </table>
                            </section>
                            <!--- section 1 end --->
                                <?php
                            $billing = $order['billing_address'];
                            $shipping = $order['shipping_address'];
                         ?>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
                                    <tr>
                                        <th width="150">Name</th>
                                        <td><?php echo @$billing['billing_first_name']; ?> <?php echo @$billing['billing_last_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td><?php echo @$billing['billing_email']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Address 1</th>
                                        <td><?php echo @$billing['billing_address_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Address 2</th>
                                        <td><?php echo @$billing['billing_address_2']; ?></td>
                                    </tr>
                                    <?php if(!empty($billing['billing_country'])) { ?>
                                    <tr>
                                        <th>Country</th>
                                        <td><?php
                                            $country = get_countries($billing['billing_country']);
                                            echo @$country; ?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <th>City</th>
                                        <td><?php echo @$billing['billing_city']; ?></td>
                                    </tr>
                                    <?php if(!empty($billing['billing_state'])) { ?>
                                    <tr>
                                        <th>State</th>
                                        <td><?php echo @$billing['billing_state']; ?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <th>Postcode</th>
                                        <td><?php echo @$billing['billing_postcode']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td><?php echo @$billing['billing_phone']; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
                                    <tr>
                                        <th width="150">Name</th>
                                        <td><?php echo @$shipping['shipping_first_name']; ?> <?php echo @$shipping['shipping_last_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Address 1</th>
                                        <td><?php echo @$shipping['shipping_address_1']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Address 2</th>
                                        <td><?php echo @$shipping['shipping_address_2']; ?></td>
                                    </tr>
                                    <?php if(!empty($shipping['shipping_country'])) { ?>
                                    <tr>
                                        <th>Country</th>
                                        <td><?php
                                            $country = get_countries($shipping['shipping_country']);
                                            echo @$country; ?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <th>City</th>
                                        <td><?php echo @$shipping['shipping_city']; ?></td>
                                    </tr>
                                    <?php if(!empty($billing['shipping_state'])) { ?>
                                    <tr>
                                        <th>State</th>
                                        <td><?php echo @$shipping['shipping_state']; ?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <th>Postcode</th>
                                        <td><?php echo @$shipping['shipping_postcode']; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                            <?php
                            if($order['order_type'] === "shop_order") {
                                if(empty($inactive_prods)) {
                                    if($status === "pending") { ?>
                                        <a href="<?php echo base_url('account/orders/pay/'.$order['order_id']) ?>" class="button">Pay Now</a>
                                    <?php }else {
                                        ?>
                                        <a href="<?php echo base_url('account/orders/order-again/'.$order['order_id']) ?>" class="button">Order again</a>
                                    <?php
                                    }
                                }
                            }
                            ?>

                        <?php }
                        else {
                            ?>
                            <div class="text-center">
                                <div><i class="fa icon-exclamation color-red"></i> </div>
                                <h4>Order not found</h4>
                            </div>
                            <?php
                        } ?>
                     

                    </div>

                    <?php }else {
                        ?>
                        <div class="text-center">
                            <div><i class="fa icon-exclamation color-red"></i> </div>
                            <h4>Order not found</h4>
                        </div>
                        <?php
                    } ?>

                </div>
            </div>
                        </div>
           
        


<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


