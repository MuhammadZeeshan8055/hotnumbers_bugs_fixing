<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Controllers;
use Dompdf\Dompdf;
use Dompdf\Options;

class OrderModel extends Model {

    protected $orderstable = 'tbl_orders';
    private $data;
    private $userModel;

    public function __construct()
    {
        parent::__construct();

        $this->userModel = model('UserModel');
    }


    // public function get_orders($extra_query='',$include_order_meta=true,$include_items = true, $select='o.*') {

    //     $output = [];

    //     $query = "SELECT $select FROM `tbl_orders` AS o LEFT JOIN tbl_users AS user ON user.user_id=o.customer_user LEFT JOIN tbl_order_meta AS meta ON meta.order_id=o.order_id WHERE 1=1 $extra_query ";

    //     if(!strstr($extra_query,"GROUP BY")) {
    //         $query .= " GROUP BY o.order_id DESC";
    //     }

    //     $db_orders = $this->db->query($query)->getResultArray();

    //     if($db_orders) {

    //         foreach($db_orders as $i=>$order) {

    //             $order_id = $order['order_id'];

    //             if(!empty($order['billing_address'])) {
    //                 $order['billing_address'] = json_decode($order['billing_address'],true);
    //             }
    //             if(!empty($order['shipping_address'])) {
    //                 $order['shipping_address'] = json_decode($order['shipping_address'], true);
    //             }
    //             if($include_items) {
    //                 $order['order_items'] = [];
    //                 $items = $this->db->query("SELECT * FROM tbl_order_items WHERE order_id='$order_id'")->getResultArray();

    //                 foreach($items as $item) {
    //                     $itemid = $item['order_item_id'];
    //                     $metas = $this->db->query("SELECT * FROM tbl_order_item_meta WHERE item_id='$itemid'")->getResultArray();
    //                     $item['item_meta'] = [];
    //                     foreach($metas as $meta) {

    //                         $meta['meta_value'] = !empty($meta['meta_value']) ? stripslashes($meta['meta_value']) : '';

    //                         if($meta['meta_key'] === "line_tax_data" && !empty($meta['meta_value'])) {

    //                             $meta['meta_value'] = unserialize($meta['meta_value']);
    //                         }
    //                         if($meta['meta_key'] === "variation" &&  !empty($meta['meta_value'])) {
    //                             $meta['meta_value'] = json_decode($meta['meta_value'],true);
    //                         }
    //                         $item['item_meta'][$meta['meta_key']] = $meta['meta_value'];
    //                     }
    //                     $order['order_items'][] = $item;

    //                     $children = [];


    //                 }
    //             }

    //             if($include_order_meta) {
    //                 $order_metas = $this->db->query("SELECT * FROM tbl_order_meta WHERE order_id='$order_id'")->getResultArray();
    //                 if(!empty($order_metas)) {
    //                     foreach($order_metas as $meta) {
    //                         $order['order_meta'][$meta['meta_key']] = $meta['meta_value'];
    //                     }
    //                 }
    //             }

    //             $output[] = $order;
    //         }


    //     }

    //     return $output;
    // }
    
    public function get_orders($extra_query='', $include_order_meta=true, $include_items=true, $select='o.*') {
        $output = [];
        
        // Construct the query
        $query = "SELECT $select FROM `tbl_orders` AS o 
                LEFT JOIN tbl_users AS user ON user.user_id=o.customer_user 
                LEFT JOIN tbl_order_meta AS meta ON meta.order_id=o.order_id 
                WHERE 1=1 $extra_query";
        
        // If no GROUP BY is specified, add it to avoid potential issues
        if (strpos($extra_query, "GROUP BY") === false) {
            $query .= " GROUP BY o.order_id";
        }

        $db_orders = $this->db->query($query)->getResultArray();
        
        if ($db_orders) {
            foreach ($db_orders as $i => $order) {
                $order_id = $order['order_id'];

                if (!empty($order['billing_address'])) {
                    $order['billing_address'] = json_decode($order['billing_address'], true);
                }
                if (!empty($order['shipping_address'])) {
                    $order['shipping_address'] = json_decode($order['shipping_address'], true);
                }
                if ($include_items) {
                    $order['order_items'] = [];
                    $items_query = "SELECT * FROM tbl_order_items WHERE order_id='$order_id'";
                    $items = $this->db->query($items_query)->getResultArray();

                    foreach ($items as $item) {
                        $itemid = $item['order_item_id'];
                        $metas_query = "SELECT * FROM tbl_order_item_meta WHERE item_id='$itemid'";
                        $metas = $this->db->query($metas_query)->getResultArray();
                        $item['item_meta'] = [];
                        foreach ($metas as $meta) {
                            $meta['meta_value'] = !empty($meta['meta_value']) ? stripslashes($meta['meta_value']) : '';

                            if ($meta['meta_key'] === "line_tax_data" && !empty($meta['meta_value'])) {
                                $meta['meta_value'] = unserialize($meta['meta_value']);
                            }
                            if ($meta['meta_key'] === "variation" && !empty($meta['meta_value'])) {
                                $meta['meta_value'] = json_decode($meta['meta_value'], true);
                            }
                            $item['item_meta'][$meta['meta_key']] = $meta['meta_value'];
                        }
                        $order['order_items'][] = $item;
                    }
                }

                if ($include_order_meta) {
                    $order_metas_query = "SELECT * FROM tbl_order_meta WHERE order_id='$order_id'";
                    $order_metas = $this->db->query($order_metas_query)->getResultArray();
                    if (!empty($order_metas)) {
                        foreach ($order_metas as $meta) {
                            $order['order_meta'][$meta['meta_key']] = $meta['meta_value'];
                        }
                    }
                }

                $output[] = $order;
            }
        }

        return $output;
    }
    
    // public function get_order_by_id($order_id = 0,$extra_query='', $include_order_meta=true, $include_items = true, $select='o.*') {

    //     $output = [];
    //     if($order_id) {
    //         $output = $this->get_orders("AND o.order_id='$order_id' $extra_query", $include_order_meta, $include_items, $select);
    //         if($output) {
    //             $output = $output[0];
    //         }
    //     }

    //     return $output;
    // }
    
    public function get_order_by_id($order_id = 0, $extra_query='', $include_order_meta=true, $include_items=true, $select='o.*') {
        $output = [];
        
        if ($order_id) {
            // Call get_orders with specific query to get the desired order
            $output = $this->get_orders("AND o.order_id='$order_id' $extra_query", $include_order_meta, $include_items, $select);
            
            if ($output) {
                $output = $output[0];
            }
        }

        return $output;
    }
    

    // public function get_order_by_transaction($transaction_id = 0,$extra_query='', $include_order_meta=true, $include_items = true) {
    //     $output = [];
    //     if($transaction_id) {
    //         $output = $this->get_orders("AND o.transaction_id='$transaction_id' $extra_query", $include_order_meta, $include_items);
    //         if($output) {
    //             $output = $output[0];
    //         }
    //     }
    //     return $output;
    // }
    

    public function get_orders_by_transaction($transaction_id = 0, $extra_query='', $include_order_meta=true, $include_items = true) {
        $output = [];
        if($transaction_id) {
            // Fetch all orders with the given transaction ID
            $output = $this->get_orders("AND o.transaction_id='$transaction_id' $extra_query", $include_order_meta, $include_items);
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
        $items = $this->db->query("SELECT * FROM tbl_order_items AS item WHERE item.order_id='$order_id' AND item.item_type = 'line_item'")->getResultArray();
        $output = [];

        foreach($items as $i=>$item) {
            $order_item_id = $item['order_item_id'];

            $meta = $this->db->query("SELECT * FROM tbl_order_item_meta WHERE item_id=$order_item_id")->getResultArray();
            $output[$order_item_id] = $item;

            foreach($meta as $m) {
                $meta_key = $m['meta_key'];
                $meta_value = $m['meta_value'];
                $output[$order_item_id]['item_meta'][$meta_key] = $meta_value;
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

    public function order_meta($order_id=0, $key='', $single=false) {
        $output = [];
        $sql = "SELECT meta_key, meta_value FROM tbl_order_meta WHERE order_id='$order_id'";
        if($key) {
            $sql .= " AND meta_key='$key'";
        }

        $metas = $this->db->query($sql)->getResultArray();

        foreach ($metas as $meta) {
            $output[$meta['meta_key']] = $meta['meta_value'];
        }

        if($single) {
            $output = $output[$key];
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

    public function refund_order($order_id, $props) {
        $order = $this->get_order_by_id($order_id);
        $productModel = model('ProductsModel');
        $order_meta = $order['order_meta'];

        if($order_meta['payment_method'] === "squareup") {
            $refund_amount = $props['refund_amount'];
            $refund_stock = $props['refund_stock'];
            $refund_reason = $props['refund_reason'];


            if(!empty($order_meta['squareup_card_id'])) {
                $squareupModel = model('SquareupModel');

                $refund = $squareupModel->refundOrder($order_meta, $refund_amount, $refund_reason);

                if($refund->isSuccess()) {
                    $result = $refund->getBody();
                    $this->add_order_meta($order_id,'squareup_refund',json_encode($result));
                    $this->add_order_meta($order_id,'refund_reason',$refund_reason);

                    if($refund_stock) {
                        foreach($order['order_items'] as $item) {
                            if($item['item_type'] === "line_item") {
                                $qty = $item['item_meta']['quantity'];
                                $product_id = $item['item_meta']['product_id'];
                                $productModel->gain_stock($product_id, $qty);
                            }
                        }
                    }

                    $this->change_order_status($order_id,'refund');

                    $get_refund = $this->order_meta($order_id,'order_refund');
                    if(empty($get_refund)) {
                        $get_refund = [];
                    }else {
                        $get_refund = json_decode($get_refund['order_refund'],true);
                    }
                    $get_refund[] = [time() => $refund_amount];
                    $get_refund = json_encode($get_refund);
                    $this->add_order_meta($order_id,'order_refund',$get_refund);

                    return ['success'=>1];
                }else {
                    return $squareupModel->sq_errors($refund->getErrors());
                }

            }else {
                return ['success'=>0, 'errors'=>['Card not found']];
            }
        }else {
            return ['success'=>0, 'errors'=>['Payment method error']];
        }
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

    public function order_complete_email($order_id, $attachment=false)
    {
        $mail = new MailModel();

        $order = $this->get_order_by_id($order_id);

        $customer = $this->userModel->get_user($order['customer_user'],'email');

        $email_to = $customer->email;
        $billing_name = $order['billing_address']['billing_first_name'].' '.$order['billing_address']['billing_last_name'];

        $mailbody = $mail->get_parsed_content('order_complete', [
            'display_name'=>$billing_name,
            'order_id' => _order_number($order_id),
            'order_date' => date('F d, Y', strtotime($order['order_date'])),
            'order_receipt' => view('checkout/order_receipt',['order'=>$order,'subtotal_width'=>'100%','show_link'=>false])
        ]);

        if($attachment) {
            $mail->attach($attachment);
        }

        return $mail->send_email($email_to,$mailbody,[
            'mail_type' => 'order',
            'post_id' => _order_number($order_id)
        ]);
    }

    public function order_status_change_email($order_id, $old_status='', $attachment=false) {
        $mail = new MailModel();

        $order = $this->get_order_by_id($order_id);

        $customer = $this->userModel->get_user($order['customer_user'],'email');

        $email_to = $customer->email;

        $billing_name = $order['billing_address']['billing_first_name'].' '.$order['billing_address']['billing_last_name'];

        $mailbody = $mail->get_parsed_content('order_status_change', [
            'display_name'=>$billing_name,
            'order_id' => _order_number($order_id),
            'order_receipt' => view('checkout/order_receipt',['order'=>$order,'subtotal_width'=>'100%','show_link'=>false]),
            'old_order_status' => $old_status,
            'order_status' => $order['status'],
            'order_date' => date('F d, Y', strtotime($order['order_date'])),
        ]);

        if($attachment) {
            $mail->attach($attachment);
        }

        return $mail->send_email($email_to,$mailbody,[
            'mail_type' => 'order',
            'post_id' => $order_id
        ]);
    }

    public function cancelled_order_email($order_id) {
        $order = $this->get_order_by_id($order_id);

        $mail = new MailModel();

        $customer = $this->userModel->get_user($order['customer_user'],'email');

        $email_to = $customer->email;

        $billing_name = $order['billing_address']['billing_first_name'].' '.$order['billing_address']['billing_last_name'];


        $mailbody = $mail->get_parsed_content('order_cancelled', [
            'display_name'=>$billing_name,
            'order_id' => _order_number($order_id),
            'order_date' => date('F d, Y', strtotime($order['order_date'])),
            'order_receipt' => view('checkout/order_receipt',['order'=>$order,'subtotal_width'=>'100%','show_link'=>false])
        ]);

        return $mail->send_email($email_to,$mailbody,[
            'mail_type' => 'order',
            'post_id' => $order_id
        ]);
    }

    public function processing_order_email($order_id) {

         // Check if $order_id is an array and not empty
        if (is_array($order_id) && !empty($order_id)) {
            $order_id = reset($order_id);  // Get the first value from the array
        }
    
    
        $order = $this->get_order_by_id($order_id);

        $mail = new MailModel();

        $customer = $this->userModel->get_user($order['customer_user'],'email');

        $email_to = $customer->email;

        $billing_name = $order['billing_address']['billing_first_name'].' '.$order['billing_address']['billing_last_name'];


        $mailbody = $mail->get_parsed_content('order_processing', [
            'display_name'=>$billing_name,
            'order_id' => _order_number($order_id),
            'order_date' => date('F d, Y', strtotime($order['order_date'])),
            'order_receipt' => view('checkout/order_receipt',['order'=>$order,'subtotal_width'=>'100%','show_link'=>false])
        ]);

        return $mail->send_email($email_to,$mailbody,[
            'mail_type' => 'order',
            'post_id' => $order_id
        ]);
    }

    public function refund_order_email($order_id) {
        $order = $this->get_order_by_id($order_id);

        $mail = new MailModel();

        $customer = $this->userModel->get_user($order['customer_user'],'email');

        $email_to = $customer->email;

        $billing_name = $order['billing_address']['billing_first_name'].' '.$order['billing_address']['billing_last_name'];

        $mailbody = $mail->get_parsed_content('order_refund', [
            'display_name'=>$billing_name,
            'order_id' => _order_number($order_id),
            'order_date' => date('F d, Y', strtotime($order['order_date'])),
            'order_receipt' => view('checkout/order_receipt',['order'=>$order,'subtotal_width'=>'100%','show_link'=>false])
        ]);

        return $mail->send_email($email_to,$mailbody,[
            'mail_type' => 'order',
            'post_id' => $order_id
        ]);
    }

    public function processing_order_ship_email($order_id) {
        $order = $this->get_order_by_id($order_id);

        $mail = new MailModel();

        $customer = $this->userModel->get_user($order['customer_user'],'email');

        $email_to = $customer->email;

        $billing_name = $order['billing_address']['billing_first_name'].' '.$order['billing_address']['billing_last_name'];


        $mailbody = $mail->get_parsed_content('order_ready_to_ship', [
            'display_name'=>$billing_name,
            'order_id' => _order_number($order_id),
            'order_date' => date('F d, Y', strtotime($order['order_date'])),
            'order_receipt' => view('checkout/order_receipt',['order'=>$order,'subtotal_width'=>'100%','show_link'=>false])
        ]);

        return $mail->send_email($email_to,$mailbody,[
            'mail_type' => 'order',
            'post_id' => $order_id
        ]);
    }

    public function order_note_email($order_id, $note_text='',$note_author='') {
        $order = $this->get_order_by_id($order_id);

        $customer = $this->userModel->get_user($order['customer_user'],'email');

        $email_to = $customer->email;

        $billing_name = $order['billing_address']['billing_first_name'].' '.$order['billing_address']['billing_last_name'];

        $mail = new MailModel();

        $mailbody = $mail->get_parsed_content('order_note_added', [
            'display_name'=>$billing_name,
            'note_author' => $note_author,
            'note_text' => $note_text,
            'order_date' => date('F d, Y', strtotime($order['order_date'])),
            'order_id' => _order_number($order_id),
            'order_receipt' => view('checkout/order_receipt',['order'=>$order,'subtotal_width'=>'100%','show_link'=>false])
        ]);

        return $mail->send_email($email_to,$mailbody,[
            'mail_type' => 'order',
            'post_id' => $order_id
        ]);
    }

    public function order_emails($post_id, $post_type='') {
        $where = ['post_id'=>$post_id];
        if($post_type) {
            $where['post_type'] = $post_type;
        }
        return $this->db->table('tbl_mail_logs')->where($where)->orderBy('mail_id','desc')->get()->getResultArray();
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

                $output['cart_total'] = ($product_total + $output['shipping_cost']) - $output['discount_amount'];
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
        $userModel = model('UserModel');

        $slips = [];

        foreach($order_ids as $order_id) {
            $order = $this->get_order_by_id($order_id);
            $order['customer'] = $userModel->get_user($order['customer_user']);
            $slips[] = $order;
        }

        $this->data['slips'] = $slips;

        $settings = $this->db->query("SELECT value FROM tbl_settings WHERE title='website'")->getRowArray();

        $this->data['setting'] = json_decode($settings['value'],true);

        $slip_view = view('admin/orders/pdf_order_slip',$this->data);

        $dompdf->loadHtml($slip_view);

        $dompdf->render();

        $dompdf->stream(implode('-',$order_ids)." - Order Slip.pdf", array("Attachment" => false));

        exit;
    }

    public function generate_wholesale_invoice($order_ids=[], $save_file=false) {

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $userModel = model('UserModel');

        $settings = $this->db->query("SELECT value FROM tbl_settings WHERE title='website'")->getRowArray();

        $this->data['setting'] = json_decode($settings['value'],true);

        $slips = [];

        foreach($order_ids as $order_id) {
            $order = $this->get_order_by_id($order_id);
            $order['customer'] = $userModel->get_user($order['customer_user']);
            $slips[] = $order;
        }

        $this->data['slips'] = $slips;

        $slip_view =  view('admin/orders/pdf_wholesale_invoice',$this->data);

        $dompdf->loadHtml($slip_view);

        $dompdf->render();

        $file_name = implode('-',$order_ids)." - Wholesale Invoice Slip.pdf";

        if($save_file) {
            $output = $dompdf->output();
            file_put_contents(WRITEPATH.'uploads/documents/'.$file_name, $output);
            return WRITEPATH.'uploads/documents/'.$file_name;
        }else {
            $dompdf->stream($file_name, array("Attachment" => false));
            exit;
        }
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


                        $productName = $item['product_name'];
                        $line_subtotal = _price($item_meta['item_price']*$item_meta['quantity']);

                        $row_array[4] = $productName;
                        $row_array[5] = implode(', ',$productCatList);
                        $row_array[6] = _price($item_meta['item_price']);
                        $row_array[7] = $item_meta['quantity'];
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

         //pr($csv_array);

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

    public function set_transaction_id($order_id, $transaction_id) {
        $orderModel = model('OrderModel');
        $orderModel->add_order_meta($order_id,'transaction_id',$transaction_id);
        return $this->db->table('tbl_orders')->where(['order_id'=>$order_id])->update(['transaction_id'=>$transaction_id]);
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
        //$this->db->query("UPDATE tbl_coupons SET use_limit = use_limit-1 WHERE code='$coupon_code' AND use_limit > 0 AND is_unlimited=0");
    }


}


 