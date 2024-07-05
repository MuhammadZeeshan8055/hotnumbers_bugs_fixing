<?php

namespace App\Controllers;
use App\Models\MailModel;
use App\Models\MasterModel;
use App\Models\OrderModel;
use App\Models\Req_wholesales_acc;
use CodeIgniter\Controllers;
use CodeIgniter\Exceptions\PageNotFoundException;
use App\Libraries;

class Payment extends MyController
{
    public function paypal_transaction() {
        $paypal_lib = new Libraries\paypal_lib();
        $validate_ipn = $paypal_lib->validate_ipn();
        $master = new MasterModel();
        $order = new OrderModel();
        $mail = new MailModel();

        $postData = $this->request->getPost();



        if($postData['custom']) {
            $order_data = $order->get_order($postData['custom']);
            $dbdata = [
              'txn_id'=>  $postData['txn_id'],
              'status'=> $postData['payment_status'],
              'ipn_verified'=> empty($validate_ipn) ? 0 : 1,
              'order_data'=>json_encode($postData)
            ];
            $master->insertData('tbl_orders',$dbdata,'order_id',$postData['custom']);

            if(!empty($postData['verify_sign'])) {

                $mailbody = $mail->get_parsed_content('order_complete',[
                    'display_name'=>$order_data['billing_fname'].' '.$order_data['billing_lname'],
                    'order_id'=>$postData['custom'],
                    'order_receipt'=>view('checkout/order_receipt',['order'=>$order_data])
                ]);

                $mail->send_email($order_data['billing_email'],$mailbody);


                return redirect()->to(base_url('payment/success/'.$postData['custom']));
            }else {

                $mailbody = $mail->get_parsed_content('order_failure',[
                    'display_name'=>$order_data['billing_fname'].' '.$order_data['billing_lname'],
                    'order_id'=>$postData['custom'],
                    'order_receipt'=>view('checkout/order_receipt',['order'=>$order_data])
                ]);

                $mail->send_email($order_data['billing_email'],$mailbody);

                return redirect()->to(base_url('payment/failure/'.$postData['custom']));
            }
        }
    }

    public function paypal_ipn() {
        $paypal_lib = new Libraries\paypal_lib();
        $validate_ipn = $paypal_lib->validate_ipn();
    }

    public function success_page($order_id) {
        $order = new OrderModel();
        $getorder = $order->get_order($order_id);

        echo view('checkout/checkout_complete',['order_id'=>$order_id,'order'=>$getorder]);
    }


}