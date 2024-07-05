<?php

namespace App\Controllers;

class Subscription extends BaseController
{
    public function index()
    {
        $ProductsModel = model('ProductsModel');

        $data['coffees'] = $ProductsModel->subscription_products('AND p.status="publish"');
        $data['media'] = model('Media');

        $data['ProductsModel'] = $ProductsModel;

        $data['coffee_product'] = $ProductsModel->product_by_id(SUBSCRIPTION_PRODUCT_ID);

        //$variations = $ProductsModel->get_attributes(SUBSCRIPTION_PRODUCT_ID);

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

            $product = $ProductsModel->product_by_id(SUBSCRIPTION_PRODUCT_ID);

            $subFields = get_setting('subscriptionForm', true);

            $subTypes = array_keys($subFields['subscription-type']);
            $subValues = array_values($subFields['subscription-type']);

            $interval = $subTypes[$postdata['subscription-type']];
            $interval_name = $subValues[$postdata['subscription-type']];

            $pid = $product->id;

            $total_price = 0;

            foreach($postdata['attribute_bag-size'] as $size) {
                $variation = $ProductsModel->product_variation($pid, ['attribute_size'=>$size]);
                if(!empty($variation)) {
                    $total_price += !empty($variation['values']['sale_price']) ? $variation['values']['sale_price'] : $variation['values']['regular_price'];
                }
            }

            $subscription = [
                'enable' => 1,
                'interval' => $postdata['subscription-type'],
                'period' => 0,
                'expire' => 0,
                'price' => $total_price
            ];

            $variations = [
                'sizes' => array_values($postdata['attribute_bag-size']),
                'period' => $interval,
                'flavour'=> $postdata['attribute_flavour'],
                'grind'=> $postdata['attribute_grind'],
            ];

            $sale_price = $product->sale_price;
            $sold_individually = $product->sold_individually;
            $free_shipping = $product->free_shipping;

            $display_price_html = _price($total_price).' Every '.$interval_name;

            $cart_data = [
                'attributes' => [
                    'subscription-type' => $interval,
                    'duration' => 0
                ],
                'product_id' => $pid,
                'quantity' => 1,
                'type'=>'club_subscription',
                'subscription' => $subscription,
                'display_price_html' => $display_price_html,
                'tax' => '',
                'variations' => $variations,
                'variation' => [],
                'price' => $total_price,
                'item_price' => 0,
                'item_price_html' => $display_price_html,
                'sale_price' => $sale_price,
                'sold_individually' => $sold_individually,
                'free_shipping' => $free_shipping
            ];


            $CartModel->add_item($cart_data);
            return  redirect()->to(base_url('cart'));

        }else {
            return redirect()->to(base_url('coffee-club-subscription'));
        }
    }

    public function __checkout() {

        $postdata = $this->request->getPost();
        $ProductsModel = model('ProductsModel');
        $master = model('MasterModel');
        $CartModel = model('CartModel');

        $subscription_config = [
            'subscription_type' => ['1 week'=>'Weekly','2 week'=>'Fortnightly','1 month'=>'Monthly'],
            'bag_size' => ['10.70'=>'250g','17.90'=>'500g','31.90'=>'1kg'],
            'flavour'=>['Fresh & Fruity','Smooth & Classic','Roasters Choice'],
            'grind'=>['Whole Bean','French Press','Filter','Aeropress','Stove Top','Espresso']
        ];

        if(is_logged_in() && !empty($postdata)) {

            $sub_keys = array_keys($subscription_config['subscription_type']);

            $sub_type = $sub_keys[$postdata['subscription-type']];
            $sub_duration = 0;

            $total_price = 0;

            foreach($subscription_config['bag_size'] as $price=>$size) {
                foreach($postdata['attribute_bag-size'] as $post_size) {
                    if($post_size == $size) {
                        $total_price += $price;
                    }
                }
            }

            $variations = [
                'sizes' => array_values($subscription_config['bag_size']),
                'period' => $sub_type,
                'flavour'=> $postdata['attribute_flavour'],
                'grind'=> $postdata['attribute_grind'],
            ];

            $subscription = [
                'enable' => 1,
                'interval' => $sub_type,
                'period' => 0,
                'expire' => $sub_duration,
                'price' => $total_price
            ];

            $display_price_html = _price($total_price);

            $cart_data = [
                'attributes' => [
                    'subscription-type' => $sub_type,
                    'duration' => $sub_duration
                ],
                'quantity' => 1,
                'type'=>'club_subscription',
                'subscription' => $subscription,
                'display_price_html' => $display_price_html,
                'tax' => '',
                'variations' => $variations,
                'price' => $total_price
            ];

            $CartModel->add_item($cart_data);
            return  redirect()->to(base_url('cart'));

        }else {
            return redirect()->to(base_url('coffee-club-subscription'));
        }
    }

    public function _checkout() {

        $postdata = $this->request->getPost();
        $ProductsModel = model('ProductsModel');
        $master = model('MasterModel');
        $CartModel = model('CartModel');

        if(is_logged_in() && !empty($postdata)) {

            $pid = $postdata['subscription_coffee'];
            $variation = $postdata['variation'];

            $prod_variations = $ProductsModel->product_variation($pid, $variation);

            if(!empty($prod_variations)) {

                $sub_fields = get_setting('subscriptionForm', true);

                $sub_type_input = array_keys($sub_fields['subscription-type']);
//                $sub_duration_input = array_keys($sub_fields['duration']);

                $sub_type = $sub_type_input[$postdata['subscription-type']];
               // $sub_duration = !empty($postdata['duration']) ? $sub_duration_input[$postdata['duration']] : 0;
                $sub_duration = 0;

                $sub = explode(" ",$sub_type);

                $price = $ProductsModel->product_price($pid, $prod_variations);
                $price_ = $ProductsModel->product_reduced_price($price);

                $prod = $ProductsModel->product_by_id($pid);

                $subscription = [
                    'enable' => 1,
                    'interval' => $sub[0],
                    'period' => $sub[1],
                    'expire' => $sub_duration,
                    'price' => $price
                ];

                $sale_price = $prod->sale_price;
                $sold_individually = $prod->sold_individually;
                $free_shipping = $prod->free_shipping;

                $display_price_html = _price($price).' every '.$sub_type;
                if($sub_duration) {
                    $display_price_html .= ' for '.$sub_duration;
                }

                $cart_data = [
                    'attributes' => [
                        'subscription-type' => $sub_type,
                        'duration' => $sub_duration
                    ],
                    'product_id' => $pid,
                    'quantity' => 1,
                    'type'=>'subscription',
                    'subscription' => $subscription,
                    'display_price_html' => $display_price_html,
                    'tax' => '',
                    'variations' => $postdata['variation'],
                    'variation' => $prod_variations,
                    'price' => $price_,
                    'item_price' => $price,
                    'item_price_html' => $display_price_html,
                    'sale_price' => $sale_price,
                    'sold_individually' => $sold_individually,
                    'free_shipping' => $free_shipping
                ];


                $CartModel->add_item($cart_data);
                return  redirect()->to(base_url('cart'));
            }else {
                notice_success('Something went wrong, please try again','error');
                return redirect()->to(base_url('coffee-club-subscription'));
            }

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