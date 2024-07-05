
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

                <?php include "menu.php"?>



<!-- appointment details --->
    <div class="woocommerce-MyAccount-content">
	    	
        <div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
		    <a class="woocommerce-Button button" href="https://hotnumberscoffee.co.uk/shop/">Book</a>
		    No appointments scheduled yet.	
        </div>
    </div>
    </div>
		</div>
    </div>




        


<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


