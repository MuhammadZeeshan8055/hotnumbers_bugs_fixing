<?php echo view('includes/header.php');?>

<div class="underbanner" style="background: url('<?php echo base_url('./assets/images/coffee-club-subscription/banner.jpg') ?>') no-repeat;  "></div>

<div class="wrapper">
    <div id="woocommerce-cart-form-div" class="container" style="top: 72px; ">

        <div class="woocommerce">
            <h1 style="margin-top: 0.5em" class="align_center">Order Complete</h1>

            <?php if(!empty($orders)): ?>
                <?php foreach($orders as $order): ?>
                    <div class="text-center">Thank you. Your order <b>#<?php echo _order_number($order['order_id']) ?></b> has been received. 
                        <a id="printlink" href="#" onclick="window.print()" style="float: right;color: #d62135;">Print</a>
                    </div>

                    <br>
                    <br>
                    <div class="order-complete-message" style="width: 800px; margin: auto">
                        <?php echo view('checkout/order_receipt', ['order' => $order]); ?>
                        <br>
                        <br>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center">No orders found.</div>
            <?php endif; ?>
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

<?php echo view('includes/footer');?>
