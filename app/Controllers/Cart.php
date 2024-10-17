<?php

namespace App\Controllers;

use FontLib\Table\Type\name;use namespacetest\model\Group;
use Braintree;

class cart extends BaseController
{
    private $cart;
    private $order;
    public function __construct()
    {
        $this->cart = model('CartModel');
        $this->order = model('OrderModel');
        helper('form');
    }

    public function index()
    {
        $data['ProductsModel'] = model('ProductsModel');
        $data['cartModel'] = model('CartModel');
        $media = model('Media');
        $data['cart'] = $cart = $this->get_cart();

        if(!empty($cart['products'])) {
            $data['shipping_methods'] = $this->cart->get_shipping_methods([
                'products' => $cart['products']
            ]);
        }

        $session = session();
        $data['shipping_id'] = $session->get('cart_shipping_method');
        $data['media'] = $media;

        $data['UserModel'] = model('UserModel');

        $data['curr_user'] = $data['UserModel']->get_user();

        if(isset($_GET['remove_discount'])) {
            $this->cart->removeCoupon();
            echo 1;
            exit;
        }

        $this->cart->sync_usr_cart();

        $data['page'] = 'cart';

        return view('checkout/cart',$data);
    }

    public function remove_item() {
        $item_id = $this->request->getPost('item_id');
        $cart_session = $this->cart->remove_item($item_id);

        return json_encode($cart_session);
    }

    public function get_cart() {
        return $this->cart->get_cart();
    }

    public function cart_destroy() {
        $destroy = $this->cart->cart_destroy();
        if(!is_ajax()) {
            return redirect()->to(base_url('cart'));
        }else {
            return $destroy;
        }
    }

    public function checkout_post() {
        $cart = $this->get_cart();

        $session = session();

        if($this->request->getPost('shipment_calculator_change')) {
            $data = $this->request->getPost();

            $addr_data = [
                'country' => $data['calc_country'],
                'state' => $data['calc_state'],
                'city' => $data['calc_city'],
                'postcode' => $data['calc_postcode']
            ];

            $tax_rates = get_tax_rate($addr_data);


            $subtotal = $cart['product_total'];
            $subtotal += $cart['shipping_cost'];

            if(get_setting('tax_on_shipping')) {
                $shipping_cost = $cart['shipping_cost'];
                if($tax_rates['type'] === "percent") {
                    $subtotal += $shipping_cost * ($tax_rates['amount'] / 100);
                }else {
                    $subtotal += $tax_rates['amount'];
                }
            }

            $cart['cart_total'] = $subtotal;

            $session->set('cart_session',$cart);

            $session->get('cart_session');

            notice_success('Tax rate is updated');

            return redirect()->back();
        }

        $shipping_index = $this->request->getPost('shipping_method');

        $session = session();
        $session->set('cart_shipping_method',$shipping_index);
        $shipping = $this->cart->shipping_method_by_idx($shipping_index);

        $cart['shipping_name'] = !empty($shipping['name']) ? $shipping['name'] : '';
        $cart['shipping_cost'] = !empty($shipping['amount']) ? $shipping['amount'] : 0;

        return redirect()->to(base_url('cart/checkout'));
    }

    public function checkout_form() {

        $session = session();

        $data['cart'] = $this->get_cart();

        if(!empty($data['cart'])) {
            $data['shipping_id'] = $session->get('cart_shipping_method');
            $data['shipping'] = $this->cart->shipping_method_by_idx($session->get('cart_shipping_method'));

            $cart = $this->get_cart();

            $data['ProductsModel'] = model('ProductsModel');
            $master = model('MasterModel');

            if(!empty($cart['products'])) {
                $data['shipping_methods'] = $this->cart->get_shipping_methods([
                    'products' => $cart['products']
                ]);
            }

            $form_data = [
                'billing_first_name'=>'',
                'billing_last_name'=>'',
                'billing_country'=>'',
                'country_to_state'=>'',
                'billing_address_1'=>'',
                'billing_address_2'=>'',
                'billing_city'=>'',
                'billing_state'=>'',
                'billing_postcode'=>'',
                'billing_phone'=>'',
                'billing_email'=>'',

                'shipping_country'=>'',
                'shipping_address_1'=>'',
                'shipping_address_2'=>'',
                'shipping_city'=>'',
                'shipping_state'=>'',
                'shipping_postcode'=>'',

                'order_comments'=>'',
                'purchase_order_number'=>''
            ];

            $data['invoice_checkout'] = '';

            if(is_logged_in()) {
                $user = model('UserModel');

                $get_user = $user->get_user();
                $fields = implode(',',array_merge(get_billing_fields(),get_shipping_fields()));
                $user_meta = $user->get_user_meta('',$fields);

                $form_data['billing_first_name'] = !empty($user_meta['billing_first_name']) ? $user_meta['billing_first_name'] : "";
                $form_data['billing_last_name'] = !empty($user_meta['billing_last_name']) ? $user_meta['billing_last_name'] : '';
                $form_data['billing_address_1'] = !empty($user_meta['billing_address_1']) ? $user_meta['billing_address_1'] : '';
                $form_data['billing_address_2'] = !empty($user_meta['billing_address_2']) ? $user_meta['billing_address_2'] : '';
                $form_data['billing_city'] = !empty($user_meta['billing_city']) ? $user_meta['billing_city'] : '';
                $form_data['billing_state'] = !empty($user_meta['billing_state']) ? $user_meta['billing_state'] : '';
                $form_data['billing_postcode'] = !empty($user_meta['billing_postcode']) ? $user_meta['billing_postcode'] :'';
                $form_data['billing_phone'] = !empty($user_meta['billing_phone']) ? $user_meta['billing_phone'] : '';
                $form_data['billing_email'] = !empty($user_meta['billing_email']) ? $user_meta['billing_email'] : $get_user->email;

                $form_data['shipping_first_name'] = !empty($user_meta['shipping_first_name']) ? $user_meta['shipping_first_name'] : '';
                $form_data['shipping_last_name'] = !empty($user_meta['shipping_last_name']) ? $user_meta['shipping_last_name'] : '';
                $form_data['shipping_address_1'] = !empty($user_meta['shipping_address_1']) ? $user_meta['shipping_address_1'] : '';
                $form_data['shipping_address_2'] = !empty($user_meta['shipping_address_2']) ? $user_meta['shipping_address_2'] : '';
                $form_data['shipping_city'] = !empty($user_meta['shipping_city']) ? $user_meta['shipping_city'] : '';
                $form_data['shipping_state'] = !empty($user_meta['shipping_state']) ? $user_meta['shipping_state'] : '';
                $form_data['shipping_postcode'] = !empty($user_meta['shipping_postcode']) ? $user_meta['shipping_postcode'] : '';

                if(is_wholesaler()) {
                    $pay_by_invoice = $user->get_user_meta($get_user->user_id,'payment_by_invoice', true);
                    $data['invoice_checkout'] = $pay_by_invoice;
                }
            }

            $data['form_data'] = $form_data;
            $data['page'] = 'checkout';

            if(!isset($data['cart']['product_total'])) {
                return redirect()->to(base_url('cart'));
            }

            $userModel = model('UserModel');

            $data['user_cards'] = $userModel->getCards();

            return view('checkout/checkout',$data);
        }else {
            return redirect()->to(base_url('cart'));
        }
    }

    public function create_order($post_data=[]) {
        if($this->request->getPost('checkout_place_order')) {
            $cart = model('CartModel');
            $postdata = array_merge($post_data,$this->request->getPost());
            $new_order_id = $cart->create_order($postdata);
            if($new_order_id) {
                return $new_order_id;
            }else {
                return 0;
            }
        }
        return 0;
    }

    // public function order_complete() {

    //     $get_order_id = $this->request->getVar('id');

    //     if($get_order_id) {

    //         $data['order'] = $this->order->get_order_by_transaction($get_order_id);

    //         if(!empty($data['order'])) {
    //             $data['order_id'] = $data['order']['order_id'];

    //             if(!empty($data['order'])) {
    //                 return view('checkout/checkout_complete',$data);
    //             }else {
    //                 return redirect()->to(base_url());
    //             }
    //         }else {
    //             return redirect()->to(base_url());
    //         }

    //     }else {
    //         return redirect()->to(base_url());
    //     }
    // }


    public function order_complete() {

        $get_transaction_id = $this->request->getVar('id');
    
        if($get_transaction_id) {
    
            // Fetch all orders with the same transaction ID
            $data['orders'] = $this->order->get_orders_by_transaction($get_transaction_id);
    
            if(!empty($data['orders'])) {
                return view('checkout/checkout_complete', $data);
            } else {
                return redirect()->to(base_url());
            }
    
        } else {
            return redirect()->to(base_url());
        }
    }
    

    public function cart_process() {

        $checkout = model('CheckoutModel');
        $cartModel = model('CartModel');
        $cart = $cartModel->get_cart();

        $is_ajax = !empty($this->request->getPost('is_ajax')) ? true : false;

        if(!is_logged_in() && ($cart['has_subscription'] || $cart['has_club_subscription'])) {
            echo json_encode(['success'=>0,'orderID'=>0,'message'=>[['detail'=>'Please login or sign up to purchase subscription']]]);
            exit;
        }

        if(!is_logged_in()) {
            $billing_email = $this->request->getPost('billing_email');
            $userModel = model('UserModel');
            $get_user = $userModel->getCustomerByEmail($billing_email,'email,role');
            if(!empty($get_user)) {
                $message = "An account with $billing_email exists in our record. Please <a href='".site_url('account')."' target='_blank'>login</a> to continue purchase";
                if($is_ajax) {
                    return json_encode(['success'=>0,'orderID'=>0,'message'=>[['detail'=>$message]]]);
                }else {
                    message_notice($message);
                    ?>
                    <script>location.reload()</script>
                    <?php
                }
                exit;
            }
        }

        if(!$cart['cart_total']) {
            $process = $checkout->process_direct();
            if($is_ajax) {
            echo json_encode($process);
            }else {
                if(!empty($process['success'])) {
                    return redirect(site_url().'cart/order-complete?id='.$process['orderID']);
                }else {
                    message_notice($process['message']);
                    ?>
                    <script>location.reload()</script>
                    <?php
                }
            }
            exit;
        }

        if($this->request->getPost('customer_user_card') === "invoice_card" && is_wholesaler()) {
            $process = $checkout->process_direct(['payment_method'=>'invoice']);
            if($is_ajax) {
                echo json_encode($process);
            }else {
                if(!empty($process['success'])) {
                    return redirect(site_url().'cart/order-complete?id='.$process['orderID']);
                }else {
                    message_notice($process['message']);
                    ?>
                    <script>location.reload()</script>
                    <?php
                }
            }
            exit;
        }

        if($this->request->getPost('payment_method') === "braintree" && env('braintree.enable')) {
            $process = $checkout->process_braintree();
            if($is_ajax) {
                echo json_encode($process);
            }else {
                if(!empty($process['success'])) {
                    return redirect(site_url().'cart/order-complete?id='.$process['orderID']);
                }else {
                    message_notice($process['message']);
                    ?>
                    <script>location.reload()</script>
                    <?php
                }
            }
            exit;
        }
        if($this->request->getPost('payment_method') === "squareup" && env('squareup.enable')) {
            $process = $checkout->process_squareup();
            if($is_ajax) {
                echo json_encode($process);
            }else {
                if(!empty($process['success'])) {
                    return redirect(site_url().'cart/order-complete?id='.$process['orderID']);
                }else {
                    message_notice($process['message']);
                    ?>
                    <script>location.reload()</script>
                    <?php
                }
            }
            exit;
        }

        if($this->request->getPost('payment_method') === "direct" && env('directcheckout.enable')) {
            $process = $checkout->process_direct();
            if($is_ajax) {
                echo json_encode($process);
            }else {
                if(!empty($process['success'])) {
                    return redirect(site_url().'cart/order-complete?id='.$process['orderID']);
                }else {

                }
            }
            exit;
        }
    }

    public function applyCouponCode()
    {
        $code = $this->request->getPost('code');

        if ($code) {
            $cart = model('CartModel');
            $applyCoupon = $cart->applyCouponCode($code);

            if (empty($applyCoupon['applied'])) {
                echo json_encode(['success' => false, 'message' => $applyCoupon['message']]);
            } else {
                echo json_encode(['success' => true, 'message' => 'Coupon applied successfully.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Coupon code is required.']);
        }
        exit;
    }

    public function switch_shipping_method() {
        $index = $this->request->getPost('shipping_method');
        $session = session();
        $session->set('cart_shipping_method', $index);
        return json_encode(['success'=>1]);
        exit;
    }

    public function capture_paypal_order() {

    }

    public function subscription_plan_options_ajax() {

    }

}