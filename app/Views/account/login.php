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

        <div class="text-center"><?php echo get_message() ?></div>

        <div class="woocommerce pt-20">

            <div class="u-columns col2-set" id="customer_login">
                <div class="d-flex">
                    <div class="u-column1 col-1">
                        <!-- login form--->

                        <form class="woocommerce-form woocommerce-form-login login validate" onsubmit="process_login(this); return false;" method="post" autocomplete="off">

                            <h2 class="login_title">Login</h2>

                            <!-- username -->
                            <div class="form-row">
                                <label for="username">Email address&nbsp;<span class="required">*</span></label>
                                <input id="username" type="email" name="username" autocomplete="off" value="">
                            </div>

                            <!-- password -->
                            <div class="form-row">
                                <label for="password">Password&nbsp;<span class="required">*</span></label>
                                <input name="password" id="password" type="password" autocomplete="off" value="">
                            </div>

                            <div id="login-notice" class="notices-wrapper" style="display: none"></div>

                            <div class="form-row">
                                <div class="row row-fluid">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn" name="login" value="Log in">Log in</button>
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

                            <?php echo csrf_field() ?>

                            <div class="woocommerce-LostPassword lost_password">
                                <a href="<?php echo base_url('reset-password') ?>">Lost your password?</a>
                            </div>

                        </form>

                    </div>

                    <div class="u-column2 col-2">
                        <form method="POST" class="woocommerce-form woocommerce-form-login login validate" id="sendemail" onsubmit="process_register(this); return false;">
                            <h2 class="login_title">Register</h2>

                            <div class="woocommerce-form-row">
                                <label for="reg_email">Email address&nbsp;<span class="required">*</span></label>
                                <input type="email" name="email" id="email" data-error="Please enter a valid email address" autocomplete="email" required>
                            </div>

                            <div id="register-notice" class="notices-wrapper" style="display: none;margin-top: 0;"></div>

                            <div class="woocommerce-privacy-policy-text">
                                <p>Your personal data will be used to support your experience throughout this website, to
                                    manage access to your account, and for other purposes described in our <a href="<?php echo base_url('privacy-policy') ?>" class="woocommerce-privacy-policy-link" target="_blank">privacy policy</a>.</p>
                                <p>*If you require a Wholesale Account but do not have one <a href="<?php echo base_url('become-wholesale-customer') ?>" style="color: #d62135">please request an account</a>.</p>
                            </div>

                            <?php echo csrf_field() ?>

                            <div class="woocommerce-FormRow form-row">
                                <button type="submit" id="register" name="register" class="btn">Register</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const process_login = function(form) {
        form.classList.add('loading');
        $('#login-notice').slideUp();
        const url = '<?php echo site_url() ?>ajax/process_login';
        const username = form.username.value;
        const password = form.password.value;

        const data = new FormData();
        data.append('username',username);
        data.append('password',password);
        data.append('remember',form.rememberme.checked);

        fetchPostRequest(url, data).then(result=>{
            if(result.success) {
                if(result.token) {
                    setCookie('remember_token',result.token,999);
                }
                setTimeout(()=>{
                    location.href = '<?php echo site_url() ?>account/dashboard';
                },500);
            }
            $('#login-notice').html(result.message).slideDown();
            form.classList.remove('loading');
            form.login.disabled=false;
        }).finally(()=>{
            form.classList.remove('loading');
            form.login.disabled=false;
        });
    }

    const process_register = function(form) {
        form.classList.add('loading');
        $('#register-notice').slideUp();
        const url = '<?php echo site_url() ?>ajax/process_register';
        const email = form.email.value;

        const data = new FormData();
        data.append('email',email);

        fetchPostRequest(url, data).then(result=>{
            $('#register-notice').html(result.message).slideDown();
            form.classList.remove('loading');
        }).finally(()=>{
            form.classList.remove('loading');
            form.register.disabled=false;
        });
    }
</script>

<!------------footer ---------------------------------------->
<?php echo view('includes/footer'); ?>
<!--------------- footer end -------------------------------->

