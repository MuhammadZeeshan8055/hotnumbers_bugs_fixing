<?php

namespace App\Controllers;

use App\Models\MailModel;
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
            notice_success('Billing address updated');
            return redirect()->back();
        }

        $this->data['user'] = $user = $this->model->get_user($user_id);

        $billing = $this->model->get_user_meta($user_id,implode(',',get_billing_fields()));

        $this->data['billing_address'] = !empty($billing) ? $billing : [];

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
            notice_success('Shipping address updated');
            return redirect()->to('account/edit-address');
        }

        $billing = $this->model->get_user_meta($user_id,implode(',',get_billing_fields()));

        $this->data['billing_address'] = !empty($billing) ? $billing : [];

        $this->data['shipping_address'] = $this->model->get_user_meta($user_id,implode(',',get_shipping_fields()));

        return view('account/edit_shipping',$this->data);
    }

    /////// login user account class function
    public function login_user()
    {
        $session = session();
        if ($session->has('user')) {
            return view('cuser_login');
        } else {
            return view('account/');
        }
    }

    /////// logout user account class function
    public function logout_user()
    {
        $user = model('UserModel');
        $user->logout();
        return redirect()->to('login');
    }

    ///// edit account
    public function edit_account()
    {
        $session = session();
        $user_id = $session->get('user')['id'];
        if (!empty($this->request->getPost())) {
            $data = $this->request->getPost();
            $email = $data['account_email'];

            if(!is_user($email)) {
                notice_success('Could not update profile','error');
                return redirect()->to(base_url("account/edit-account"));
            }

            if(!empty($data['account_display_name'])) {
                $this->model->update_data(['display_name'=>$data['account_display_name']]);
                notice_success('Profile Updated Successfully');
            }


            if(!empty($data['password_current'])) {
                $pass = md5($data['password_current']);
                $user_row = $this->master->getRow('tbl_users', ['user_id' => $user_id, 'password' => $pass]);
                $new_pass = $data['password_1'];
                $rpass = $data['password_2'];

                if (empty($user_row)) {
                    notice_success('Current password was incorrect', 'error');
                    return redirect()->to(base_url("account/edit-account"));
                } else if ($new_pass != $rpass) {
                    notice_success('Password did not match', 'error');
                    return redirect()->to(base_url("account/edit-account"));
                } else {
                    $result = $this->model->change_pass(md5($new_pass));
                    if ($result) {
                        $mail = new MailModel();
                        $mailbody = $mail->get_parsed_content('password_change', [
                            'display_name'=>$user_row['display_name']
                        ]);

                        $mail->send_email($user_row['email'],$mailbody);

                        notice_success('Password Updated Successfully');
                        return redirect()->to(base_url("account/edit-account"));
                    }
                }
            }
        }
        $data = $this->master->getRow('tbl_users', ['user_id' => $user_id]);
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

        return view('account/orders', $data);
    }

    public function view_order()
    {
        $uri = service('uri');
        $order_id = $uri->getSegment(4);
        $user_id = is_logged_in();

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

        return view('account/view_order', $data);
    }

    public function get_subscriptions()
    {
        $session = session();
        $session->get('user')['id'];
        $user_id = is_logged_in();
        $subscription = model('SubscriptionModel');

        $subscription->renew(3,1);

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
        // $orderdetails['order'] = $this->orders->get_order($order_id);
        //$orderdetails['subscription'] = $subscription->get_subscription($order_id);
        return view('account/view_order', $orderdetails);
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
        return view('account/payment_methods');
    }

    public function get_appointments()
    {
        return view('account/appointments');
    }

    public function wholesale_request() {
        $userModel = model('UserModel');
        $post = $this->request->getPost();
        if(!empty($post)) {
            set_message('contact_success','Thank you for requesting your wholesale account, our representative will contact you shortly');

            $url = base_url('become-wholesale-customer').'/#contact-form';

            $userModel->request_wholesale_customer([
                'account_name' => $post['account_name'],
                'coffee_usage' => $post['coffee_usage'],
                'full_name' => $post['your_name'],
                'company_name' => $post['company_name'],
                'phone_number' => $post['your_number'],
                'email_address' => $post['your_email'],
                'message' => $post['your_message']
            ]);

            return redirect()->to($url);
        }
    }


}
