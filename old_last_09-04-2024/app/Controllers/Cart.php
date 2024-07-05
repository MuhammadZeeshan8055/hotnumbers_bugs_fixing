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
        $master = model('MasterModel');
        $media = model('Media');
        $data['cart'] = $this->get_cart();

        $data['shipping_methods'] = $this->cart->get_shipping_methods();

        $session = session();
        $data['shipping_id'] = $session->get('cart_shipping_method');
        $data['media'] = $media;

        $data['UserModel'] = model('UserModel');

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

            $cart['subtotal'] = $subtotal;

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

            $data['ProductsModel'] = model('ProductsModel');
            $master = model('MasterModel');
            $data['shipping_methods'] = $this->cart->get_shipping_methods();

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

                'order_comments'=>''
            ];

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
            }

            $data['form_data'] = $form_data;
            $data['page'] = 'checkout';

            if(empty($data['cart']['product_total'])) {
                return redirect()->to(base_url('cart'));
            }

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

    public function order_complete() {

        $get_order_id = $this->request->getVar('id');

        if($get_order_id) {

            $data['order'] = $this->order->get_order_by_id($get_order_id);
            $data['order_id'] = $get_order_id;

            if(!empty($data['order'])) {
                return view('checkout/checkout_complete',$data);
            }else {
                return redirect()->to(base_url());
            }
        }else {
            return redirect()->to(base_url());
        }
    }



    public function cart_form_validate() {

    }

    public function cart_process() {

        $checkout = model('CheckoutModel');

        if($this->request->getPost('payment_method') === "braintree" && env('braintree.enable')) {
            $process = $checkout->process_braintree();
            echo json_encode($process);
        }
        if($this->request->getPost('payment_method') === "squareup" && env('squareup.enable')) {
            $process = $checkout->process_squareup();
            echo json_encode($process);
        }
        if($this->request->getPost('payment_method') === "direct" && env('directcheckout.enable')) {
            $process = $checkout->process_direct();
            echo json_encode($process);
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

    public function capture_paypal_order() {

    }

    public function subscription_plan_options_ajax() {

    }

}