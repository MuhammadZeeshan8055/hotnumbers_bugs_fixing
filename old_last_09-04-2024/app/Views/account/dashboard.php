
<?php $session = session();?>

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

            <div class="woocommerce-MyAccount-content">

                <div class="col-12" style="margin: 0 auto">
                    <?php get_message() ?>
                </div>

                <p>
                    Hello <strong><?php if(!empty($user->display_name)){echo $user->display_name;}else{echo $user->fname.' '.$user->lname;} ?>
                    </strong> (not <strong><?php if(!empty($user->display_name)){echo $user->display_name;}else{echo $user->fname.' '.$user->lname;} ?></strong>?
                    <a class="logout-btn" href="<?php echo base_url('account/logout') ?>">Log out</a>)
                </p>

                <p>
                    From your account dashboard you can view your
                    <a href="<?php echo base_url('account/orders') ?>">recent orders</a>
                    , manage your <a href="<?php echo base_url('account/edit-address') ?>">shipping and billing addresses</a>
                    , and <a href="<?php echo base_url('account/edit-account') ?>">edit your password and account details</a>.
                </p>

            </div>
        </div>
    </div>
</div>





<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


