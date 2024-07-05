<?php

namespace App\Models;
use Braintree\Gateway;
use Cassandra\Date;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class SubscriptionModel extends Model {

    private $mail, $orderModel, $masterModel;

    public function __construct()
    {
        $this->mail = new MailModel();
        $this->orderModel = new OrderModel();
        $this->masterModel = new MasterModel();
    }


    public function init($data=[]) {
        $subscription_id = 0;

        if(!empty($data['order_id'])) {

            $master = $this->masterModel;
            $orderModel = $this->orderModel;

            $order = $orderModel->get_order_by_id($data['order_id']);
            $item = $orderModel->order_item($data['order_id'], $data['item_id']);
            $item_meta = $orderModel->order_item_meta($data['item_id']);

            $sub_data = [
                'order_title' => 'Subscription &ndash; '.date('F d, Y @ H:i A'),
                'status'=>'pending',
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

            $subscription_id = $master->insertData('tbl_orders',$sub_data);

            $meta_data = $order['order_meta'];

            $meta_data = array_merge($meta_data, [
                'schedule_trial_end' => 0,
                'schedule_next_payment' => 0,
                'schedule_cancelled' => 0,
                'schedule_start' => 0,
                'schedule_end' => 0,
                'schedule_payment_retry' => 0,
            ]);

            foreach($meta_data as $key=>$value) {
               $master->insertData('tbl_order_meta',['order_id'=>$subscription_id,'meta_key'=>$key,'meta_value'=>$value]);
            }

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
                    $end_date = date('Y-m-d h:i:s',strtotime('+'.$subscription['expire'].' '.$subscription['period']));
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

        //Send Mail
        $mailbody = $this->mail->get_parsed_content('subscription_started', [
            'display_name'=>$user->display_name,
            'subscription_id' => $order_id
        ]);

        $email_to = $user->email;

        $this->mail->send_email($email_to,$mailbody);

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

        if(empty($order)) {
            return false;
        }

        if(empty($order)) {
            alert_message('Action failed: Invalid Order');
            return redirect()->back();
        }

        if($order['status'] === "on-hold") {
            alert_message('Action failed: Subscription is already suspended');
            return redirect()->back();
        }

        $curr_date = date(env('datetime_db'));

        $new_meta = [
            'schedule_end' => NULL,
            'schedule_hold_date' => $curr_date
        ];

        $orderModel->update_order_meta($order_id,$new_meta);

        $orderModel->change_order_status($order_id,'on-hold');

        if($mail_to_customer) {
            $user = $userModel->get_user($order['customer_user']);

            $mailbody = $this->mail->get_parsed_content('subscription_suspended', [
                'display_name'=>$user->display_name,
                'subscription_id' => $order_id
            ]);

            $email_to = $user->email;

            $this->mail->send_email($email_to,$mailbody);
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
                        $duration = $subscription['expire'].' '.$subscription['period'];
                        $old_end_date = date(env('datetime_db'), strtotime($start_date . ' +' . $duration));
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

                        $new_end_date = date(env('datetime_db'), strtotime($old_end_date . $time_inc));

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

            if($mail_to_customer) {

                $mailbody = $this->mail->get_parsed_content('subscription_resume', [
                    'display_name'=>$user->display_name,
                    'subscription_id' => $order_id
                ]);

                $email_to = $user->email;

                $this->mail->send_email($email_to,$mailbody);
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

            $new_meta = [
                'schedule_end' => NULL,
                'schedule_hold_date' => NULL,
                'schedule_resume_date' => NULL,
                'schedule_cancelled' => $curr_date,
            ];

            $orderModel->update_order_meta($order_id,$new_meta);

            $orderModel->change_order_status($order_id,'cancelled');

            if($mail_to_customer) {

                $mailbody = $this->mail->get_parsed_content('subscription_cancelled', [
                    'display_name'=>$user->display_name,
                    'subscription_id' => $order_id
                ]);

                $email_to = $user->email;

                $this->mail->send_email($email_to,$mailbody);
            }
        }


        notice_success('Subscription cancelled successfully');
    }

    public function sub_orders($order_id=0,$include_meta=true,$include_items=true) {
        $order = $this->orderModel->get_order_by_id($order_id,'',true,false);

        $output = [];
        if(!empty($order)) {
            $order_meta = $order['order_meta'];

            if(!empty($order_meta)) {
                $sub_ids = !empty($order_meta['subscription_renewal_order_ids_cache']) ? unserialize($order_meta['subscription_renewal_order_ids_cache']) : [];
                if(!empty($sub_ids)) {
                    foreach($sub_ids as $oid) {
                        $order = $this->orderModel->get_order_by_id($oid,'',$include_meta,$include_items);
                        $output[$oid] = $order;
                    }
                }

                if(!empty($order_meta['subscription_renewal'])) {
                    $parent_id = $order_meta['subscription_renewal'];
                    $parent_order = $this->orderModel->get_order_by_id($parent_id,'',$include_meta,$include_items);
                    $order_meta = $parent_order['order_meta'];
                    $sub_ids = !empty($order_meta['subscription_renewal_order_ids_cache']) ? unserialize($order_meta['subscription_renewal_order_ids_cache']) : [];
                    if(!empty($sub_ids)) {
                        foreach($sub_ids as $oid) {
                            $order = $this->orderModel->get_order_by_id($oid,'',$include_meta,$include_items);
                            $output[$oid] = $order;
                        }
                    }
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



            if($date->format('Y-m-d') > $schedule_end) {
                return ['success' => 0, 'time'=>''];
            }
            else if(!$add_next_schedule->invert || ($add_next_schedule->invert && $date->format('Y-m-d') <= $schedule_end)) {
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

            $order = $masterModel->query("SELECT o.order_id, o.parent_id, item.order_item_id, $sub_item_query AS subscription FROM tbl_orders AS o JOIN tbl_order_items AS item ON item.order_id=o.parent_id WHERE o.order_id='$order_id' AND item.item_type='line_item'",true,true);

            if(empty($order)) {
                return ['success'=>0,'message'=>'Invalid order'];
            }

            $subscription = json_decode($order['subscription'],true);

            $masterModel->query("UPDATE tbl_orders SET status='completed' WHERE order_id=".$order_id."");

            $new_order_id = $this->init([
                'order_id' => $order['order_id'],
                'item_id' => $order['order_item_id']
            ]);

            $new_order = $orderModel->get_order_by_id($new_order_id);

            $order_id = $new_order['order_id'];

            $order_meta = $new_order['order_meta'];

            if(!empty($order_meta['schedule_next_payment'])) {
                $schedule_next_payment = $order_meta['schedule_next_payment'];
                $interval = $subscription['interval'] == 0 ? 1 : $subscription['interval'];
                $period = $subscription['period'];
                $expire = $subscription['expire'];

                $new_next_schedule = date('Y-m-d h:i:s',strtotime($schedule_next_payment." +$interval $period"));

                //Unset next schedule if next schedule is after expire date
                if($expire > $new_next_schedule) {
                    $new_next_schedule = 0;
                }
                $orderModel->update_order_meta($new_order_id,['schedule_next_payment'=>$new_next_schedule]);
            }

            if(empty($order_meta['order_total'])) {
                return ['success'=>1,'message'=>'Action failed: Something went wrong'];
            }

            $order_total = $order_meta['order_total'];
            $user_id = $new_order['customer_user'];
            $noteText = "Subscription#$order_id renewal payment";

            $charge = $this->chargeSubscription($user_id, $order_total, $order_meta['payment_method'],$noteText);

            if($charge) {
                $orderModel->add_note($order_id,$noteText);

                $user = $userModel->get_user($user_id);
                $email = $order_meta['billing_email'];

                if($sendmail) {
                    $mailbody = $this->mail->get_parsed_content('subscription_renewed', [
                        'display_name'=>$user->display_name,
                        'order_id' => $order_id,
                        'order_receipt' => view('checkout/order_receipt',['order'=>$new_order])
                    ]);
                    $this->mail->send_email($email,$mailbody);
                }


                $orderModel->change_order_status($order_id,'active','Subscription renew order');


                return ['success'=>1,'message'=>'Subscription renewed successfully'];
            }
        }else {
            return ['success'=>0,'message'=>'Next payment is not available'];
        }
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
                    'order_id' => $order_id,
                    'order_receipt' => view('checkout/order_receipt',['order'=>$order])
                ];

                if(!empty($order['order_items'][0])) {
                    $order_item = $order['order_items'][0];
                    $item_name = !empty($order_item['name']) ? $order_item['name'] : '';
                    $mail_config['item_name'] = $item_name;
                }

                $mailbody = $this->mail->get_parsed_content('subscription_expired', $mail_config);

                $this->mail->send_email($email,$mailbody);
            }

            return ['success'=>1,'message'=>'Subscription is expired'];
        }else {
            return ['success'=>0,'message'=>'Could not initiate request'];
        }
    }

    public function chargeSubscription($user_id='', $amount='', $payment_method='', $chargeNote='') {
        if($user_id) {
            $userModel = model('UserModel');
            $squareup = model('SquareupModel');

            $user_meta = $userModel->get_user_meta($user_id);
            if($payment_method === "squareup") {
                $customer_id = $user_meta['squareup_customer_id'];
                $customer_card = $user_meta['squareup_customer_card'];

                $idempotencyKey = uniqid();

                $payment = $squareup->chargeCard($idempotencyKey, $customer_id, $customer_card, $amount,$chargeNote);

                if($payment->isSuccess()) {
                    return $payment->getBody();
                }else {
                    $errors = $squareup->sq_errors($payment->getErrors());
                    echo json_encode($errors);
                    exit;
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
        $curr_day = date('Y-m-d');
        $tb_meta_exp = "(SELECT meta.meta_value FROM tbl_order_meta as meta WHERE meta.order_id=o.order_id AND meta_key='schedule_end')";
        $tb_meta_start = "(SELECT meta.meta_value FROM tbl_order_meta as meta WHERE meta.order_id=o.order_id AND meta_key='schedule_start')";
        $tb_meta_next = "(SELECT meta.meta_value FROM tbl_order_meta as meta WHERE meta.order_id=o.order_id AND meta_key='schedule_next_payment')";

        $query = "SELECT *, $tb_meta_start AS schedule_start, $tb_meta_next AS schedule_next_payment, $tb_meta_exp AS schedule_end FROM tbl_orders AS o JOIN tbl_order_meta AS meta ON meta.order_id=o.order_id AND $tb_meta_exp < '$curr_day' $where GROUP BY o.order_id";

        return $master->query($query);
    }

    public function current_day_renewals() {
        $master = $this->masterModel;
        $curr_date = date("Y-m-d");

        $sch_end_query = "(SELECT meta_value FROM tbl_order_meta WHERE order_id=o.order_id AND meta_key='schedule_end' LIMIT 1)";
        $sub_item_query = "(SELECT meta.meta_value FROM tbl_order_items AS item JOIN tbl_order_item_meta AS meta ON meta.item_id=item.order_item_id WHERE item.order_id=o.order_id AND item.item_type='line_item' AND meta.meta_key='subscription' LIMIT 1)";
        $sub_item_id_query = "(SELECT meta.item_id FROM tbl_order_items AS item JOIN tbl_order_item_meta AS meta ON meta.item_id=item.order_item_id WHERE item.order_id=o.order_id AND item.item_type='line_item' AND meta.meta_key='subscription' LIMIT 1)";

        $date_diff = "DATEDIFF(meta.meta_value, '$curr_date')-1";

        $sql = "SELECT o.order_id, o.parent_id, meta.*, $sch_end_query AS schedule_end, $date_diff AS payment_date_diff, $sub_item_query AS item_subscription, $sub_item_id_query AS item_id FROM tbl_orders AS o JOIN tbl_order_meta AS meta ON meta.order_id=o.order_id WHERE meta.meta_value <= '$curr_date' AND meta.meta_key='schedule_next_payment' AND meta.meta_value !=0 AND meta.meta_value IS NOT NULL AND '$curr_date' <= $sch_end_query AND $date_diff <= 0 AND o.status='active'";

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

