<?php
namespace App\Controllers;

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
        if (!empty($this->request->getPost())) {
            $user = model('UserModel');
            if ($user->process_login()) {
                return redirect()->to(base_url('account/dashboard'));
            } else {
                set_message('message',"Email or Password is not correct",0,'message');

                return redirect()->to(base_url('login'));
            }
        }
        return view('account/login');
    }


    public function register_customer()
    {
        if (!empty($this->request->getPost())) {
            $data = $this->request->getPost();
            $random_num = getRandomString();
            $mail_code = getRandomString();
            $successMessage = "Success fully registered, Please check your email for verification";

            $fname = strstr($data['email'],'@',true);

            $mailModel = model('MailModel');
            $userModel = model('UserModel');

            if ($userModel->email_exists($data['email'])) {

                set_message('register_message',"A user with this email already exists",'','error');

                return redirect()->to(base_url('login'));
            }

            $user_id = $this->master->insertData($this->table, ['username'=>$data['email'],'email'=>$data['email'],'display_name'=>$fname,'password'=>md5($random_num),'status'=>2,'code'=>$mail_code,'role'=>'customer']);

            if ($user_id) {

                $mailbody = $mailModel->get_parsed_content("user_register",[
                    'display_name'=>$fname,
                    'username'=>$data['email'],
                    'password'=>$random_num,
                    'verification_link'=>'<a href="'.base_url('account/user-verification/'.$mail_code).'">'.base_url('account/user-verification/'.$mail_code).'</a>'
                ]);

                $mailModel->send_email($data['email'], $mailbody);

                set_message('register_message',$successMessage,'','text');

                return redirect()->to(base_url('login'));
            }
        }
    }

    public function reset_password()
    {
        if (!empty($this->request->getPost())) {
            $data = $this->request->getPost();

            $mail = new MailModel();
            $code = getRandomString(20);

            $user_data = $this->master->getRow($this->table, ['email' => $data['email']]);
            if (empty($user_data)) {
                set_message('resetsuccess',"Email not found",0,'message');
            } else {
                $this->master->insertData($this->table, ['code' => $code], 'email', $data['email']);

                $mailbody = $mail->get_parsed_content('password_reset',[
                    'reset_link' => '<a href="'.base_url('account/password-reset/'.$code).'">'.base_url('account/password-reset/'.$code).'</a>'
                ]);
                $mail->send_email($data['email'], $mailbody);

                set_message('resetsuccess',"Please check your email for password reset instructions",0,'message');
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
                set_message('resetsuccess',"Link has been expired",0,'message');
                return redirect()->to(base_url('login'));
            }
        }
    }

    public function password_renew() {
        $postData = $this->request->getPost();

        if(is_user($postData['email'])) {
            $u_row = $this->master->getRow($this->table, ['code' => $postData['code'],'email'=>$postData['email']]);
            if(!empty($u_row)) {
                $pass1 = $postData['password1'];
                $pass2 = $postData['password2'];

                if($pass1 !== $pass2) {
                    set_message('resetsuccess',"Password did not match",0,'message');
                    return redirect()->to($_SERVER['HTTP_REFERER']);
                }
                else {
                    $pass1_ = md5($pass1);
                    $this->master->insertData($this->table,['password'=>$pass1_],'email',$postData['email']);

                    set_message('message',"Password reset successful",0,'message');

                    $mail = new MailModel();

                    $display_name = $u_row['display_name'];

                    if(empty($display_name)) {
                        $display_name = $u_row['fname'].' '.$u_row['lname'];
                    }

                    $mailbody = $mail->get_parsed_content('password_change', [
                        'display_name'=>$display_name
                    ]);

                    $mail->send_email($postData['email'],$mailbody);

                    return redirect()->to(base_url('login'));
                }
            }else {
                set_message('resetsuccess',"Invalid code",0,'message');
                return redirect()->to($_SERVER['HTTP_REFERER']);
            }

        }else {
            set_message('resetsuccess',"Invalid email address",0,'message');
            return redirect()->to($_SERVER['HTTP_REFERER']);
        }

        pr($postData);
    }

    public function register_code_verification($code) {
        if($code) {
            $getcode = $this->master->getRow($this->table, ['code' => $code]);
            $userModel = model('UserModel');
            if($getcode) {
                $this->master->query('UPDATE '.$this->table.' SET status=1,code=NULL WHERE code="'.$code.'"');

                set_message('sessionmessage',"Profile verified successfully",0,'message');

                return redirect()->to(base_url('account/dashboard'));
            }else {
                set_message('sessionmessage',"Link has been expired",0,'message');
                $userModel->logout();
                return redirect()->to(base_url('login'));
            }
        }else {
            set_message('sessionmessage',"Link has been expired",0,'message');
            return redirect()->to(base_url('login'));
        }
    }


}
