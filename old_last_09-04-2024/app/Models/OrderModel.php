<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Controllers;
use Dompdf\Dompdf;
use Dompdf\Options;

class OrderModel extends Model {

    protected $orderstable = 'tbl_orders';
    private $data;

    public function __construct()
    {
        parent::__construct();
    }


    public function get_orders($extra_query='',$include_order_meta=true,$include_items = true) {

        $output = [];

        $query = "SELECT o.* FROM `tbl_orders` AS o LEFT JOIN tbl_users AS user ON user.user_id=o.customer_user LEFT JOIN tbl_order_meta AS meta ON meta.order_id=o.order_id WHERE 1=1 $extra_query ";

        if(!strstr($extra_query,"GROUP BY")) {
            $query .= " GROUP BY o.order_id DESC";
        }

        $db_orders = $this->db->query($query)->getResultArray();

        if($db_orders) {

            foreach($db_orders as $i=>$order) {

                $order_id = $order['order_id'];

                $order['order_items'] = [];

                $order['billing_address'] = json_decode($order['billing_address'],true);
                $order['shipping_address'] = json_decode($order['shipping_address'],true);

                if($include_items) {
                    $items = $this->db->query("SELECT * FROM tbl_order_items WHERE order_id='$order_id'")->getResultArray();

                    foreach($items as $item) {
                        $itemid = $item['order_item_id'];
                        $metas = $this->db->query("SELECT * FROM tbl_order_item_meta WHERE item_id='$itemid'")->getResultArray();
                        $item['item_meta'] = [];
                        foreach($metas as $meta) {

                            $meta['meta_value'] = !empty($meta['meta_value']) ? stripslashes($meta['meta_value']) : '';

                            if($meta['meta_key'] === "line_tax_data" && !empty($meta['meta_value'])) {

                                $meta['meta_value'] = unserialize($meta['meta_value']);
                            }
                            if($meta['meta_key'] === "variation" &&  !empty($meta['meta_value'])) {
                                $meta['meta_value'] = json_decode($meta['meta_value'],true);
                            }
                            $item['item_meta'][$meta['meta_key']] = $meta['meta_value'];
                        }
                        $order['order_items'][] = $item;

                        $children = [];


                    }
                }

                if($include_order_meta) {
                    $order_metas = $this->db->query("SELECT * FROM tbl_order_meta WHERE order_id='$order_id'")->getResultArray();
                    if(!empty($order_metas)) {
                        foreach($order_metas as $meta) {
                            $order['order_meta'][$meta['meta_key']] = $meta['meta_value'];
                        }
                    }
                }


                $output[] = $order;
            }


        }

        return $output;
    }

    public function get_order_by_id($order_id = 0,$extra_query='', $include_order_meta=true, $include_items = true) {

        $output = [];
        if($order_id) {
            $output = $this->get_orders("AND o.order_id='$order_id' $extra_query", $include_order_meta, $include_items);
            if($output) {
                $output = $output[0];
            }
        }

        return $output;
    }

    public function get_order_by_customer($customer_id=0,$extra_query='', $include_order_meta=true, $include_items = true) {
        $output = [];
        if($customer_id) {
            $output = $this->get_orders("AND o.customer_user='$customer_id' $extra_query", $include_order_meta, $include_items);
        }

        return $output;
    }

    public function order_items($order_id='') {
        $items = $this->db->query("SELECT * FROM tbl_order_items AS item LEFT JOIN tbl_order_item_meta AS meta on meta.item_id=item.order_item_id WHERE item.order_id='$order_id'")->getResultArray();

        $output = [];

        foreach($items as $i=>$item) {
            $order_item_id = $item['order_item_id'];
            $order_id = $item['order_id'];
            if($item['item_type'] === 'line_item') {
                $meta_key = $item['meta_key'];
                $meta_value = $item['meta_value'];
                unset($item['meta_key'], $item['meta_value']);
                if(empty($output[$order_id])) {
                    $output[$order_id] = $item;
                    $output[$order_id]['item_meta'] = [$meta_key => $meta_value];
                }else {
                    $output[$order_id]['item_meta'][$meta_key] = $meta_value;
                }
            }
        }

        return $output;
    }

    public function order_childs($order_id=0,$extra_query='', $include_order_meta=true, $include_items = true) {
        $output = [];
        if(!empty($order_id)) {
            $order_childs = $this->db->table('tbl_orders')->select('order_id')->where('parent_id',$order_id)->get()->getResultArray();
            if(!empty($order_childs)) {
                foreach($order_childs as $oid) {
                    $output[] = $this->get_order_by_id($oid['order_id'],$extra_query,$include_order_meta,$include_items);
                }
            }
        }
        return $output;
    }

    public function parent_order($order_id=0,$extra_query='', $include_order_meta=true, $include_items = true) {
        $output = [];
        if(!empty($order_id)) {
            $order_childs = $this->db->table('tbl_orders')->select('order_id')->where('parent_id', $order_id)->get()->getResultArray();

            // pr($order_childs);
            if(!empty($order_childs)) {
                foreach($order_childs as $oid) {
                    $output[] = $this->get_order_by_id($oid['order_id'],$extra_query,$include_order_meta,$include_items);
                }
            }
        }
        return $output;
    }

    public function order_item($order_id='', $item_id='',$fields='*') {
        return $this->db->query("SELECT $fields FROM tbl_order_items WHERE order_id='$order_id' AND order_item_id='$item_id'")->getRowArray();
    }

    public function order_item_meta($item_id=0, $meta_key='') {
        $sql = "SELECT meta.* FROM tbl_order_items AS item JOIN tbl_order_item_meta AS meta ON meta.item_id=item.order_item_id WHERE item.order_item_id='$item_id'";

        if($meta_key) {
            $sql .= " AND meta.meta_key='$meta_key'";
        }

        $getItem = $this->db->query($sql);

        if(!empty($getItem)) {
            if($meta_key) {
                $item = $getItem->getRowArray();

                return !empty($item['meta_value']) ? $item['meta_value'] : '';
            }else {
                $item_meta = $getItem->getResultArray();
                $output = [];
                foreach($item_meta as $item) {
                    $output[$item['meta_key']] = $item['meta_value'];
                }
                return $output;
            }
        }

    }

    public function order_meta($order_id=0, $key='') {
        $output = [];
        $sql = "SELECT meta_key, meta_value FROM tbl_order_meta WHERE order_id='$order_id'";
        if($key) {
            $sql .= " AND meta_key='$key'";
        }

        $metas = $this->db->query($sql)->getResultArray();

        foreach ($metas as $meta) {
            $output[$meta['meta_key']] = $meta['meta_value'];
        }

        return $output;
    }

    public function get_order_notes($order_id=0, $where=[], $fields='*', $status=1, $include_meta=true) {

        $sql = "SELECT $fields FROM tbl_comments AS comment WHERE 1=1 ";
        if(!empty($where)) {
            foreach($where as $k=>$v) {
                $sql .= " AND $k='$v'";
            }
        }
        $sql .= " AND comment.comment_post_ID='$order_id' ORDER BY comment_ID DESC";

        $get_notes = $this->db->query($sql)->getResultArray();
        $result = $get_notes;

        if($include_meta) {
            $result = [];
            foreach($get_notes AS $note) {
                $cmdID = $note['comment_ID'];
                $note['meta_data'] = $this->db->query("SELECT * FROM tbl_commentmeta WHERE comment_id='$cmdID'")->getResultArray();
                $result[] = $note;
            }
        }

        return $result;
    }


    public function customer_orders_($user_id='',$extraQuery='',$fields='o.*',$order_by='order_date desc') {

        $sql = "SELECT $fields, o.parent_id FROM tbl_orders AS o JOIN tbl_users AS u ON u.user_id=o.customer_user JOIN tbl_order_items AS item ON item.order_id=o.order_id
             WHERE IF(o.parent_id > 0, (SELECT order_type FROM tbl_orders WHERE parent_id=o.parent_id AND order_id=o.order_id) = 'shop_order',1=1) AND o.customer_user='$user_id' $extraQuery GROUP BY o.order_id ORDER BY $order_by";

        $db_orders = $this->db->query($sql)->getResultArray();

        $output = [];

        if(!empty($db_orders)) {

            foreach($db_orders as $order) {

                $order['billing_address'] = json_decode($order['billing_address'],true);
                $order['shipping_address'] = json_decode($order['shipping_address'],true);

                $order['order_meta'] = $this->order_meta($order['order_id']);
                $order['order_items'] = $this->order_items($order['order_id']);

                $order_customer = $order['customer_user'];

                $customer = $this->db->query("SELECT * FROM tbl_users WHERE user_id='$order_customer'")->getRowArray();

                unset($customer['password'],$customer['billing_info'],$customer['shipping_info']);

                $order['order_customer'] = $customer;

                $output[] = $order;
            }

        }

        pr($output);

        return $output;
    }

    public function customer_orders($user_id='',$extraQuery='',$fields='o.*',$order_by='order_date desc',$config=[]) {

        $config = array_merge(['order_meta'=>true, 'order_items'=>true, 'order_customer'=>true,'order_result'=>true],$config);

        $sql = "SELECT $fields, o.parent_id, o.billing_address, o.shipping_address, o.order_id FROM tbl_orders AS o 
    JOIN tbl_users AS u ON u.user_id=o.customer_user 
    JOIN tbl_order_items AS item ON item.order_id=o.order_id WHERE o.order_type='shop_order' AND o.customer_user='$user_id' $extraQuery GROUP BY o.order_id ORDER BY $order_by";


        if(!$config['order_result']) {
            return $this->db->query($sql);
        }else {
            $db_orders = $this->db->query($sql)->getResultArray();
        }

        $userModel = model('UserModel');

        $output = [];

        if(!empty($db_orders)) {

            foreach($db_orders as $order) {

                $order['billing_address'] = json_decode($order['billing_address'],true);
                $order['shipping_address'] = json_decode($order['shipping_address'],true);

                if($config['order_meta']) {
                    $order['order_meta'] = $this->order_meta($order['order_id']);
                }
                if($config['order_items']) {
                    $order['order_items'] = $this->order_items($order['order_id']);
                }
                if($config['order_items']) {
                    $order_customer = $order['customer_user'];
                    $customer = $userModel->get_user($order_customer);
                    $order['order_customer'] = $customer;
                }

                $output[] = $order;
            }
        }

        return $output;
    }

    public function customer_subscriptions($user_id='',$extraQuery='',$fields='o.*',$order_by='order_date desc') {

        $query = $extraQuery;
        $query .= ' AND o.parent_id > 0 ';

        $query .= " AND o.customer_user='$user_id'";
        $query .= " AND o.order_type='shop_subscription'";

        $orders = $this->get_orders($query);

        if(!empty($orders)) {
            return $orders;
        }

    }

    public function order_complete_email($order_id)
    {
        $mail = new MailModel();

        $order = $this->get_order_by_id($order_id);

        $email_to = $order['billing_address']['billing_email'];
        $billing_name = $order['billing_address']['billing_first_name'].' '.$order['billing_address']['billing_last_name'];

        $mailbody = $mail->get_parsed_content('order_complete', [
            'display_name'=>$billing_name,
            'order_id' => $order_id,
            'order_receipt' => view('checkout/order_receipt',['order'=>$order])
        ]);

        return $mail->send_email($email_to,$mailbody);
    }

    public function order_status_change_email($order_id, $old_status='') {
        $mail = new MailModel();

        $order = $this->get_order_by_id($order_id);

        $email_to = $order['billing_address']['billing_email'];
        $billing_name = $order['billing_address']['billing_first_name'].' '.$order['billing_address']['billing_last_name'];

        $mailbody = $mail->get_parsed_content('order_status_change', [
            'display_name'=>$billing_name,
            'order_id' => $order_id,
            'order_receipt' => view('checkout/order_receipt',['order'=>$order]),
            'old_order_status' => $old_status,
            'order_status' => $order['status']
        ]);

        return $mail->send_email($email_to,$mailbody);
    }

    public function cancelled_order_email($order_id) {
        $order = $this->get_order_by_id($order_id);

        $mail = new MailModel();

        $email_to = $order['billing_address']['billing_email'];
        $billing_name = $order['billing_address']['billing_first_name'].' '.$order['billing_address']['billing_last_name'];


        $mailbody = $mail->get_parsed_content('order_cancelled', [
            'display_name'=>$billing_name,
            'order_id' => $order_id,
            'order_receipt' => view('checkout/order_receipt',['order'=>$order])
        ]);

        return $mail->send_email($email_to,$mailbody);
    }

    public function order_note_email($order_id, $note_text='',$note_author='') {
        $order = $this->get_order_by_id($order_id);

        $email_to = $order['billing_address']['billing_email'];
        $billing_name = $order['billing_address']['billing_first_name'].' '.$order['billing_address']['billing_last_name'];

        $mail = new MailModel();

        $mailbody = $mail->get_parsed_content('order_note_added', [
            'display_name'=>$billing_name,
            'note_author' => $note_author,
            'note_text' => $note_text,
            'order_id' => $order_id
        ]);

        return $mail->send_email($email_to,$mailbody);
    }

    public function delete_note($note_id,$order_id) {
        $this->db->table('tbl_comments')->where(['comment_ID'=>$note_id,'comment_post_ID'=>$order_id])->delete();
        $this->db->table('tbl_commentmeta')->where(['comment_id'=>$note_id])->delete();
        return 1;
    }

    public function get_totals($order_id = "",$type='product',$multiple=false,$extra_query='') {
        $orders = $this->get_order_by_id($order_id,$type,$multiple,$extra_query);

        $output = [
            'shipping_cost'=>0,
            'product_count'=>0,
            'product_total'=>0,
            'discount_amount'=>0,
            'subtotal'=>0
        ];

        if(!empty($orders)) {

            foreach($orders as $order) {
                $output['shipping_cost'] = $order['shipping_cost'];
                $output['discount_amount'] = $order['discount_amount'];
                $output['product_count'] = count($order['products']);
                $products = $order['products'];
                $product_total = 0;

                foreach($products as $product) {
                    $product_total += $product['product_price'];
                }

                $output['product_total'] = $product_total;

                $output['subtotal'] = ($product_total + $output['shipping_cost']) - $output['discount_amount'];
            }
        }

        return $output;
    }

    public function change_order_status($order_id, $status='',$custom_note='') {
        if($status) {
            if(empty($custom_note)) {
                $order = $this->db->query("SELECT status FROM tbl_orders WHERE order_id='$order_id'")->getRowArray();
                $old_order_status = $order['status'];
                $custom_note = "Order status changed from $old_order_status to $status";
            }
            if($this->db->query("UPDATE tbl_orders SET status='$status' WHERE order_id='$order_id'")) {
                $this->add_note($order_id, $custom_note);
            }
        }
    }

    public function generate_order_slip($order_ids=[]) {

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $slips = [];

        foreach($order_ids as $order_id) {
            $slips[] = $this->get_order_by_id($order_id);
        }

        $this->data['slips'] = $slips;

        $settings = $this->db->query("SELECT value FROM tbl_settings WHERE title='website'")->getRowArray();

        $this->data['setting'] = json_decode($settings['value'],true);

        $slip_view = view('admin/orders/pdf_order_slip',$this->data);

//        echo $slip_view;
//        exit;

        $dompdf->loadHtml($slip_view);

        $dompdf->render();

        $dompdf->stream(implode('-',$order_ids)." - Order Slip.pdf", array("Attachment" => false));

        exit(0);
    }

    public function generate_order_csv($order_ids=[]) {
        $csv_array = [
            ['Order ID','Order Date','Customer Name (Billing)','Customer Role','Item name','Category','Item cost','Quantity','Item total','Shipping','Discount','Subtotal']
        ];
        $userModel = model('UserModel');
        $productModel = model('ProductsModel');
        $file_name = '';

        foreach($order_ids as $i=>$order_id) {
            $order = $this->get_order_by_id($order_id);
            if($i === 0) {
                $first_order = $order;
            }
            if($i === count($order_ids)-1) {
                $last_order = $order;
                $fodata = date('Y-m-d',strtotime($first_order['order_date']));
                $lodate = date('Y-m-d',strtotime($last_order['order_date']));
                $file_name = 'orders-'.$fodata.'-'.$lodate.'.csv';
            }

            if(!empty($order)) {
                $meta = $order['order_meta'];
                $customerId = $meta['customer_user'];
                $customerRoles = $userModel->get_user_roles($customerId);
                $order_items = $order['order_items'];
                $productCatList = [];
                $productCount = 0;
                $discount = _price($meta['cart_discount']);
                $shipping_title = $meta['order_shipping_title'];
                $shipping = _price($meta['order_shipping']);
                $subtotal = _price($meta['order_total']);

                $row_array = [
                    $order['order_id'],
                    _date($order['order_date']),
                    @$meta['billing_first_name'].' '.@$meta['billing_last_name'],
                    implode(', ',$customerRoles),
                    '',
                    '',
                    '',
                    '',
                    '',
                    $shipping.' ('.$shipping_title.')',
                    $discount,
                    $subtotal,
                ];

                foreach($order_items as $j=>$item) {
                    $item_meta = $item['item_meta'];
                    if(!empty($item_meta['product_id'])) {
                        $productCats = $productModel->product_categories($item_meta['product_id']);
                        foreach($productCats as $cat) {
                            $productCatList[] = $cat['name'];
                        }
                        $productCatList = array_unique($productCatList);

                        $line_subtotal = $item_meta['line_subtotal'];
                        $productName = $item['product_name'];

                        $row_array[4] = $productName;
                        $row_array[5] = implode(', ',$productCatList);
                        $row_array[6] = _price($item_meta['line_total']);
                        $row_array[7] = $item_meta['qty'];
                        $row_array[8] =  $line_subtotal;

                        if($j > 0) {
                            $row_array[0] = '';
                            $row_array[1] = '';
                            $row_array[2] = '';
                            $row_array[3] = '';
                            $row_array[9] = '';
                            $row_array[10] = '';
                            $row_array[11] = '';
                        }

                        $csv_array[] = $row_array;
                    }
                }
            }
        }

        // pr($csv_array);

        if(!empty($csv_array)) {
            header("Content-type: application/csv; charset=UTF-8");
            header("Content-Disposition: attachment; filename=$file_name");
            $fp = fopen('php://output', 'w'); // or use php://stdout
            echo "\xEF\xBB\xBF";
            foreach ($csv_array as $row) {
                fputcsv($fp, $row);
            }
        }else {
            echo 'Could not create file';
        }
        exit;
    }

    public function add_note($order_id,$note_text='',$note_type='order_note') {

        if(is_logged_in()) {
            $userModel = model('UserModel');
            $user = $userModel->get_user();
            $display_name = $user->display_name;
            $user_id = $user->user_id;
            $email = $user->email;
        }else {
            $order = $this->get_order_by_id($order_id);
            $b_address = $order['billing_address'];
            $display_name = $b_address['billing_first_name'].' '.$b_address['billing_last_name'];
            $user_id = $order['customer_user'];
            $email = $b_address['billing_email'];
        }

        $db_data = [
            'comment_post_ID' => $order_id,
            'comment_author' => $display_name,
            'comment_author_email' => $email,
            'comment_author_ip' => get_client_ip(),
            'comment_date' => date('Y-m-d h:i:s'),
            'comment_date_gmt' => gmdate('Y-m-d h:i:s'),
            'comment_content' => urlencode($note_text),
            'comment_approved' => 1,
            'comment_agent' => $display_name,
            'comment_type' => $note_type,
            'user_id' => $user_id
        ];

        $this->db->table('tbl_comments')->insert($db_data);

        $comment_id = $this->db->insertID();
        $meta_db = [
            'comment_id' => $comment_id,
            'meta_key' => 'is_customer_note',
            'meta_value' => $note_type === "customer"
        ];
        $this->db->table('tbl_commentmeta')->insert($meta_db);
        $meta_db = [
            'comment_id' => $comment_id,
            'meta_key' => 'verified',
            'meta_value' => 1
        ];
        $this->db->table('tbl_commentmeta')->insert($meta_db);

        if($note_type === "customer") {
            $this->order_note_email($order_id,$note_text,$user->display_name);
        }

        return $comment_id;
    }

    public function add_item_meta($item_id, $meta_key='', $meta_value='') {
        return $this->db->table('tbl_order_item_meta')->insert(['item_id'=>$item_id,'meta_key'=>$meta_key, 'meta_value'=>$meta_value]);
    }

    public function update_item_meta($item_id, $meta_key='', $meta_value='') {
        return $this->db->table('tbl_order_item_meta')->where(['item_id'=>$item_id,'meta_key'=>$meta_key])->update(['meta_value'=>$meta_value]);
    }

    public function add_order_meta($order_id, $meta_key='', $meta_value='') {
        return $this->db->table('tbl_order_meta')->insert(['order_id'=>$order_id,'meta_key'=>$meta_key, 'meta_value'=>$meta_value]);
    }

    public function update_order_meta($order_id, $meta=[]) {
        $result = true;
        if(!empty($order_id) && !empty($meta)) {

            foreach($meta as $key=>$value) {
                $exists = $this->db->table('tbl_order_meta')->select('order_id')->where(['order_id'=>$order_id,'meta_key'=>$key])->get()->getRowArray();

                if($exists) {
                    $this->db->table('tbl_order_meta')->where(['order_id'=>$order_id,'meta_key'=>$key])->update(['order_id'=>$order_id,'meta_key'=>$key,'meta_value'=>$value]);

                    if($this->db->error()['code']) {
                        $result = $this->db->error()['message'];
                        break;
                    }
                }else {
                    $this->db->table('tbl_order_meta')->insert(['order_id'=>$order_id,'meta_key'=>$key,'meta_value'=>$value]);
                    if($this->db->error()['code']) {
                        $result = $this->db->error()['message'];
                        break;
                    }
                }
            }
        }
        return $result;
    }



    public function use_coupon($coupon_code='') {
        $this->db->query("UPDATE tbl_coupons SET use_count = use_count+1 WHERE code='$coupon_code'");
    }


}


 