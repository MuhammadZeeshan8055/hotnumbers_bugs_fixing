
<?php $session = session(); ?>

<?php echo view( 'includes/header');?>

    <style>
        .row>* {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: unset;
            padding-left: unset;
            margin-top: unset;
        }
    </style>




<div class="underbanner" style="background: url('<?php echo base_url('assets/images'); ?>/banner.jpg');"></div>

<!-- wrapper -->
<div class="wrapper">
        <!-- title -->
	<h1 class="pagetitle">My account</h1>
			<div class="container">
			<div class="woocommerce">

    <?php include "menu.php" ?>


    <!--- payment methods --->
    <div class="woocommerce-MyAccount-content">
        
            <div id="wcs_delete_token_warning" style="display: none;">
            <ul class="woocommerce-error" role="alert">
                <li></li>
            </ul>
        </div>
        <p class="woocommerce-Message woocommerce-Message--info woocommerce-info">No saved methods found.</p>
        <a class="button" href="">Add payment method</a>
    </div>
    </div>
            </div>
            </div>



<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


