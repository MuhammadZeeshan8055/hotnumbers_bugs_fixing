<?php echo view('includes/header'); ?>

<style>
    /*.row > * {*/
    /*    flex-shrink: 0;*/
    /*    width: 100%;*/
    /*    max-width: 100%;*/
    /*    padding-right: unset;*/
    /*    padding-left: unset;*/
    /*    margin-top: unset;*/
    /*}*/
    .flex-center{
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 99999;

    }
</style>


<!-- main body -->

<div class="underbanner"
     style="background: url('<?php echo base_url('./assets/images/coffee-club-subscription/banner.jpg') ?>');"></div>

<!-- wrapper --->
<div class="wrapper">
    <h1 class="pagetitle">My account</h1>
    <div class="container">
        <div class="woocommerce">

            <div class="u-columns col2-set" id="customer_login">


                <div class="flex-center">

                    <div class="u-column1 col-1">
                        <form method="POST" id="sendemail" action="<?php echo base_url('reset-password') ?>"
                              class="woocommerce-form woocommerce-form-register register">
                            <h2 class="login_title">Reset Password</h2>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="reg_email">Email address&nbsp;<span class="required">*</span></label>
                                <input type="email" class="--text input-text" name="email" id="email" autocomplete="email" value="<?php echo old('email') ?>" required/>
                            </p>

                            <?php echo get_message('resetsuccess', true) ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="woocommerce-FormRow form-row">
                                        <button type="submit" id="register" class="woocommerce-Button btn btn-primary" >Submit
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right dp-flex-end">
                                    <div class="woocommerce-privacy-policy-text" style="text-align: right">

                                        <a href="<?php echo base_url('login') ?>" style="color: #d62135">Login</a>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <!------------footer ---------------------------------------->
    <?php echo view('includes/footer'); ?>
    <!--------------- footer end -------------------------------->

