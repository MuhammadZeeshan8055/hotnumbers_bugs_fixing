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
                <div style="margin: 0 auto">
                    <?php echo get_message() ?>
                </div>
                <!-- form -->
                <form id="form" class="woocommerce-EditAccountForm edit-account validate" action="<?php echo base_url('account/edit-account') ?>" method="post">

                    <?php

                        if(is_logged_in()) {
                            $user = model('UserModel');

                            $get_user = $user->get_user();

                            $display_name=$get_user->display_name;
                        }

                    ?>

                    <div class="woocommerce-form-row">
                        <label for="account_display_name">Display Name&nbsp;<span class="required">*</span></label>
                        <input type="text" placeholder="Full Name" name="account_display_name" id="account_display_name" value="<?php echo old('account_display_name', $display_name) ?>"/>
                        <small><em>This will be how your name will be displayed in the account section and in reviews</em></small>
                        <?php echo error_message(@$form_error['account_display_name']) ?>
                    </div>

                    <div class="clear"></div>
                    <div class="woocommerce-form-row">
                        <label for="account_email">Email address&nbsp;<span class="required">*</span></label>
                        <input type="email" class="input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo old('account_email', $email) ?>">
                        <?php echo error_message(@$form_error['account_email']) ?>
                    </div>

                    <div class="flex_space pt-15"></div>

                    <!--- change password ---->
                    <fieldset>
                        <legend>Password change</legend>
                        <div class="woocommerce-form-row">
                            <label for="password_current">Current password (leave blank to leave unchanged)</label>
                            <input type="password" name="password_current" class="password_input" value="<?php echo old('password_current') ?>" id="password_current" autocomplete="off">
                            <?php echo error_message(@$form_error['password_current']) ?>
                        </div>
                        <div class="woocommerce-form-row">
                            <label for="password_1">New password (leave blank to leave unchanged)</label>
                            <input type="password" name="password_1" class="password_input" value="<?php echo old('password_1') ?>" id="password_1" autocomplete="off">
                            <?php echo error_message(@$form_error['password_1']) ?>
                            <div class="invalid-msg" style="display:none;"><p style="font-size: 14px;"><i class="lni lni-question-circle" style="position: relative; top: 2px"></i> Hint: The password should be at least twelve characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ & ).</p></div>
                        </div>
                        <div class="woocommerce-form-row">
                            <label for="password_2">Confirm new password</label>
                            <input type="password" name="password_2" class="password_input" value="<?php echo old('password_2') ?>" id="password_2" autocomplete="off">
                            <?php echo error_message(@$form_error['password_2']) ?>
                        </div>
                    </fieldset>

                    <?php echo csrf_field() ?>

                    <div class="flex_space mb-10"></div>
                    <div class="pt-15">
                        <button type="submit" class="woocommerce-Button button">Save changes</button>
                    </div>
                </form>
                <!-- form end -->
            </div>
        </div>
    </div>
</div>


<!------------footer ---------------------------------------->
<?php echo view('includes/footer'); ?>
<!--------------- footer end -------------------------------->


