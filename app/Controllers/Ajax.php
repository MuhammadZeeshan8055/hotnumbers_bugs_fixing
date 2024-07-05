<?php
namespace App\Controllers;
use App\Controllers\BaseController;


class Ajax extends BaseController
{

    public function update_item_quantity() {
        $cart = model('CartModel');
        $cart = $cart->get_cart();
        $productModel = model('ProductsModel');
        $session = session();
        $item_id=$this->request->getPost('item_id');
        $quantity=$this->request->getPost('quantity');
        if(!empty($cart['products'])) {
            $cart_item = $cart['products'][$item_id];
            $variation_list = [];
            if(!empty($cart_item['variations'])) {
                $variation_list = $cart_item['variations'];
            }

            $available = $productModel->stock_availability($cart_item['product_id'],$cart_item['quantity'], $variation_list);
            $cart['products'][$item_id]['error'] = $available['error'];

            $cart_product = $cart['products'][$item_id];
            $total_price = (float)$cart_product['price']*$quantity;
           // $discount_price = $productModel->product_reduced_price($total_price);
            $cart['products'][$item_id]['quantity'] = $quantity;
            $cart['products'][$item_id]['price'] = $total_price;
           // $cart['products'][$item_id]['discount_price'] = $discount_price;
        }

        //pr($cart['products'][$item_id]);

        $session->set('cart_session',$cart);

        echo json_encode($cart['products'][$item_id]);
    }

    public function add_cart($cart_data=[]) {
        $cart = model('CartModel');
        $post = !empty($cart_data) ? $cart_data : $this->request->getPost();
        $post['type'] = 'product';
        $add_cart = $cart->add_item($post);
        $cart->sync_usr_cart();
        echo json_encode($add_cart);
    }

    public function get_cart() {
        $cart = model('CartModel');
        $cart = $cart->get_cart();
        echo json_encode($cart);
    }

    public function sub_plan_expire_options() {
        $plan = get_setting('subscription_plans',true);

        if(!empty($plan)) {
            $plan = $plan[0];
        }
        $getInterval = $this->request->getGet('interval');
        $getPeriod = $this->request->getGet('period');
        $product_id = $this->request->getGet('product_id');



        if(isset($plan[$getPeriod.'_interval']) && in_array($getInterval,$plan[$getPeriod.'_interval'])) {

            $expire_lims = $plan[$getPeriod.'_expire'];
            $expire_options = [];

            if(empty($expire_lims['min']) && !empty($expire_lims['max'])) {
                $expire_options = [$expire_lims['max']];
            }
            else if(!empty($expire_lims['min']) && empty($expire_lims['max'])) {
                $expire_options = [$expire_lims['min']];
            }
            else if(!empty($expire_lims['min']) && !empty($expire_lims['max'])) {
                for($e = $expire_lims['min']; $e<=$expire_lims['max']; $e++) {
                    if($getInterval && ($e%$getInterval)) {
                        continue;
                    }
                    $expire_options[$e] = $e.' '.$getPeriod.($getPeriod > 0 ? 's':'');
                }
            }else {
                $expire_options = [0];
            }
          echo json_encode($expire_options);
        }
    }

    public function shop_subscription_form() {
        $price = $this->request->getGet('price');
        $qty = $this->request->getGet('qty');
        $variation = $this->request->getGet('variation');
        echo view('shop/shop_subscription_form',['product_price'=>$price,'product_quantity'=>$qty,'variation'=>$variation]);
    }

    public function check_email_exists() {
        $userModel = model('UserModel');
        $email = $this->request->getPost('email');
        $exists = 0;
        if($email) {
            $get_user = $userModel->getCustomerByEmail($email,'email,role');
            $exists = !empty($get_user) ? $get_user : false;
        }
        echo json_encode(['exists'=>$exists]);
    }
}