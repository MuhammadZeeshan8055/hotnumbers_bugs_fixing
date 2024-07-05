<?php $this->load->view('admin/includes/header'); ?>
    <div class="row login_page">
        <div class="col-lg-5">
            <div class="flex-center login_leftbar" style="height: 100%;">
                <form id="login_form" onsubmit="return false">
                    <div>
                        <div class="logo_admin" >
                            <img src="<?php echo base_url('assets/images/olympia-india-logo.svg') ?>" width="100%">
                        </div>
                        <div class="login_form">
                            <div class="loading flex-center"><i class="icon-spin5" ></i></div>
                            <div class="alert_danger">Faild</div>
                            <h4>Login</h4>

                            <div class="input_field mb-50">
                                <label>Email address</label>
                                <input type="email" name="email"
                                       required autocomplete="off" value="admin@gmail.com">
                            </div>
                            <div class="input_field mb-50">
                                <label>Password</label>
                                <input type="password" name="password"
                                       required autocomplete="off" value="admin">
                            </div>
                            <div class="flex_space forgot_pwd">
                                <div class="remember">
                                    <input type="checkbox" name="remember" value="yes">
                                    <label>Remember me</label>
                                </div>
                                <div class="forget_pwd">
                                    <a href="javascript:void(0)"  onclick="$('#login_form').hide();$('#forget_form').show();">Forgot password</a>
                                </div>
                            </div>
                            <div class="btn-submit mt-30">
                                <button type="submit" name="">Log in</button>

                            </div>
                            <div class="login_footer_text mt-50">
                                <p>Copyright © 2021 Olympia Publishers</p>
                            </div>
                        </div>
                    </div>
                </form>
                <form id="forget_form" onsubmit="return false" style="display: none">
                    <div>
                        <div class="logo_admin" >
                            <img src="<?php echo base_url('assets/images/olympia-india-logo.svg') ?>" width="100%">
                        </div>
                        <div class="login_form">
                            <div class="loading flex-center"><i class="icon-spin5" ></i></div>
                            <div class="alert_danger">Faild</div>
                            <h4>Forget Password</h4>

                            <div class="input_field mb-50">
                                <label>Enter Email</label>
                                <input type="email" name="email"
                                       required autocomplete="off" value="admin@gmail.com">
                            </div>

                            <div class="forgot_pwd flex-end">

                                <div class="forget_pwd back_to_form">
                                    <a href="javascript:void(0)"  class="login_form" onclick="$('#login_form').show();$('#forget_form').hide();"> Back</a>
                                </div>
                            </div>
                            <div class="btn-submit mt-30">
                                <button type="submit" name="">Submit</button>

                            </div>
                            <div class="login_footer_text mt-50">
                                <p>Copyright © 2021 Olympia Publishers</p>

                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <div class="col-lg-7 login-body"></div>

    </div>

<?php   $this->load->view('admin/includes/footer'); ?>