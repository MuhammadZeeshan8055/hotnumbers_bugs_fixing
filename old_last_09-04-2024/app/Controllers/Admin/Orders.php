<?php

namespace App\Controllers\Admin;

use App\Controllers\MyController;

use App\Models\MasterModel;
use App\Models\OrderModel;
use Dompdf\Dompdf as Dompdf;
use Dompdf\Options;
use function Aws\default_user_agent;


class Orders extends MyController
{

    protected $uri;
    protected $table = "tbl_orders";


    public function __construct()
    {
        parent::__construct();
        $this->uri = service('uri');
        $this->master = new MasterModel();
        $this->data['page'] = "orders";
    }

    public function index()
    {

        $userModel = model('UserModel');

        if($this->request->getGet('get_data')) {

            $payment_method_field = "(SELECT meta_value FROM tbl_order_meta WHERE order_id=o.order_id AND meta_key='payment_method' LIMIT 1)";
            $order_total_field = "(SELECT meta_value FROM tbl_order_meta WHERE order_id=o.order_id AND meta_key='order_total' LIMIT 1)";
            $first_name_field = "(SELECT meta_value FROM tbl_order_meta WHERE order_id=o.order_id AND meta_key='billing_first_name' LIMIT 1)";
            $shipping_addr_field = "(SELECT meta_value FROM tbl_order_meta WHERE order_id=o.order_id AND meta_key='shipping_address_index' LIMIT 1)";
            $order_shipping_title = "(SELECT meta_value FROM tbl_order_meta WHERE order_id=o.order_id AND meta_key='order_shipping_title' LIMIT 1)";
            $username_field = "(SELECT username FROM tbl_users WHERE user_id=o.customer_user LIMIT 1)";
            $role_field = "(SELECT roles.name FROM tbl_users AS user JOIN tbl_user_roles AS roles ON roles.id=user.role WHERE user.user_id=o.customer_user LIMIT 1)";

            $rows = [
                'o.order_id',
                'o.order_title',
                'o.order_date',
                'o.payment_method',
                'o.status',
                ''=>['searchable'=>0],
                'o.customer_user',
                'o.shipping_address',
                "$username_field AS customer_username",
                "$payment_method_field AS payment_method",
                "$order_total_field AS order_total",
                "$first_name_field AS billing_first_name",
                "$shipping_addr_field AS shipping_address_index",
                "$order_shipping_title AS order_shipping_title",
                "$role_field AS user_role",
            ];

            $sort_cols = [
                '',
                'o.order_id',
                'o.order_date',
                'o.status',
                $username_field,
                $role_field,
                'o.shipping_address',
                'o.payment_method',
                $order_shipping_title,
                 $order_total_field
            ];

            $where = " AND item.item_type='line_item'";

            if(!empty($_GET['status'])) {
                $where .= " AND o.status='".$_GET['status']."'";
            }

            if(!empty($_GET['date'])) {
                $where .= " AND DATE_FORMAT(o.order_date,'%m-%Y')='".$_GET['date']."'";
            }

            if(!empty($_GET['role'])) {
                $where .= " AND $role_field='".$_GET['role']."'";
            }

            if(!empty($_GET['customer'])) {
                $where .= " AND o.customer_user='".$_GET['customer']."'";
            }

            $output = datatable_query("tbl_orders AS o JOIN tbl_order_meta AS meta ON o.order_id=meta.order_id JOIN tbl_order_items AS item ON item.order_id=o.order_id",$rows,$sort_cols,"GROUP BY o.order_id",$where);

            $records = $output['records'];


            unset($output['records']);

            foreach($records as $i=>$row) {

                $metas = $this->master->getRows('tbl_order_meta',['order_id'=>$row['order_id']]);

                $price = 0;

                $customer_name = [];

                foreach($metas AS $meta) {

                    if($meta->meta_key === "order_total") {
                        $price += $meta->meta_value;
                    }
//                    if($meta->meta_key === "order_shipping_tax") {
//                        $price += floatval($meta->meta_value);
//                    }
                    if($meta->meta_key === "order_shipping") {
                        //$price += floatval($meta->meta_value);
                    }
                    if($meta->meta_key === "order_tax") {
                        //$price += floatval($meta->meta_value);
                    }
                    if($meta->meta_key === "cart_discount") {
                        //$price -= floatval($meta->meta_value);
                    }

                    if($meta->meta_key === "billing_first_name" || $meta->meta_key === "billing_first_name") {
                        $customer_name[] = $meta->meta_value;
                    }
                }

                $shipping_address = json_decode($row['shipping_address'],true);

                $shipping_address = [
                    @$shipping_address['shipping_first_name'].' '.@$shipping_address['shipping_last_name'],
                    @$shipping_address['shipping_address_1'].' '.@$shipping_address['shipping_address_2'],
                    @$shipping_address['shipping_city'].' '.@$shipping_address['shipping_postcode'],
                    @$shipping_address['shipping_country']
                ];

                $shipping_address = implode(', ',$shipping_address);

                $shipping_address = str_replace(', ,',',',$shipping_address);

                $shipping_address = '<a target="_blank" href="https://maps.google.com/maps?&q='.$shipping_address.'">'.$shipping_address.'</a>';

                $customer_name = implode(" ",$customer_name);

                $status = !empty($row['status']) ? str_replace('_',' ',$row['status']) : '';


                $price = _price(number_format($price,2));

                $roles = $userModel->get_user_roles($row['customer_user']);

                $roles = ucfirst(implode(', ',$roles));

                $actions = '<div class="list-dropdown"> <a href="" class="btn btn-primary btn-sm bg-red"><i class="lni lni-menu"></i></a>';
                $actions .= '<ul class="dropdown">
                <li><a href="'.site_url().ADMIN . '/orders/view'.'/'.$row['order_id'].'">View Order</a></li>
                <li><a href="'.site_url().ADMIN . '/orders/edit'.'/'.$row['order_id'].'">Edit Order</a> </li>
                ';
                if($status !== "completed") {
                    $actions .= '<li><a href="'.admin_url().'change-order-status/completed?orders='.$row['order_id'].'&ref=order_list" href="">Mark as completed</a></li>';
                }
                $actions .= '<li><a href="'.admin_url().'generate-pdf-slip/'.$row['order_id'].'" target="_blank">View order slip</a></li>';

                $actions .= '</ul></div>';

                $output['data'][] = [
                    '<div class="input_field inline-checkbox"><label><input type="checkbox" class="checkrow" name="product-row[]" value="'.$row['order_id'].'"></label></div>',
                    '<div style="min-width: 130px; width: fit-content"><a title="View order #'.$row['order_id'].'" href="'.admin_url().'orders/view/'.$row['order_id'].'">'.'#'.$row['order_id'].' - '.$customer_name.'</a></div> <a href="#" title="Preview order #'.$row['order_id'].'" class="preview-open" data-id="'.$row['order_id'].'"><i class="lni lni-eye"></i></a>',
                    '<div title="'._datetime_full($row['order_date']).'" style="width:80px">'.date('d M Y',strtotime($row['order_date'])).'</div>',
                    ucfirst($status),
                    '<a title="View customer: '.$row['billing_first_name'].'" href="'.admin_url().'users/edit/'.$row['customer_user'].'" target="_blank">'.$row['customer_username'].'</a>',
                    $roles,
                    $shipping_address,
                    payment_method_map($row['payment_method']),
                    $row['order_shipping_title'],
                    $price,
                    '<div class="text-right" style="width: max-content;">'.$actions.'</div>'
                ];
            }

            echo json_encode($output);

            exit;

        }

        $order_counts = [];

        $get_count = $this->master->query('SELECT COUNT(order_id) AS total FROM tbl_orders',true,true);
        $order_counts['all'] = $get_count['total'];

        $get_count = $this->master->query("SELECT COUNT(order_id) AS total FROM tbl_orders WHERE status='completed'",true,true);
        $order_counts['completed'] = $get_count['total'];

        $get_count = $this->master->query("SELECT COUNT(order_id) AS total FROM tbl_orders WHERE status='processing'",true,true);
        $order_counts['processing'] = $get_count['total'];

        $get_count = $this->master->query("SELECT COUNT(order_id) AS total FROM tbl_orders WHERE status='pending'",true,true);
        $order_counts['pending'] = $get_count['total'];

        $get_count = $this->master->query("SELECT COUNT(order_id) AS total FROM tbl_orders WHERE status='ready_to_ship'",true,true);
        $order_counts['ready_to_ship'] = $get_count['total'];

        $get_count = $this->master->query("SELECT COUNT(order_id) AS total FROM tbl_orders WHERE status='cancelled'",true,true);
        $order_counts['cancelled'] = $get_count['total'];

        $get_count = $this->master->query("SELECT COUNT(order_id) AS total FROM tbl_orders WHERE status='refunded'",true,true);
        $order_counts['refunded'] = $get_count['total'];

        $get_count = $this->master->query("SELECT COUNT(order_id) AS total FROM tbl_orders WHERE status='failed'",true,true);
        $order_counts['failed'] = $get_count['total'];

        $get_count = $this->master->query("SELECT COUNT(order_id) AS total FROM tbl_orders WHERE status='trashed'",true,true);
        $order_counts['trashed'] = $get_count['total'];

        $this->data['status'] = $this->request->getGet('status');

        $this->data['customers'] = $userModel->get_users('user.user_id,user.display_name,user.username','any','any',false);

        $masterModel = model('masterModel');

        $order_dates = $masterModel->query("SELECT DISTINCT order_date FROM tbl_orders");
        $order_months_arr = [];
        foreach($order_dates as $date) {
            $order_months_arr[date('m-Y',strtotime($date->order_date))] = date('F Y',strtotime($date->order_date));
        }
        $order_months_arr = array_unique($order_months_arr);
        $order_months_arr = array_reverse($order_months_arr);

        $this->data['order_months'] = $order_months_arr;

        $this->data['order_count'] = $order_counts;

        $this->data['content'] = ADMIN . "/orders/index";

        _render_page('/' . ADMIN . '/index', $this->data);

    }

    public function add_order($order_id=0) {
        $orderModel = model('OrderModel');
        $userModel = model('UserModel');
        $this->data['media'] = model('Media');
        $subscriptionModel = model('SubscriptionModel');
        $ProductsModel = model('ProductsModel');
        $masterModel = model('MasterModel');

        if(!empty($_POST['products'])) {
            $post = $_POST;

            $orderShipping = $post['order-shipping'];
            $shippingName = '';
            $shippingCost = 0;

            if(!empty($post['billing_first_name'])) {
                $first_name = $post['billing_first_name'];
                $last_name = $post['billing_last_name'];
                $billing_country = $post['billing_country'];
                $billing_address1 = $post['billing_address_1'];
                $billing_address2 = $post['billing_address_2'];
                $billing_city = $post['billing_city'];
                //$billing_state = $post['billing_state'];
                $billing_postcode = $post['billing_postcode'];
                $billing_phone = $post['billing_phone'];
                $billing_email = $post['billing_email'];

                $shipping_first_name = $post['shipping_first_name'];
                $shipping_last_name = $post['shipping_last_name'];
                $shipping_country = $post['shipping_country'];
                $shipping_address1 = $post['shipping_address_1'];
                $shipping_address2 = $post['shipping_address_2'];
                $shipping_city = $post['shipping_city'];
                //$shipping_state = $post['shipping_state'];
                $shipping_postcode = $post['shipping_postcode'];

                $date_created = explode('/',$post['date_created']);
                $date_created = $date_created[2].'-'.$date_created[1].'-'.$date_created[0];
                $date_created = $date_created.' 00:00:00';

                $order_status = $post['order_status'];
                $customer = $post['customer'];

                $tax_settings = get_tax_price([
                    'country' => $shipping_country,
                    'postcode' => $shipping_postcode,
                    'state' => '',
                    'city' => $shipping_city,
                ]);
            }

            $coupon = $post['coupon'];
            $order_subtotal = $post['order-subtotal'];



            $order_tax = 0;
            $include_tax = 'No';
            $subtotal = 0;

            $products = [];

            $product_total = 0;

            foreach($post['products'] as $i=>$pid) {
                $product = $ProductsModel->product_by_id($pid);
                $price = $product->price;
                $qty = $post['qty'][$i];
                $product->qty = $qty;
                $variations = [];
                foreach($post as $key=>$values) {
                    if(strstr($key,'variation_') && !empty($values[$i])) {
                        $variations[str_replace('variation_','',$key)] = $values[$i];
                    }
                }
                $p_vats = $ProductsModel->product_variation($pid, $variations);
                $products[] = [
                    'product' => $product,
                    'variation' => $p_vats
                ];
                if(!empty($p_vats['values']['regular_price'])) {
                    $price = $p_vats['values']['regular_price'];
                }
                $product_total += $price;
            }

            $subtotal += $product_total;

            foreach(get_setting('shippingmethods',true) as $i=>$method) {
                if($orderShipping == $i) {
                    $shippingName = $method['name'];
                    $shippingCost = $method['value'];

                    if(!empty($tax_settings['amount']) && get_setting('tax_on_shipping')) {
                        $method_vat = $tax_settings['amount'];
                        $tax_name = $tax_settings['tax_name'];
                        if($tax_settings['type'] === "percent") {
                            $order_tax = ($method_vat/100)*$shippingCost;
                        }else {
                            $order_tax = $shippingCost - $method_vat;
                        }
                    }

                    break;
                }
            }

            $subtotal += ($shippingCost + $order_tax);

            if(!empty($post['discount'])) {
                $subtotal -= $post['discount'];
            }

            $order_data = [
                'payment_method' => env('default_paymentmethod'),
                'customer_ip_address' => get_client_ip(),
                'customer_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'order_currency' => env('default_currency_code'),
                'cart_id' => NULL,
            ];

            if(!empty($post['billing_first_name'])) {
                $billing_info = [
                    'billing_first_name'=>$first_name,
                    'billing_last_name'=>$last_name,
                    'billing_country'=>$billing_country,
                    'billing_address_1'=>$billing_address1,
                    'billing_address_2'=>$billing_address2,
                    'billing_city'=>$billing_city,
                    // 'billing_county'=>$billing_state,
                    'billing_postcode'=>$billing_postcode,
                    'billing_phone'=>$billing_phone,
                    'billing_email'=>$billing_email
                ];
                $shipping_info = [
                    'shipping_first_name'=>$shipping_first_name,
                    'shipping_last_name'=>$shipping_last_name,
                    'shipping_country'=>$shipping_country,
                    'shipping_address_1'=>$shipping_address1,
                    'shipping_address_2'=>$shipping_address2,
                    'shipping_city'=>$shipping_city,
                    //'shipping_county'=>$shipping_state,
                    'shipping_postcode'=>$shipping_postcode,
                    'shipping_phone'=>$billing_phone,
                    'shipping_email'=>$billing_email
                ];

                $order_data['status'] = $post['order_status'];
                $order_data['customer_user'] = $post['customer'];
                $order_data['order_title'] = "Order &ndash; "._datetime_full($post['date_created']);
                $order_data['billing_address'] = json_encode($billing_info);
                $order_data['shipping_address'] = json_encode($shipping_info);
                $order_data['order_date'] = $date_created;
            }

            $get_order_id = !empty($post['order_id']) ? $post['order_id'] : 0;

            if($get_order_id){
                $order_id = $get_order_id;
                $masterModel->insertData('tbl_orders',$order_data,'order_id',$order_id);
            }else {
                $order_id = $masterModel->insertData('tbl_orders',$order_data);
            }

            $order_meta = [
                'payment_method'=>$order_data['payment_method'],
                'payment_method_title'=>$order_data['payment_method'],
                'customer_ip_address'=>get_client_ip(),
                'customer_user_agent'=>$_SERVER['HTTP_USER_AGENT'],
                'created_via'=>'admin',
                'order_currency'=>env('default_currency_code'),
                'cart_discount'=>$post['discount'],
                'order_comments'=>'',
                'order_shipping'=>$shippingCost,
                'order_shipping_title'=>$shippingName,
                'order_shipping_tax'=>$order_tax,
                'order_total'=>$subtotal,
                'order_tax' => $order_tax,
                'cart_discount_tax' => 0,
                'prices_include_tax' => $include_tax,
                'order_total' => $order_subtotal
            ];


            if(!empty($post['billing_first_name'])) {
                $order_meta['customer_user'] = $post['customer'];
                $order_meta['billing_first_name'] = $first_name;
                $order_meta['billing_last_name'] =$last_name;
                $order_meta['billing_address_1'] =$billing_address1;
                $order_meta['billing_address_2'] =$billing_address2;
                $order_meta['billing_country'] =$billing_country;
                $order_meta['billing_phone'] =$billing_phone;
                $order_meta['billing_postcode'] =$billing_postcode;
                $order_meta['billing_city'] =$billing_city;
                $order_meta['billing_email'] =$billing_email;
                $order_meta['billing_address_index'] =$first_name.' '.$last_name.' '.$billing_address1.' '.$billing_address2.' '.$billing_email.' '.$billing_phone;

                $order_meta['shipping_first_name'] =$post['shipping_first_name'];
                $order_meta['shipping_last_name'] =$post['shipping_last_name'];
                $order_meta['shipping_address_1'] =$shipping_address1;
                $order_meta['shipping_address_2'] =$shipping_address2;
                $order_meta['shipping_country'] =$shipping_country;
                $order_meta['shipping_phone'] =$billing_phone;
                $order_meta['shipping_postcode'] =$shipping_postcode;
                $order_meta['shipping_city'] =$shipping_city;
                $order_meta['shipping_email'] =$billing_email;
                $order_meta['shipping_address_index'] =$first_name.' '.$last_name.' '.$shipping_address1.' '.$shipping_address2.' '.$billing_email.' '.$billing_phone;
            }

            if(!empty($coupon)) {
                $getCoupon = $ProductsModel->getCouponByID($coupon);

                $order_meta['coupon_id'] = $getCoupon['id'];
                $order_meta['coupon_code'] = $getCoupon['code'];
            }else {
                $order_meta['coupon_id'] = 0;
                $order_meta['coupon_code'] = "";
            }

            foreach($order_meta as $k=>$v) {
                if($get_order_id) {
                    $orderModel->update_order_meta($order_id,[
                        $k => $v
                    ]);
                }else {
                    $masterModel->insertData('tbl_order_meta',[
                        'order_id' => $order_id,
                        'meta_key' => $k,
                        'meta_value' => $v
                    ]);
                }
            }

            if($get_order_id) {
                $order_items = $orderModel->order_items($get_order_id);
                foreach($order_items as $item) {
                    $masterModel->delete_data('tbl_order_item_meta','item_id', $item['order_item_id']);
                }
                $masterModel->delete_data('tbl_order_items','order_id', $get_order_id);
            }


          //  pr($order_items);

           // pr($products);

            foreach($products as $i=>$product) {
                $order_items = [
                    'order_id' => $order_id,
                    'product_name' => $product['product']->title,
                    'item_type' => 'line_item'
                ];

                //pr($product);

                $product_price = $product['product']->price;
                $qty = $product['product']->qty;

                if(!empty($product['variation']['values']['regular_price'])) {
                    $product_price = $product['variation']['values']['regular_price'];
                }

                $variations = $product['variation'];

                $vars = [];

                foreach($post as $k=>$v) {
                    if(strstr($k,'variation_') && !empty($v[$i])) {
                        $key = $v[$i];
                        $item_meta[str_replace('variation_','',$k)] = $key;
                        $vars[str_replace('variation_','',$k)] = $key;
                    }
                }

                $item_meta = [
                    'product_id' => $product['product']->id,
                    'quantity' => $qty,
                    'line_subtotal' => $product_price*$qty,
                    'line_total' => $product_price,
                    'line_tax' => 0,
                    'type' => 'product',
                    'display_price_html' => _price($product_price*$qty),
                    'item_price' => $product_price,
                    'item_price_html' => _price($product_price),
                    'sale_price' => $product['product']->sale_price,
                    'sold_individually' => $product['product']->sold_individually,
                    'free_shipping' => $product['product']->free_shipping,
                ];
                $item_meta['variation'] = json_encode($variations);
                $item_meta['variations'] = json_encode($vars);

                $item_id = $masterModel->insertData('tbl_order_items',$order_items);

                foreach($item_meta as $k=>$v) {
                    $masterModel->insertData('tbl_order_item_meta',[
                        'item_id' => $item_id,
                        'meta_key' => $k,
                        'meta_value' => $v
                    ]);
                }
            }

            //pr($item_meta_);

            if($get_order_id) {
                notice_success("Order #$order_id updated successfully");
                return redirect()->to($_POST['current_url']);
            }else {
                notice_success("Order #$order_id added successfully");
                return redirect()->to(admin_url().'orders');
            }

            exit;
        }

        $this->data['orderModel'] = $orderModel;
        $this->data['products'] = $ProductsModel->get_products();
        $this->data['customers'] = $userModel->get_users('user_id,display_name','any','any',false);
        $this->data['order_id'] = $order_id;
        $this->data['productsModel'] = $ProductsModel;
        $this->data['order_data'] = [];

        if($order_id) {
            $order_data = $orderModel->get_order_by_id($order_id);
            $this->data['order_data'] = $order_data;
            //$this->data['billing'] = $order_data['billing_address'];
           // $this->data['shipping'] = $order_data['shipping_address'];
        }

        $this->data['content'] = ADMIN . "/orders/add_order";

        $this->data['coupons'] = $this->master->getRows('tbl_coupons');

        $this->data['mode'] = 'add';

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function view_order($order_id)
    {
        $orderModel = model('OrderModel');
        $subscriptionModel = model('SubscriptionModel');
        $ProductsModel = model('ProductsModel');
        $this->data['order_type'] = 'order';

        $segment = $this->uri->getSegments();

        $this->data['coupons'] = $this->master->getRows('tbl_coupons');

        $this->data['order'] = $order = $orderModel->get_order_by_id($order_id);
        
        if($order_id) {

            $this->data['order_notes'] = $orderModel->get_order_notes($order_id);

            if($segment[1] === "subscription") {
                $sub_item = $orderModel->order_items($order_id);

                $this->data['order_type'] = 'subscription';
                $this->data['page'] = 'subscriptions';
                $this->data['subscription_item'] = $sub_item;
            }

            $this->data['order_meta'] = $this->data['order']['order_meta'];
        }

        $this->data['media'] = model('Media');

        $get_action = $this->request->getGet('action');


        if($this->request->getPost('send_order_email')) {
            $action = $this->request->getPost('order_email_action');
            if($action === "invoice") {
                if($orderModel->order_complete_email($order_id)){
                    notice_success('Invoice sent successfully');
                }else {
                    notice_success('Invoice failed to send');
                }
            }
            if($action === "cancelled") {
                $orderModel->change_order_status($order_id,'cancelled');

                if($orderModel->cancelled_order_email($order_id)){
                    notice_success('Order status has been cancelled');
                }else {
                    notice_success('Failed to change order status');
                }
            }
            if($action === "processing") {
                $orderModel->change_order_status($order_id,'pending');

                if($orderModel->processing_order_email($order_id)){
                    notice_success('Order status is set to processing');
                }else {
                    notice_success('Failed to change order status');
                }
            }
            if($action === "completed") {
                $orderModel->change_order_status($order_id,'completed');

                if($orderModel->order_complete_email($order_id)){
                    notice_success('Order status is set to completed');
                }else {
                    notice_success('Failed to change order status');
                }
            }

            return redirect()->back();
        }

        if($this->request->getPost('order_note_delete')) {
            $note_id = $this->request->getPost('order_note_delete');
            $orderModel->delete_note($note_id,$order_id);
            notice_success('Order note deleted');
            return redirect()->back();
        }

        if($this->request->getPost('add_order_note')) {
            $note_text = $_POST['note_text'];
            $note_type = $_POST['note_recipient'];

            if($orderModel->add_note($order_id,$note_text,$note_type)) {
                if($note_type === "customer") {
                    notice_success('Order note is added and notified to customer');
                }else {
                    notice_success('Order note is added successfully');
                }

            }else {
                notice_success('Failed to add order note');
            }

            return redirect()->back();
        }

        if($get_action === "generate_pdf_slip") {
            $orderModel->generate_order_slip($order_id);
            exit;
        }

        if($this->request->getPost('subscription_setting')) {
            $sub_post = $this->request->getPost('subscription_setting');
            $interval = $sub_post['interval'];
            $period = $sub_post['period'];
            $expire = $sub_post['expire'];
            $next_payment_date = '';
            $schedule_end = '';
            if(!empty($sub_post['next_payment_date'])) {
                $next_payment_date = explode('/',$sub_post['next_payment_date']);
                $next_payment_date = $next_payment_date[2].'-'.$next_payment_date[1].'-'.$next_payment_date[0];
            }

            if(!empty($sub_post['schedule_end'])) {
                $schedule_end = explode('/',$sub_post['schedule_end']);
                $schedule_end = $schedule_end[2].'-'.$schedule_end[1].'-'.$schedule_end[0];
            }

            $order_item = $order['order_items'][0];
            $order_meta = $order['order_meta'];
            $item_meta = $order_item['item_meta'];

            if(!empty($item_meta['subscription'])) {
                $sub_setting = json_decode($item_meta['subscription'],true);
                $new_sub_setting = array_merge($sub_setting,[
                        'interval' => $interval,
                        'period' => $period,
                        'expire' => $expire,
                ]);
                $new_sub_setting = json_encode($new_sub_setting);

                $_interval = $interval == 0 ? 'every':'every '.number_position($interval);

                $item_price_html = _price($item_meta['item_price']).' '.$_interval.' '.$period;
                $display_price_html = _price($sub_setting['price']).' '.$_interval.' '.$period;

                if($expire) {
                    $p_s = $period > 1 ? 's':'';
                    $item_price_html .= ' for '.$expire.' '.$period.$p_s;
                    $display_price_html .= ' for '.$expire.' '.$period.$p_s;
                }


                $orderModel->update_item_meta($order_item['order_item_id'],'subscription', $new_sub_setting);

                $orderModel->update_item_meta($order_item['order_item_id'],'item_price_html', $item_price_html);
                $orderModel->update_item_meta($order_item['order_item_id'],'display_price_html', $display_price_html);

                $orderModel->update_order_meta($order_id,['schedule_next_payment'=>$next_payment_date]);

                $orderModel->update_order_meta($order_id,['schedule_end'=>$schedule_end]);

                notice_success('Subscription schedule changed successfully');
            }else {
                notice_success('Could not update schedule');
            }
            return redirect()->back();
        }

        if ($this->request->getPost('save_order_information')) {

            $data = $this->request->getPost();

            $d = explode('/',$data['date_created']);
            $date = $d[2].'-'.$d[1].'-'.$d[0];

            $billing_addr = [
                'billing_first_name' => $data['billing_first_name'],
                'billing_last_name' => $data['billing_last_name'],
                'billing_address_1' => $data['billing_address_1'],
                'billing_address_2' => $data['billing_address_2'],
                'billing_city' => $data['billing_city'],
                'billing_phone' => $data['billing_phone'],
                'billing_country' => $data['billing_country'],
                'billing_email' => $data['billing_email']
            ];

            $shipping_addr = [
                'shipping_first_name' => $data['shipping_first_name'],
                'shipping_last_name' => $data['shipping_last_name'],
                'shipping_address_1' => $data['shipping_address_1'],
                'shipping_address_2' => $data['shipping_address_2'],
                'shipping_city' => $data['shipping_city'],
                'shipping_postcode' => $data['shipping_postcode'],
                'shipping_country' => $data['shipping_country'],
                'shipping_email' => $data['shipping_email']
            ];

            $db_data = [
                'order_date' => $date,
                'status' => $data['order_status'],
                'customer_user' => $data['customer'],
                'billing_address' => json_encode($billing_addr),
                'shipping_address' => json_encode($shipping_addr)
            ];
            if($this->data['order']['status']!=$data['order_status']){
                $note_text = "Order status changed from ".$this->data['order']['status']." to ".$data['order_status'].".";
                $note_type = 'private';
                $orderModel->add_note($order_id,$note_text,$note_type);
            }

            $this->master->insertData('tbl_orders',$db_data,'order_id',$order_id);

            if($data['order_send_email']) {
                $orderModel->order_status_change_email($order_id,$data['old_order_status']);
            }

            notice_success('Order information updated successfully');

            return redirect()->back();
        }

        $userModel = model('UserModel');

        $this->data['orderModel'] = $orderModel;
        $this->data['ProductsModel'] = $ProductsModel;
        $this->data['subscriptionModel'] = $subscriptionModel;

        $this->data['customers'] = $userModel->get_users('user_id,display_name','any','any',false);

        if($this->data['order_type'] == 'subscription') {
            $this->data['content'] = ADMIN . "/orders/subscription_view";
        }else {
            $this->data['content'] = ADMIN . "/orders/view_order";
        }

        $this->data['mode'] = 'edit';

        _render_page('/' . ADMIN . '/index', $this->data);


    }

    public function generate_pdf_slip($order_ids) {
        $slip_ids = explode(',',$order_ids);
        $orderModel = model('OrderModel');
        $orderModel->generate_order_slip($slip_ids);
    }

    public function generate_order_csv($order_ids) {
        $slip_ids = explode(',',$order_ids);
        $orderModel = model('OrderModel');
        $orderModel->generate_order_csv($slip_ids);
    }

    public function change_order_status($status='') {
        $orderIds = explode(',',$_GET['orders']);
        $orderModel = model('OrderModel');
        foreach($orderIds as $oid) {
            $orderModel->change_order_status($oid, $status);
        }
        notice_success('Order status changed');

        if(empty($_GET['ref'])) {
            ?>
            <script>
                window.close();
            </script>
            <?php
        }else {
            ?>
            <script>
                history.back();
            </script>
            <?php
        }


    }

    public function delete_orders($orders='') {
        $masterModel = model('MasterModel');
        foreach(explode(',',$orders) as $orderID) {
            $masterModel->delete_data('tbl_orders','order_id',$orderID);
            $masterModel->delete_data('tbl_order_meta','order_id',$orderID);
            $items = $masterModel->getRows('tbl_order_items', ['order_id'=>$orderID]);

            foreach($items as $item) {
                $item_id = $item->order_item_id;
                $masterModel->delete_data('tbl_order_item_meta','item_id',$item_id);
            }
            $masterModel->delete_data('tbl_order_items','order_id',$orderID);
        }
        notice_success('Orders deleted successfully');
        if(empty($_GET['ref'])) {
            ?>
            <script>
                window.close();
            </script>
            <?php
        }else {
            ?>
            <script>
                history.back();
            </script>
            <?php
        }
    }

    public function order_email($order_id)
    {
        $orderModel = model('OrderModel');
        $orderModel->order_complete_email($order_id);
    }

    public function shipping_methods() {
        $this->data['shipping_methods'] = (object) $this->master->getRow('tbl_settings', ['title' => 'shippingmethods']);

        $this->data['content'] = ADMIN . "/orders/shipping_methods";

        $this->data['page'] = "shipping_methods";

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function subscriptions_settings() {

        $this->data['subscriptionForm'] = (object) $this->master->getRow('tbl_settings', ['title' => 'subscriptionForm']);

        if($this->request->getPost('save_method')) {
            $data = $this->request->getPost();
            $db_data = [];

            foreach($data['subscription_type']['name'] as $i=>$name) {
                $value = $data['subscription_type']['value'][$i];
                $db_data['subscription_type'][$value] = $name;
            }
            foreach($data['subscription_duration']['name'] as $i=>$name) {
                $value = $data['subscription_duration']['value'][$i];
                $db_data['subscription_duration'][$value] = $name;
            }

            $db_data_json = json_encode($db_data);

            $this->master->query("UPDATE tbl_settings SET value='$db_data_json' WHERE title='subscriptionForm'");

            notice_success('Settings saved successfully');

            return redirect()->back();
        }

        if($this->request->getPost('subscription_reminder_save')) {
            $reminder = $this->request->getPost('subscription_reminder');
            $this->master->query("UPDATE tbl_settings SET value='$reminder' WHERE title='subscriptionReminderDay'");

            return redirect()->back();
        }

        $this->data['content'] = ADMIN . "/orders/subscription_form";

        $this->data['page'] = "subscriptions";

        _render_page('/' . ADMIN . '/index', $this->data);
    }

    public function subscriptions_index()
    {
        $orderModel = model('OrderModel');
        $subscriptionModel = model('SubscriptionModel');

        $action = $this->request->getGet('action');
        if($action) {
            $id = $this->request->getGet('ID');
            switch ($action) {
                case 'start':
                    $subscriptionModel->start($id);
                    break;
                case 'suspend':
                    $subscriptionModel->suspend($id);
                    break;
                case 'resume':
                    $subscriptionModel->resume($id);
                    break;
                case 'cancel':
                    $subscriptionModel->cancel($id);
                    break;
            }
        }

        if($this->request->getGet('get_data')) {

            $rows = [
                'o.order_id',
                'item.order_item_id',
                'o.order_title',
                'o.order_date',
                'o.parent_id',
                'o.payment_method',
                'o.order_type',
                'o.status',
                ''=>['searchable'=>false]
            ];

            $sort_cols = [
                'o.order_id',
                'o.order_date',
                'o.status'
            ];

            $where = " WHERE o.order_type='shop_subscription' AND item.item_type='line_item'";

            if(!empty($_GET['status'])) {
                $where .= " AND o.status='".$_GET['status']."' ";
            }

            // echo $where;

            $output = datatable_query("tbl_order_items AS item JOIN tbl_orders AS o on o.order_id=item.order_id ",$rows,$sort_cols,"GROUP BY o.order_id",$where);

            $records = $output['records'];

            unset($output['records']);

            foreach($records as $i=>$order) {

                $order_id = $order['order_id'];

                $order_parent = $order['parent_id'];

                $item_id = $order['order_item_id'];

                $order_meta = $orderModel->order_meta($order_id);

                $order_total = _price($order_meta['order_total']);

                $display_price_html = $orderModel->order_item_meta($item_id,'display_price_html');

                $start_date = _date($order_meta['schedule_start']);
                $end_date = _date($order_meta['schedule_end']);
                $next_payment = _date($order_meta['schedule_next_payment']);

                $order_ids = !empty($order_meta['subscription_renewal_order_ids_cache']) ? unserialize($order_meta['subscription_renewal_order_ids_cache']) : [];
                $order_ids[] = $order_id;

                $last_order_id = !empty($order_ids) ? $order_ids[0] : 0;

                $last_order = $order;
                if($last_order_id) {
                    $last_order = $orderModel->get_order_by_id($last_order_id, '', false, false);
                    if (empty($last_order)) {
                        $last_order = $order;
                    }
                }


                $last_order_date = _date($last_order['order_date']);
                $order_count = count($order_ids);

                $actions = [
                    '<a href="subscription/'.$order_id.'" class="btn btn-primary btn-sm">View</a>',
                    '<a data-confirm="Are you sure to suspend this subscription?" data-href="?action=suspend&ID='.$order_id.'" class="btn btn-primary btn-sm">Suspend</a>',
                    '<a data-confirm="Are you sure to cancel this subscription?" data-href="?action=cancel&ID='.$order_id.'" class="btn btn-primary btn-sm btn-cancel">Cancel</a>',
                ];

                if($order['status'] === "pending") {
                    $actions[1] = '<a href="?action=start&ID='.$order_id.'" class="btn btn-primary btn-sm btn-start">Start</a>';
                }

                if($order['status'] === "on-hold") {
                    $actions[1] = '<a href="?action=resume&ID='.$order_id.'" class="btn btn-primary btn-sm btn-resume">Resume</a>';
                }

                if($order['status'] === "cancelled") {
                    $cancel_date = _date_full($order_meta['schedule_cancelled']);
                    $end_date = '<div class="text-center"><h5 class="color-base">Cancelled on </h5>'.$cancel_date.'<p></p></div>';
                }

                $status_text = ucfirst($order['status']);

                $display_price = '<div class="text-center">'.$display_price_html.'</div>';


                $output['data'][] = [
                    '<span class="status-'.$order['status'].'">'.'#'.$order['order_id'].'</span>',
                    $display_price,
                    '<p class="text-center">'.$order_total.'</p>',
                    '<p class="text-center">'.$start_date.'</p>',
                    '<p class="text-center">'.$next_payment.'</p>',
                    '<p class="text-center">'.$last_order_date.'</p>',
                    '<p class="text-center">'.$end_date.'</p>',
                    '<p class="text-center">'.$order_count.'</p>',
                    '<p class="text-center status-text">'.$status_text.'</p>',
                    '<div class="btn-group" style="width: max-content; display: inline-block">'.implode('&nbsp;&nbsp;',$actions).'</div>'
                ];
            }

            echo json_encode($output);

            exit;

        }

        $item_sql = "SELECT COUNT(s.order_id) AS total FROM tbl_orders AS s WHERE 1=1 ";

        if(!empty($item_sql)) {
            $get_count = $this->master->query($item_sql,true,true);


            $this->data['all_count'] = $get_count['total'];

            $get_count = $this->master->query($item_sql." AND s.status='active'",true,true);
            $this->data['active_count'] = $get_count['total'];

            $get_count = $this->master->query("SELECT COUNT(order_id) AS total FROM tbl_orders WHERE status='processing'",true,true);
            $this->data['processing'] = $get_count['total'];

            $get_count = $this->master->query($item_sql." AND s.status='expired'",true,true);
            $this->data['expired_count'] = $get_count['total'];

            $get_count = $this->master->query($item_sql." AND s.status='pending-cancel'",true,true);
            $this->data['pending_cancel_count'] = $get_count['total'];

            $get_count = $this->master->query($item_sql." AND s.status='pending'",true,true);
            $this->data['pending_count'] = $get_count['total'];

            $get_count = $this->master->query($item_sql." AND s.status='on-hold'",true,true);
            $this->data['on_hold_count'] = $get_count['total'];

            $get_count = $this->master->query($item_sql." AND s.status='cancelled'",true,true);
            $this->data['cancelled_count'] = $get_count['total'];

            $this->data['status'] = $this->request->getGet('status');

            $this->data['content'] = ADMIN . "/orders/subscriptions";

            $this->data['page'] = 'subscriptions';

            _render_page('/' . ADMIN . '/index', $this->data);
        }



    }

    public function subscription_plans() {
        $orderModel = model('OrderModel');
        $subscriptionModel = model('SubscriptionModel');
        $this->data['page'] = "subscriptions";

        $this->data['content'] = ADMIN . "/orders/subscription-plans";

        if($this->request->getPost('update_plan')) {
            $post = $this->request->getPost();
            unset($post['update_plan']);
            $post = [$post]; //array for multiple plans in the future..

            $value = json_encode($post);

            $this->master->insertOrUpdate('tbl_settings', ['title'=>'subscription_plans','value'=>$value], 'title', 'subscription_plans');

            notice_success('Subscription plan is updated successfully');
            return redirect()->back();
        }

        $this->data['plan'] = get_setting('subscription_plans',true);
        if(!empty($this->data['plan'])) {
            $this->data['plan'] = $this->data['plan'][0];
        }

        _render_page('/' . ADMIN . '/index', $this->data);
    }

}