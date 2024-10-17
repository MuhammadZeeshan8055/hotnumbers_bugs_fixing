<?php
namespace App\Controllers;

use App\Libraries\Pass_hash_lib;
use App\Models\MasterModel;
use App\Models\MailModel;
use App\Controllers\BaseController;
use CodeIgniter\Model;


class LoginController extends BaseController
{
    protected $table;
    protected $uri;
    public $master;

    public function __construct()
    {
        $this->master = model('MasterModel');
        $this->table = "tbl_users";
        $this->uri = current_url(true);
    }

    public function index()
    {
        if (is_logged_in()) {
            return redirect()->to(site_url('account/dashboard'));
        }

        return view('account/login');
    }

    public function process_login() {
        $output = ['success'=>0,'message'=>''];
        if (!empty($this->request->getPost())) {
            $userModel = model('UserModel');
            if ($user = $userModel->process_login()) {
                if($user->status != 1) {
                    $output = ['success'=>0,'message'=>'User is not active'];
                }
                /*else {
                    $display_name = display_name($user);
                    $mail_code = getRandomString();
                    $mail = new MailModel();
                    $admin_mail = get_setting('website.admin_email');
                    $mail->set_from($admin_mail);
                    $mail->subject('Account confirmation');
                    $mail_link = '<a href="'.base_url('account/user-verification/'.$mail_code).'">'.base_url('account/user-verification/'.$mail_code).'</a>';
                    $this->master->insertData('tbl_users',['code'=>$mail_code],'email',$user->email);
                    $mail_html = "<p>Dear $display_name,</p>
                        <p>Please visit the following link to complete your account verification.</p>
                        $mail_link
                        <br>
                        <p>Best regards,</p>
                        <p>Hot Numbers Roasters</p>
                    ";
                    $userModel->logout();
                    $mail->send_email($user->email,$mail_html);
                    $output = ['success'=>0,'message'=>'Please verify account ownership via link sent to email'];
                }*/
                else {
                    $output = ['success'=>1,'message'=>'Processing request, please wait...'];
                }
            } else {
                $output = ['success'=>0,'message'=>'Email or Password is not correct'];
            }
        }
        return json_encode($output);
    }

    public function register_customer()
    {
        $output = ['success'=>0,'message'=>''];
        if (!empty($this->request->getPost())) {
            $data = $this->request->getPost();
            $random_num = getRandomString();
            $mail_code = getRandomString();

            $user_roles = shop_roles(" WHERE role='customer'");
            $role_id = !empty($user_roles[0]) ? $user_roles[0]->id : 0;

            $hash = new Pass_hash_lib();

            $userPassword = $hash->HashPassword($random_num);

            $fname = strstr($data['email'],'@',true);

            $mailModel = model('MailModel');
            $userModel = model('UserModel');
            $notification = model('NotificationModel');

            $registered_data = $userModel->getCustomerByEmail($data['email']);

            $is_guest = false;
            if(!empty($registered_data['user_id'])) {
                $user_roles = $userModel->get_user_roles($registered_data['user_id']);
                $is_guest = !empty($user_roles['guest']);
            }

            if (!empty($registered_data) && !$is_guest) {
                $output = ['success'=>0,'message'=>'Email address is already registered.'];
            }else {
                $user_data = [
                    'username'=>$data['email'],
                    'email'=>$data['email'],
                    'display_name'=>$fname,
                    'password'=>$userPassword,
                    'status'=>1,
                    'is_guest'=>0,
                    'role'=>$role_id
                ];

                if(empty($registered_data)) {
                    $user_id = $this->master->insertData($this->table, $user_data);
                    $notification->create('New User#'.$user_id, 'users/edit/'.$user_id,'New User Registered','1','1',$customerID);
                }else {
                    $user_id = $this->master->insertData($this->table, $user_data,'user_id',$registered_data['user_id']);
                }

                if ($user_id) {
                    $this->master->insertData('tbl_user_meta',['user_id'=>$user_id,'meta_key'=>'billing_email','meta_value'=>$data['email']]);
                    $this->master->insertData('tbl_user_meta',['user_id'=>$user_id,'meta_key'=>'shipping_email','meta_value'=>$data['email']]);

                    $mailbody = $mailModel->get_parsed_content("user_register",[
                        'display_name'=>$fname,
                        'username'=>$data['email'],
                        'password'=>$random_num
                    ]);

                    $mailModel->send_email($data['email'], $mailbody);
                    $output = ['success'=>1,'message'=>'User registration successful. Please check your email for login credentials'];
                }
            }
        }
        echo json_encode($output);
    }

    public function reset_password()
    {
        if(is_logged_in()) {
            return redirect()->to(base_url('login'));
        }
        if (!empty($this->request->getPost())) {
            $data = $this->request->getPost();

            $mail = new MailModel();
            $code = getRandomString(20);

            $user_data = $this->master->getRow($this->table, ['email' => $data['email']]);
            if (empty($user_data)) {
                set_message('resetsuccess',"Email not found",'message');
            } else {
                $this->master->insertData($this->table, ['code' => $code], 'email', $data['email']);

                $mailbody = $mail->get_parsed_content('password_reset',[
                    'reset_link' => base_url('account/password-reset/'.$code)
                ]);
                $mail->send_email($data['email'], $mailbody);

                set_message('resetsuccess',"Please check your email for password reset instructions",'message');
            }
            return redirect()->to(base_url('reset-password'));
        }
        return view('account/reset_password');
    }

    public function register_password_reset($code) {
        if($code) {
            $u_row = $this->master->getRow($this->table, ['code' => $code]);
            if (!empty($u_row)) {

                return view('account/renew_password',['code'=>$code]);
            } else {
                set_message('resetsuccess',"Link has been expired",'message');
                return redirect()->to(base_url('login'));
            }
        }
    }

    public function password_renew() {
        $postData = $this->request->getPost();
        $userModel = model('UserModel');
        $email = $postData['email'];
        $code = $postData['code'];

        if($u_row = $userModel->get_user_by_code($email, $code)) {

            if(!empty($u_row)) {
                $pass1 = $postData['password1'];
                $pass2 = $postData['password2'];

                if($pass1 !== $pass2) {
                    set_message('resetsuccess',"Password did not match",'message');
                    return redirect()->to($_SERVER['HTTP_REFERER']);
                }
                else {
                    if(!is_password_strong($pass1)) {
                        set_message('resetsuccess',"Please enter a strong password",'message');
                        return redirect()->to($_SERVER['HTTP_REFERER'])->withInput();
                    }
                    $pass1_ = md5($pass1);
                    $this->master->insertData($this->table,['password'=>$pass1_],'email',$postData['email']);

                    $mail = new MailModel();
                    $display_name = $u_row['display_name'];
                    if(empty($display_name)) {
                        $display_name = $u_row['fname'].' '.$u_row['lname'];
                    }
                    $mailbody = $mail->get_parsed_content('password_change', [
                        'display_name'=>$display_name
                    ]);
                    $mail->send_email($postData['email'],$mailbody);

                    $this->master->insertData($this->table,['code'=>''],'email',$email);

                    set_message('sessionmessage',"Your password has been changed successfully",'message');

                    return redirect()->to(base_url('login'));
                }
            }
            else {
                set_message('resetsuccess',"Invalid code",'message');
                return redirect()->to($_SERVER['HTTP_REFERER'])->withInput();
            }

        }else {
            set_message('resetsuccess',"Invalid email address",'message');
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }

    }

    public function register_code_verification($code) {

        if($code) {
            $getcode = $this->master->getRow($this->table, ['code' => $code]);
            if($getcode) {
                $mailchange = '';

                $new_email = $getcode['email_change'];
                if(!empty($new_email)) {
                    $mailchange = ",email='$new_email',email_change=''";
                }
                $this->master->query('UPDATE '.$this->table.' SET status=1,code=NULL '.$mailchange.' WHERE code="'.$code.'"');
                set_message("sessionmessage", "Thank you for confirming your account ownership. You can proceed to login now.", 'message');
                return redirect()->to(base_url('login'));
            }else {
                set_message('sessionmessage',"Verification link has been expired",'message');
                $userModel = model('UserModel');
                $userModel->logout();
                return redirect()->to(base_url('login'));
            }
        }else {
            set_message('sessionmessage',"Verification link has been expired",'message');
            return redirect()->to(base_url('login'));
        }
    }


    public function logout_user()
    {
        $user = model('UserModel');
        $user->logout();
        return redirect()->to('login');
    }


}
