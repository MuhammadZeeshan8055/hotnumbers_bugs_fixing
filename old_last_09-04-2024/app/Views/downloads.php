
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

<!-- menue user dashboard -->
<nav class="woocommerce-MyAccount-navigation">
	<ul>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard">
			<a href="<?php echo base_url('cuser_login') ?>">Dashboard</a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--orders">
			<a href="<?php echo base_url('orders') ?>">Orders</a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--subscriptions">
			<a href="<?php echo base_url('shop') ?>">Subscriptions</a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--downloads  is-active">
			<a href="<?php echo base_url('downloads') ?>">Downloads</a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-address">
			<a href="<?php echo base_url('edit_address') ?>">Address</a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--payment-methods">
		    <a href="<?php echo base_url('payment_methods') ?>">Payment methods</a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-account">
			<a href="<?php echo base_url('edit-account') ?>">Account details</a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--appointments">
			<a href="<?php echo base_url('appointments') ?>">Appointments</a>
		</li>
		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--customer-logout">
			<a href="<?php echo base_url('account/logout') ?>">Logout</a>
		</li>
	</ul>
</nav>




<!-- downlods --->
<div class="woocommerce-MyAccount-content">
	
	    <div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
		    <a class="woocommerce-Button button" href="https://hotnumberscoffee.co.uk/shop/">
			    Browse products		
            </a>No downloads available yet.	
        </div>

    </div>
</div>
		</div>
        </div>







<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->

