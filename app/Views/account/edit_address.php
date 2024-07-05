<?php $session = session(); ?>

<?php echo view( 'includes/header'); ?>


<div class="underbanner" style="background: url('<?php echo base_url('assets/images'); ?>/banner.jpg');"></div>

<!-- wrapper -->
<div class="wrapper">
        <!-- title -->
	<h1 class="pagetitle">My account</h1>
			<div class="container">
			<div class="woocommerce">
    <?php include "menu.php" ?>
            <!---address change successfully-->
            <div class="woocommerce-MyAccount-content">

                <?php echo get_message() ?>

            <!-- edit address --->
            <p>	The following addresses will be used on the checkout page by default.</p>

                <div class="u-columns woocommerce-Addresses col2-set addresses">
                    <!-- billing address -->
                    <div class="u-column1 woocommerce-Address">
                        <div class="row d-flex">
                            <div class="col-md-6">
                                    <header class="woocommerce-Address-title title">
                                        <h3>Billing address</h3>
                                        <a href="<?php echo base_url('account/edit-address/billing')?>" class="edit color-red"><i class="lni lni-pencil"></i> Edit</a>
                                    </header>
                                <address>
                                        <?php

                                            if(!empty($billing_info)) {
                                                ?>
                                                <p><?php echo @$billing_info['billing_first_name'] ?> <?php echo @$billing_info['billing_last_name'] ?></p>
                                                <p><?php echo @$billing_info['billing_company'] ?></p>
                                                <p><?php echo @$billing_info['billing_address_1'] ?></p>
                                                <p><?php echo @$billing_info['billing_address_2'] ?></p>
                                                <p><?php echo @$billing_info['billing_city'] ?></p>
                                                <p><?php echo @$billing_info['billing_state'] ?></p>
                                                <p><?php echo @$billing_info['billing_postcode'] ?></p>
                                                <p><?php echo @$billing_info['billing_email'] ?></p>
                                                <?php
                                            }
                                        ?>
                                </address>
                            </div>

                            <div class="col-md-6">
                                <header class="woocommerce-Address-title title">
                                    <h3>Shipping address</h3>
                                    <a href="<?php echo base_url('account/edit-address/shipping')?>" class="edit color-red"><i class="lni lni-pencil"></i> Edit</a>
                                </header>
                                <address>
                                        <?php
                                            if(!empty($shipping_info)) {
                                                ?>
                                                <p><?php echo @$shipping_info['shipping_first_name'] ?> <?php echo @$shipping_info['shipping_last_name'] ?></p>
                                                <p><?php echo @$shipping_info['shipping_company'] ?></p>
                                                <p><?php echo @$shipping_info['shipping_address_1'] ?></p>
                                                <p><?php echo @$shipping_info['shipping_address_2'] ?></p>
                                                <p><?php echo @$shipping_info['shipping_city'] ?></p>
                                                <p><?php echo @$shipping_info['shipping_state'] ?></p>
                                                <p><?php echo @$shipping_info['shipping_postcode'] ?></p>
                                                <p><?php echo @$shipping_info['shipping_email'] ?></p>
                                                <?php
                                            }
                                        ?>
                                </address>
                            </div>
                        </div>
                    </div>


                    <style>
                       .woocommerce-Address-title a {
                           font-size: 18px;
                           font-weight: 900;
                           margin-bottom: 1em;
                           display: block;
                       }
                       .woocommerce-Address-title a:hover {
                           color: var(--red);
                       }
                    </style>

                </div>

            </div>

</div>
		</div>
        </div>

    





<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


