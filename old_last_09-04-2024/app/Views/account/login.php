<?php echo view('includes/header'); ?>

<style>
    .user_info{

        width: 80%;
        border: 1px solid #eba5ad;
        padding: 20px;
        margin: 0 auto;
        margin-bottom: 20px;
        background: #ccc3;
    }
    .user_info p{
        margin: 0;

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
                <div class="col-8 text-center" style="margin: 0 auto">
                    <?php //get_message() ?>
                </div>

                <div class="d-flex">
                    <div class="u-column1 col-1">
                        <?php
                        if(!empty($user_name)){?>
                            <div class="user_info">
                                <p>New Credentials for login</p>
                                <p>Username : <?php echo $user_name ?></p>
                                <p>Password : <?php echo $password?></p>
                            </div>
                        <?php } ?>
                        <!-- login form--->

                        <form action="<?php echo base_url('/login') ?>"
                              class="woocommerce-form woocommerce-form-login login" method="post">

                            <h2 class="login_title">Login</h2>

                            <?php echo get_message('message') ?>

                            <!-- username -->
                            <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="username">Username or email address&nbsp;<span class="required">*</span></label>
                                <input type="text" name="email" id="email"
                                       class="woocommerce-Input woocommerce-Input--text input-text" autocomplete="username"
                                       value="<?php echo !empty($user_name) ? $user_name : '' ?>"/>
                            </div>
                            <!-- password -->
                            <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="password">Password&nbsp;<span class="required">*</span></label>

                                <input name="password" id="password"
                                       class="woocommerce-Input woocommerce-Input--text input-text" type="password"
                                       autocomplete="current-password" value="<?php echo !empty($password) ? $password : '' ?>"/>
                            </div>


                            <div class="form-row">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="woocommerce-Button button" name="login" value="Log in">Log in
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <div class="input_field inline-checkbox">
                                            <label>
                                                <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever"/>
                                                <span>Remember me</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="woocommerce-LostPassword lost_password">
                                <a href="<?php echo base_url('reset-password') ?>">Lost your password?</a>
                            </div>


                        </form>

                    </div>

                    <div class="u-column2 col-2">

                        <form method="POST" id="sendemail" action="<?php echo base_url('register') ?>"
                              class="woocommerce-form woocommerce-form-register register">
                            <h2 class="login_title">Register</h2>

                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="reg_email">Email address&nbsp;<span class="required">*</span></label>
                                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text"
                                       name="email" id="email" autocomplete="email" required/>
                            </p>

                            <?php
                            $register_msg = get_message('register_message');
                            if(!empty($register_msg)) {
                                ?>
                                <div class="notices-wrapper"><?php echo $register_msg ?></div>
                                <?php
                            }
                            ?>

                            <div class="woocommerce-privacy-policy-text">
                                <p>Your personal data will be used to support your experience throughout this website, to
                                    manage access to your account, and for other purposes described in our <a
                                            href="<?php echo base_url('privacy-policy') ?>"
                                            class="woocommerce-privacy-policy-link" target="_blank">privacy policy</a>.</p>
                                <p>*If you require a Wholesale Account but do not have one <a
                                            href="<?php echo base_url('become-wholesale-customer') ?>"
                                            style="color: #d62135">please request an account</a>.</p>
                            </div>


                            <p class="woocommerce-FormRow form-row">
                                <button type="submit" id="register" class="woocommerce-Button button">Register</button>
                            </p>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<?php
//password generate function
$string = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+";
$randompass = substr(str_shuffle($string), 0, 12);
?>


<!------------footer ---------------------------------------->
<?php echo view('includes/footer'); ?>
<!--------------- footer end -------------------------------->

