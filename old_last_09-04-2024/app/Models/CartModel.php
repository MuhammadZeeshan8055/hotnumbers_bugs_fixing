<?php

namespace App\Controllers;
use _PHPStan_71572f9a1\Nette\Utils\DateTime;
use Cassandra\Date;
use PHPMailer\PHPMailer\Exception;
use Stripe;
use App\Libraries\Paypal_lib;

class CartModel extends BaseController {

    public function usr_cart_update() {
        $is_logged_in = is_logged_in();
        if(!empty($is_logged_in)) {
            $master = model('MasterModel');
            $cart_session = $this->get_cart();
            $user_id = is_logged_in()['id'];
            $cart_data = [
                'user_id' => $user_id,
                'cart_data' => json_encode($cart_session)
            ];
            if(empty($cart_session['products'])) {
                return $master->delete_data('tbl_carts','user_id',$user_id);
            }else {
                return $master->insertData('tbl_carts',$cart_data,'user_id',$user_id);
            }
        }
        return 0;
    }

    public function sync_usr_cart() {
        $logged_in = is_logged_in();
        if(!empty($logged_in)) {
            $session = session();
            $master = model('MasterModel');
            $cart = $this->get_cart();
            $user_id = $logged_in;
            $cart_session = $session->get('cart_session');

            $cart_session_json = json_encode($cart_session);
            $get_db_cart = $master->getRow("tbl_carts",['user_id'=>$user_id]);

            if(empty($cart_session['products'])) {
                $master->query("DELETE FROM tbl_carts WHERE user_id='$user_id'");
            }else {
                if(empty($get_db_cart)) {
                    $master->query("INSERT INTO tbl_carts SET cart_data='".addslashes($cart_session_json)."', user_id='$user_id'");
                }else {
                    $master->query("UPDATE tbl_carts SET cart_data='".addslashes($cart_session_json)."' WHERE user_id='$user_id'");
                }
            }

        }
    }

    public function remove_item($item_id) {
        $cart = session();
        $cart_session = !empty($cart->get('cart_session')) ? $cart->get('cart_session') : [];

        unset($cart_session['products'][$item_id]);

        $cart->set('cart_session', $cart_session);

        $this->sync_usr_cart();

        return $cart_session;
    }

    public function exists_in_cart($cart_data) {
        /*Return existing Index or False*/

        $product_id = $cart_data['product_id'];


        $exists = false;
        $cart = $this->get_cart();
        $product = model('ProductsModel');

        if(!empty($cart_data['variations'])) {
            $cart_variations = $cart_data['variations'];
            //Match by variation size
            if(!empty($cart['products'])) {
                foreach ($cart['products'] as $idx=>$product) {
                    $matched_variations = [];
                    foreach($cart['products'] as $productdata) {
                        foreach($productdata['variations'] as $pk=>$prod) {
                            if(!empty($cart_variations[$pk]) && $cart_variations[$pk] == $prod) {
                                $matched_variations[$pk] = $cart_variations[$pk];
                            }
                        }
                    }

                    if(count($matched_variations) == count($cart_variations)) {
                        $exists = $idx;
                    }
                }
            }
        }else {
            //Match by product ID
            if(!empty($cart['products'])) {
                foreach($cart['products'] as $i=>$product) {
                    if(!empty($product['product_id']) && $product['product_id']==$product_id) {
                        $exists = $i;
                        break;
                    }
                }
            }
        }

        return $exists;
    }

    public function add_item($cart_data=[]) {
        if(!empty($cart_data)) {

            $session = session();
            $cart = $session->get('cart_session');
            $productModel = model('ProductsModel');
            $exists = $this->exists_in_cart($cart_data);

            $errors = [];
            if(empty($cart)) {
                $cart = [];
            }

            $availability = $productModel->product_availability($cart_data['product_id']);

            if(!$availability) {
                $errors[] = 'Product is inactive';
            }

            if(!empty($errors)) {
                return ['status'=>'error','errors'=>$errors];
                exit;
            }

            if($exists === false) {
                $cart['products'][] = $cart_data;
            }else {
                //Increment quantity of existing product
                $cart['products'][$exists]['quantity']+=$cart_data['quantity'];
            }

            $session->set('cart_session',$cart);

            $this->sync_usr_cart();

            return $session->get('cart_session');
        }
    }

    public function order_remake($order_id,$replace_order=false,$user_id=0) {
        $orderModel = model('OrderModel');

        $query = '';
        if($user_id) {
            $query .= " AND o.customer_user='$user_id'";
        }

        $order = $orderModel->get_order_by_id($order_id, $query);

        if(empty($order)) {
            return false;
        }

        $order_items = $order['order_items'];

        $order_type = $order['order_type'];
        $type = str_replace('shop_','',$order_type);

        if($type == 'order') {
            $type = 'product';
        }

        if(!empty($order_items)) {

            foreach($order_items as $item) {

                $item_meta = $item['item_meta'];
                $item_attrs = [];
                $item_vars = [];

                $product_id = $item_meta['product_id'];
                $qty = $item_meta['qty'];

                unset($item_meta['item_id']);
                unset($item_meta['qty']);
                unset($item_meta['line_total']);
                unset($item_meta['line_subtotal']);
                unset($item_meta['product']);
                unset($item_meta['product_id']);

                foreach($item_meta as $key=>$val) {
                    if(strstr($key, 'attribute_')) {
                        $item_vars[$key] = $val;
                    }else {
                        $item_attrs[$key] = $val;
                    }
                    if($key === "subscription-type") {
                        $type = 'subscription';
                    }
                }

                $cart_arr = [
                    'attributes' => $item_attrs,
                    'variations' => $item_vars,
                    'product_id' => $product_id,
                    'quantity' => $qty,
                    'type' => $type
                ];

                if($replace_order) {
                    $cart_arr['replace_order'] = $order_id;
                }

                $add_item = $this->add_item($cart_arr);

                if(!empty($add_item['errors'])) {
                    notice_success(implode('<br>',$add_item['errors']),'error');
                    _redirect(base_url().'/account/orders/view_order/'.$order_id);
                }
            }
        }

    }

    public function update_item($product_id) {
        if(!empty($product_id)) {
            $cart = session();
            $cart_data = $this->get_cart();
            $cart_session['products'][$product_id] = $cart_data;
            $cart->set('cart_session',$cart_session);
            return $cart->get('cart_session');
        }
    }

    public function get_cart() {
        //$this->cart_destroy();
        $cart_subtotal = 0;
        $product_total = 0;
        $shipping_cost_item_count = 0;
        $free_shipping_items = [];
        $session = session();
        $cart = $session->get('cart_session');
        $ProductsModel = model('ProductsModel');
        $has_subscription = false;

        helper('filters');

        $subscription_plan = get_setting('subscription_plans', true);
        if(!empty($subscription_plan)) {
            $subscription_plan = $subscription_plan[0];
        }

        if(!empty($cart['products'])) {

            $productModel = model('ProductsModel');

            foreach($cart['products'] as $i=>$item) {

                if(!$productModel->in_stock($item['product_id'],1)) {
                    unset($cart['products'][$i]);
                    continue;
                }

                if($i === "") {
                    unset($cart['products'][$i]);
                    continue;
                }

                $status = is_admin() ? 'any':'publish';
                $product = $productModel->product_by_id($item['product_id'],'*',$status);
                if($product) {
                    $product_variations = [];
                    $variation_db = [];

                    if($product->sold_individually) {
                        $item['quantity'] = 1;
                    }

                    if(!empty($item['variations'])) {
                        foreach($item['variations'] as $k=>$value) {
                            if(strstr($k,'attribute_')) {
                                $key = str_replace('attribute_','',$k);
                                $key = str_replace('_',' ',$key);
                                $key = ucfirst($key);
                                $product_variations[$key] = $value;
                                $variation_db[$k] = $value;
                            }
                        }

                        $variation = $productModel->product_variation($item['product_id'],$variation_db);

                        if(!empty($variation)) {
                            $variation = array_merge($variation,$variation_db);
                        }else {
                            $variation = false;
                        }
                    }else {
                        $variation = false;
                    }

                    $errors = [];

                    if($variation) {
                        if(!empty($variation['stock_managed']) && $variation['stock_managed'] == 'yes') {
                            if(empty($variation['stock_quantity']) && $variation['stock_quantity'] !== 'instock') {
                                $errors[] = 'Out of stock';
                            }
                            elseif($item['quantity'] > $variation['stock_quantity']) {
                                $errors[] = 'Quantity in cart is more than available stock';
                            }
                        }
                    }else {
                        if(!empty($product->stock_managed) && $product->stock_managed !== 'no') {
                            $item['quantity'] = empty($item['quantity']) ? 0 : $item['quantity'];
                            if(empty($product->stock)) {
                                $errors[] = 'Out of stock';
                            }
                            elseif($item['quantity'] > $product->stock) {
                                $errors[] = 'Quantity in cart is more than available stock';
                            }
                        }
                    }

                    $price = $regular_price = floatval($product->price);
                    $sale_price = floatval($product->sale_price);
                    $prod_price = $price;

                    $tax_rate = get_tax_rate();

                    if(!empty($variation['values']['regular_price'])) {
                        $prod_price = $price = $regular_price = $variation['values']['regular_price'];
                        if(!empty($variation['values']['sale_price'])) {
                            $sale_price = $variation['values']['sale_price'];
                            $prod_price = $sale_price;
                        }
                    }

                    $price = $ProductsModel->product_reduced_price($price);

                    $item['total_price'] = $prod_price;

                    $tax_amount = 0;

                    if(get_setting('price_with_tax') === "inclusive") {
                        if(!empty($tax_rate['amount'])) {
                            if($tax_rate['type'] === "percent") {
                                $tax_amount = $price - ( $price / ( ( $tax_rate['amount'] / 100 ) + 1 ) );
                            }else {
                                $tax_amount = $price - $tax_rate['amount'];
                            }
                        }
                    }

                    if(get_setting('price_with_tax') === "exclusive") {
                        if(!empty($tax_rate['amount'])) {
                            if($tax_rate['type'] === "percent") {
                                $tax_amount = $price * ($tax_rate['amount'] / 100);
                            }else {
                                $tax_amount = $price - $tax_rate['amount'];
                            }
                            $price += $tax_amount;
                        }
                    }

                    $display_price = number_format($price,2);
                    $discount_html = '';
                    if($sale_price && ($sale_price !== $price)) {
                        $display_price = number_format($sale_price,2);
                        $discount_html = '<small class="strike-through">'._price($regular_price).'</small>';
                    }

                    $price_display = $discount_html._price($display_price);

                    $item_price_html = _price($regular_price);

                    if(!empty($subscription_plan['plan_enable']) && !empty($item['subscription']['enable'])) {
                        $has_subscription = true;
                        $item_sub_plan = $item['subscription'];
                        $plan_discount = percent_reduce($prod_price,$subscription_plan['discount_percent'],true);
                        $price = $plan_discount * $item['quantity'];
                        $cart['products'][$i]['subscription']['price'] = $plan_discount;
                        $day_name = $item_sub_plan['period'].($item_sub_plan['expire'] > 0 ? 's':'');
                        $inteval_text = 'every '.number_position($item_sub_plan['interval']);

                        $price_display = _price($plan_discount).' '.$inteval_text.' '.$item_sub_plan['period'].' for '.$item_sub_plan['expire'].' '.$day_name;

                        $item_price_html = _price($plan_discount).' '.$inteval_text.' '.$item_sub_plan['period'].' for '.$item_sub_plan['expire'].' '.$day_name;

                        $regular_price = $plan_discount;
                    }else {
                        $price = $price * $item['quantity'];
                        $price_display = _price($price);
                    }

                    $display_price_html = '<span class="display-price">'.$price_display.'</span>';

                    $cart['products'][$i]['display_price_html'] = $display_price_html;

                    $cart['products'][$i]['tax'] = number_format($tax_amount,2);
                    $cart['products'][$i]['variation'] = $variation;

                    //$cart_total += $prod_price * $item['quantity'];
                    $cart['products'][$i]['price'] = number_format($price,2);
                    $cart['products'][$i]['img'] = $product->img;
                    $cart['products'][$i]['item_price'] = $regular_price;
                    $cart['products'][$i]['item_price_html'] = $item_price_html;
                    $cart['products'][$i]['sale_price'] = number_format($sale_price,2);
                    $cart['products'][$i]['sold_individually'] = $product->sold_individually;
                    $cart['products'][$i]['free_shipping'] = $product->free_shipping;

                    $cart_subtotal += $cart['products'][$i]['price'];
                    $product_total += $cart['products'][$i]['price'];

                    if(!$product->free_shipping) {
                        $shipping_cost_item_count++;
                    }else {
                        $free_shipping_items[] = $item;
                    }
                }


            }
        }

        $shipping_cost = 0;
        $shipping_name = '';
        $shipping_id = 0;

        $vat_value = 0;

        if($product_total) {

            $shipping_vat = 0;

            $cart_tax_amt =  0;

            if(get_setting('enable_tax_rates')) {

                $db_shipping_methods = $this->get_shipping_methods();

                if(!empty($db_shipping_methods) && $shipping_cost_item_count) {
                    $ship_idx = 0;
                    if($session->get('cart_shipping_method')) {
                        $ship_idx = $session->get('cart_shipping_method');
                    }
                    $shipping_id = $ship_idx;

                    $shipping_cost = $db_shipping_methods[$ship_idx]['value'];
                    $shipping_name = $db_shipping_methods[$ship_idx]['name'];
                    $shipping_vat = !empty($db_shipping_methods[$ship_idx]['tax_amount']) ? $db_shipping_methods[$ship_idx]['tax_amount'] : 0;

                    $ship_cost = ($shipping_vat + $shipping_cost);

                    $cart_subtotal += $ship_cost;

                    if(!empty($free_shipping_items)) {
                        $free_ship_discount = $shipping_cost_item_count * $ship_cost;
                        $cart_subtotal -= $free_ship_discount;
                    }
                }


                $cart_tax = $this->get_cart_tax();

                if(!empty($cart_tax)) {
                    $cart_tax_amt = percent_reduce($cart_subtotal,$cart_tax['amount'],true);
                    $cart_subtotal += $cart_tax_amt;
                }
            }

            $cart['discount_amount'] = 0;

            $discount = 0;

            if(!empty($cart['coupon_code'])) {
                $coupon = $productModel->getCouponByCode($cart['coupon_code']);
                if($coupon) {
                    $sign = $coupon['type'] == 'percent' ? '%' : '';
                    $cart['discount_text'] = $coupon['amount'].$sign;

                    if($sign == '%') {
                        $discount = percent_reduce($cart_subtotal, $coupon['amount'], true);
                    }else {
                        $discount = $coupon['amount'];
                    }
                    $cart_subtotal -= $discount;
                }
            }
            else {
                $global_discount = get_setting('global_discount',true);
                if(!empty($global_discount['price'])) {
                    $sign = $global_discount['type'] == 'percent' ? '%' : '';
                    $cart['discount_text'] = $global_discount['price'].$sign;

                    if($sign == '%') {
                        $discount = percent_reduce($cart_subtotal, $global_discount['price'], true);
                    }else {
                        $discount = $global_discount['price'];
                    }
                    $cart_subtotal -= $discount;
                }

                $curr_user_role = current_user_role();
                $user_role_discounts = get_setting('user_discount',true);
                if($user_role_discounts) {
                    $role_discount = [];

                    foreach ($user_role_discounts as $_discount) {
                        if ($_discount['role_id'] == $curr_user_role['id']) {
                            $role_discount = $_discount;
                            break;
                        }
                    }

                    if(!empty($role_discount['role_discount'])) {
                        $sign = $role_discount['role_discount_type'] == 'percent' ? '%' : '';
                        $cart['discount_text'] = $role_discount['role_discount'].$sign;

                        if($sign == '%') {
                            $discount = percent_reduce($cart_subtotal, $role_discount['role_discount'], true);
                        }else {
                            $discount = $global_discount['role_discount'];
                        }

                        $cart_subtotal -= $discount;
                    }
                }
            }
            $cart['has_subscription'] = $has_subscription;
            $cart['shipping_free_products'] = $free_shipping_items;
            $cart['discount_amount'] = $discount;
            $cart['product_total'] = $product_total;
            $cart['shipping_cost'] = $shipping_cost;
            $cart['shipping_tax'] = $shipping_vat;
            $cart['shipping_name'] = $shipping_name;
            $cart['shipping_id'] = $shipping_id;
            $cart['shipping_cost_item_count'] = $shipping_cost_item_count;
            $cart['vat_price'] = $cart_tax_amt;
            $cart['total_tax'] = $cart_tax_amt+$shipping_vat;
            $cart['subtotal'] = $cart_subtotal;

            $debug = 0;

            $cart = filter_shipping_rules($cart);


            if($debug) {
                pr($cart);
            }


            return $cart;
        }
    }

    public function cart_destroy() {
        $cart = session();
        $cart->remove('cart_session');
        $cart->remove('cart_id');
        if(is_logged_in()) {
            $user_id = is_logged_in();
            $master = model('MasterModel');
            $master->delete_data('tbl_carts','user_id',$user_id);
        }
        return 1;
    }

    public function get_cart_tax() {
        $address_tax = [];
        if(get_setting('price_with_tax') === "exclusive") {
            $tax_class_address = session('tax_class_address');

            if(!empty($tax_class_address)) {
                $tax_addr = [];
                foreach($tax_class_address as $address) {
                    $tax_addr[$address[1]] = $address[0];
                }
                $tax_class = get_setting('tax_shipping_class');

                if($tax_class) {
                    $tax_rates = tax_rates();
                    if(!empty($tax_rates[$tax_class])) {
                        $address_tax = get_address_tax($tax_rates[$tax_class], [
                            'country' => $tax_addr['Country'],
                            'state' => $tax_addr['State'],
                            'city' => $tax_addr['City'],
                            'postcode' => $tax_addr['Postcode'],
                        ]);
                    }
                }
            }
        }
        return $address_tax;
    }

    public function validateCoupon($code) {
        $master = model('MasterModel');

        $valid = ['active'=>0,'message'=>''];

        $coupon = $master->getRow("tbl_coupons",['code'=>$code,'status'=>1]);
        if($coupon) {

            if($coupon['has_expiration']) {
                $cp_start = strtotime($coupon['valid_from']);
                $cp_end = strtotime($coupon['valid_to']);
                $curr = time();

                if($curr >= $cp_start && $curr <= $cp_end) {
                    $valid = ['active'=>1,'message'=>'Coupon is active', 'data'=>$coupon];
                }
                elseif($cp_end >= $curr) {
                    $valid = ['active'=>0,'message'=>'Coupon is expired', 'data'=>$coupon];
                }else {
                    $valid = ['active'=>0,'message'=>'Invalid coupon'];
                }
            }else {
                if($coupon['use_count'] && $coupon['use_limit'] > 0 && ($coupon['use_count'] > $coupon['use_limit'])) {
                    $valid = ['active'=>0,'message'=>'Coupon limit exceeded', 'data'=>$coupon];
                }else {
                    $valid = ['active'=>1,'message'=>'Coupon is active', 'data'=>$coupon];
                }
            }
        }else {
            $valid = ['active'=>0,'message'=>'Invalid coupon'];
        }

        return $valid;
    }

    public function applyCouponCode($code) {
        $applied = [];
        if($code) {
            $validate = $this->validateCoupon($code);

            $cart = $this->get_cart();

            if(!empty($validate['active'])) {

                if($cart) {
                    $cart['coupon_code'] = $code;
                    $cart['coupon_id'] = $validate['data']['id'];
                    $session = session();
                    $session->set('cart_session',$cart);
                    $applied = ['applied'=>1,'message'=>'Coupon applied'];
                }
            }else {
                $cart['coupon_code'] = '';
                $cart['coupon_id'] = '';
                $session = session();
                $session->set('cart_session',$cart);
                $applied = ['applied'=>0,'message'=>$validate['message']];
            }
        }
        return ($applied);
    }

    public function removeCoupon() {
        $cart = $this->get_cart();
        if(!empty($cart['discount_amount'])) {
            $cart['discount_amount'] = 0;
            $cart['coupon_code'] = 0;
            $cart['coupon_id'] = 0;
        }
        $session = session();
        $session->set('cart_session',$cart);
    }

    public function shipping_method_by_idx($idx) {
        $shipping_methods = get_setting('shippingmethods');
        $output = [];
        if(!empty($shipping_methods)) {
            $shipping_values = json_decode($shipping_methods);
            if(!empty($shipping_values[$idx])) {
                $shipping_name = $shipping_values[$idx]->name;
                $shipping_amt = $shipping_values[$idx]->value;

                $output = ['name'=>$shipping_name,'amount'=>$shipping_amt];
            }
        }
        return $output;
    }

    public function get_shipping_methods($address=[]) {
        $shipping_methods = get_setting('shippingmethods', true);
        $tax_class = get_setting('tax_shipping_class');
        $tax_allowed = get_setting('enable_tax_rates');

        $address_tax = [
            'country' => '',
            'state' => '',
            'postcode' => '',
            'city' => '',
            'amount' => 0,
            'type' => '',
            'tax_name' => '',
            'tax_shipping' => '',
            'label' => '',
        ];
        if($tax_class) {
            $tax_rates = tax_rates();
            if(!empty($tax_rates[$tax_class])) {
                $address_tax = get_address_tax($tax_rates[$tax_class], $address);
            }
        }

        $output = [];

        foreach($shipping_methods as $shipping_method) {

            $calculated_value = $shipping_method['value'];

            $ship_arr = $shipping_method;
            $tax_amount = 0;
            //If tax applies on shipping
            if($tax_allowed && $address_tax['tax_shipping']) {
                if($address_tax['type'] === 'percent') {
                    $calculated_value = percent_increase($shipping_method['value'], $address_tax['amount']);
                    $tax_amount = percent_reduce($shipping_method['value'], $address_tax['amount'], true);
                }else {
                    $calculated_value = $shipping_method['value'] + $address_tax['amount'];
                    $tax_amount = $calculated_value;
                }

                $ship_arr = array_merge($ship_arr, [
                    'tax_class' => $address_tax['label'],
                    'tax_value' => $address_tax['amount'],
                    'tax_amount' => $tax_amount,
                    'tax_amount_type' => $address_tax['type']
                ]);
            }

            $ship_arr['calculated_value'] = $calculated_value;

            $output[] = $ship_arr;
        }

        return $output;
    }

    public function create_order($getPost=[]) {

        $ProductsModel = model('ProductsModel');
        $orderModel = model('OrderModel');
        $master = model('MasterModel');
        $session = session();
        helper('text_helper');

        $first_name = $getPost['billing_first_name'];
        $last_name = $getPost['billing_last_name'];
        $billing_country = $getPost['billing_country'];
        $billing_address1 = $getPost['billing_address_1'];
        $billing_address2 = $getPost['billing_address_2'];
        $billing_city = $getPost['billing_city'];
        $billing_state = $getPost['billing_state'];
        $billing_postcode = $getPost['billing_postcode'];
        $billing_phone = $getPost['billing_phone'];
        $billing_email = $getPost['billing_email'];

        $ship_to_different_address = !empty($getPost['ship_to_different_address']) ? $getPost['ship_to_different_address'] : 0;
        $shipping_country = $getPost['shipping_country'];
        $shipping_address1 = $getPost['shipping_address_1'];
        $shipping_address2 = $getPost['shipping_address_2'];
        $shipping_city = $getPost['shipping_city'];
        $shipping_state = $getPost['shipping_state'];
        $shipping_postcode = $getPost['shipping_postcode'];

        $payment_method = !empty($getPost['payment_method']) ? $getPost['payment_method'] : '';

        $order_comments = $getPost['order_comments'];
        // $subscribe = $getPost['fue_subscribe'];

        $include_tax = 'No';

        $cart_products = $this->get_cart();

        if(empty($cart_products['cart_id'])) {
            $cart_id = random_string();
            $cart_products['cart_id'] = random_string();
            $session->set('cart_id',$cart_id);
        }

        if($cart_products['vat_price']) {
            $include_tax = 'Yes';
        }

        if(!empty($getPost['customer_id'])) {
            $customer_id = $getPost['customer_id'];
        }else {
            $customer_id = 0;
            if(is_logged_in()) {
                $customer_id = is_logged_in();
            }
        }

        if (empty($cart_products)) {
            return redirect()->to(base_url('cart'));
        }

        $discount_amount = !empty($cart_products['discount_amount']) ? $cart_products['discount_amount'] : 0;

        $billing_info = [
            'billing_first_name'=>$first_name,
            'billing_last_name'=>$last_name,
            'billing_country'=>$billing_country,
            'billing_address_1'=>$billing_address1,
            'billing_address_2'=>$billing_address2,
            'billing_city'=>$billing_city,
            'billing_county'=>$billing_state,
            'billing_postcode'=>$billing_postcode,
            'billing_phone'=>$billing_phone,
            'billing_email'=>$billing_email
        ];

        $shipping_info = [
            'shipping_first_name'=>$first_name,
            'shipping_last_name'=>$last_name,
            'shipping_country'=>$shipping_country,
            'shipping_address_1'=>$shipping_address1,
            'shipping_address_2'=>$shipping_address2,
            'shipping_city'=>$shipping_city,
            'shipping_county'=>$shipping_state,
            'shipping_postcode'=>$shipping_postcode,
            'shipping_phone'=>$billing_phone,
            'shipping_email'=>$billing_email
        ];

        $cart_exists = $master->query("SELECT order_id FROM `tbl_orders` WHERE cart_id='".$cart_products['cart_id']."'",true,true);

        $order_data = [
            'status'=>!empty($getPost['post_status']) ? $getPost['post_status'] : 'pending',
            'order_title' => 'Order &ndash; '.date('F d, Y').' @ '.date('h:i A'),
            'customer_user'=>$customer_id,
            'payment_method'=> $payment_method,
            'customer_ip_address'=>get_client_ip(),
            'customer_user_agent'=>$_SERVER['HTTP_USER_AGENT'],
            'order_currency'=>env('default_currency_code'),
            'billing_address' => json_encode($billing_info),
            'shipping_address' => json_encode($shipping_info),
            'cart_id' => $cart_products['cart_id']
        ];

        if(empty($cart_exists)) {
            $master->insertData('tbl_orders',$order_data);
            $new_order_id = $master->last_insert_id();
        }else {
            $new_order_id = $cart_exists['order_id'];
        }

        //Clear order in db if exists.
        $master->delete_data('tbl_order_meta','order_id',$new_order_id);

        $existing_items = $master->getRows('tbl_order_items',['order_id'=>$new_order_id]);

        if($existing_items) {
            foreach ($existing_items as $item) {
                $master->delete_data('tbl_order_item_meta', 'item_id', $item->order_item_id);
            }
        }

        $master->delete_data('tbl_order_items','order_id',$new_order_id);

        $product_meta = [];

        if(!empty($cart_products)) {
            $order_total = 0;

            foreach($cart_products['products'] as $item) {

                $product = $ProductsModel->product_by_id($item['product_id']);

                $item_meta = $item;

                $order_items = [
                    'order_id'=>$new_order_id,
                    'product_name'=>$product->title,
                    'item_type'=>'line_item'
                ];

                $master->insertData('tbl_order_items', $order_items);

                $item_id = $master->last_insert_id();

                foreach($item_meta as $key=>$value) {
                    if(is_array($value)) {
                        $value = json_encode($value);
                    }
                    $master->insertData('tbl_order_item_meta', ['meta_key'=>$key,'meta_value'=>$value,'item_id'=>$item_id]);
                }

                if(!empty($item['replace_order'])) {
                    $old_order_id = $item['replace_order'];
                    $o_order_items = $orderModel->order_items($old_order_id);
                    foreach($o_order_items as $item) {
                        $o_item_id = $item['order_item_id'];
                        $master->delete_data('tbl_order_item_meta','item_id',$o_item_id);
                    }
                    $master->delete_data('tbl_orders','order_id',$old_order_id);
                    $master->delete_data('tbl_orders','parent_id',$old_order_id);
                    $master->delete_data('tbl_order_meta','order_id',$old_order_id);
                }

            }

            $subtotal = $cart_products['subtotal'];
            $shipping_cost = $cart_products['shipping_cost'];
            $shipping_method = $cart_products['shipping_name'];
            $shipping_vat_amt = $cart_products['shipping_tax'];
            $product_total = $cart_products['product_total'];

            $order_tax = $cart_products['total_tax'];
            $discount_tax = 0;

            unset($cart_products['products']);

            $shipping_discount = 0;
            $shipping_add = 0;
            $shipping_options = '';


            $order_meta = array_merge($cart_products, [
                'customer_user' => $customer_id,
                'payment_method'=>$payment_method,
                'payment_method_title'=>payment_method_map($payment_method),
                'customer_ip_address'=>get_client_ip(),
                'customer_user_agent'=>$_SERVER['HTTP_USER_AGENT'],
                'created_via'=>'checkout',
                'order_currency'=>env('default_currency_code'),
                'cart_discount'=>$discount_amount,
                'order_comments'=>$order_comments,
                'order_shipping'=>$shipping_cost,
                'order_shipping_title'=>$shipping_method,
                'order_shipping_tax'=>$shipping_vat_amt,
                'order_total'=>$subtotal,
                'order_tax' => $order_tax,
                'cart_discount_tax' => $discount_tax,
                'prices_include_tax' => $include_tax,
                'product_total' => $product_total,
                'date_paid'=>time(),
                'paid_date'=>date('Y-m-d h:i:s'),

                'billing_first_name'=>$first_name,
                'billing_last_name'=>$last_name,
                'billing_address_1'=>$billing_address1,
                'billing_address_2'=>$billing_address2,
                'billing_country'=>$billing_country,
                'billing_phone'=>$billing_phone,
                'billing_postcode'=>$billing_postcode,
                'billing_city'=>$billing_city,
                'billing_email'=>$billing_email,
                'billing_address_index'=>$first_name.' '.$last_name.' '.$billing_address1.' '.$billing_address2.' '.$billing_email.' '.$billing_phone,

                'shipping_first_name'=>$first_name,
                'shipping_last_name'=>$last_name,
                'shipping_address_1'=>$shipping_address1,
                'shipping_address_2'=>$shipping_address2,
                'shipping_country'=>$shipping_country,
                'shipping_phone'=>$billing_phone,
                'shipping_postcode'=>$shipping_postcode,
                'shipping_city'=>$shipping_city,
                'shipping_email'=>$billing_email,
                'shipping_address_index'=>$first_name.' '.$last_name.' '.$shipping_address1.' '.$shipping_address2.' '.$billing_email.' '.$billing_phone,
                'shipping_discount' => $shipping_discount,
                'shipping_add' => $shipping_add,
                'shipping_options' => $shipping_options
            ]);

            foreach($order_meta as $key=>$value) {
                if(is_array($value)) {
                    $value = json_encode($value);
                }
                $master->insertData('tbl_order_meta', ['meta_key'=>$key,'meta_value'=>$value, 'order_id'=>$new_order_id]);
            }

            $master->insertData('tbl_order_items', [
                'item_type' => 'shipping',
                'order_id' => $new_order_id,
                'product_name' => $shipping_method
            ]);

            $master->insertData('tbl_order_items', [
                'item_type' => 'tax',
                'order_id' => $new_order_id,
                'product_name' => $shipping_vat_amt
            ]);
        }

        return $new_order_id;
    }


}

?>