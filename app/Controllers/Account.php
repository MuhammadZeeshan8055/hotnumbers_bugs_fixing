<?php

namespace App\Controllers;

use App\Libraries\Pass_hash_lib;
use App\Models\MailModel;
use App\Models\SquareupModel;
use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\MasterModel;
use CodeIgniter\Controllers;


class Account extends BaseController
{
    protected $model;
    protected $mail;
    protected $orders;
    protected $master;
    public $data;

    public function __construct()
    {
        helper('functions');
        $this->model = new UserModel();
        $this->orders = new OrderModel();
        $this->master = new MasterModel();
    }

    public function dashboard()
    {
        $session = session();
        if(!empty($session->user)) {
            $session_user = $session->user;
            $user_id = $session_user['id'];
            $this->data['user'] = $this->model->get_user($user_id);
            return view('account/dashboard',$this->data);
        }
        else {
            return redirect()->to(base_url('account'));
        }
    }

    //fetch billing and shipping information
    public function edit_address()
    {
        $session = session();
        $session->get('user')['id'];
        $user_id = $session->get('user')['id'];
        $this->data['user'] = $user = $this->model->get_user($user_id);

        $billing = $this->model->get_user_meta($user_id,implode(',',get_billing_fields()));

        $shipping = $this->model->get_user_meta($user_id,implode(',',get_shipping_fields()));


        if(!empty($this->data['user'])) {
            $this->data['billing_info'] = !empty($billing) ? $billing : [];
            $this->data['shipping_info'] = !empty($shipping) ? $shipping : [];
            return view('account/edit_address', $this->data);
        }
    }

    // fetch billing address
    public function edit_billing()
    {
        $session = session();
        $user_id = $session->get('user')['id'];
        $userModel = model('UserModel');

        $post_data = $this->request->getPost();

        if(!empty($post_data)) {

            $validation =  \Config\Services::validation();

            $validation->setRules([
                'address_1' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'=>'Your address is required'
                    ]
                ],
                'city' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'=>'Your city name is required'
                    ]
                ],
                'postcode' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'=>'Your postcode is required'
                    ]
                ],
            ]);

            $validation->run($post_data);

            if (!empty($validation->getErrors())) {
                $errors = $validation->getErrors();
                session()->setFlashdata('form_errors', $errors);
                return redirect()->to(base_url('account/edit-address/billing'))->withInput();
            }

            $dbdata = [
                'billing_first_name'=>$post_data['first_name'],
                'billing_last_name'=>$post_data['last_name'],
                'billing_company'=>$post_data['company'],
                'billing_country'=>env('default_region'),
                'billing_address_1'=>$post_data['address_1'],
                'billing_address_2'=>$post_data['address_2'],
                'billing_city'=>$post_data['city'],
                'billing_state'=>$post_data['county'],
                'billing_postcode'=>$post_data['postcode'],
                'billing_phone'=>$post_data['phone'],
                'billing_email'=>$post_data['email']
            ];

            $userModel->update_billing($dbdata, $user_id);
            notice_success('Billing address is updated', 'success');
            return redirect()->to('account/edit-address');
        }

        $this->data['user'] = $user = $this->model->get_user($user_id);

        $billing = $this->model->get_user_meta($user_id,implode(',',get_billing_fields()));

        $this->data['billing_address'] = !empty($billing) ? $billing : [];

        $this->data['form_error'] = session()->get('form_errors');

        return view('account/edit_billing',$this->data);
    }

    // fetch shipping address
    public function edit_shipping()
    {
        $session = session();
        $user_id = $session->get('user')['id'];
        $userModel = model('UserModel');

        $post_data = $this->request->getPost();

        if(!empty($post_data)) {

            $validation =  \Config\Services::validation();

            $validation->setRules([
                'address_1' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'=>'Your address is required'
                    ]
                ],
                'city' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'=>'Your city name is required'
                    ]
                ],
                'postcode' => [
                    'rules' => 'required',
                    'errors' => [
                        'required'=>'Your postcode is required'
                    ]
                ],
            ]);

            $validation->run($post_data);

            if (!empty($validation->getErrors())) {
                $errors = $validation->getErrors();
                session()->setFlashdata('form_errors', $errors);
                return redirect()->to(base_url('account/edit-address/billing'))->withInput();
            }

            $dbdata = [
                'shipping_first_name'=>$post_data['first_name'],
                'shipping_last_name'=>$post_data['last_name'],
                'shipping_company'=>$post_data['company'],
                'shipping_country'=>env('default_region'),
                'shipping_address_1'=>$post_data['address_1'],
                'shipping_address_2'=>$post_data['address_2'],
                'shipping_city'=>$post_data['city'],
                'shipping_state'=>$post_data['county'],
                'shipping_postcode'=>$post_data['postcode'],
                'shipping_phone'=>$post_data['phone'],
                'shipping_email'=>$post_data['email']
            ];

            $userModel->update_shipping($dbdata, $user_id);
            notice_success('Shipping address is updated', 'success');

            return redirect()->to('account/edit-address');
        }

        $billing = $this->model->get_user_meta($user_id,implode(',',get_billing_fields()));

        $this->data['billing_address'] = !empty($billing) ? $billing : [];

        $this->data['form_error'] = session()->get('form_errors');

        $this->data['shipping_address'] = $this->model->get_user_meta($user_id,implode(',',get_shipping_fields()));

        return view('account/edit_shipping',$this->data);
    }

    // login user account class function
    public function login_user()
    {
        $session = session();
//        if ($session->has('user')) {
//            return view('cuser_login');
//        } else {
//            return view('account/');
//        }
    }

    // edit account
    public function edit_account()
    {
        $session = session();
        $user_id = $session->get('user')['id'];
        $hash = new Pass_hash_lib();
        $mail = new MailModel();

        if (!empty($this->request->getPost())) {

            $data = $this->request->getPost();

            $validation =  \Config\Services::validation();

            $data = array_filter($data);

            $validation->setRules([
                'account_email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'valid_email'=>'Please enter valid email address.'
                    ]
                ]
            ]);

            $errors_arr = [];

            $user = $this->model->get_user();

            if(!empty($data['account_email'])) {
                $exists = $this->model->email_exists($data['account_email'], $user->user_id);
                if($exists) {
                    $errors_arr['account_email'] = 'Email address already exists';
                }
            }

            if(!empty($data['password_current'])) {

                if(!$hash->CheckPassword($data['password_current'],$user->username)) {
                    $errors_arr['password_current'] = 'Current password is incorrect';
                }

                if (empty(@$data['password_1'])) {
                    $errors_arr['password_1'] = 'Please enter new password';
                }else if (empty(@$data['password_2'])) {
                    $errors_arr['password_2'] = 'Please confirm your password';
                }

                if (!empty($data['password_1']) && !is_password_strong(@$data['password_1'])) {
                    $errors_arr['password_1'] = 'Please enter a strong password';
                }
                elseif (@$data['password_1'] != @$data['password_2']) {
                    $errors_arr['password_2'] = 'Password not matched';
                }
            };

            $validation->run($data);

            if (!empty($validation->getErrors()) || !empty($errors_arr)) {
                $errors = $validation->getErrors();
                $errors = array_merge($errors, $errors_arr);
                session()->setFlashdata('form_error', $errors);
                return redirect()->to(base_url('account/edit-account#form'))->withInput();
            }else {

                $success_message = '';
                $is_updated_email = '';
                $is_updated_password = '';
                $mail_text = '';
                $mail_code = getRandomString();

                if(!empty($data['account_display_name'])) {
                    $update_data = [
                        'display_name'=>_text_input($data['account_display_name'])
                    ];

                    $success_message = '<p>Profile updated successfully.</p>';

                    if(_text_input($data['account_email']) != $user->email) {
                        //$update_data['code'] = $mail_code;
                        $update_data['email'] = $data['account_email'];

                        $mail_link = '<a href="'.base_url('account/user-verification/'.$mail_code).'">'.base_url('account/user-verification/'.$mail_code).'</a>';

                        $mail_text .= '<p>Please visit the following link to confirm your account ownership.</p>
                        <p>'.$mail_link.'</p>';

                        $success_message .= '<p>Email address has been updated.</p>';
                    }

                    $update_data = array_filter($update_data);

                    $this->model->update_data($update_data);
                }

                if(!empty($data['password_current']) && !empty(@$data['password_1']) && empty($errors)) {

                    $user_row = $this->master->getRow('tbl_users', ['user_id' => $user_id]);

                    $new_pass = @$data['password_1'];

                    $new_pass = $hash->HashPassword($new_pass);

                    $result = $this->model->change_pass($new_pass);
                    if ($result) {
                        $mail = new MailModel();

                        $mailbody = $mail->get_parsed_content('password_change', [
                            'display_name'=>$user_row['display_name']
                        ]);

                        $mail->send_email($user_row['email'],$mailbody);

                        $success_message .= "<p>Your password has been updated successfully</p>";
                    }
                }

                if(!empty($data['account_email'])) {

                    $mail_html = '
                    <p>
                    Hi,</p>
                    <p>We are writing to inform you about recent updates to your account.</p>
                    '.$mail_text.'
                    <br>
                    <p>If you did not make these changes, please contact our customer support immediately.</p>
                  
                    <p>Your account information:</p>
                    
                    <table width="100%" border="0" cellspacing="5" cellpadding="5" style="width: 550px">
                        <tr>
                            <th style="text-align: left">Display name</th>
                            <td style="text-align: left">'.$data['account_display_name'].'</td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Email address</th>
                            <td style="text-align: left">'.$data['account_email'].'</td>
                        </tr>
                        '.$is_updated_email.'
                        '.$is_updated_password.'
                    </table>
                    
                    <br>
                    
                    <p>Thank you for your attention.</p>
                    
                    <p>Best regards,</p>
                   
                    <p>Hot Numbers Roasters</p>
                    
                   <br>
              
                    <div style="margin-top: 15px; padding-bottom: 20px">
                    
                    </div>';

//                    $admin_mail = get_setting('website.admin_email');
//
//                    $mail->set_from($admin_mail);
//
//                    $mail->subject('Account Update - Hotnumbers');
//                    $mail->send_email($data['account_email'],$mail_html);
                }

                notice_success($success_message, 'message');
            }

            return redirect()->to(base_url('account/edit-account#form'));

        }

        $data = array_merge($this->master->getRow('tbl_users', ['user_id' => $user_id]), [
            'form_error' => session()->get('form_error')
        ]);

        $data['active_page'] = 'edit_account';

        return view('account/edit_account', $data);
    }

    public function orders()
    {
        $session = session();
        $session->get('user')['id'];
        $user_id = is_logged_in();

        $statuses = order_statuses();

        unset($statuses['failed'],$statuses['trashed']);

        $statuses = array_keys($statuses);

        $order_counts = [];

        $data['orderModel'] = $this->orders;

        foreach($statuses as $status) {
            $order_counts[$status] = $this->orders->customer_orders($user_id,"AND item.item_type='line_item' AND o.status='$status'", '*','order_date desc',['order_result'=>false])->getNumRows();
        }

        $data['curr_status'] = $curr_status = !empty($_GET['status']) ? $_GET['status'] : '';

        $data['order_counts'] = $order_counts;

        if(empty($curr_status) && !empty(array_keys(array_filter($order_counts))[0])) {
            $curr_status = array_keys(array_filter($order_counts))[0];
        }

        $data['list_orders'] = $this->orders->customer_orders($user_id,"AND item.item_type='line_item' AND o.status='$curr_status'");

        $data['productModel'] = model('ProductsModel');

        $data['active_page'] = 'orders';

        return view('account/orders', $data);
    }

    public function view_order()
    {
        $uri = service('uri');
        $order_id = $uri->getSegment(4);
        $user_id = is_logged_in();

        $data['active_page'] = 'orders';

        $subscriptionModel = model('SubscriptionModel');

        if(!empty($this->request->getGet('action'))) {
            switch ($this->request->getGet('action')) {
                case 'pause':
                    $subscriptionModel->suspend($order_id,$user_id);
                    break;
                case 'cancel':
                    $subscriptionModel->cancel($order_id,$user_id);
                    break;
                case 'resume':
                    $subscriptionModel->resume($order_id,$user_id);
                    break;
            }
            notice_success('Action completed successfully');
            return redirect()->back();
        }

        $order = $this->orders->get_orders("AND o.customer_user='$user_id' AND o.order_id='$order_id'");

        $data['productModel'] = model('ProductsModel');

        $data['order'] = !empty($order) ? $order[0] : '';

        if($data['order']['order_type'] === "shop_subscription") {
            $data['active_page'] = 'orders';
        }

        return view('account/view_order', $data);
    }

    public function get_subscriptions()
    {
        $session = session();
        $session->get('user')['id'];
        $user_id = is_logged_in();
        $subscription = model('SubscriptionModel');

        $data['subscription_order'] = $this->orders->customer_subscriptions($user_id,"");

        $data['productModel'] = model('ProductsModel');
        $data['subscriptionModel'] = model('subscriptionModel');

        return view('account/subscriptions', $data);
    }

    public function view_subscription()
    {
        $uri = service('uri');
        $order_id = $uri->getSegment(4);
        $subscription = model('SubscriptionModel');
        $data['active_page'] = 'subscriptions';

        // $orderdetails['order'] = $this->orders->get_order($order_id);
        //$orderdetails['subscription'] = $subscription->get_subscription($order_id);
        return view('account/view_order', $data);
    }

    public function pay_order($order_id) {
        $CartModel = model('CartModel');
        $CartModel->order_remake($order_id, true);

        return redirect()->to(base_url().'/cart/checkout');
    }

    public function order_remake($order_id) {
        $CartModel = model('CartModel');
        $user_id = is_logged_in();

        $CartModel->order_remake($order_id,false,$user_id);

        return redirect()->to(base_url('cart'));
    }

    public function pay_subscription($order_id) {
        $cartModel = model('CartModel');
        $cartModel->paypal_charge_subscribe($order_id);
    }

    public function payment_methods()
    {
        $uid = is_logged_in();
        $userModel = model('UserModel');
        $user = $userModel->get_user($uid);
        $squareUpModel = new SquareupModel();

        $payment_methods = $userModel->getCards();

        $cards_info = [];
        if(!empty($payment_methods)) {
            foreach($payment_methods as $card) {
                $cards_info[] = $squareUpModel->getCard($card['card_id'], $uid);
            }
        }

        $cards_info = array_filter($cards_info);

        $display_name = display_name($user);

        $pay_by_invoice = $userModel->get_user_meta($uid,'payment_by_invoice', true);
        $pay_by_invoice = !empty($pay_by_invoice) ? $pay_by_invoice : 'true';

        return view('account/payment_methods', ['user' => $user, 'payment_methods'=>$cards_info, 'display_name' => $display_name,'pay_by_invoice'=>$pay_by_invoice]);
    }

    public function get_appointments()
    {
        return view('account/appointments');
    }

    public function wholesale_request() {
        $userModel = model('UserModel');
        $post = $this->request->getPost();
        if(!empty($post)) {
            $validation =  \Config\Services::validation();

            $validation->setRules([
                'your_name' => [
                    'rules' => 'required|alpha_space|min_length[3]',
                    'errors' => [
                        'alpha_space'=>'Please enter alphabetical characters only.'
                    ]
                ],
                'your_number' => [
                    'rules' => 'min_length[9]|max_length[12]',
                    'errors' => [
                        'min_length'=>'Please enter a valid phone number.',
                        'max_length'=>'Please enter a valid phone number.'
                    ]
                ],
                'your_email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'valid_email'=>'Please enter valid email address.'
                    ]
                ]
            ]);

            $email_exists = $userModel->email_exists($this->request->getPost('your_email'));

            $form_errs = [];

            if($email_exists) {
                $form_errs['your_email'] = 'A user with this email already exists';
            }

            $validation->run($post);

            if (!empty($validation->getErrors()) || !empty($form_errs)) {
                $errors = $validation->getErrors();
                $form_errs = array_merge($errors, $form_errs);
                session()->setFlashdata('form_errors', $form_errs);
                return redirect()->to(base_url('become-wholesale-customer#contact-form'))->withInput();
            }

            $url = base_url('become-wholesale-customer').'/#contact-form';

            $message = _textarea_input($this->request->getPost('your_message'));
            $account_name = _text_input($this->request->getPost('account_name'));
            $coffee_usage = _number_input($this->request->getPost('coffee_usage'));
            $your_name = _text_input($this->request->getPost('your_name'));
            $company_name = _text_input($this->request->getPost('company_name'));
            $your_number = _number_input($this->request->getPost('your_number'));
            $your_email = _email_input($this->request->getPost('your_email'));

            $data_arr = [
                'account_name' => $account_name,
                'coffee_usage' => $coffee_usage,
                'full_name' => $your_name,
                'company_name' => $company_name,
                'phone_number' => $your_number,
                'email_address' => $your_email,
                'message' => $message
            ];

            $userModel->request_wholesale_customer($data_arr);


            set_message('register_success','Thank you for requesting your wholesale account, our representative will contact you shortly',
            'message','message');

            return redirect()->to($url);
        }
    }

    public function add_payment_method() {
        $uid = is_logged_in();
        $userModel = model('UserModel');
        $squareUpModel = new SquareupModel();

        $holderName = $this->request->getPost('card_holder_name');

        if(!$holderName) {
            set_message('sessionmessage','Card holder name is required', 'message');
            return redirect()->back()->withInput();
        }

        $squareupCustomerID = $userModel->get_user_meta($uid,'squareup_customer_id',true);

        $idempotencyKey = uniqid();

        if(!$squareupCustomerID) {
            $createCustomer = $squareUpModel->createSquareupCustomer($idempotencyKey, $uid);
            if($createCustomer->isSuccess()) {
                $result = $createCustomer->getBody();
                $res = json_decode($result,true);
                $squareupCustomerID = $res['customer']['id'];
                $userModel->update_meta(['squareup_customer_id'=>$squareupCustomerID], $uid);
            }else {
                $errors = $squareUpModel->sq_errors($createCustomer->getErrors());
                echo json_encode($errors);
                exit;
            }
        }

        if($squareupCustomerID) {

            $Card = $squareUpModel->SquareupCard($squareupCustomerID, $idempotencyKey, $uid, $holderName);

            if($Card->isSuccess()) {
                $cardData = $Card->getBody();
                $res = json_decode($cardData,true);
                $cardID = $res['card']['id'];

                $cardBody = json_encode($res);

                $this->master->query("INSERT INTO tbl_card_meta SET user_id='$uid', card_id='$cardID', value='$cardBody'");

                echo json_encode([
                   'success' => 1,
                   'message' => 'Payment method added successfully'
                ]);

            }else {
                $errors = $squareUpModel->sq_errors($Card->getErrors());
                echo json_encode($errors);
                exit;
            }
        }

    }

    /*public function toggle_payby_invoice() {
        if($uid = is_wholesaler()) {
            $userModel = model('UserModel');
            $get_inv = $userModel->get_user_meta($uid,'payment_by_invoice',true);
            $userModel->update_meta(['payment_by_invoice'=>$get_inv == 'false' ? 'true':'false'], $uid);

            if($get_inv == 'false') {
                notice_success('Payment by invoice is enabled','message');
            }else {
                notice_success('Payment by invoice is disabled','message');
            }
        }
        return redirect()->to('account/payment-methods');
    }*/

    public function disable_payment_method($card_id='') {
        $uid = is_logged_in();
        $squareUpModel = new SquareupModel();
        $squareUpModel->disableCard($card_id, $uid);

        set_message('sessionmessage', 'Payment method deleted successfully', 'message');

        return redirect()->back();
    }

}
