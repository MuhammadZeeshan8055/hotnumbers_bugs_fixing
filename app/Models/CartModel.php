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

        $cart = $this->get_cart();
        if(!empty($cart_data['type']) && $cart_data['type'] === "club_subscription") {
            return false;
        }
        $product_id = $cart_data['product_id'];
        $exists = false;
        if(!empty($cart['products'])) {
            foreach($cart['products'] as $i=>$product) {
                if(!empty($product['product_id']) && $product['product_id']==$product_id) {
                    if(!empty($cart_data['variations'])) {
                        $cart_variations = $cart_data['variations'];
                        $prod_variations = $product['variations'];
                        if(count(array_intersect($cart_variations, $prod_variations)) === count($cart_variations)) {
                            $exists = $i;
                            break;
                        }
                    }else {
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

            if(!empty($cart_data['type']) && $cart_data['type'] == 'club_subscription') {
                $cart['products'][] = $cart_data;
            }else {
                $availability = $productModel->product_availability($cart_data['product_id']);
                if(!$availability) {
                    $errors[] = 'Product is inactive';
                }

                if(!empty($cart_data['variations'])) {
                    $variation = $productModel->product_variation($cart_data['product_id'], $cart_data['variations']);
                    if(empty($variation)) {
                        $errors[] = 'Invalid variation';
                    }
                    if(!empty($variation['values']['manage_stock']) && $variation['values']['manage_stock'] == 'yes') {
                        if($cart_data['quantity'] > $variation['values']['stock']) {
                            $errors[] = 'Product quantity is more than available stock';
                        }
                        if(!empty($variation['values']['stock_status']) && $variation['values']['stock_status'] != 'instock') {
                            $errors[] = 'Product is out of stock';
                        }
                    }
                }

                if(!empty($errors)) {
                    return ['status'=>'error','errors'=>$errors];
                }
                if($exists === false) {
                    $cart['products'][] = $cart_data;
                }else {
                    //Increment quantity of existing product
                    $cart['products'][$exists]['quantity']+=$cart_data['quantity'];
                }
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

                if($item['item_type'] == "line_item") {
                    $item_meta = $item['item_meta'];
                    $item_attrs = [];
                    $item_vars = [];

                    $product_id = (int)@$item_meta['product_id'];
                    $qty = $item_meta['quantity'];

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

                    if(!empty($item_attrs['variations'])) {
                        $item_attrs['variations'] = json_decode($item_attrs['variations'],true);
                    }

                    $item_attrs = array_merge($item_attrs, [
                        'product_id' => $product_id,
                        'quantity' => $qty,
                        'type' => $type
                    ]);

                    $cart_arr = $item_attrs;

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


    public function get_cart($config=[], $cart_data=[]) {
        //$this->cart_destroy();
        $product_total = 0;
        $product_total_tax = 0;
        $shipping_cost_item_count = 0;
        $no_shipping_count = 0;
        $free_shipping_items = [];
        $session = session();
        $cart = !empty($cart_data) ? $cart_data : $session->get('cart_session');

        //$ProductsModel = model('ProductsModel');
        $has_subscription = false;
        $has_club_subscription = false;
        $free_shipping = 1;
        $has_shipping = 0;

        $data['UserModel'] = model('UserModel');

        $curr_user = $data['UserModel']->get_user();

        helper('filters');

        $display_tax_price = !empty($config['display_tax_price']) ? $config['display_tax_price'] : get_setting('display_tax_price');

        $subscription_plan = get_setting('subscriptionForm', true);

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
                $tax_class = '';
                $tax_name = '';
                $tax_rate_amt = 0;
                $tax_type = '';

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

                    if($item['type'] == "club_subscription") {
                        $price = $regular_price = $item['price'];
                    }

                    $sale_price = floatval($product->sale_price);
                    $prod_price = $price;

                    $item_price = $price;
                    if($sale_price) {
                        $item_price = $sale_price;
                    }

                    $variation_taxable = false;
                    $variation_tax_class = '';

                    if(!empty($variation['values']['regular_price'])) {
                        $prod_price = $price = $regular_price = $variation['values']['regular_price'];
                        $var_values = $variation['values'];
                        $item_price = $price;
                        if(!empty($var_values['sale_price'])) {
                            $sale_price = $variation['values']['sale_price'];
                            $prod_price = $sale_price;
                            $item_price = $sale_price;
                        }
                        if($var_values['tax_status'] === "taxable" && !empty($var_values['tax_class'])) {
                            $variation_taxable = true;
                            $variation_tax_class = $var_values['tax_class'];
                        }
                    }

                    $price = $prod_price * $item['quantity'];
                    $sale_price = $sale_price * $item['quantity'];

                    //$price = $ProductsModel->product_reduced_price($price);

                    $item['total_price'] = $prod_price;

                    $tax_amount = 0;

                    if(($product->tax_status === "taxable" || $variation_taxable) && get_setting('enable_tax_rates')) {
                        $addr_array = [
                            'country' => '',
                            'postcode' => '',
                            'state' => '',
                            'city' => '',
                        ];
                        $taxClass = strtolower($product->tax_class).'_tax_rate';

                        if($variation_taxable) {
                            $taxClass = strtolower($variation_tax_class).'_tax_rate';
                        }

                        $tax_rates = tax_rates();
                        $tax_rates = !empty($tax_rates[$taxClass]) ? $tax_rates[$taxClass] : [];
                        $tax_rate = get_address_tax($tax_rates, $addr_array);

                        if(!empty($tax_rate['amount'])) {
                            $tax_name = $tax_rate['tax_name'];
                            $tax_class = $taxClass;
                            $tax_rate_amt = $tax_rate['amount'];
                            $tax_type = $tax_rate['type'];

                            if($tax_rate['type'] === "percent") {
                                if(get_setting('price_with_tax') === "exclusive" && $display_tax_price === "excluding_tax") {
                                    $tax_amount = percent_reduce($price, $tax_rate['amount'],true);
                                    $sale_price_tax = percent_reduce($sale_price, $tax_rate['amount'],true);
                                    $regular_price_tax = percent_reduce($regular_price, $tax_rate['amount'],true);
                                }else {
                                    $tax_amount = sale_percent_reduce($price, $tax_rate['amount'],true);
                                    $sale_price_tax = sale_percent_reduce($sale_price, $tax_rate['amount'],true);
                                    $regular_price_tax = sale_percent_reduce($regular_price, $tax_rate['amount'],true);
                                }

                                if(get_setting('price_with_tax') === "inclusive" && $display_tax_price === "excluding_tax") {
                                    $prod_price = sale_percent_reduce($prod_price, $tax_rate['amount']);
                                    $sale_price -= $sale_price_tax;
                                    $regular_price -= $regular_price_tax;
                                }
                                if(get_setting('price_with_tax') === "exclusive" && $display_tax_price === "including_tax") {
                                    $tax_amount = percent_increase($price, $tax_rate['amount'],true);
                                    $sale_price_tax = percent_increase($sale_price, $tax_rate['amount'],true);
                                    $regular_price_tax = percent_increase($regular_price, $tax_rate['amount'],true);

                                    $price += $tax_amount;
                                    $sale_price += $sale_price_tax;
                                    $regular_price += $regular_price_tax;
                                }
                            }else {
                                $tax_amount = $price - $tax_rate['amount'];
                                $sale_price_tax = $sale_price  - $tax_rate['amount'];
                                $regular_price_tax = $regular_price  - $tax_rate['amount'];

                                if(get_setting('price_with_tax') === "exclusive" && $display_tax_price === "including_tax") {
                                    $price -= $tax_amount;
                                    $sale_price -= $sale_price_tax;
                                    $regular_price -= $regular_price_tax;
                                }
                                if(get_setting('price_with_tax') === "exclusive" && $display_tax_price === "including_tax") {
                                    $tax_amount = $price + $tax_rate['amount'];
                                    $sale_price_tax = $sale_price  + $tax_rate['amount'];
                                    $regular_price_tax = $regular_price  + $tax_rate['amount'];

                                    $price += $tax_amount;
                                    $sale_price += $sale_price_tax;
                                    $regular_price += $regular_price_tax;
                                }
                            }
                        }
                    }

                    $discount_html = '';
                    if($sale_price && ($sale_price !== $regular_price)) {
                        $price = $sale_price;
                        $discount_html = '<small class="strike-through">'._price($regular_price).'</small>';
                    }

                    $price_display = _price($price);

                    $item_price_html = trim($discount_html.' '._price($item_price));

                    if($item['type'] == "club_subscription") {
                        $price = $item['price'];
                        $plan_types = array_values($subscription_plan['subscription-type']);
                        $interval_name = $plan_types[$item['subscription']['interval']];
                        $price_display = $item_price_html = _price($price).' Every '.$interval_name;
                        $has_club_subscription = true;
                    }else {
                        if(!empty($subscription_plan) && !empty($item['subscription']['enable'])) {
                            $has_subscription = true;
                            $item_sub_plan = $item['subscription'];

                            $inteval_text = 'Every';
                            if(isset($item_sub_plan['interval'])) {
                                $plan_types = array_values($subscription_plan['subscription-type']);
                                $plan_keys = array_keys($subscription_plan['subscription-type']);
                                $inteval_text .= ' '.ucwords($plan_types[$item_sub_plan['interval']]);
                                $cart['products'][$i]['subscription']['period'] = $plan_keys[$item_sub_plan['interval']];
                            }

                            $cart['products'][$i]['subscription']['expire'] = 0;
                            $cart['products'][$i]['subscription']['price'] = $price;

                            $price_display = _price($price).' '.$inteval_text;
                            $item_price_html = _price($regular_price).' '.$inteval_text;

                            if(!empty($item_sub_plan['expire'])) {
                                $price_display .=  " for ".$item_sub_plan['expire'];
                                $item_price_html .= " for ".$item_sub_plan['expire'];
                            }
                        }
                    }

                   // $price = $price * $item['quantity'];

                    $item['price'] = $price;
                    $item['total_price'] = $price;

                    $display_price_html = '<span class="display-price">'.$price_display.'</span>';

                    $cart['products'][$i]['display_price_html'] = $display_price_html;


                    $cart['products'][$i]['tax'] = $tax_amount;
                    $cart['products'][$i]['tax_name'] = $tax_name;
                    $cart['products'][$i]['tax_class'] = $tax_class;
                    $cart['products'][$i]['tax_amount'] = $tax_rate_amt;
                    $cart['products'][$i]['tax_type'] = $tax_type;
                    $cart['products'][$i]['variation'] = $variation;

                    //$cart_total += $prod_price * $item['quantity'];
                    $cart['products'][$i]['price'] = $price;
                    $cart['products'][$i]['img'] = $product->img;
                    $cart['products'][$i]['item_price'] = $regular_price;
                    $cart['products'][$i]['item_price_html'] = $item_price_html;
                    $cart['products'][$i]['sale_price'] = $sale_price;
                    $cart['products'][$i]['sold_individually'] = $product->sold_individually;
                    $cart['products'][$i]['free_shipping'] = $product->free_shipping;

                    $product_total += $cart['products'][$i]['price'];

                    if($tax_amount) {
                        $product_total_tax += $tax_amount;
                    }

                    if($product->no_shipping) {
                        $no_shipping_count++;
                    }else {
                        $has_shipping = 1;
                    }

                    if(!$product->free_shipping) {
                        $shipping_cost_item_count++;
                        $free_shipping = 0;
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

        $cart['coupon_discount_text'] = '';
        $cart['wholesale_discount_text'] = '';
        $cart['wholesale_discount_type'] = '';

        $cart['user_discount_text'] = '';
        $cart['user_discount'] = '';
        $cart['user_discount_type'] = '';

        $cart['global_discount'] = '';
        $cart['global_discount_text'] = '';
        $cart['global_discount_type'] = '';

        $shipping_discount_text = '';

        if(isset($product_total) && !empty($cart['products'])) {

            $shipping_vat = 0;

            $cart_tax_amt =  0;

            $discount_text = [];

            $coupon_discount_amt = $global_discount_amt = $wholesale_discount_amt = $user_discount_amt = 0;

            $cart['coupon_discount'] = $cart['global_discount'] = $cart['wholesale_discount'] = $cart['user_discount'] = 0;

            $global_discount = get_setting('global_discount',true);

            if(!empty($global_discount['price']) && $global_discount['type'] != 'off') {
                $sign = $global_discount['type'] == 'percent' ? '%' : '';
                if($sign == '%') {
                    $cart['global_discount_type'] = 'percent';
                    $cart['global_discount_text'] = $global_discount['price'].$sign;
                    $global_discount_amt = percent_reduce($product_total, $global_discount['price'], true);
                    if($product_total_tax) {
                        $product_total_tax  -= percent_reduce($product_total_tax, $global_discount['price'], true);
                    }
                }else {
                    $cart['global_discount_type'] = 'fixed';
                    $cart['global_discount_text'] = _price($global_discount['price']);
                    $global_discount_amt = $global_discount['price'];
                    if($product_total_tax) {
                        $product_total_tax  -= $global_discount['price'];
                    }
                }
                $cart['global_discount'] = $global_discount_amt;
            }

            if(!empty($curr_user->user_id) && $curr_user->role_id === 'wholesale_customer' && isset($curr_user->wholesale_discount) && $curr_user->wholesale_discount_type != 'off') {
                $sign = $curr_user->wholesale_discount_type == 'percent' ? '%' : '';
                if ($sign == '%') {
                    $cart['wholesale_discount_type'] = 'percent';
                    $cart['wholesale_discount_text'] = $curr_user->wholesale_discount . $sign;
                    $wholesale_discount_amt = percent_reduce($product_total, $curr_user->wholesale_discount, true);
                    if($product_total_tax) {
                        $product_total_tax  -= percent_reduce($product_total_tax, $curr_user->wholesale_discount, true);
                    }
                } else {
                    $cart['wholesale_discount_type'] = 'fixed';
                    $cart['wholesale_discount_text'] = _price($curr_user->wholesale_discount);
                    $wholesale_discount_amt = $curr_user->wholesale_discount;
                    if($product_total_tax) {
                        $product_total_tax  -= $curr_user->wholesale_discount;
                    }
                }
                $cart['wholesale_discount'] = $wholesale_discount_amt;
            }
            else {
                $curr_user_role = current_user_role();
                $user_role_discounts = get_setting('user_discount',true);

                if($curr_user && $curr_user->role_id === 'wholesale_customer') {
                    $key_name_type = 'wholesale_discount_type';
                    $key_name = 'wholesale_discount';
                    $key_name_text = 'wholesale_discount_text';
                }else {
                    $key_name_type = 'user_discount_type';
                    $key_name = 'user_discount';
                    $key_name_text = 'user_discount_text';
                }

                if($user_role_discounts) {
                    $role_discount = [];
                    foreach ($user_role_discounts as $_discount) {
                        if ($_discount['role_id'] == $curr_user_role['id']) {
                            $role_discount = $_discount;
                            break;
                        }
                    }
                    if(!empty($role_discount['role_discount']) && $role_discount['role_discount_type'] != 'off') {
                        $sign = $role_discount['role_discount_type'] == 'percent' ? '%' : '';

                        if($sign == '%') {
                            $cart[$key_name_type] = 'percent';
                            $cart[$key_name_text] =  $role_discount['role_discount'].$sign;
                            $user_discount_amt = percent_reduce($product_total, $role_discount['role_discount'], true);
                            if($product_total_tax) {
                                $product_total_tax  -= percent_reduce($product_total_tax, $role_discount['role_discount'], true);
                            }
                        }else {
                            $cart[$key_name_type] = 'fixed';
                            $cart[$key_name_text] =  _price($role_discount['role_discount']);
                            $user_discount_amt = $role_discount['role_discount'];
                            if($product_total_tax) {
                                $product_total_tax  -= $user_discount_amt;
                            }
                        }
                        $cart[$key_name] = $user_discount_amt;
                    }
                }
            }

            $discount = ($global_discount_amt + $wholesale_discount_amt + $user_discount_amt);
            $discount = round((float)$discount, 2);

            if(!empty($cart['coupon_code'])) {

                $coupon = $productModel->getCouponByCode($cart['coupon_code']);
                if($coupon) {
                    $sign = $coupon['type'] == 'percent' ? '%' : '';
                    if($coupon['type'] == 'percent') {
                        $cart['coupon_discount_type'] = 'percent';
                        $cart['coupon_discount_text'] = $coupon['amount'].$sign;
                        $coupon_discount_amt = percent_reduce($product_total-$discount, $coupon['amount'], true);
                        if($product_total_tax) {
                            $product_total_tax  -= percent_reduce($product_total_tax, $coupon['amount'], true);
                        }
                    }else {
                        $cart['coupon_discount_type'] = 'fixed';
                        $cart['coupon_discount_text'] = _price($coupon['amount']);
                        $coupon_discount_amt = $coupon['amount'];
                        if($product_total_tax) {
                            $product_total_tax  -= $coupon['amount'];
                        }
                    }
                    $cart['coupon_discount'] = $coupon_discount_amt;
                    $cart['coupon_type'] = $coupon['type'];
                }

                $discount += $coupon_discount_amt;
            }

            $free_ship_discount = 0;
            $ship_cost = 0;

            $db_shipping_methods = $this->get_shipping_methods([
                'products'=>$cart['products']
            ]);

           // pr($product_total_tax);

            $cart_total = ($product_total - $discount);

            $cart_tax_name = '';
            $shipping_vat_data = [];
            if(get_setting('enable_tax_rates')) {
                $cart_tax = $this->get_cart_tax();
               
                if(!empty($cart_tax)) {
                    if($product_total_tax) {
                        //$cart_tax_amt = percent_reduce($product_total_tax,$cart_tax['amount'],true);
                        $cart_tax_amt = $product_total_tax;
                        if($display_tax_price !== 'including_tax') {
                            $cart_total += $product_total_tax;
                        }
                    }
                    $cart_tax_name = $cart_tax['tax_name'];
                    if($cart_tax['tax_shipping']) {
                        $shipping_vat_data = $cart_tax;
                    }
                }
            }

            if(!empty($db_shipping_methods) && $shipping_cost_item_count) {
                $ship_idx = 0;
                if($session->get('cart_shipping_method')) {
                    $ship_idx = $session->get('cart_shipping_method');
                }
                $shipping_id = $ship_idx;

                $shipping_cost = $db_shipping_methods[$ship_idx]['value'];
                $shipping_name = $db_shipping_methods[$ship_idx]['name'];
                $shipping_vat = !empty($db_shipping_methods[$ship_idx]['tax_amount']) && get_setting('enable_tax_rates') ? $db_shipping_methods[$ship_idx]['tax_amount'] : 0;

                $cart_tax_amt += $shipping_vat;

                if($free_shipping) {
                    $shipping_cost = 0;
                }

                $ship_cost = $shipping_cost ? ($shipping_vat + $shipping_cost) : 0;
            }


            if($cart_tax_amt < 0) {
                $cart_tax_amt = 0;
            }

            if($cart_total < 0) {
                $cart_total = 0;
            }


            $cart['shipping_discount'] = $free_ship_discount;
            $cart['shipping_discount_text'] = $shipping_discount_text;

            $cart['discount_text'] = implode(', ',$discount_text);
            $cart['has_subscription'] = $has_subscription;
            $cart['shipping_free_products'] = $free_shipping_items;
            $cart['has_club_subscription'] = $has_club_subscription;
            $cart['no_shipping_count'] = $no_shipping_count;
            $cart['has_shipping'] = $has_shipping;
            $cart['free_shipping'] = $free_shipping;
            $cart['discount_amount'] = $discount;
            $cart['product_total'] = $product_total;

            if($cart['has_shipping']=='0'){
                $cart['shipping_cost'] = 0 ;
            }else{
                $cart['shipping_cost'] = $ship_cost;
            }

            // $cart['shipping_cost'] = $ship_cost;

            $cart['shipping_tax'] = $shipping_vat;
            $cart['shipping_name'] = $shipping_name;
            $cart['shipping_id'] = $shipping_id;
            $cart['shipping_cost_item_count'] = $shipping_cost_item_count;
            $cart['vat_price'] = $cart_tax_amt;
            $cart['total_tax'] = $cart_tax_amt;
            $cart['tax_name'] = $cart_tax_name;
            $cart['cart_total'] = $cart_total;

            $cart = filter_shipping_rules($cart);

            if($cart['has_shipping']=='0'){
                $cart_total = $cart_total ;
            }else{
                $cart_total = $cart_total + $cart['shipping_cost'];
            }
            // $cart_total = $cart_total + $cart['shipping_cost'];

            // $cart['cart_total'] = $cart_total;

            $cart['cart_total'] = number_format($cart_total);
            
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
        $input_arr = [
            'calc_state' => ['','State'],
            'calc_city' => ['','City'],
            'calc_postcode' => ['','Postcode'],
            'calc_country' => ['','Country'],
        ];
        if(!is_logged_in()) {
            $UserModel = model('UserModel');
            if(empty(get_setting('tax_based_on')) || get_setting('tax_based_on') == "billing") {
                $billing = $UserModel->get_billing_address();
                if(!empty($billing['billing_country'])) {
                    $input_arr = [
                        'calc_country' => [@$billing['billing_country'],'Country'],
                        'calc_state' => [@$billing['billing_state'],'State'],
                        'calc_city' => [@$billing['billing_city'],'City'],
                        'calc_postcode' => [@$billing['billing_postcode'],'Postcode']
                    ];
                }

            }
            if(get_setting('tax_based_on') == "shipping") {
                $shipping = $UserModel->get_shipping_address();
                if(!empty($shipping['shipping_country'])) {
                    $input_arr = [
                        'calc_country' => [@$shipping['shipping_country'],'Country'],
                        'calc_state' => [@$shipping['shipping_state'],'State'],
                        'calc_city' => [@$shipping['shipping_city'],'City'],
                        'calc_postcode' => [@$shipping['shipping_postcode'],'Postcode']
                    ];
                }
            }
            $tax_class_address = $input_arr;
        }else {
            $tax_class_address = ['calc_country'=>[0=>'',1=>''], "calc_state"=>[0=>'',1=>''],"calc_city"=>[0=>'',1=>''],"calc_postcode"=>[0=>'',1=>'']];
        }
            if(!empty($tax_class_address)) {
                $tax_addr = [
                    'Country'=>'',
                    'State' =>'',
                    'City' => '',
                    'Postcode' => ''
                ];
                $tax_arr = [];
                foreach($tax_class_address as $address) {
                    if(!empty($address)) {
                        $tax_addr[$address[1]] = $address[0];
                    }
                }
                $tax_class = get_setting('tax_shipping_class');
                if(!$tax_class) {
                    $tax_class = 'standard_tax_rate';
                }
                $tax_rates = tax_rates();

                if($tax_class) {
                    if(!empty($tax_rates[$tax_class])) {
                        $tax_arr = $tax_rates[$tax_class];
                    }
                }
                $address_tax = get_address_tax($tax_arr, [
                    'country' => $tax_addr['Country'],
                    'state' => $tax_addr['State'],
                    'city' => $tax_addr['City'],
                    'postcode' => $tax_addr['Postcode'],
                ]);
            }

        return $address_tax;
    }

    public function validateCoupon($code) {
        $master = model('MasterModel');
        $coupon = $master->getRow("tbl_coupons",['code'=>$code,'status'=>1]);
        if(coupon_status($coupon) == "active") {
            $valid = ['active'=>1,'message'=>'Coupon is active', 'data'=>$coupon];
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

        $cart['coupon_discount'] = '';
        $cart['coupon_code'] = '';
        $cart['coupon_id'] = 0;
        $cart['coupon_discount_text'] = '';

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
        $display_tax_price = get_setting('display_tax_price');
        $cart_products = !empty($address['products']) ? $address['products'] : [];
        unset($address['products']);

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
        $tax_rates = tax_rates();

        if($tax_class === "cart_items" && !empty($cart_products)) {
            $prod_tax_rates = [];
            foreach($cart_products as $product) {
                $prod_tax_rates[$product['tax_class']] = $product['tax_amount'];
            }
            $max_tax_amt =  array_keys($prod_tax_rates, max($prod_tax_rates));
            if(!empty($max_tax_amt[0]) && !empty($tax_rates[$max_tax_amt[0]])) {
                $address_tax = get_address_tax($tax_rates[$max_tax_amt[0]], $address);
            }
        }else {
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
                    if($display_tax_price === "including_tax") {
                        $calculated_value = percent_increase($shipping_method['value'], $address_tax['amount']);
                    }
                    $tax_amount = percent_reduce($shipping_method['value'], $address_tax['amount'], true);
                }else {
                    if($display_tax_price === "including_tax") {
                        $calculated_value = $shipping_method['value'] + $address_tax['amount'];
                    }
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

    // public function create_order($getPost=[]) {

    //     $ProductsModel = model('ProductsModel');
    //     $orderModel = model('OrderModel');
    //     $master = model('MasterModel');
    //     $session = session();
    //     helper('text_helper');

    //     $first_name = $getPost['billing_first_name'];
    //     $last_name = $getPost['billing_last_name'];
    //     $billing_country = $getPost['billing_country'];
    //     $billing_address1 = $getPost['billing_address_1'];
    //     $billing_address2 = $getPost['billing_address_2'];
    //     $billing_city = $getPost['billing_city'];
    //     $billing_state = $getPost['billing_state'];
    //     $billing_postcode = $getPost['billing_postcode'];
    //     $billing_phone = $getPost['billing_phone'];
    //     $billing_email = $getPost['billing_email'];

    //     $ship_to_different_address = !empty($getPost['ship_to_different_address']) ? $getPost['ship_to_different_address'] : 0;
    //     $shipping_country = $getPost['shipping_country'];
    //     $shipping_address1 = $getPost['shipping_address_1'];
    //     $shipping_address2 = $getPost['shipping_address_2'];
    //     $shipping_city = $getPost['shipping_city'];
    //     $shipping_state = $getPost['shipping_state'];
    //     $shipping_postcode = $getPost['shipping_postcode'];

    //     // $payment_method = !empty($getPost['payment_method']) ? $getPost['payment_method'] : '';
    //     $payment_method = !empty($getPost['payment_method']) ? $getPost['payment_method'] : '';

    //     if ($payment_method === 'squareup') {
    //         $payment_method = 'Credit/Debit Card';
    //     }

    //     $order_comments = $getPost['order_comments'];
    //     // $subscribe = $getPost['fue_subscribe'];

    //     $include_tax = 'No';
    //     $price_with_tax = get_setting('price_with_tax');
    //     $display_tax_price = get_setting('display_tax_price');

    //     $cart_products = $this->get_cart();

    //     if(empty($cart_products['cart_id'])) {
    //         $cart_id = random_string();
    //         $cart_products['cart_id'] = random_string();
    //         $session->set('cart_id',$cart_id);
    //     }

    //     if($cart_products['vat_price']) {
    //         $include_tax = 'Yes';
    //     }

    //     $customer_id = empty($getPost['customer_id']) ? (int)is_logged_in() : $getPost['customer_id'];

    //     if (empty($cart_products)) {
    //         return redirect()->to(base_url('cart'));
    //     }

    //     $discount_amount = !empty($cart_products['discount_amount']) ? $cart_products['discount_amount'] : 0;

    //     $billing_info = [
    //         'billing_first_name'=>$first_name,
    //         'billing_last_name'=>$last_name,
    //         'billing_country'=>$billing_country,
    //         'billing_address_1'=>$billing_address1,
    //         'billing_address_2'=>$billing_address2,
    //         'billing_city'=>$billing_city,
    //         'billing_county'=>$billing_state,
    //         'billing_postcode'=>$billing_postcode,
    //         'billing_phone'=>$billing_phone,
    //         'billing_email'=>$billing_email
    //     ];

    //     $shipping_info = [
    //         'shipping_first_name'=>$first_name,
    //         'shipping_last_name'=>$last_name,
    //         'shipping_country'=>$shipping_country,
    //         'shipping_address_1'=>$shipping_address1,
    //         'shipping_address_2'=>$shipping_address2,
    //         'shipping_city'=>$shipping_city,
    //         'shipping_county'=>$shipping_state,
    //         'shipping_postcode'=>$shipping_postcode,
    //         'shipping_phone'=>$billing_phone,
    //         'shipping_email'=>$billing_email
    //     ];

    //     $cart_exists = $master->query("SELECT order_id FROM `tbl_orders` WHERE cart_id='".$cart_products['cart_id']."'",true,true);

    //     $order_data = [
    //         'status'=>!empty($getPost['post_status']) ? $getPost['post_status'] : 'pending',
    //         'order_title' => 'Order &ndash; '.date('F d, Y').' @ '.date('h:i A'),
    //         'customer_user'=>$customer_id,
    //         'payment_method'=> $payment_method,
    //         'customer_ip_address'=>get_client_ip(),
    //         'customer_user_agent'=>$_SERVER['HTTP_USER_AGENT'],
    //         'order_currency'=>env('default_currency_code'),
    //         'billing_address' => json_encode($billing_info),
    //         'shipping_address' => json_encode($shipping_info),
    //         'cart_id' => $cart_products['cart_id']
    //     ];

    //     $purchase_order_number = '';

    //     if(is_wholesaler() && $getPost['purchase_order_number']) {
    //         $purchase_order_number = $getPost['purchase_order_number'];
    //     }

    //     if(empty($cart_exists)) {
    //         $master->insertData('tbl_orders',$order_data);
    //         $new_order_id = $master->last_insert_id();
    //     }else {
    //         $new_order_id = $cart_exists['order_id'];
    //     }

    //     //Clear order in db if exists.
    //     $master->delete_data('tbl_order_meta','order_id',$new_order_id);

    //     $existing_items = $master->getRows('tbl_order_items',['order_id'=>$new_order_id]);

    //     if($existing_items) {
    //         foreach ($existing_items as $item) {
    //             $master->delete_data('tbl_order_item_meta', 'item_id', $item->order_item_id);
    //         }
    //     }

    //     $master->delete_data('tbl_order_items','order_id',$new_order_id);

    //     $product_meta = [];

    //     if(!empty($cart_products)) {
    //         $order_total = 0;

    //         foreach($cart_products['products'] as $item) {

    //             $product = $ProductsModel->product_by_id($item['product_id']);

    //             $item_meta = $item;

    //             $order_items = [
    //                 'order_id'=>$new_order_id,
    //                 'product_name'=>$product->title,
    //                 'item_type'=>'line_item'
    //             ];

    //             $master->insertData('tbl_order_items', $order_items);

    //             $item_id = $master->last_insert_id();

    //             foreach($item_meta as $key=>$value) {
    //                 if(is_array($value)) {
    //                     $value = json_encode($value);
    //                 }
    //                 $master->insertData('tbl_order_item_meta', ['meta_key'=>$key,'meta_value'=>$value,'item_id'=>$item_id]);
    //             }

    //             if(!empty($item['replace_order'])) {
    //                 $old_order_id = $item['replace_order'];
    //                 $o_order_items = $orderModel->order_items($old_order_id);
    //                 foreach($o_order_items as $item) {
    //                     $o_item_id = $item['order_item_id'];
    //                     $master->delete_data('tbl_order_item_meta','item_id',$o_item_id);
    //                 }
    //                 $master->delete_data('tbl_orders','order_id',$old_order_id);
    //                 $master->delete_data('tbl_orders','parent_id',$old_order_id);
    //                 $master->delete_data('tbl_order_meta','order_id',$old_order_id);
    //             }

    //         }



    //         $order_total = $cart_products['cart_total'];
    //         $shipping_cost = $cart_products['shipping_cost'];
    //         $shipping_method = $cart_products['shipping_name'];
    //         $shipping_vat_amt = $cart_products['shipping_tax'];
    //         $product_total = $cart_products['product_total'];
    //         $has_shipping = $cart_products['has_shipping'];
    //         $free_shipping = $cart_products['free_shipping'];

    //         $order_tax = $cart_products['total_tax'];
    //         $discount_tax = 0;

    //         unset($cart_products['products']);

    //         $shipping_discount = 0;
    //         $shipping_add = 0;
    //         $shipping_options = '';

    //         $paid_date = !empty($getPost['order_paid']) ? date('Y-m-d h:i:s') : '';

    //         $order_meta = array_merge($cart_products, [
    //             'customer_user' => $customer_id,
    //             'payment_method'=>$payment_method,
    //             'payment_method_title'=>payment_method_map($payment_method),
    //             'customer_ip_address'=>get_client_ip(),
    //             'customer_user_agent'=>$_SERVER['HTTP_USER_AGENT'],
    //             'created_via'=>'checkout',
    //             'order_currency'=>env('default_currency_code'),
    //             'cart_discount'=>$discount_amount,
    //             'order_comments'=>$order_comments,
    //             'order_shipping'=>$shipping_cost,
    //             'has_shipping'=>$has_shipping,
    //             'free_shipping'=>$free_shipping,
    //             'order_shipping_title'=>$shipping_method,
    //             'order_shipping_tax'=>$shipping_vat_amt,
    //             'order_total'=>$order_total,
    //             'order_tax' => $order_tax,
    //             'price_with_tax' => $price_with_tax,
    //             'display_tax_price' => $display_tax_price,
    //             'cart_discount_tax' => $discount_tax,
    //             'prices_include_tax' => $include_tax,
    //             'product_total' => $product_total,
    //             'paid_date'=>$paid_date,
    //             'order_date'=>date('Y-m-d h:i:s'),
    //             'purchase_order_number' => $purchase_order_number,

    //             'billing_first_name'=>$first_name,
    //             'billing_last_name'=>$last_name,
    //             'billing_address_1'=>$billing_address1,
    //             'billing_address_2'=>$billing_address2,
    //             'billing_country'=>$billing_country,
    //             'billing_phone'=>$billing_phone,
    //             'billing_postcode'=>$billing_postcode,
    //             'billing_city'=>$billing_city,
    //             'billing_email'=>$billing_email,
    //             'billing_address_index'=>$first_name.' '.$last_name.' '.$billing_address1.' '.$billing_address2.' '.$billing_email.' '.$billing_phone,

    //             'shipping_first_name'=>$first_name,
    //             'shipping_last_name'=>$last_name,
    //             'shipping_address_1'=>$shipping_address1,
    //             'shipping_address_2'=>$shipping_address2,
    //             'shipping_country'=>$shipping_country,
    //             'shipping_phone'=>$billing_phone,
    //             'shipping_postcode'=>$shipping_postcode,
    //             'shipping_city'=>$shipping_city,
    //             'shipping_email'=>$billing_email,
    //             'shipping_address_index'=>$first_name.' '.$last_name.' '.$shipping_address1.' '.$shipping_address2.' '.$billing_email.' '.$billing_phone,
    //             'shipping_discount' => $shipping_discount,
    //             'shipping_add' => $shipping_add,
    //             'shipping_options' => $shipping_options
    //         ]);

    //         foreach($order_meta as $key=>$value) {
    //             if(is_array($value)) {
    //                 $value = json_encode($value);
    //             }
    //             $master->insertData('tbl_order_meta', ['meta_key'=>$key,'meta_value'=>$value, 'order_id'=>$new_order_id]);
    //         }

    //         $master->insertData('tbl_order_items', [
    //             'item_type' => 'shipping',
    //             'order_id' => $new_order_id,
    //             'product_name' => $shipping_method
    //         ]);

    //         $master->insertData('tbl_order_items', [
    //             'item_type' => 'tax',
    //             'order_id' => $new_order_id,
    //             'product_name' => $shipping_vat_amt
    //         ]);
    //     }

    //     return $new_order_id;
    // }


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
    
        if ($payment_method === 'squareup') {
            $payment_method = 'Credit/Debit Card';
        }
        if ($payment_method === 'direct') {
            $payment_method = 'Zero Charge';
        }
    
        $order_comments = $getPost['order_comments'];
        $include_tax = 'No';
        $price_with_tax = get_setting('price_with_tax');
        $display_tax_price = get_setting('display_tax_price');
    
        $cart_products = $this->get_cart();
    
        if(empty($cart_products['cart_id'])) {
            $cart_id = random_string();
            $cart_products['cart_id'] = $cart_id;
            $session->set('cart_id',$cart_id);
        }
    
        if($cart_products['vat_price']) {
            $include_tax = 'Yes';
        }
    
        $customer_id = empty($getPost['customer_id']) ? (int)is_logged_in() : $getPost['customer_id'];
    
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
    
        // Separate subscription items and other products
        $subscription_items = array_filter($cart_products['products'], function($item) {
            return $item['type'] === 'club_subscription';
        });
    
        $other_items = array_filter($cart_products['products'], function($item) {
            return $item['type'] !== 'club_subscription';
        });
    
        $order_ids = [];
    
        // Create order for subscription items if present
        if (!empty($subscription_items)) {
            $order_data = [
                'status'=>!empty($getPost['post_status']) ? $getPost['post_status'] : 'pending',
                'order_title' => 'Subscription Order &ndash; '.date('F d, Y').' @ '.date('h:i A'),
                'customer_user'=>$customer_id,
                'payment_method'=> $payment_method,
                'customer_ip_address'=>get_client_ip(),
                'customer_user_agent'=>$_SERVER['HTTP_USER_AGENT'],
                'order_currency'=>env('default_currency_code'),
                'billing_address' => json_encode($billing_info),
                'shipping_address' => json_encode($shipping_info),
                'cart_id' => $cart_products['cart_id']
            ];
    
            $order_ids['subscription'] = $master->insertData('tbl_orders', $order_data);
            $order_ids['subscription'] = $master->last_insert_id();
            
            $order_total = 0;
            $subs_item_price=0;
            
            foreach($subscription_items as $item) {
                $product = $ProductsModel->product_by_id($item['product_id']);
                $item_meta = $item;
                $order_items = [
                    'order_id' => $order_ids['subscription'],
                    'product_name' => $product->title,
                    'item_type' => 'line_item'
                ];
    
                $master->insertData('tbl_order_items', $order_items);
                $item_id = $master->last_insert_id();
    
                foreach($item_meta as $key=>$value) {
                        if ($key === 'item_price') {
                            $subs_item_price = $value; 
                        }

                    if(is_array($value)) {
                        $value = json_encode($value);
                    }
                    $master->insertData('tbl_order_item_meta', ['meta_key'=>$key,'meta_value'=>$value,'item_id'=>$item_id]);
                }
            }
    
            $order_meta = array_merge($cart_products, [
                'customer_user' => $customer_id,
                'payment_method' => $payment_method,
                'payment_method_title' => payment_method_map($payment_method),
                'customer_ip_address' => get_client_ip(),
                'customer_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_via' => 'checkout',
                'order_currency' => env('default_currency_code'),
                'cart_discount' => $discount_amount,
                'order_comments' => $order_comments,
                
                // 'order_shipping' => $cart_products['shipping_cost'],
                'order_shipping' => 0,
                
                'has_shipping' => $cart_products['has_shipping'],
                'free_shipping' => $cart_products['free_shipping'],
                'order_shipping_title' => $cart_products['shipping_name'],
                'order_shipping_tax' => $cart_products['shipping_tax'],

                // 'order_total' => $cart_products['cart_total'],
                'order_total' => $subs_item_price,
               
                'order_tax' => $cart_products['total_tax'],
                'price_with_tax' => $price_with_tax,
                'display_tax_price' => $display_tax_price,
                'cart_discount_tax' => 0,
                'prices_include_tax' => $include_tax,
                
                // 'product_total' => $cart_products['product_total'],
                'product_total' => $subs_item_price,

                'paid_date' => !empty($getPost['order_paid']) ? date('Y-m-d h:i:s') : '',
                'order_date' => date('Y-m-d h:i:s'),
                'purchase_order_number' => $getPost['purchase_order_number'] ?? '',
                
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
                $master->insertData('tbl_order_meta', ['meta_key'=>$key,'meta_value'=>$value, 'order_id'=>$order_ids['subscription']]);
            }
    
            $master->insertData('tbl_order_items', [
                'item_type' => 'shipping',
                'order_id' => $order_ids['subscription'],
                'product_name' => $cart_products['shipping_name']
            ]);
    
            $master->insertData('tbl_order_items', [
                'item_type' => 'tax',
                'order_id' => $order_ids['subscription'],
                'product_name' => $cart_products['shipping_tax']
            ]);
        }
    
        // Create order for other products if present
        if (!empty($other_items)) {

            $is_internal=is_internal();
            $is_wholesaler=is_wholesaler();

            // Set post status to 'processing' for internal users or wholesalers, otherwise 'pending' by default
            $getPost['post_status'] = ($is_internal || $is_wholesaler) ? 'processing' : ($getPost['post_status'] ?? 'pending');


            $order_data = [
                'status' => $getPost['post_status'],
                'order_title' => 'Product Order &ndash; '.date('F d, Y').' @ '.date('h:i A'),
                'customer_user'=>$customer_id,
                'payment_method'=> $payment_method,
                'customer_ip_address'=>get_client_ip(),
                'customer_user_agent'=>$_SERVER['HTTP_USER_AGENT'],
                'order_currency'=>env('default_currency_code'),
                'billing_address' => json_encode($billing_info),
                'shipping_address' => json_encode($shipping_info),
                'cart_id' => $cart_products['cart_id']
            ];
    
            $order_ids['product'] = $master->insertData('tbl_orders', $order_data);
            $order_ids['product'] = $master->last_insert_id();
    
            foreach($other_items as $item) {
                $product = $ProductsModel->product_by_id($item['product_id']);
                $item_meta = $item;
                $order_items = [
                    'order_id' => $order_ids['product'],
                    'product_name' => $product->title,
                    'item_type' => 'line_item'
                ];
    
                $master->insertData('tbl_order_items', $order_items);
                $item_id = $master->last_insert_id();
    
                foreach($item_meta as $key=>$value) {
                    if(is_array($value)) {
                        $value = json_encode($value);
                    }
                    $master->insertData('tbl_order_item_meta', ['meta_key'=>$key,'meta_value'=>$value,'item_id'=>$item_id]);
                }
            }
    
            $order_meta = array_merge($cart_products, [
                'customer_user' => $customer_id,
                'payment_method' => $payment_method,
                'payment_method_title' => payment_method_map($payment_method),
                'customer_ip_address' => get_client_ip(),
                'customer_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_via' => 'checkout',
                'order_currency' => env('default_currency_code'),
                'cart_discount' => $discount_amount,
                'order_comments' => $order_comments,
                'order_shipping' => $cart_products['shipping_cost'],
                'has_shipping' => $cart_products['has_shipping'],
                'free_shipping' => $cart_products['free_shipping'],
                'order_shipping_title' => $cart_products['shipping_name'],
                'order_shipping_tax' => $cart_products['shipping_tax'],
                
                // 'order_total' => $cart_products['cart_total'],
                'order_total' => $cart_products['cart_total'] - $subs_item_price,
                
                'order_tax' => $cart_products['total_tax'],
                'price_with_tax' => $price_with_tax,
                'display_tax_price' => $display_tax_price,
                'cart_discount_tax' => 0,
                'prices_include_tax' => $include_tax,
                
                // 'product_total' => $cart_products['product_total'],
                'product_total' => $cart_products['product_total'] - $subs_item_price,
                
                'paid_date' => !empty($getPost['order_paid']) ? date('Y-m-d h:i:s') : '',
                'order_date' => date('Y-m-d h:i:s'),
                'purchase_order_number' => $getPost['purchase_order_number'] ?? '',
                
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
                $master->insertData('tbl_order_meta', ['meta_key'=>$key,'meta_value'=>$value, 'order_id'=>$order_ids['product']]);
            }
    
            $master->insertData('tbl_order_items', [
                'item_type' => 'shipping',
                'order_id' => $order_ids['product'],
                'product_name' => $cart_products['shipping_name']
            ]);
    
            $master->insertData('tbl_order_items', [
                'item_type' => 'tax',
                'order_id' => $order_ids['product'],
                'product_name' => $cart_products['shipping_tax']
            ]);
        }
    
        return $order_ids;
    }
    



}

?>