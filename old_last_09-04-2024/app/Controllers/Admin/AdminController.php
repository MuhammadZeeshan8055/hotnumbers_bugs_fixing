<?php

namespace App\Controllers\Admin;

use App\Libraries\Pass_hash_lib;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Models\MasterModel;
use PHPMailer\PHPMailer\PHPMailer;
use App\Libraries\Ckfinder;

class AdminController extends BaseController
{

    protected $master;
    protected $table = 'tbl_users';
    private $data;
    private $viewdata;

    public function __construct()
    {
        $this->master = new MasterModel();
        $this->data['content'] = "";
    }

    public function index()
    {
        return view("admin/index", $this->data);
    }
    public function login()
    {
        $this->data['page'] = "";

        if(is_admin()) {
            return redirect()->to(site_url('admin/dashboard'));
        }
        $this->_render_page(ADMIN . '/login', $this->data);
    }

    public function authentication()
    {
        $user = model('UserModel');
        if ($user->process_login()) {
            $res['success'] = 1;
            $res['redirect_url'] = base_url(ADMIN . '/dashboard');
            notice_success('Login success');
        } else {
            $res['success'] = 0;
            $res['msg'] = "Authentication Failed";
        }
        echo json_encode($res);
        exit;
    }

    public function pass_req() {

        if ($data = $this->input->post()) {
            $row = $this->master->getRow($this->table, array('email' => $data['email']), true);
            if ($row['id'] != '') {
                $link = base_url(ADMIN) . "/reset_password/".$row['forget_code']."/".$row['id'];

                $bool = false;
                if ($bool) {
                    $response['msg']="Email sent to you given email address";
                    $response['error']="1";
                }
            } else {
                $response['msg']="Opps! something wrong";
                $response['error']="0";
            }
            echo json_encode($response);
        }
    }
    public function settings()
    {
        $user = model('UserModel');
        $productModel = model('ProductsModel');

        if ($post_data = $this->request->getPost()) {

            $curr_url = $post_data['current_url'];

            unset($post_data['current_url']);

            if(!empty($post_data['tax_rate'])) {
                $tax_arr = [];

                foreach($post_data['tax_rate']['country'] as $i=>$country) {
                    $state = $post_data['tax_rate']['state'][$i];
                    $postcode = $post_data['tax_rate']['postcode'][$i];
                    $city = $post_data['tax_rate']['city'][$i];
                    $amount = $post_data['tax_rate']['amount'][$i];
                    $type = $post_data['tax_rate']['type'][$i];
                    $tax_name = $post_data['tax_rate']['tax_name'][$i];
                    $tax_shipping = $post_data['tax_rate']['tax_shipping'][$i];

                    $tax_arr[] = [
                      'country' => $country,
                      'postcode' => $postcode,
                      'state' => $state,
                      'city' => $city,
                      'amount' => $amount,
                      'type' => $type,
                      'tax_name' => $tax_name,
                      'tax_shipping' => $tax_shipping,
                    ];
                }
                $post_data['tax_rate'] = $tax_arr;
            }else {
                $post_data['tax_rate'] = [];
            }

            if(empty($post_data['tax_on_shipping'])) {
                $post_data['tax_on_shipping'] = 0;
            }

            if(!empty($post_data['shippingmethods'])) {
                $ship_arr = [];
                foreach($post_data['shippingmethods']['name'] as $i=>$name) {
                    $value = $post_data['shippingmethods']['value'][$i];
                    $ship_arr[] = [
                        'name' => $name,
                        'value' => $value
                    ];
                }

                $post_data['shippingmethods'] = $ship_arr;
            }

            if(!empty($post_data['subscriptionForm'])) {

                foreach($post_data['subscriptionForm']['subscription-type']['name'] as $i=>$name) {
                    $value = $post_data['subscriptionForm']['subscription-type']['value'][$i];
                    $post_data['subscriptionForm']['subscription-type'][$value] = $name;
                }

                unset($post_data['subscriptionForm']['subscription-type']['name']);
                unset($post_data['subscriptionForm']['subscription-type']['value']);

                foreach($post_data['subscriptionForm']['duration']['name'] as $i=>$name) {
                    $value = $post_data['subscriptionForm']['duration']['value'][$i];
                    $post_data['subscriptionForm']['duration'][$value] = $name;
                }

                unset($post_data['subscriptionForm']['duration']['name']);
                unset($post_data['subscriptionForm']['duration']['value']);

            }


            if(!empty($post_data['shipment_rule'])) {
                $option_data = [];

                foreach($post_data['shipment_rule'] as $i=>$rules) {
                    foreach($rules['subject'] as $k=>$subject) {
                        if(!empty($rules['value'])) {
                            $condition = $rules['condition'][$k];
                            $value = $rules['value'][$k];
                            $option_data[] = [
                                'subject' => $subject,
                                'condition' => $condition,
                                'value' => $value,
                            ];
                        }

                    }
                    $post_data['shipment_rule'][$i]['option_data'] = $option_data;
                    unset($post_data['shipment_rule'][$i]['subject']);
                    unset($post_data['shipment_rule'][$i]['condition']);
                    unset($post_data['shipment_rule'][$i]['value']);
                }
            }

           // pr($post_data);

            if(!empty($post_data['subscription_changes'])) {
                if(empty($post_data['subscription_enabled'])) {
                    $post_data['subscription_enabled'] = 0;
                }
                unset($post_data['subscription_changes']);
            }

            if(!empty($post_data['tax_update'])) {
                $post_data['enable_tax_rates'] = $this->request->getPost('enable_tax_rates') ? 1 : 0;
                unset($post_data['tax_update']);
                $tax_classes = tax_rates();
                if(!empty($tax_classes)) {
                    $tax_classes_names = array_keys($tax_classes);
                    foreach($tax_classes_names as $tax_classes_name) {
                        if(empty($post_data[$tax_classes_name])) {
                            $this->master->delete_data('tbl_settings','title',$tax_classes_name);
                        }
                    }
                }
            }

            foreach($post_data as $key=>$value) {
                if(is_array($value)) {
                    $value = json_encode($value);
                }
                $this->master->insertOrUpdate('tbl_settings', ['title'=>$key,'value'=>$value], 'title', $key);
            }

            notice_success('Settings updated successfully');
            return redirect()->to($curr_url);
        }

        $this->data['shipping_methods'] = get_setting('shippingmethods',true);
        $this->data['currency_symbol'] = get_setting('currency_symbol');
        $this->data['payment_method'] = get_setting('payment_method');
        $this->data['tax_rates'] = get_setting('tax_rate');
        $this->data['enable_tax_rates'] = get_setting('enable_tax_rates');

        $this->data['user_roles'] = $user->get_roles();

        $this->data['db_users'] = $user->get_users("user.user_id,user.fname,user.lname,user.display_name,user.email",'any','any',false);
        $this->data['db_user_roles'] = shop_roles('','any');
        $this->data['db_products'] = $productModel->get_products('id,title');
        $this->data['db_product_categories'] = $productModel->get_shop_categories();

        $this->data['setting_website'] = get_setting('website');;

        $this->data['vatValue'] = (object) $this->master->getRow('tbl_settings', ['title' => 'vatValue']);
        //pr(   $this->data['shipping_methods']);

        $this->data['page'] = "setting";

        $this->data['content'] = ADMIN . "/settings/setting";
        _render_page('/' . ADMIN . "/index", $this->data);


    }

    public function profile()
    {
        if ($data = $this->request->getPost()) {

            $dbdata = [
              'fname'=>$data['fname'],
              'lname'=>$data['lname'],
              'display_name'=>$data['fname'].' '.$data['lname'],
              'email'=>$data['email'],
            ];

            if(!empty($data['password'])) {
                $hash = new Pass_hash_lib();
                $dbdata['password'] = $hash->HashPassword($hash);
            }

            $uid = is_logged_in();

            $this->master->insertData($this->table, $data, 'user_id', $uid);

            set_message('profile_message','Profile updated successfully');

            return redirect()->to(base_url(ADMIN.'/profile/'.$uid));
        }

        $this->data['profile_row'] = (object)$this->master->getRow($this->table, ['user_id' => is_logged_in()]);
        $this->data['page'] = "profile";

        $this->data['content'] = ADMIN . "/profile";
        _render_page('/' . ADMIN . "/index", $this->data);
    }

    public function logout() {
        $user = model('UserModel');
        $user->logout();
        notice_success('Session logged out successfully');
        return redirect()->to(base_url(ADMIN.'/login'));
    }


    public function pass_request() {

        if ($arr = $this->input->post()) {

            $row = $this->master->getRow($this->table, array('id' => $arr['id'],'forget_code'=>$arr['code']));

            if (($row->id) > 0) {
                $new_code = md5(rand(111111, 999999));
                $id = $this->master->save($this->table, array('forget_code' => $new_code, 'password' => $arr['pwd'],'password' => md5($arr['pwd'])), 'id', $row->id);
                $response['msg'] = "Successfully Reset..Please wait";
                $response['redirect_url'] = base_url(ADMIN.'/login');
                $response['error'] = 0;
                $response['success'] = 1;
            } else {
                $response['msg'] = "Oops! Something Wrong";
                $response['error'] = 1;
            }
            echo json_encode($response);exit;
        }
    }
    public function reset_password() {


        $code = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        if ($code != '' && $id != '') {
            $data['row'] = $this->master->getRow($this->table, array('id' => $id,'forget_code' => $code));
            if (($data['row']->id) > 0) {

                $this->data['page']= "rest_password";
                $this->data['code'] = $code;
                $this->data['id'] = $id;

                $this->_render_page(ADMIN.'/login',$this->data);

            } else {
                redirect('404');
            }
        } else {
            redirect('404');
        }
    }

    public function _render_page($view, $data = null, $returnhtml = false)
    {
        $view_html = \Config\Services::renderer();
        $this->viewdata = (empty($data)) ? $this->data : $data;
        $view_html = $view_html->setData($this->viewdata)->render($view);
        echo $view_html;

    }


    public function ckfinder() {
        $ckfinder = new Ckfinder();
        $ckfinder->index();
    }

    public function ckfinder_connector() {
        $ckfinder = new Ckfinder();
        $ckfinder->connector();
    }

}
