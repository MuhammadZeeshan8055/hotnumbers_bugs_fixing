<?php  echo view('admin/includes/header'); ?>

    <div class="login_page">

    
        <div class="col-md-12 login-body">
            <div class="flex-center login_leftbar" style="height: 100%;">
                <?php
                if(!isset($_GET['forgot_password'])) { ?>
                <form id="login_form" class="login_form" onsubmit="return false">
                    <div>
                        <br>
                        <div class="logo_admin">
                            <img src="<?php echo base_url('assets/images/logo.png') ?>" width="100">
                        </div>

                        <div>
                            <div class="loading flex-center">
                                <img src="<?php echo base_url('assets/images/loader-2.svg') ?>">
                            </div>

                            <br>
                            <br>

                            <div class="input_field">
                                <label>Email address</label>
                                <input type="text" name="username" required autocomplete="off" >
                            </div>
                            <div class="input_field">
                                <label>Password</label>
                                <input type="password" name="password"
                                       required autocomplete="off" >
                            </div>
                            <br>
                            <?php /* <div class="flex_space forgot_pwd">
                                <div class="remember">
                                    <input type="checkbox" name="remember" value="yes">
                                    <label>Remember me</label>
                                </div>
                                <div class="forget_pwd">
                                    <a href="?forgot_password">Forgot password</a>
                                </div>
                            </div>*/ ?>
                            <div class="btn-submit mt-30">
                                <button type="submit" name="">Log in</button>
                            </div>


                            <div class="alert_danger">Login Failed</div>

                            <div class="login_footer_text mt-20">
                                <p>Copyright ©  <?php echo date('Y') ?> Hotnumbers</p>
                            </div>
                        </div>
                    </div>
                </form>
                <?php }
                else {

                    ?>
                    <form id="forget_form" class="login_form" onsubmit="return false">
                        <div>
                            <div>
                                <div class="logo_admin">
                                    <img src="<?php echo base_url('assets/images/logo.png') ?>" width="100">
                                </div>

                                <br>

                                <h4>Forget Password</h4>

                                <div class="input_field mb-50">
                                    <label>Enter Email</label>
                                    <input type="email" name="email"
                                           required autocomplete="off">
                                </div>

                                <div class="forgot_pwd flex-end">

                                    <div class="forget_pwd back_to_form">
                                        <a href="<?php echo site_url() ?>captain"> Back</a>
                                    </div>
                                </div>
                                <div class="btn-submit mt-30">
                                    <button type="submit" name="">Submit</button>
                                </div>

                                <div class="alert_danger">Failed</div>

                                <div class="login_footer_text mt-20">
                                    <p>Copyright ©  <?php echo date('Y') ?> Hotnumbers</p>

                                </div>
                            </div>
                        </div>
                    </form>
                <?php
                } ?>

                <?php
                if ($page == 'rest_password') {
                    ?>
                    <style>
                        #login_form{
                            display: none;

                        }
                    </style>
                    <form id="restpwd_form" onsubmit="return false">
                        <input type="hidden" name="code" value="<?php echo $code ?>">
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <div>
                            <div class="logo_admin">
                                <img src="<?php echo base_url('assets/images/olympia-india-logo.svg') ?>" width="100%">
                            </div>
                            <div class="login_form">
                                <div class="loading flex-center"><img src="<?php echo base_url('assets/images/loader-2.svg') ?>"></div>
                                <div class="alert_danger">Failed</div>
                                <h4>Reset Password</h4>

                                <div class="input_field mb-50">
                                    <label>Enter New Password</label>
                                    <input type="password" name="pwd"
                                           required autocomplete="off" >
                                </div>

                                <div class="alert_danger">Failed</div>


                                <div class="btn-submit mt-30">
                                    <button type="submit" name="">Submit</button>

                                </div>
                                <div class="login_footer_text mt-50">
                                    <p>Copyright © <?php echo date('Y') ?> Hot Numbers</p>

                                </div>


                            </div>


                        </div>
                    </form>
                <?php } ?>

            </div>
        </div>

    </div>

<?php echo view('admin/includes/footer'); ?>

