<?php

namespace App\Controllers;

class Subscription extends BaseController
{
    public function index()
    {
        $ProductsModel = model('ProductsModel');

        $data['coffees'] = $ProductsModel->subscription_products('AND p.status="publish"');
        $data['media'] = model('Media');

        return view('subscription/coffee_club_subscription', $data);
    }

    public function coffee_variations_data() {
        $pid = $_POST['pid'];
        $ProductsModel = model('ProductsModel');
        $product = $ProductsModel->product_by_id($pid,'attributes');
        $variations = [];
        if(!empty($product->attributes)) {
            $attributes = json_decode($product->attributes,true);
            foreach($attributes as $attribute) {
                if (!empty($attribute['attribute_variation'])) {
                    $variations[] = $attribute;
                }
            }
        }
        echo json_encode($variations);
        exit;
    }

    public function checkout() {

        $postdata = $this->request->getPost();
        $ProductsModel = model('ProductsModel');
        $master = model('MasterModel');
        $CartModel = model('CartModel');

        if(is_logged_in() && !empty($postdata)) {

            $cart_data = [
                'attributes' => [
                    'subscription-type' => $postdata['subscription-type'],
                    'duration' => $postdata['duration']
                ],
                'product_id' => $postdata['subscription_coffee'],
                'quantity' => 1,
                'type'=>'subscription',
                'variations' => $postdata['variation']
            ];

            $CartModel->add_item($cart_data);

            return  redirect()->to(base_url('cart'));

            /*$product = $ProductsModel->product_by_id($postdata['subscription_coffee']);
            $postdata['product'] = $product;
            $product_variation = $ProductsModel->product_variation($postdata['subscription_coffee'],$postdata['variation']);
            $postdata['variation_data'] = $product_variation;

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
                if(!empty($get_user->billing_info)) {
                    $billing = json_decode($get_user->billing_info,true);
                    $form_data['billing_first_name'] = $billing['first_name'];
                    $form_data['billing_last_name'] = $billing['last_name'];
                    $form_data['billing_address_1'] = $billing['address_1'];
                    $form_data['billing_address_2'] = $billing['address_2'];
                    $form_data['billing_city'] = $billing['city'];
                    $form_data['billing_state'] = $billing['county'];
                    $form_data['billing_postcode'] = $billing['postcode'];
                    $form_data['billing_phone'] = $billing['phone'];
                    $form_data['billing_email'] = $billing['email'];
                }

                if(!empty($get_user->shipping_info)) {
                    $shipping = json_decode($get_user->billing_info,true);
                    $form_data['shipping_first_name'] = $shipping['first_name'];
                    $form_data['shipping_last_name'] = $shipping['last_name'];
                    $form_data['shipping_address_1'] = $shipping['address_1'];
                    $form_data['shipping_address_2'] = $shipping['address_2'];
                    $form_data['shipping_city'] = $shipping['city'];
                    $form_data['shipping_state'] = $shipping['county'];
                    $form_data['shipping_postcode'] = $shipping['postcode'];
                }
            }

            $postdata['form_data'] = $form_data;

            $postdata['shipping_methods'] = (object) $master->getRow('tbl_settings', ['title' => 'shippingmethods']);

            return view('subscription/checkout', $postdata);*/
        }else {
            return redirect()->to(base_url('coffee-club-subscription'));
        }
    }

//    public function submit() {
//
//        $cart = model('CartModel');
//        $postdata = $this->request->getPost();
//        $subscription = model('SubscriptionModel');
//
//        if(is_logged_in()) {
//            $create = $cart->create_order($postdata);
//            pr($create);
//            if($create) {
//                $subscription->start($create);
//            }
//        }
//
//        exit;
//
//        return redirect()->to(base_url('cart'));
//    }

}