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

                        <form method="POST" id="sendemail" autocomplete="off" action="<?php echo base_url('account/renew-password') ?>"
                              class="woocommerce-form woocommerce-form-register register">
                            <h2 class="login_title">Renew Password</h2>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="reg_email">Email address&nbsp;<span class="required">*</span></label>
                                <input type="email" class=" --text input-text"
                                       name="email" id="email" required/>
                            </p>

                            <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="reg_email">Your new password&nbsp;<span class="required">*</span></label>
                                <input type="password" class="password_input" name="password1" id="password1" required/>
                                <div class="invalid-msg" style="display:none;"><p>Hint: The password should be at least twelve characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ & ).</p></div>
                            </div>

                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="reg_email">Re-enter password&nbsp;<span class="required">*</span></label>
                                <input type="password"
                                       name="password2" class="password_input" id="password2" required/>
                            </p>

                            <?php echo get_message('resetsuccess',true) ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="woocommerce-FormRow form-row">
                                        <button type="submit" id="register" class="woocommerce-Button button" >Submit
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="woocommerce-privacy-policy-text" style="text-align: right">
                                        <br>
                                        <a href="<?php echo base_url('login') ?>" style="color: #d62135">Login</a></p>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="code" value="<?php echo $code ?>">


                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <!------------footer ---------------------------------------->
    <?php echo view('includes/footer'); ?>
    <!--------------- footer end -------------------------------->

