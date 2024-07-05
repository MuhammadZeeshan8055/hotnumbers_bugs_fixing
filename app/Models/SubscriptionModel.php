<?php

namespace App\Models;
use Braintree\Gateway;
use Cassandra\Date;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class SubscriptionModel extends Model {

    private $mail, $orderModel, $masterModel, $notification;

    public function __construct()
    {
        $this->mail = new MailModel();
        $this->orderModel = new OrderModel();
        $this->masterModel = new MasterModel();
        $this->notification = new NotificationModel();
    }


    public function init($data=[]) {
        $subscription_id = 0;

        if(!empty($data['order_id'])) {

            $master = $this->masterModel;
            $orderModel = $this->orderModel;

            /*$cartModel = model('CartModel');

            $item_oid = !empty($data['parent_id']) ? $data['parent_id'] : $data['order_id'];
            $item = $orderModel->order_item($item_oid, $data['item_id']);
            $item_meta = $orderModel->order_item_meta($data['item_id']);

            $product_data = [
                'products' => [
                    0 => [
                        'type'=>'product',
                        'variations' => json_decode($item_meta['variations'],true),
                        'quantity' => $item_meta['quantity'],
                        'product_id' => $item_meta['product_id'],
                        'subscription' => $item_meta['subscription']
                    ]
                ]
            ];


            $orderCart = $cartModel->get_cart([],$product_data);

            pr($orderCart);*/

            $order = $orderModel->get_order_by_id($data['order_id']);
            $item_oid = !empty($data['parent_id']) ? $data['parent_id'] : $data['order_id'];
            $item = $orderModel->order_item($item_oid, $data['item_id']);
            $item_meta = $orderModel->order_item_meta($data['item_id']);
            $meta_data = $order['order_meta'];

            $meta_data['coupon_discount'] = 0;
            $meta_data['coupon_code'] = '';
            $meta_data['coupon_discount_text'] = '';
            $meta_data['coupon_discount_type'] = '';
            $meta_data['coupon_type'] = '';

            $meta_data['wholesale_discount'] = 0;
            $meta_data['wholesale_discount_text'] = '';
            $meta_data['wholesale_discount_type'] = '';

            $meta_data['user_discount'] = 0;
            $meta_data['user_discount_text'] = '';
            $meta_data['user_discount_type'] = '';

            $meta_data['shipping_discount'] = 0;
            $meta_data['shipping_discount_text'] = '';

            $sub_data = [
                'order_title' => 'Subscription &ndash; '.date('F d, Y @ H:i A'),
                'status'=>'processing',
                'customer_user' => $order['customer_user'],
                'payment_method' => $order['payment_method'],
                'customer_ip_address' => get_client_ip(),
                'customer_user_agent' => $order['customer_user_agent'],
                'order_currency' => $order['order_currency'],
                'billing_address' => json_encode($order['billing_address']),
                'shipping_address' => json_encode($order['shipping_address']),
                'order_type' => 'shop_subscription',
                'parent_id' => $data['order_id'],
                'order_date' => $order['order_date']
            ];

            $shipping_cost = $meta_data['shipping_cost'];
            $item_tax = $item_meta['tax'];
            $shipping_tax = $meta_data['shipping_tax'];

            $price_with_tax = $meta_data['price_with_tax'];
            $display_tax_price = $meta_data['display_tax_price'];

            $item_price = $item_meta['price'] + $shipping_cost;

            $meta_data['total_tax'] = $item_tax + $shipping_tax;
            $meta_data['vat_price'] = $item_tax + $shipping_tax;

            $meta_data['cart_total'] = $item_price;
            $meta_data['order_total'] = $item_price;

            $sub_arr = [
                'schedule_trial_end' => 0,
                'schedule_next_payment' => $meta_data['paid_date'],
                'schedule_cancelled' => 0,
                'schedule_start' => 0,
                'schedule_end' => 0,
                'schedule_payment_retry' => 0,
            ];

            $meta_data = array_merge($meta_data, $sub_arr);



            $subscription_id = $master->insertData('tbl_orders',$sub_data);

            foreach($meta_data as $key=>$value) {
               $master->insertData('tbl_order_meta',['order_id'=>$subscription_id,'meta_key'=>$key,'meta_value'=>$value]);
            }

            if(!empty($item)) {
                $item_data = [
                    'order_id' => $subscription_id,
                    'product_name' => $item['product_name'],
                    'item_type' => $item['item_type'],
                ];

                $item_id = $master->insertData('tbl_order_items',$item_data);

                foreach($item_meta as $key=>$value) {
                    if(is_array($value)) {
                        $value = json_encode($value);
                    }
                    $master->insertData('tbl_order_item_meta',['item_id'=>$item_id,'meta_key'=>$key,'meta_value'=>$value]);
                }
            }

        }

        return $subscription_id;
    }

    public function start($order_id,$user_id=0) {

        $orderModel = $this->orderModel;
        $userModel = model('UserModel');

        $where = '';
        if($user_id) {
            $where .= ' AND o.customer_user="'.$user_id.'"';
        }

        $order = $orderModel->get_order_by_id($order_id, $where);

        $product_name = !empty($order_items[0]['product_name']) ? $order_items[0]['product_name'] : '';
        $order_price = !empty($order_items[0]['item_meta']['display_price_html']) ? $order_items[0]['item_meta']['display_price_html'] : '';
        $product_price = !empty($order_items[0]['item_meta']['product_price']) ? $order_items[0]['item_meta']['product_price'] : '';

        if(empty($order)) {
            return false;
        }

        if(empty($order)) {
            alert_message('Action failed: Invalid Order');
            return redirect()->back();
        }

        $order_items = $order['order_items'];
        $user = $userModel->get_user($order['customer_user']);

        //pr($order_items);

        if($order['status'] === "active") {
            alert_message('Action failed: Subscription is already active');
            return redirect()->back();
        }

        foreach($order_items as $item) {
            $meta_data = $item['item_meta'];

            if(!empty($meta_data['subscription'])) {
                $subscription = json_decode($meta_data['subscription'],true);
                if(!empty($subscription['enable'])) {
                    $interval = $subscription['interval'] == 0 ? 1 : $subscription['interval'];

                    $start_date = date(env('datetime_db'));
                    if($subscription['expire']) {
                        $end_date = date('Y-m-d h:i:s',strtotime('+'.$subscription['expire']));
                    }
                    else {
                        $end_date = 0;
                    }

                    $next_payment_time = date('Y-m-d h:i:s',strtotime('+'.$interval.' '.$subscription['period']));
                    $new_meta = [
                        'schedule_start' => $start_date,
                        'schedule_end' => $end_date,
                        'schedule_next_payment' => $next_payment_time
                    ];

                    $orderModel->update_order_meta($order_id,$new_meta);
                }
            }
        }

        $orderModel->change_order_status($order_id,'active');

        //Add note
        $orderModel->add_note($order_id,'Subscription #'.$order_id.' is activated');

        $display_name = !empty($user->display_name) ? $user->display_name : $user->fname.' '.$user->lname;

        $this->notification->create('Subscription#'.$order_id.' has been started','subscription/'.$order_id,'shop_subscription',$user->user_id);

        $this->subscription_start_email($order_id);



        notice_success('Subscription activated successfully');

    }

    public function suspend($order_id,$user_id=0,$mail_to_customer=true,$order_note='') {

        $orderModel = $this->orderModel;
        $userModel = model('UserModel');

        $where = '';
        if($user_id) {
            $where .= ' AND o.customer_user="'.$user_id.'"';
        }

        $order = $orderModel->get_order_by_id($order_id, $where);

        $order_items = $order['order_items'];

        $product_name = !empty($order_items[0]['product_name']) ? $order_items[0]['product_name'] : '';
        $order_price = !empty($order_items[0]['item_meta']['display_price_html']) ? $order_items[0]['item_meta']['display_price_html'] : '';

        if(empty($order)) {
            return false;
        }

        if(empty($order)) {
            alert_message('Action failed: Invalid Order');
            return redirect()->back();
        }

        if($order['status'] === "on-hold") {
            alert_message('Action failed: Subscription is already paused');
            return redirect()->back();
        }

        $curr_date = date(env('datetime_db'));

        $new_meta = [
            'schedule_end' => NULL,
            'schedule_hold_date' => $curr_date
        ];

        $orderModel->update_order_meta($order_id,$new_meta);

        $user = $userModel->get_user($order['customer_user']);

        $orderModel->change_order_status($order_id,'on-hold');

        $display_name = !empty($user->display_name) ? $user->display_name : $user->fname.' '.$user->lname;

        $this->notification->create('Subscription#'.$order_id.' is suspended','subscription/'.$order_id,'shop_subscription',$user->user_id);

        if($mail_to_customer) {

            $mailbody = $this->mail->get_parsed_content('subscription_suspended', [
                'display_name'=>$display_name,
                'order_id' => _order_number($order_id),
                'product_name' => $product_name,
                'order_price' => $order_price,
                'order_date' => date('F d, Y', strtotime($order['order_date'])),
                'order_receipt' => view('checkout/order_receipt',['order'=>$order,'hide_variation'=>1,'subtotal_width'=>'100%','show_link'=>false])
            ]);

            $email_to = $user->email;

            $this->mail->send_email($email_to,$mailbody,[
                'mail_type' => 'order',
                'post_id' => $order_id
            ]);
        }

        notice_success('Subscription has been suspended');
    }

    public function resume($order_id=0,$user_id=0,$mail_to_customer=true) {

        $orderModel = $this->orderModel;
        $userModel = model('UserModel');

        $where = '';
        if($user_id) {
            $where .= ' AND o.customer_user="'.$user_id.'"';
        }

        $order = $orderModel->get_order_by_id($order_id,$where);

        if(empty($order)) {
            return false;
        }

        if($order['status'] === "active") {
            alert_message('Action failed: Subscription is already active');
            return redirect()->back();
        }

        if(!empty($order)) {
            $user = $userModel->get_user($order['customer_user']);
            $order_meta = $orderModel->order_meta($order_id);

            $order_items = $order['order_items'];

            $product_name = !empty($order_items[0]['product_name']) ? $order_items[0]['product_name'] : '';
            $order_price = !empty($order_items[0]['item_meta']['display_price_html']) ? $order_items[0]['item_meta']['display_price_html'] : '';

            if(empty($order_meta['schedule_hold_date'])) {
                alert_message('Action failed: Schedule hold date not found');
                return redirect()->back();
            }

            $order_items = $order['order_items'];

            $suspend_date_ = new \DateTime($order_meta['schedule_hold_date']);
            $start_date = $order_meta['schedule_start'];

            foreach($order_items as $item) {

                $item_meta = $item['item_meta'];

                if(!empty($item_meta['subscription'])) {
                    $subscription = json_decode($item_meta['subscription'],true);

                    if(!empty($subscription['enable'])) {
                        $interval = $subscription['interval']+1;
                        $duration = $subscription['expire'];
                        $curr_date = date(env('datetime_db'));

                        $curr_time_ = new \DateTime();

                        $suspend_date_diff = $curr_time_->diff($suspend_date_);

                        $diff_y = $suspend_date_diff->y;
                        $diff_m = $suspend_date_diff->m;
                        $diff_d = $suspend_date_diff->d;
                        $diff_h = $suspend_date_diff->h;
                        $diff_i = $suspend_date_diff->i;
                        $diff_s = $suspend_date_diff->s;

                        $time_inc = " +$diff_y years +$diff_m months +$diff_d days +$diff_h hours +$diff_i minutes +$diff_s seconds";

                        if($duration) {
                            $old_end_date = date(env('datetime_db'), strtotime($start_date . ' +' . $duration));

                            $new_end_date = date(env('datetime_db'), strtotime($old_end_date . $time_inc));
                        }else {
                            $new_end_date = NULL;
                        }


                        $next_payment_time = date('Y-m-d h:i:s',strtotime('+'.$interval.' '.$subscription['period']));

                        $new_meta = [
                            'schedule_end' => $new_end_date,
                            'schedule_next_payment' => $next_payment_time,
                            'schedule_hold_date' => NULL,
                            'schedule_resume_date' => $curr_date
                        ];

                        $orderModel->update_order_meta($order_id, $new_meta);
                    }
                }
            }

            $orderModel->change_order_status($order_id,'active');

            $display_name = !empty($user->display_name) ? $user->display_name : $user->fname.' '.$user->lname;

            $this->notification->create('Subscription#'.$order_id.' is resumed','subscription/'.$order_id,'shop_subscription',$user->user_id);

            if($mail_to_customer) {

                $mailbody = $this->mail->get_parsed_content('subscription_resume', [
                    'display_name'=>$display_name,
                    'order_id' => _order_number($order_id),
                    'product_name' => $product_name,
                    'order_price' => $order_price,
                    'order_date' => date('F d, Y', strtotime($order['order_date'])),
                    'order_receipt' => view('checkout/order_receipt',['order'=>$order,'hide_variation'=>1,'subtotal_width'=>'100%','show_link'=>false])
                ]);

                $email_to = $user->email;

                $this->mail->send_email($email_to,$mailbody,[
                    'mail_type' => 'order',
                    'post_id' => $order_id
                ]);
            }
        }


        notice_success('Subscription resumed successfully');

    }

    public function cancel($order_id=0,$user_id=0,$mail_to_customer=true) {

        $orderModel = $this->orderModel;
        $userModel = model('UserModel');

        $where = '';
        if($user_id) {
            $where .= ' AND o.customer_user="'.$user_id.'"';
        }

        $order = $orderModel->get_order_by_id($order_id, $where);


        if(empty($order)) {
            return false;
        }

        if($order['status'] === "cancelled") {
            alert_message('Action failed: Subscription is already cancelled');
            return redirect()->back();
        }

        if(!empty($order)) {
            $user = $userModel->get_user($order['customer_user']);

            $curr_date = date(env('datetime_db'));

            $order_items = $order['order_items'];

            $product_name = !empty($order_items[0]['product_name']) ? $order_items[0]['product_name'] : '';
            $order_price = !empty($order_items[0]['item_meta']['display_price_html']) ? $order_items[0]['item_meta']['display_price_html'] : '';

            $new_meta = [
                'schedule_end' => NULL,
                'schedule_hold_date' => NULL,
                'schedule_resume_date' => NULL,
                'schedule_cancelled' => $curr_date,
            ];

            $orderModel->update_order_meta($order_id,$new_meta);

            $orderModel->change_order_status($order_id,'cancelled');

            $display_name = !empty($user->display_name) ? $user->display_name : $user->fname.' '.$user->lname;

            $this->notification->create('Subscription#'.$order_id.' has been cancelled.','subscription/'.$order_id,'shop_subscription',$user->user_id);

            if($mail_to_customer) {

                $mailbody = $this->mail->get_parsed_content('subscription_cancelled', [
                    'display_name'=>$display_name,
                    'order_id' => _order_number($order_id),
                    'product_name' => $product_name,
                    'order_price' => $order_price,
                    'order_date' => date('F d, Y', strtotime($order['order_date'])),
                    'order_receipt' => view('checkout/order_receipt',['order'=>$order,'hide_variation'=>1,'subtotal_width'=>'100%','show_link'=>false])
                ]);

                $email_to = $user->email;

                $this->mail->send_email($email_to,$mailbody,[
                    'mail_type' => 'order',
                    'post_id' => $order_id
                ]);
            }
        }


        notice_success('Subscription cancelled successfully');
    }

    public function sub_orders($order_id=0,$include_meta=true,$include_items=true, $select='o.*') {
        $output = [];
        if($order_id) {
            while($order_id) {
                $order = $this->orderModel->get_order_by_id($order_id,' AND o.order_type="shop_subscription"',$include_meta,$include_items, $select);
                if(!empty($order)) {
                    $output[] = $order;
                    $order_id = $order['parent_id'];
                }else {
                    $order_id = false;
                }
            }
        }
        return $output;
    }

    public function has_next_schedule($order_id) {
        $orderModel = $this->orderModel;
        $order = $orderModel->get_order_by_id($order_id, 'AND o.status="active"',true,false);
        if($order && $order['status'] === 'active') {
            $meta = $order['order_meta'];
            $next_payment = new \DateTime($meta['schedule_next_payment']);

            $schedule_end = $meta['schedule_end'];
            $date = new \DateTime();

            $add_next_schedule = $date->diff($next_payment);

            if($schedule_end && $date->format('Y-m-d') > $schedule_end) {
                return ['success' => 0, 'time'=>''];
            }

            else if(!$add_next_schedule->invert || ($add_next_schedule->invert)) {
                return ['success' => 1, 'time'=>$add_next_schedule];
            }

        }
        return ['success' => 0, 'time'=>''];
    }


    public function renew($order_id=0, $sendmail=true) {
        $orderModel = $this->orderModel;
        $userModel = model('UserModel');
        $masterModel = model('MasterModel');

        $has_next = $this->has_next_schedule($order_id);

        if($has_next['success']) {

            $sub_item_query = "(SELECT meta.meta_value FROM tbl_order_items AS item JOIN tbl_order_item_meta AS meta ON meta.item_id=item.order_item_id WHERE item.order_id=o.order_id AND item.item_type='line_item' AND meta.meta_key='subscription' LIMIT 1)";

            $order = $masterModel->query("SELECT o.order_id, o.parent_id, o.customer_user, item.order_item_id, o.order_date, $sub_item_query AS subscription FROM tbl_orders AS o JOIN tbl_order_items AS item ON item.order_id=o.order_id WHERE o.order_id='$order_id' AND item.item_type='line_item'",true,true);

            if(empty($order)) {
                return ['success'=>0,'message'=>'Invalid order'];
            }

            $_order_meta = $orderModel->order_meta($order_id);

            $order_items = $orderModel->order_items($order_id);

            $order_items = array_values($order_items);

            $item_meta = $order_items[0]['item_meta'];

            $order_total = $_order_meta['order_total'];

            $user_id = $order['customer_user'];

            $noteText = "Subscription#$order_id renewal payment";

            $payment_method = $_order_meta['payment_method'];

            $user = $userModel->get_user($user_id);
            $user_meta = $userModel->get_user_meta($user_id);

            if(empty($user_meta['squareup_customer_id'])) {
                return ['success'=>0,'Customer error'];
            }

            $charge = $this->chargeSubscription($_order_meta['squareup_card_id'],$user_meta['squareup_customer_id'], $order_total, $payment_method, $noteText);

            $product_name = !empty($order_items[0]['product_name']) ? $order_items[0]['product_name'] : '';
            $order_price = !empty($order_items[0]['item_meta']['display_price_html']) ? $order_items[0]['item_meta']['display_price_html'] : '';

            $email = $_order_meta['billing_email'];

            if(isset($charge['success']) && $charge['success'] == 0) {
                $order_ = $orderModel->get_order_by_id($order['order_id']);

                $mailbody = $this->mail->get_parsed_content('subscription_renew_failed', [
                    'display_name'=>$user->display_name,
                    'order_id' => _order_number($order_id),
                    'product_name' => $product_name,
                    'order_price' => $order_price,
                    'order_date' => date('F d, Y', strtotime($order_['order_date'])),
                    'order_receipt' => view('checkout/order_receipt',['order'=>$order_,'hide_variation'=>1,'subtotal_width'=>'100%','show_link'=>false])
                ]);

                $orderModel->change_order_status($order_id,'renew-failed');

                $orderModel->add_note($order_id,"Subscription renewal failed");

                if(!empty($charge['message'][0]['detail'])) {
                    $failed_reason = $orderModel->order_meta($order_id,'renewal_failed_reason');
                    if($failed_reason) {
                        $orderModel->update_order_meta($order_id,['renewal_failed_reason'=>$charge['message'][0]['detail']]);
                    }else {
                        $orderModel->add_order_meta($order_id,'renewal_failed_reason',$charge['message'][0]['detail']);
                    }
                }

                $this->mail->send_email($email,$mailbody,[
                    'mail_type' => 'order',
                    'post_id' => $order_id
                ]);
            }
            else
            {
                    $new_order_id = $this->init([
                        'order_id' => $order['order_id'],
                        'item_id' => $order['order_item_id']
                    ]);

                    $charge_ = json_decode($charge, true);
                    if(!empty($charge_)) {
                        $txn_id = $charge_['payment']['id'];
                        $orderModel->add_order_meta($new_order_id,'transaction_id',$txn_id);
                        $orderModel->add_order_meta($new_order_id,'squareup_card_id',$_order_meta['squareup_card_id']);
                        $orderModel->add_order_meta($new_order_id,'squareup_body',json_encode($charge));
                    }

                    $order_meta = $orderModel->order_meta($new_order_id);

                    $masterModel->query("UPDATE tbl_orders SET status='completed' WHERE order_id=".$order_id."");

                    $new_order = $orderModel->get_order_by_id($new_order_id);

                    $order_id = $new_order['order_id'];

                    if(!empty($item_meta['subscription'])) {
                        $subscription_json = json_decode($item_meta['subscription'],true);
                        if($subscription_json['enable']) {
                            $sub_interval = $subscription_json['interval'] == 0 ? 1 : $subscription_json['interval'];
                            $sub_period = $subscription_json['period'];
                            $meta_next_payment = $_order_meta['schedule_next_payment'];
                            $next_payment = date('Y-m-d', strtotime($meta_next_payment.' +'.$sub_interval.' '.$sub_period));

                            if($_order_meta['schedule_end'] && $next_payment >= $_order_meta['schedule_end']) {
                                $next_payment = 0;
                            }
                            $sub_arr = [
                                'schedule_trial_end' => 0,
                                'schedule_next_payment' => $next_payment,
                                'schedule_cancelled' => $_order_meta['schedule_cancelled'],
                                'schedule_start' => $_order_meta['schedule_start'],
                                'schedule_end' => !empty($_order_meta['schedule_end']) ? $_order_meta['schedule_end'] : 0,
                                'schedule_payment_retry' => $_order_meta['schedule_payment_retry'],
                            ];

                            $orderModel->update_order_meta($new_order_id,$sub_arr);
                        }
                    }

                    if(empty($order_meta['order_total'])) {
                        return ['success'=>1,'message'=>'Action failed: Something went wrong'];
                    }

                    $orderModel->add_note($order_id,$noteText);

                    $display_name = !empty($user->display_name) ? $user->display_name : $user->fname.' '.$user->lname;

                    $this->notification->create('Subscription#'.$order_id.' is renewed','subscription/'.$order_id,'shop_subscription',$user->user_id);

                    if($sendmail) {
                        $this->subscription_renew_email($order_id);
                    }

                    $orderModel->change_order_status($order_id,'active','Subscription renew order');

                    return ['success'=>1,'message'=>'Subscription renewed successfully'];
            }


        }else {
            return ['success'=>0,'message'=>'Next payment is not available'];
        }
    }

    public function subscription_start_email($order_id=0) {
        $orderModel = $this->orderModel;
        $userModel = model('UserModel');

        $order = $orderModel->get_order_by_id($order_id);
        $customer = $userModel->get_user($order['customer_user']);
        $display_name = display_name($customer);

        $email_to = $customer->email;

        //Send Mail
        $mailbody = $this->mail->get_parsed_content('subscription_started', [
            'display_name'=>$display_name,
            'order_id' => _order_number($order_id),
            'order_date' => date('F d, Y', strtotime($order['order_date'])),
            'order_receipt' => view('checkout/order_receipt',['order'=>$order,'hide_variation'=>1,'subtotal_width'=>'100%','show_link'=>false])
        ]);

        $this->mail->send_email($email_to,$mailbody,[
            'mail_type' => 'order',
            'post_id' => $order_id
        ]);
    }

    public function subscription_renew_email($order_id=0) {
        $orderModel = $this->orderModel;
        $userModel = model('UserModel');

        $order = $orderModel->get_order_by_id($order_id);
        $customer = $userModel->get_user($order['customer_user']);
        $display_name = display_name($customer);

        $email = $customer->email;

        $mailbody = $this->mail->get_parsed_content('subscription_renewed', [
            'display_name'=> $display_name,
            'order_id' => _order_number($order_id),
            'order_date' => date('F d, Y', strtotime($order['order_date'])),
            'order_receipt' => view('checkout/order_receipt',['order'=>$order,'hide_variation'=>1,'subtotal_width'=>'100%','show_link'=>false])
        ]);
        $this->mail->send_email($email,$mailbody,[
            'mail_type' => 'order',
            'post_id' => $order_id
        ]);
    }

    public function expire($order_id=0, $sendmail=true) {
        $orderModel = $this->orderModel;
        $userModel = model('UserModel');
        $masterModel = model('MasterModel');

        $order = $orderModel->get_order_by_id($order_id);

        if(empty($order)) {
            return ['success'=>0,'message'=>'Invalid order'];
        }

        if($masterModel->query("UPDATE tbl_orders SET status='expired' WHERE order_id='$order_id' AND order_type='shop_subscription'")) {

            $orderModel->update_order_meta($order_id,['schedule_next_payment'=>0]);
            $orderModel->add_order_meta($order_id,'subscription_end_date',date("Y-m-d h:i:s"));

            if($sendmail) {
                $user_id = $order['customer_user'];
                $email = $order['order_meta']['billing_email'];
                $user = $userModel->get_user($user_id,'display_name');

                $mail_config = [
                    'display_name'=>$user->display_name,
                    'order_id' => _order_number($order_id),
                    'order_receipt' => view('checkout/order_receipt',['order'=>$order])
                ];

                if(!empty($order['order_items'][0])) {
                    $order_item = $order['order_items'][0];
                    $item_name = !empty($order_item['name']) ? $order_item['name'] : '';
                    $mail_config['item_name'] = $item_name;
                }

                $mailbody = $this->mail->get_parsed_content('subscription_expired', $mail_config);

                $this->mail->send_email($email,$mailbody,[
                    'mail_type' => 'order',
                    'post_id' => $order_id,
                    'order_date' => date('F d, Y', strtotime($order['order_date'])),
                    'order_receipt' => view('checkout/order_receipt',['order'=>$order,'hide_variation'=>1,'subtotal_width'=>'100%','show_link'=>false])
                ]);
            }

            return ['success'=>1,'message'=>'Subscription is expired'];
        }else {
            return ['success'=>0,'message'=>'Could not initiate request'];
        }
    }

    public function chargeSubscription($card_id='', $customer_id=0, $amount='', $payment_method='', $chargeNote='') {
        if($card_id) {
            $squareup = model('SquareupModel');

            if($payment_method === "squareup") {
                $customer_card = $card_id;

                $idempotencyKey = uniqid();

                $payment = $squareup->chargeCard($idempotencyKey, $customer_id, $customer_card, $amount,$chargeNote);

                if($payment->isSuccess()) {
                    return $payment->getBody();
                }else {
                    return $squareup->sq_errors($payment->getErrors());
                }
            }
        }
    }

    public function subscription_duration($duration_id='') {
        $get_setting = get_setting('subscriptionForm');
        if($get_setting) {
            $get_setting = json_decode($get_setting,true);
            return !empty($get_setting['subscription_duration'][$duration_id]) ? $get_setting['subscription_duration'][$duration_id] : '';
        }
    }

    public function get_all($where=[],$fields='*') {
        $master = $this->masterModel;
        $where['order_type'] = 'shop_subscription';
        return $master->getRows('tbl_orders',$where,$fields);
    }

    public function get_active($where='') {
        $master = $this->masterModel;
        $curr_day = date('Y-m-d');
        $tb_meta_exp = "(SELECT meta.meta_value FROM tbl_order_meta as meta WHERE meta.order_id=o.order_id AND meta_key='schedule_end')";
        $tb_meta_start = "(SELECT meta.meta_value FROM tbl_order_meta as meta WHERE meta.order_id=o.order_id AND meta_key='schedule_start')";
        $tb_meta_next = "(SELECT meta.meta_value FROM tbl_order_meta as meta WHERE meta.order_id=o.order_id AND meta_key='schedule_next_payment')";

        $query = "SELECT *, $tb_meta_start AS schedule_start, $tb_meta_next AS schedule_next_payment, $tb_meta_exp AS schedule_end FROM tbl_orders AS o JOIN tbl_order_meta AS meta ON meta.order_id=o.order_id AND $tb_meta_exp > '$curr_day' AND o.status='active' $where GROUP BY o.order_id";

        return $master->query($query);
    }

    public function get_expired($where='') {
        $master = $this->masterModel;
        $curr_day = date(env('CURRENT_DATE'));
        $tb_meta_exp = "(SELECT meta.meta_value FROM tbl_order_meta as meta WHERE meta.order_id=o.order_id AND meta_key='schedule_end')";
        $tb_meta_start = "(SELECT meta.meta_value FROM tbl_order_meta as meta WHERE meta.order_id=o.order_id AND meta_key='schedule_start')";
        $tb_meta_next = "(SELECT meta.meta_value FROM tbl_order_meta as meta WHERE meta.order_id=o.order_id AND meta_key='schedule_next_payment')";

        $query = "SELECT *, $tb_meta_start AS schedule_start, $tb_meta_next AS schedule_next_payment, $tb_meta_exp AS schedule_end FROM tbl_orders AS o JOIN tbl_order_meta AS meta ON meta.order_id=o.order_id AND $tb_meta_exp < '$curr_day' AND $tb_meta_exp IS NOT NULL AND $tb_meta_exp > 0 $where GROUP BY o.order_id";


        return $master->query($query);
    }

    public function current_day_renewals() {
        $master = $this->masterModel;
        $curr_date = date(env('CURRENT_DATE'));

       // $sch_end_query = "(SELECT meta_value FROM tbl_order_meta WHERE order_id=o.order_id AND meta_key='schedule_end' LIMIT 1)";
        $sub_item_query = "(SELECT meta.meta_value FROM tbl_order_items AS item JOIN tbl_order_item_meta AS meta ON meta.item_id=item.order_item_id WHERE item.order_id=o.order_id AND item.item_type='line_item' AND meta.meta_key='subscription' LIMIT 1)";

        $sub_item_id_query = "(SELECT meta.item_id FROM tbl_order_items AS item JOIN tbl_order_item_meta AS meta ON meta.item_id=item.order_item_id WHERE item.order_id=o.order_id AND item.item_type='line_item' AND meta.meta_key='subscription' LIMIT 1)";

        $date_diff = "DATEDIFF(meta.meta_value, '$curr_date')-1";

        $sql = "SELECT o.order_id, o.parent_id, meta.*, $date_diff AS payment_date_diff, $sub_item_query AS item_subscription, $sub_item_id_query AS item_id FROM tbl_orders AS o JOIN tbl_order_meta AS meta ON meta.order_id=o.order_id WHERE meta.meta_value <= '$curr_date' AND meta.meta_key='schedule_next_payment' AND meta.meta_value !=0 AND meta.meta_value IS NOT NULL AND $date_diff <= 0 AND o.status='active'";

        $query = $master->query($sql);

        return $query;
    }

    public function get_expiring_subscriptions() {
        $master = $this->orderModel;

        $expire_reminder_days = get_setting('subscriptionReminderDay');
        $curr_date = date('Y-m-d ').'00:00:00';

        return $master->query("SELECT * FROM `tbl_orders` AS o JOIN tbl_order_meta AS meta ON meta.order_id=o.order_id WHERE o.status='active' AND '$curr_date' >= DATE_SUB(meta.schedule_next_payment,INTERVAL $expire_reminder_days DAY) AND meta.schedule_end > '$curr_date' GROUP BY meta.ometa_id ORDER BY meta.ometa_id DESC");
    }

    public function subscription_renewal_coming($subscription_id=0) {
        $orderModel = $this->orderModel;

        $expire_reminder_days = get_setting('subscriptionReminderDay');
        $curr_date = date('Y-m-d ').'00:00:00';

        $order = $orderModel->get_order_by_id($subscription_id,' AND o.status="active"',true,false);
        $result = false;
        if(!empty($order)) {
            $order_meta = $order['order_meta'];

            if(!empty_date($order_meta['schedule_next_payment'])) {
                $next_date = date('Y-m-d H:i:s',strtotime($order_meta['schedule_next_payment'].' -'.$expire_reminder_days.' DAYS'));

                if($curr_date >= $next_date) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    public function subscription_is_expiring($subscription_id=0) {
        $orderModel = $this->orderModel;

        $expire_reminder_days = get_setting('subscriptionReminderDay');
        $curr_date = date('Y-m-d ').'00:00:00';

        $order = $orderModel->get_order_by_id($subscription_id,' AND o.status="active"',true,false);
        $result = false;
        if(!empty($order)) {
            $order_meta = $order['order_meta'];
            if(!empty_date($order_meta['schedule_end'])) {
                $end_date = $order_meta['schedule_end'];
                $schedule_end = date('Y-m-d H:i:s',strtotime($order_meta['schedule_end'].' -'.$expire_reminder_days.' DAYS'));
                if($curr_date >= $schedule_end && $end_date > $curr_date) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    public function subscription_is_expired($subscription_id=0) {
        $orderModel = $this->orderModel;

        $curr_date = date('Y-m-d ').'00:00:00';

        $order = $orderModel->get_order_by_id($subscription_id,' AND o.status="active"',true,false);
        $result = false;
        if(!empty($order)) {
            $order_meta = $order['order_meta'];
            if(!empty_date($order_meta['schedule_end'])) {
                $end_date = $order_meta['schedule_end'];
                if($end_date > $curr_date) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    public function get_ending_subscriptions() {
        $master = $this->masterModel;
        $date = date("Y-m-d");

        $sql = "SELECT o.*, meta.* FROM tbl_orders AS o JOIN tbl_order_meta AS meta ON meta.order_id=o.order_id WHERE meta.meta_value <= '$date' AND meta.meta_key='schedule_end' AND meta.meta_value !=0 AND meta.meta_value IS NOT NULL";

        $query = $master->query($sql);

        pr($query);
    }

}

