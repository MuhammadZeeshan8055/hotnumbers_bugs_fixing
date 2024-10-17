<?php

namespace App\Models;
use App\Libraries\Pass_hash_lib;
use CodeIgniter\Config\Services;
use CodeIgniter\Model;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class UserModel extends Model {

    protected $table = 'tbl_users';
    protected $allowedFields = ['username', 'password'] ;
    public $user_id;
    public $master;

    public function __construct()
    {
        $this->user_id = NULL;
        helper('functions_helper');

        if(is_logged_in()) {
            $this->user_id = is_logged_in();
        }

        $this->master = model('MasterModel');
    }

    public function set_uid($uid=0) {
        if($uid) {
            $this->user_id = $uid;
        }
    }

    // fetch pass
    public function get_user($user_id=0, $fields = 'user.*', $viewpass=false)
    {
        $fields .= ',role.name AS role_name, role.role AS role_id';
        if(!$user_id) {
            $user_id = $this->user_id;
        }
        if($user_id) {
            $fetch_pass=$this->master->query("select $fields from tbl_users AS user LEFT JOIN tbl_user_roles AS role ON role.id=user.role where user.user_id=$user_id",false,true);
            if(!$viewpass) {
                unset($fetch_pass->password);
            }
            return $fetch_pass;
        }
    }

    public function get_user_by_email($email=0, $fields = '*', $viewpass=false)
    {
        if($email) {
            $fetch_pass=$this->master->query("select $fields from tbl_users where email='$email'",false,true);
            if(!$viewpass) {
                unset($fetch_pass->password);
            }
            return $fetch_pass;
        }
    }

    public function get_user_by_code($email, $code) {
        $getUser = $this->master->getRow($this->table, ['code'=>$code, 'email'=>$email],true);
        return $getUser;
    }

    public function get_users($fields = '*',$status=1, $role="customer",$include_meta = true, $search='') {

        $query = "SELECT $fields FROM `tbl_users` AS user JOIN tbl_user_roles AS role ON FIND_IN_SET(role.id,user.role) WHERE 1=1";

        if($role !== 'any') {
            $query .= " AND role.role='$role'";
        }

        if($status !== "any") {
            $query .= " AND user.status=$status";
        }

        if($search) {
            $query .= " AND (user.fname LIKE '%$search%' OR user.lname LIKE '%$search%' OR user.display_name LIKE '%$search%' OR user.email LIKE '%$search%')";
        }

        $get_users =  $this->master->query($query);


        if(!empty($get_users) && $include_meta) {
            foreach($get_users as $i=>$user) {
                $uid = $user->user_id;
                $metas = $this->master->query("SELECT * FROM tbl_user_meta WHERE user_id='$uid'");
                $meta_data = [];
                foreach($metas as $meta) {
                    $meta_data[$meta->meta_key] = $meta->meta_value;
                }
                $get_users[$i]->meta_data = $meta_data;
            }
        }

        return $get_users;
    }

    public function get_user_meta($user_id=0, $key='',$single=false) {
        if(!$user_id) {
            $user_id = $this->user_id;
        }
        $fields = '*';
        $meta_arr = [];
        if($user_id) {
            $sql = "select $fields from tbl_user_meta where user_id=$user_id";
            if($key) {
                $keys = explode(',',$key);
                $sql .= " AND (";
                foreach($keys as $k) {
                    $sql .= "meta_key='$k' OR ";
                }
                $sql = rtrim($sql," OR").')';
            }

            if($fields == '*') {
                $get_metas =  $this->master->query($sql);
                if(!empty($get_metas)) {
                    foreach($get_metas as $meta) {
                        $key = $meta->meta_key;
                        $value = $meta->meta_value;
                        $meta_arr[$key] = $value;
                    }
                }
            }else {
                $meta_arr = $this->master->query($sql,1,1);
            }

            if($single) {
                $meta_arr = !empty($meta_arr[$key]) ? $meta_arr[$key] : '';
            }
        }

        return $meta_arr;
    }

    public function delete_user_meta($key='',$user_id=0) {
        if(!$user_id) {
            $user_id = $this->user_id;
        }
        $sql = "DELETE FROM tbl_user_meta WHERE user_id=$user_id AND meta_key='$key'";
        $this->master->query($sql);
    }

    public function get_billing_address($user_id=0) {
        if(!$user_id) {
            $user_id = $this->user_id;
        }
        $output = [];
        $user_meta = $this->get_user_meta($user_id);
        if(!empty($user_meta)) {
            foreach($user_meta as $field=>$value) {
                if(strstr($field,'billing_')) {
                    $output[$field] = $value;
                }
            }
        }
        return $output;
    }

    public function get_shipping_address($user_id=0) {
        if(!$user_id) {
            $user_id = $this->user_id;
        }
        $output = [];
        $user_meta = $this->get_user_meta($user_id);
        if(!empty($user_meta)) {
            foreach($user_meta as $field=>$value) {
                if(strstr($field,'shipping_')) {
                    $output[$field] = $value;
                }
            }
        }
        return $output;
    }

    public function get_user_roles($user_id=0) {
        if(!$user_id) {
            $user_id = $this->user_id;
        }
        if($user_id) {
            $sql = "select user.user_id, role.name, role.role from tbl_users AS user JOIN tbl_user_roles AS role ON FIND_IN_SET(role.id,user.role) where user.user_id=$user_id GROUP BY role.id";

            $user_role = $this->master->query($sql);
            //  pr($user_role);
            $role_list = [];
            foreach($user_role AS $user) {
                $role_list[$user->role] = $user->name;
            }
            return $role_list;
        }else {
            return [CUSTOMER_ROLE_ID => 'customer'];
        }
    }

    public function get_role_meta($role_id=0) {
        $sql = "SELECT * FROM tbl_user_role_meta WHERE role_id='$role_id'";
        return $this->master->query($sql);
    }

    public function get_roles($where='') {
        return shop_roles($where);
    }

    // change pass
    public function change_pass($new_pass)
    {
        return $this->master->insertData('tbl_users', ['password'=>$new_pass], 'user_id', $this->user_id);
    }

    public function update_data($data=[], $uid=0) {
        $user_id = $uid > 0 ? $uid : $this->user_id;

        return $this->master->insertData('tbl_users', $data, 'user_id', $user_id);
    }

    public function update_meta($data=[], $uid=0) {
        $user_id = $uid > 0 ? $uid : $this->user_id;
        $dbdata = array_filter($data);
        if($user_id) {
            foreach($dbdata as $k=>$v) {
                $i = $this->master->insertOrUpdate('tbl_user_meta', ['meta_key' => $k,'meta_value'=>$v,'user_id'=>$user_id], '', ['user_id'=>$user_id,'meta_key' => $k]);
            }
        }
    }

    public function delete_meta($key='', $uid=0) {
        $user_id = $uid > 0 ? $uid : $this->user_id;
        $this->master->query("DELETE FROM tbl_user_meta WHERE meta_key='$key' AND user_id='$user_id'");
    }

    public function update_billing($data=[],$user_id=0) {
        if(!empty($data) && is_logged_in()) {

            $user_id = !empty($user_id) ? $user_id : $this->user_id;

            $dbdata = [
                'billing_first_name'=>$data['billing_first_name'],
                'billing_last_name'=>$data['billing_last_name'],
                'billing_company'=>$data['billing_company'],
                'billing_country'=>env("default_region"),
                'billing_address_1'=>$data['billing_address_1'],
                'billing_address_2'=>$data['billing_address_2'],
                'billing_city'=>$data['billing_city'],
                'billing_state'=>$data['billing_state'],
                'billing_postcode'=>$data['billing_postcode'],
                'billing_phone'=>$data['billing_phone'],
                'billing_email'=>$data['billing_email']
            ];

            $dbdata = array_filter($dbdata);

            if($user_id) {
                foreach($dbdata as $k=>$v) {
                    $i = $this->master->insertOrUpdate('tbl_user_meta', ['meta_key' => $k,'meta_value'=>$v,'user_id'=>$user_id], '', ['user_id'=>$user_id,'meta_key' => $k]);
                }
            }
        }
    }

    public function update_shipping($data=[],$user_id=0) {

        $user_id = !empty($user_id) ? $user_id : $this->user_id;

        $dbdata = [
            'shipping_first_name'=>$data['shipping_first_name'],
            'shipping_last_name'=>$data['shipping_last_name'],
            'shipping_company'=>$data['shipping_company'],
            'shipping_country'=>env("default_region"),
            'shipping_address_1'=>$data['shipping_address_1'],
            'shipping_address_2'=>$data['shipping_address_2'],
            'shipping_city'=>$data['shipping_city'],
            'shipping_state'=>$data['shipping_state'],
            'shipping_postcode'=>$data['shipping_postcode'],
            'shipping_phone'=>$data['shipping_phone'],
            'shipping_email'=>$data['shipping_email']
        ];

        $dbdata = array_filter($dbdata);

        if($user_id) {
            foreach($dbdata as $k=>$v) {
                $i = $this->master->insertOrUpdate('tbl_user_meta', ['meta_key' => $k,'meta_value'=>$v,'user_id'=>$user_id], '', ['user_id'=>$user_id,'meta_key' => $k]);
            }
        }
    }

    // register email
    public function register_email($email, $randompass) {

        $getUser = $this->get_user_by_email($email);

        $mail_code = getRandomString();

        $this->master->insertData($this->table, ['code'=>$mail_code],'email', $email);

        if($getUser) {
            $mail = model('MailModel');

            $mailbody = $mail->get_parsed_content('user_register', [
                'display_name' => $getUser->display_name,
                'username' => $getUser->username,
                'password' => $randompass,
                'verification_link'=>'<a href="'.base_url('account/user-verification/'.$mail_code).'">'.base_url('account/user-verification/'.$mail_code).'</a>'
            ]);

            $mail->send_email($email,$mailbody,[
                'mail_type' => 'user',
                'post_id' => $getUser->user_id
            ]);
        }
    }

    public function register_wholesale_email($email, $randompass, $maildata=[]) {

        $getUser = $this->get_user_by_email($email);

        $mail_code = getRandomString();

      //  $this->master->insertData($this->table, ['code'=>$mail_code],'email', $email);

        if($getUser) {
            $mail = model('MailModel');

            $data = array_merge([
                'display_name' => $getUser->display_name,
                'username' => $getUser->username,
                'password' => $randompass,
                'verification_link'=>'<a href="'.base_url('account/user-verification/'.$mail_code).'">'.base_url('account/user-verification/'.$mail_code).'</a>'
            ], $maildata);

            $mailbody = $mail->get_parsed_content('wholesale_account_request_approved', $data);

            $mail->send_email($email,$mailbody,[
                'mail_type' => 'user',
                'post_id' => $getUser->user_id
            ]);
        }
    }

    public function register_wholesaler($data) {

        $notification = model('NotificationModel');

        $name = str_replace(' ','_',$data['full_name']);
        $username = strtolower($name).rand();

        $fname = strstr($data['full_name'],' ');
        $lname = strstr($data['full_name'],' ',true);

        $hash = new Pass_hash_lib();

        $randomPass = getRandomString();

        $password = $hash->HashPassword($randomPass);

        $getRoles = $this->get_roles('WHERE role="wholesale_customer"');

        if(!empty($getRoles)) {
            $getRoles = $getRoles[0]->id;
        }

        $req_id = $data['req_id'];

        $user_data = [
            'username' => $data['email_address'],
            'fname' => $fname,
            'lname' => $lname,
            'display_name' => $data['full_name'],
            'slug' => $name,
            'email' => $data['email_address'],
            'password' => $password,
            'role' => $getRoles,
            'wholesale_discount_type' => 'off',
            'wholesale_discount'=>0,
            'status' => 1
        ];

        $login_uid = is_logged_in();

        $user_id = $this->master->insertData('tbl_users',$user_data);

        $check=record_exists_in_notifications('New Wholesale Registration#'.$user_id);

        if(empty($check)){
            $notification->create('New Wholesale Registration#'.$user_id, 'users/edit/'.$user_id,'New Wholesale Registration',$login_uid,$customerID);
        }

        $this->master->insertData('tbl_request_acc',['user_id'=>$user_id],'req_id',$req_id);

        $this->master->insertData('tbl_user_meta',['user_id'=>$user_id,'meta_key'=>'billing_email','meta_value'=>$data['email_address']]);
        $this->master->insertData('tbl_user_meta',['user_id'=>$user_id,'meta_key'=>'shipping_email','meta_value'=>$data['email_address']]);
        $this->master->insertData('tbl_user_meta',['user_id'=>$user_id,'meta_key'=>'wholesale_discount','meta_value'=>0]);
        $this->master->insertData('tbl_user_meta',['user_id'=>$user_id,'meta_key'=>'wholesale_discount_type','meta_value'=>'off']);

        $this->register_wholesale_email($data['email_address'], $randomPass, $data);

        return true;
    }

    public function wholesaler_update($id='', $data=[]) {
        if($id) {
            return $this->master->insertData('tbl_request_acc',$data,'req_id',$id);
        }
    }


    public function process_login() {
        $request = Services::request();
        $data = $request->getPost();
        $success = false;
        helper('text');

        if(!empty($data)) {
            $master = model('MasterModel');
            $hash = new Pass_hash_lib();
            $tbl = "tbl_users";
            $username = $data['username'];
            $password = $data['password'];

            $password = $hash->CheckPassword($password,$username);

            if($password) {
                $sql = "SELECT user.user_id,user.email,user.username,user.fname,user.lname,user.display_name,user.status,user.code,role.role FROM $tbl AS user JOIN tbl_user_roles AS role ON role.id=user.role WHERE (email='$username') AND role.role!='guest'";
                $user_row = $master->query($sql, false, true);

                if (!empty($user_row)) {
                    if(!empty($data['remember']) && $data['remember'] === "true") {
                        $token = random_string('alnum',100);
                        $this->master->insertData('tbl_user_tokens',[
                                'user_id'=>$user_row->user_id,
                                'token'=>$token,
                                'user_ip' => get_client_ip(),
                                'user_agent' => $_SERVER['HTTP_USER_AGENT']
                            ]
                        );
                    };

                    $user_sesstion['user'] = ['id' => $user_row->user_id, 'username' => $user_row->username];
                    $session = session();
                    $session->set($user_sesstion);

                    $cart = model('CartModel');
                    $cart->sync_usr_cart();
                    $this->master->insertData('tbl_users',['last_active'=>time()],'user_id',$user_row->user_id);
                    $success = $user_row;
                }
            }
        }
        return $success;
    }

    public function logout() {
        $session = session();
        $uid = !empty($session->get('user')['id']) ? $session->get('user')['id'] : 0;
        setcookie('remember_token', '', -1, '/');
        $session->remove('user');
        $this->master->delete_data('tbl_user_tokens','user_id',$uid);
    }

    public function add_log($user_id='',$code='',$message='',$user_ip='',$user_agent='') {
        if(!$user_ip) {
            $user_ip = get_client_ip();
        }
        if(!$user_agent) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
        }
        $this->master->insertData("tbl_user_logs",[
            'user_id'=>$user_id,
            'code'=>$code,
            'message'=>$message,
            'user_ip'=>$user_ip,
            'user_agent'=>$user_agent
        ]);
    }

    public function email_exists($email, $user_not_equal=0)
    {
        $arr = ['email' => $email];
        if($user_not_equal) {
            $arr['user_id !='] = $user_not_equal;
        }
        $user_data = $this->master->getRow($this->table, $arr,true,'email');
        if (!empty($user_data)) {
            return true;
        }
        return false;
    }


    public function getCustomerByEmail($email, $field='*') {
        $query = $this->master->query("SELECT $field FROM $this->table  WHERE email='$email'",1,1);
        if($query) {
            return $query;
        }
        return false;
    }

    public function postCodeLookup($str='') {
        $api_key = 'wxmGhUhfv0epyrPkm0AN2Q35555';
        $autocomplete_endpoint = "https://api.getAddress.io/suggest/$str?api-key=$api_key";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$autocomplete_endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, false);
        $headers = array( "Content-Type: application/json");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $suggestions = json_decode($response,true);

        return !empty($suggestions) ? $suggestions : '';
    }

    public function addressAutoComplete($str='') {
        $api_key = env('address_autocomplete_key');
        $autocomplete_endpoint = "https://api.getAddress.io/autocomplete/$str?api-key=$api_key";
        $address_res = file_get_contents($autocomplete_endpoint);
        $suggestions = json_decode($address_res,true);
        return !empty($suggestions) ? $suggestions['suggestions'] : '';
    }

    public function getAddressData($addr_id='') {
        $api_key = env('address_data_key');
        $addr_data = file_get_contents("https://api.getAddress.io/get/$addr_id?api-key=$api_key");
        return json_decode($addr_data,true);
    }

    public function request_wholesale_customer($input=[]) {
        $mail = model('MailModel');

        $arr = [
            'coffee_usage' => $input['coffee_usage'],
            'account_name' => $input['account_name'],
            'full_name' => $input['full_name'],
            'company_name' => $input['company_name'],
            'phone_number' => $input['phone_number'],
            'email_address' => $input['email_address'],
            'message' => $input['message']
        ];

        $web_settings = get_setting('website', true);

        $admin_mail = !empty($web_settings['admin_email']) ? $web_settings['admin_email'] : false;

        $mailbody = $mail->get_parsed_content('wholesale_account_request_admin',$arr);

        if($admin_mail) {
            $mail->send_email($admin_mail,$mailbody,[
                'mail_type' => 'wholesale_user_request'
            ]);
        }

        $mailbody = $mail->get_parsed_content('wholesale_account_request_customer',$arr);

        $mail->send_email($input['email_address'],$mailbody,[
            'mail_type' => 'wholesale_user_request'
        ]);

        return $this->master->insertData('tbl_request_acc',$arr);
    }

    public function wholesale_requests() {
        return $this->master->query("SELECT * FROM tbl_request_acc", 1);
    }

    public function delete_user($user_id) {
        $this->master->delete_data('tbl_user_meta','user_id',$user_id);
        $this->master->delete_data('tbl_users','user_id',$user_id);
    }

    public function getCards($status=1) {
        $uid = is_logged_in();
        $query = "SELECT * FROM tbl_card_meta WHERE user_id='$uid' ";
        if($status !== 'any') {
            $status = intval($status);
            $query .= " AND status=$status";
        }
        return $this->master->query($query,true);
    }
}

