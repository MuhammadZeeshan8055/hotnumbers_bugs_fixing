<?php $session = session(); ?>

<?php echo view('includes/header'); ?>

<style>
    .row > * {
        flex-shrink: 0;
        width: 100%;
        max-width: 100%;
        padding-right: unset;
        padding-left: unset;
        margin-top: unset;
    }

    legend {
        float: unset;
        width: unset;
        font-size: 16px;
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
            <!--- edit account details---->
            <div class="woocommerce-MyAccount-content">
                <div class="col-12" style="margin: 0 auto">
                    <?php get_message() ?>
                </div>
                <!-- form -->
                <form class="woocommerce-EditAccountForm edit-account"
                      action="<?php echo base_url('account/edit-account') ?>" method="post">

                    <p class="woocommerce-form-row">
                        <label for="account_display_name">Display Name&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input"
                               placeholder="Full Name" name="account_display_name" id="account_display_name"
                               value="<?php echo $display_name ?>"/>
                        <span><em>This will be how your name will be displayed in the account section and in reviews</em></span>
                    </p>
                    <div class="clear"></div>
                    <p class="woocommerce-form-row">
                        <label for="account_email">Email address&nbsp;<span class="required">*</span></label>
                        <input type="email" class="woocommerce-Input woocommerce-Input--email input-text"
                               name="account_email" id="account_email" autocomplete="email"
                               value="<?php echo $email ?>" readonly>
                    </p>


                    <!--- change password ---->
                    <fieldset>
                        <legend>Password change</legend>
                        <p class="woocommerce-form-row">
                            <label for="password_current">Current password (leave blank to leave unchanged)</label>
                            <input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
                                   name="password_current" id="password_current" autocomplete="off">
                        </p>
                        <p class="woocommerce-form-row">
                            <label for="password_1">New password (leave blank to leave unchanged)</label>
                            <input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
                                   name="password_1" id="password_1" autocomplete="off">
                        </p>
                        <p class="woocommerce-form-row">
                            <label for="password_2">Confirm new password</label>
                            <input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
                                   name="password_2" id="password_2" autocomplete="off">
                        </p>
                    </fieldset>

                    <div class="clear"></div>
                    <p>
                        <!--                <input type="hidden" id="save-account-details-nonce" name="save-account-details-nonce" value="69e1fcb733" />-->
                        <!--                <input type="hidden" name="_wp_http_referer" value="/my-account/edit-account/" />		-->
                        <button type="submit" class="woocommerce-Button button">Save changes</button>
                        <!--                <input type="hidden" name="action" value="save_account_details" />-->
                    </p>
                </form>
                <!-- form end -->
            </div>
        </div>
    </div>
</div>


<!------------footer ---------------------------------------->
<?php echo view('includes/footer'); ?>
<!--------------- footer end -------------------------------->


