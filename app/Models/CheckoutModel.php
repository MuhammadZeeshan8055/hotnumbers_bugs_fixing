<?php

namespace App\Controllers;

use App\Libraries\Pass_hash_lib;
use CodeIgniter\Config\Services;

class CheckoutModel extends BaseController {

    private $payment_method_nonce;
    private $device_data;
    private $billing_email;
    private $first_name;
    private $last_name;
    private $billing_phone;
    private $address1;
    private $address2;
    private $countrycode;
    private $city;
    private $postcode;

    private $shipping_address_1;
    private $shipping_address_2;
    private $shipping_country;
    private $shipping_city;
    private $shipping_state;
    private $shipping_postcode;

    private $payment_method;

    private $MasterModel;
    private $CartModel;
    private $UserModel;
    private $ProductsModel;
    private $OrderModel;
    private $SubscriptionModel;

    private $successMessage = 'Order placed successfully';

    protected $request;

    private $create_account;

    public function __construct()
    {
        $this->request = $request = Services::request();

        $this->payment_method_nonce = $request->getPost('payment_method_nonce');
        $this->device_data = $request->getPost('device_data');
        $this->billing_email = $request->getPost('billing_email');
        $this->first_name = $request->getPost('billing_first_name');
        $this->last_name = $request->getPost('billing_last_name');
        $this->billing_phone = $request->getPost('billing_phone');
        $this->address1 = $request->getPost('billing_address_1');
        $this->address2 = $request->getPost('billing_address_2');
        $this->countrycode = $request->getPost('billing_country');
        $this->city = $request->getPost('billing_city');
        $this->postcode = $request->getPost('billing_postcode');

        $this->shipping_address_1 = $request->getPost('shipping_address_1');
        $this->shipping_address_2 = $request->getPost('shipping_address_2');
        $this->shipping_country = $request->getPost('shipping_country');
        $this->shipping_city = $request->getPost('shipping_city');
        $this->shipping_state = $request->getPost('shipping_state');
        $this->shipping_postcode = $request->getPost('shipping_postcode');

        $this->payment_method = $request->getPost('payment_method');

        $this->CartModel = model('CartModel');
        $this->MasterModel = model('MasterModel');
        $this->ProductsModel = model('ProductsModel');
        $this->UserModel = model('UserModel');
        $this->OrderModel = model('OrderModel');
        $this->SubscriptionModel = model('SubscriptionModel');

        $this->create_account = $request->getPost('create_account');
    }

    private function validateForm($data=[]) {
        $validation_arr = [
            'billing_first_name' => 'First name is required',
            'billing_last_name' => 'Last name is required',
            'billing_address_1' => 'Address is required',
            'billing_city' => 'Billing city is required',
            'billing_postcode' => 'Billing postcode is required',
            'billing_phone' => 'Billing phone is required',
            'billing_email' => 'Billing email is required'
        ];
        $err_msgs = [];
        foreach($validation_arr as $key=>$msg) {
            if(empty($data[$key])) {
                $err_msgs[] = $msg;
            }
        }
        if(!empty($err_msgs)) {
            return json_encode(['success'=>0,'message'=>implode('<br>',$err_msgs)]);
        }
        return ['success'=>1];
    }

    private function processCustomer() {

        $username = strstr($this->billing_email,'@',true);
        $username = strtolower($username);
        $mailModel = model('MailModel');


        $usrdata = [
            'username'=>$this->billing_email,
            'fname'=>$this->first_name,
            'lname'=>$this->last_name,
            'email'=>$this->billing_email,
            'role'=>8,
            'is_guest'=>1
        ];

        $customer_id = is_logged_in();

        $billing_usr_meta = [
            'billing_first_name' => $this->first_name,
            'billing_last_name' => $this->last_name,
            'billing_address_1' => $this->address1,
            'billing_address_2' => $this->address2,
            'billing_city' => $this->city,
            'billing_postcode' => $this->postcode,
            'billing_country' => $this->countrycode,
            'billing_email' => $this->billing_email,
            'billing_phone' => $this->billing_phone
        ];

        if(!$customer_id) {
            $email_exists = $this->UserModel->getCustomerByEmail($this->billing_email, 'user_id');

            if (empty($email_exists['user_id'])) {
                if ($this->create_account) {
                    $usrdata['role'] = 2;
                    $usrdata['is_guest'] = 0;
                    $usrdata['display_name'] = $this->first_name . ' ' . $this->last_name;
                    $random_num = getRandomString();
                    $hash = new Pass_hash_lib();
                    $usrdata['password'] = $hash->HashPassword($random_num);
                    $mailbody = $mailModel->get_parsed_content("user_register", [
                        'display_name' => $this->first_name,
                        'username' => $this->billing_email,
                        'password' => $random_num
                    ]);
                    $mailModel->send_email($this->billing_email, $mailbody);
                }

                if (!$email_exists) {
                    //Create customer if not exists
                    $customer_id = $this->MasterModel->insertData('tbl_users', $usrdata);

                }
            } else {
                $customer_id = $email_exists['user_id'];
            }
        }

        if($customer_id) {
            $billing_usr_meta = array_filter($billing_usr_meta);
            $shipping_usr_meta = [
                'shipping_address_1' => $this->shipping_address_1,
                'shipping_address_2' => $this->shipping_address_2,
                'shipping_city' => $this->shipping_city,
                'shipping_postcode' => $this->shipping_postcode,
                'shipping_country' => $this->shipping_country,
                'shipping_state' => $this->shipping_state
            ];
            $shipping_usr_meta = array_filter($shipping_usr_meta);
            $this->UserModel->update_meta($billing_usr_meta, $customer_id);
            $this->UserModel->update_meta($shipping_usr_meta, $customer_id);
        }

        return $customer_id;
    }

    private function useCoupon($orderID) {
        $getCart = $this->CartModel->get_cart();
        if(!empty($getCart['coupon_code'])) {
            $coupon = $this->ProductsModel->getCouponByCode($getCart['coupon_code']);
            if(!empty($coupon['id']) && coupon_status($coupon) == "active") {
                $this->OrderModel->use_coupon($getCart['coupon_code']);
                $this->OrderModel->add_order_meta($orderID,'coupon_id',$coupon['id']);
                $this->OrderModel->add_order_meta($orderID,'coupon_code',$getCart['coupon_code']);
            }
        };
    }

    public function orderCompleteActions($orderID, $customerID) {

        if(get_setting('default_status_on_payment') === 'processing') {
            $this->OrderModel->processing_order_email($orderID);
        }
        if(get_setting('default_status_on_payment') === 'completed') {
            $this->OrderModel->order_complete_email($orderID);
        }

        $this->OrderModel->change_order_status($orderID, get_setting('default_status_on_payment'));

        $this->OrderModel->add_note($orderID,'Order placed successfully');

        $order_items = $this->OrderModel->order_items($orderID);

        $notification = model('NotificationModel');

        $productModel = model('ProductsModel');

        $this->useCoupon($orderID);

        if(!empty($order_items)) {

            foreach($order_items as $item) {
                if(!empty($item['item_meta'])) {
                    $item_meta = $item['item_meta'];

                    $product = $productModel->product_by_id($item_meta['product_id'],'title');

                    $notification->create('Order#'._order_number($orderID).' '.$product->title.' x '.$item_meta['quantity'], 'orders/view/'.$orderID,'shop_order',$customerID);

                    $this->ProductsModel->reduce_stock($item_meta['product_id'],$item_meta['quantity']);
                    $this->ProductsModel->add_sale($item_meta['product_id'],$item_meta['quantity']);

                    if(!empty($item_meta['subscription'])) {
                        $subscription = json_decode($item_meta['subscription'],true);
                        if(!empty($subscription['enable'])) {
//                            $sub_id = $this->SubscriptionModel->init([
//                                'order_id' => $orderID,
//                                'item_id' => $item['order_item_id']
//                            ]);
                            //Activate subscription
                            //$this->SubscriptionModel->start($sub_id, $customerID);
                        }
                    }
                }

            }
        }

        $this->CartModel->cart_destroy();
    }

    public function process_direct($data=[]) {
        $request = $this->request;

        $data = array_merge($request->getPost(), $data);

        $validation = $this->validateForm($request->getPost());

        if(empty($validation['success'])) {
            return ['success'=>0,'message'=>$validation['message'],'orderID'=>0];
        }

        helper('text');

        $this->processCustomer();

        $transaction_id = random_string('alnum',20);

        $orderID = $this->CartModel->create_order($data);

        if(!empty($data['customer_id'])) {
            $customer_id = $data['customer_id'];
        }else {
            $customer_id = 0;
            if(is_logged_in()) {
                $customer_id = is_logged_in();
            }
        }

        if(!empty($orderID)) {

            $this->OrderModel->set_transaction_id($orderID, $transaction_id);

            $this->orderCompleteActions($orderID, $customer_id);

            return ['success'=>1,'message'=>$this->successMessage,'orderID'=>$transaction_id];
        }
        else {
            return ['success'=>0,'message'=>'Could not process order','orderID'=>0];
        }

    }

    public function process_squareup() {
        $request = $this->request;
        $SquareupModel = model('SquareupModel');

        $validation = $this->validateForm($request->getPost());

        if(empty($validation['success'])) {
            return ['success'=>0,'message'=>$validation['message'],'orderID'=>0];
        }

        $customer_id = $this->processCustomer();

        return $SquareupModel->process_squareup($customer_id);
    }

    public function process_braintree() {

        $nonceFromTheClient = $this->payment_method_nonce;

        $deviceDataFromTheClient = $this->device_data;

        $request = $this->request;

        $validation = $this->validateForm($request->getPost());

        if(empty($validation['success'])) {
            return ['success'=>0,'message'=>$validation['message'],'orderID'=>0];
            exit;
        }

        $getCart = $this->CartModel->get_cart();



        $braintree_config = get_setting('payment_method',true);

        if(empty($braintree_config['braintree'])) {
            echo json_encode(['success'=>0,'message'=>'Payment method not configured']);
            exit;
        }


        if($braintree_config['braintree']['mode'] === "live") {
            $live = $braintree_config['braintree']['live'];
            $payment_config = [
                'environment' => 'live',
                'merchantId' => $live['merchant_id'],
                'publicKey' => $live['public_key'],
                'privateKey' => $live['private_key'],
            ];
        }else {
            $sandbox = $braintree_config['braintree']['sandbox'];
            $payment_config = [
                'environment' => 'sandbox',
                'merchantId' => $sandbox['merchant_id'],
                'publicKey' => $sandbox['public_key'],
                'privateKey' => $sandbox['private_key'],
            ];
        }

        $customer_id = $this->processCustomer();

        $getCustomer = (array)$this->UserModel->get_user($customer_id);

        $orderTotal = $getCart['cart_total'];

        $config = new Braintree\Configuration($payment_config);

        $post = $request->getPost();
        $post['order_paid'] = '';

        $orderID = $this->CartModel->create_order($request->getPost());

        $gateway = new Braintree\Gateway($config);

        $output = '';

        if(!empty($gateway)) {

            //Make Customer
            $custData = [
                'firstName'=> $this->first_name,
                'lastName'=> $this->last_name,
                'email'=> $this->billing_email,
                'phone'=> $this->billing_phone,
            ];

            if(empty($getCustomer['braintreeID'])) {
                $custData['paymentMethodNonce'] = $nonceFromTheClient;

                $makeCustomer = $gateway->customer()->create($custData);

                if($makeCustomer->success) {
                    $customerID = $makeCustomer->customer->id;
                    $paymentToken = $makeCustomer->customer->paymentMethods[0]->token;
                }else {
                    echo json_encode(['success'=>0,'message'=>$makeCustomer->message]);
                    exit;
                }
            }else {
                $paymentToken = $getCustomer['braintreePaymentToken'];
                $customerID = $getCustomer['braintreeID'];
                try {
                    $gateway->customer()->find($customerID);
                    $makeCustomer = $gateway->customer()->update($customerID,$custData);
                    if($makeCustomer->success) {
                        $paymentToken = $makeCustomer->customer->paymentMethods[0]->token;
                    }
                }
                catch(\Exception $e) {
                    $makeCustomer = $gateway->customer()->create($custData);

                    if(!$makeCustomer->success) {
                        echo json_encode(['success'=>0,'message'=>$makeCustomer->message]);
                        exit;
                    }else {
                        $paymentToken = $makeCustomer->customer->paymentMethods[0]->token;
                    }
                }

                if($makeCustomer->success) {
                    $customerID = $makeCustomer->customer->id;

                }else {
                    echo json_encode(['success'=>0,'message'=>$makeCustomer->message]);
                    exit;
                }
            }

            $this->UserModel->update_data(['braintreeID'=>$customerID,'braintreePaymentToken'=>$paymentToken],$customer_id);

            if(!empty($customerID) && $makeCustomer->success) {
                //Process payment
                $sale_arr = [
                    'amount'=>number_format($orderTotal,2),
                    'deviceData'=>$deviceDataFromTheClient,
                    'paymentMethodToken'=>$paymentToken,
                    'billing'=>[
                        'countryCodeAlpha2'=>$this->countrycode,
                        'streetAddress'=>$this->address1.' '.$this->address2,
                        'postalCode'=>$this->postcode,
                        'locality'=>$this->city
                    ],
                    'options'=>[
                        'submitForSettlement'=>true
                    ],
                ];
                if(!empty($getCart['discount_amount'])) {
                    $sale_arr['discountAmount'] = number_format($getCart['discount_amount'],2);
                }
                if(!empty($getCart['vat_price'])) {
                    $sale_arr['taxAmount'] = number_format($getCart['vat_price'],2);
                }

                $sale = $gateway->transaction()->sale($sale_arr);

                if($sale->success) {

                    $transaction_id = $sale->transaction->id;

                    $this->transaction_id = $transaction_id;

                    $this->OrderModel->add_order_meta($orderID,'transaction_id',$transaction_id);
                    $this->OrderModel->update_order_meta($orderID,['paid_date'=>date('Y-m-d h:i:s')]);

                    $paymentConfig = get_setting('payment_method',true);
                    if(!empty($paymentConfig['braintree'])) {
                        $braintree = $paymentConfig['braintree'];
                        $merchantID = $braintree['mode'] === 'live' ? $braintree['live']['merchant_id'] : $braintree['sandbox']['merchant_id'];
                        if($braintree['mode'] === "sandbox") {
                            $transaction_link = 'https://sandbox.braintreegateway.com/merchants/'.$merchantID.'/transactions/'.$transaction_id;
                        }else {
                            $transaction_link = 'https://braintreegateway.com/merchants/'.$merchantID.'/transactions/'.$transaction_id;
                        }

                        $this->OrderModel->add_order_meta($orderID,'transaction_link',$transaction_link);
                    }

                    if($orderID) {

                        if(!empty($getCart['coupon_code'])) {
                            $this->OrderModel->use_coupon($getCart['coupon_code']);
                            $coupon = $this->ProductsModel->getCouponByCode($getCart['coupon_code']);
                            if(!empty($coupon['id'])) {
                                $this->OrderModel->add_order_meta($orderID,'coupon_id',$coupon['id']);
                                $this->OrderModel->add_order_meta($orderID,'coupon_code',$getCart['coupon_code']);
                            }
                        };

                        $this->orderCompleteActions($orderID, $customerID);

                        $output = json_encode(['success'=>1,'message'=>$this->successMessage,'orderID'=>$orderID]);

                    }else {
                        $output = json_encode(['success'=>0,'message'=>'Order failed']);
                    }
                }else {
                    $output = json_encode(['success'=>0,'message'=>$sale->message]);
                }
            }


        }

        return $output;
    }
}