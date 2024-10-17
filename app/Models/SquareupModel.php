<?php
namespace App\Models;

use CodeIgniter\Config\Services;
use CodeIgniter\Model;
use PHPMailer\PHPMailer\Exception;
use Square\Environment;
use Square\SquareClient;


class SquareupModel extends Model {


    private $request;

    private $UserModel;
    private $CartModel;
    private $OrderModel;
    private $masterModel;

    private $squareClient;

    private $successMessage = 'Order placed successfully';

    public function __construct()
    {
        $this->request = Services::request();

        $this->CartModel = model('CartModel');
        $this->UserModel = model('UserModel');
        $this->OrderModel = model('OrderModel');
        $this->masterModel = model('MasterModel');

        $this->squareClient = new SquareClient([
            'accessToken' => getenv('squareup.token'),
            'environment' => Environment::SANDBOX
        ]);
    }

    // public function process_squareup($customer_id='') {

    //     $square = $this->squareClient;

    //     $request = $this->request;
    //     $checkout = model('CheckoutModel');

    //     $idempotencyKey = uniqid();

    //     try {
    //         //$squareupCustomerID = $this->UserModel->get_user_meta($customer_id,'squareup_customer_id',true);
    //         $isSqCustomer = false;
    //         if(!empty($request->getPost('customer_user_card'))) {
    //             $cid = (int)$request->getPost('customer_user_card');
    //             $uid = is_logged_in();
    //             $getCard = $this->masterModel->query("SELECT card_id, value FROM tbl_card_meta WHERE user_id='$uid' AND id='$cid'",true,true);
    //             if(!empty($getCard['card_id'])) {
    //                 $cardID = $getCard['card_id'];
    //                 $cardvalue = !empty($getCard['value']) ? json_decode($getCard['value'],true) : '';

    //                 if(!empty($cardvalue['card']['customer_id'])) {
    //                     $sqCustomer = $square->getCustomersApi()->retrieveCustomer($cardvalue['card']['customer_id']);
    //                     if($sqCustomer->isSuccess()) {
    //                         $isSqCustomer = true;
    //                     }
    //                 }
    //             }else {
    //                 echo json_encode(['Invalid card']);
    //                 exit;
    //             }
    //         }else {
    //             $sqCustomer = $this->createSquareupCustomer($idempotencyKey);
    //             if($sqCustomer->isSuccess()) {
    //                 $isSqCustomer = true;
    //             }
    //         }

    //         if(!empty($sqCustomer->getErrors())) {
    //             return ['success'=>0,'message'=>$sqCustomer->getErrors(),'orderID'=>0];
    //         }else if ($isSqCustomer) {
    //             $result = $sqCustomer->getBody();
    //             $res = json_decode($result,true);
    //             $squareupCustomerID = $res['customer']['id'];
    //             $this->UserModel->update_meta(['squareup_customer_id'=>$squareupCustomerID], $customer_id);

    //             if(empty($request->getPost('customer_user_card'))) {
    //                 $Card = $this->SquareupCard($squareupCustomerID, $idempotencyKey, $customer_id);
    //                 if($Card->isSuccess()) {
    //                     $cardData = $Card->getBody();
    //                     $res = json_decode($cardData,true);
    //                     $cardID = $res['card']['id'];
    //                 }else {
    //                     $errors = $this->sq_errors($Card->getErrors());
    //                     echo json_encode($errors);
    //                     exit;
    //                 }
    //             }

    //             $idempotencyKey = uniqid();

    //             $getCart = $this->CartModel->get_cart();
    //             $subtotal = $getCart['cart_total'];

    //             $payment = $this->chargeCard($idempotencyKey, $squareupCustomerID, $cardID, $subtotal);

    //             if ($payment->isSuccess()) {
    //                 $result = json_decode($payment->getBody(),true);
    //                 $paymentData = $result['payment'];
    //                 $transaction_id = $paymentData['id'];

    //                 $orderPost = $request->getPost();
    //                 $orderPost['post_status'] = 'completed';

    //                 $postData = $request->getPost();
    //                 $postData['customer_id'] = $customer_id;
    //                 $postData['order_paid'] = 1;

    //                 $orderID = $this->CartModel->create_order($postData);

    //                 $this->OrderModel->add_order_meta($orderID,'transaction_id',$transaction_id);

    //                 $this->OrderModel->set_transaction_id($orderID, $transaction_id);

    //                 $this->OrderModel->add_order_meta($orderID,'squareup_body',$payment->getBody());
    //                 $this->OrderModel->add_order_meta($orderID,'squareup_card_id',$cardID);
    //                 $this->OrderModel->update_order_meta($orderID,['paid_date'=>date('Y-m-d h:i:s')]);

    //                 $checkout->orderCompleteActions($orderID, $customer_id);

    //                 return ['success'=>1,'message'=>$this->successMessage,'orderID'=>$transaction_id];

    //             } else {
    //                 $errors = $this->sq_errors($payment->getErrors());
    //                 echo json_encode($errors);
    //                 exit;
    //             }
    //         }

    //     }catch (Exception $e) {
    //         return ['success'=>0,'message'=>$e->errorMessage(),'orderID'=>0];
    //     }

    // }


    public function process_squareup($customer_id='') {
        $square = $this->squareClient;
        $request = $this->request;
        $checkout = model('CheckoutModel');
        $idempotencyKey = uniqid();
    
        try {
            // Check or create Square customer
            $isSqCustomer = false;
            if (!empty($request->getPost('customer_user_card'))) {
                $cid = (int)$request->getPost('customer_user_card');
                $uid = is_logged_in();
                $getCard = $this->masterModel->query("SELECT card_id, value FROM tbl_card_meta WHERE user_id='$uid' AND id='$cid'", true, true);
                if (!empty($getCard['card_id'])) {
                    $cardID = $getCard['card_id'];
                    $cardvalue = !empty($getCard['value']) ? json_decode($getCard['value'], true) : '';
    
                    if (!empty($cardvalue['card']['customer_id'])) {
                        $sqCustomer = $square->getCustomersApi()->retrieveCustomer($cardvalue['card']['customer_id']);
                        if ($sqCustomer->isSuccess()) {
                            $isSqCustomer = true;
                        }
                    }
                } else {
                    echo json_encode(['Invalid card']);
                    exit;
                }
            } else {
                $sqCustomer = $this->createSquareupCustomer($idempotencyKey);
                if ($sqCustomer->isSuccess()) {
                    $isSqCustomer = true;
                }
            }
    
            if (!empty($sqCustomer->getErrors())) {
                return ['success' => 0, 'message' => $sqCustomer->getErrors(), 'orderID' => 0];
            } else if ($isSqCustomer) {
                $result = $sqCustomer->getBody();
                $res = json_decode($result, true);
                $squareupCustomerID = $res['customer']['id'];
                $this->UserModel->update_meta(['squareup_customer_id' => $squareupCustomerID], $customer_id);
    
                if (empty($request->getPost('customer_user_card'))) {
                    $Card = $this->SquareupCard($squareupCustomerID, $idempotencyKey, $customer_id);
                    if ($Card->isSuccess()) {
                        $cardData = $Card->getBody();
                        $res = json_decode($cardData, true);
                        $cardID = $res['card']['id'];
                    } else {
                        $errors = $this->sq_errors($Card->getErrors());
                        echo json_encode($errors);
                        exit;
                    }
                }
    
                $idempotencyKey = uniqid();
                $getCart = $this->CartModel->get_cart();
                $subtotal = $getCart['cart_total'];
    
                $payment = $this->chargeCard($idempotencyKey, $squareupCustomerID, $cardID, $subtotal);
    
                if ($payment->isSuccess()) {
                    $result = json_decode($payment->getBody(), true);
                    $paymentData = $result['payment'];
                    $transaction_id = $paymentData['id'];
    
                    $orderPost = $request->getPost();
                    $orderPost['post_status'] = 'completed';
                    $orderPost['order_paid'] = 1;
    
                    // Create orders
                    $orderIDs = $this->CartModel->create_order($orderPost);
    
                    if (isset($orderIDs['subscription'])) {
                        $this->OrderModel->add_order_meta($orderIDs['subscription'], 'transaction_id', $transaction_id);
                        $this->OrderModel->set_transaction_id($orderIDs['subscription'], $transaction_id);
                        $this->OrderModel->add_order_meta($orderIDs['subscription'], 'squareup_body', $payment->getBody());
                        $this->OrderModel->add_order_meta($orderIDs['subscription'], 'squareup_card_id', $cardID);
                        $this->OrderModel->update_order_meta($orderIDs['subscription'], ['paid_date' => date('Y-m-d h:i:s')]);
                        $checkout->orderCompleteActions($orderIDs['subscription'], $customer_id);
                    }
    
                    if (isset($orderIDs['product'])) {
                        $this->OrderModel->add_order_meta($orderIDs['product'], 'transaction_id', $transaction_id);
                        $this->OrderModel->set_transaction_id($orderIDs['product'], $transaction_id);
                        $this->OrderModel->add_order_meta($orderIDs['product'], 'squareup_body', $payment->getBody());
                        $this->OrderModel->add_order_meta($orderIDs['product'], 'squareup_card_id', $cardID);
                        $this->OrderModel->update_order_meta($orderIDs['product'], ['paid_date' => date('Y-m-d h:i:s')]);
                        $checkout->orderCompleteActions($orderIDs['product'], $customer_id);
                    }
    
                    return ['success' => 1, 'message' => $this->successMessage, 'orderID' => $transaction_id];
    
                } else {
                    $errors = $this->sq_errors($payment->getErrors());
                    echo json_encode($errors);
                    exit;
                }
            }
    
        } catch (Exception $e) {
            return ['success' => 0, 'message' => $e->getMessage(), 'orderID' => 0];
        }
    }
    

    public function createSquareupCustomer($idempotencyKey, $customerID='') {

        $square = $this->squareClient;

        $customer = $square->getCustomersApi();
        $request = $this->request;

        $billing_address_1 = $request->getPost('billing_address_1');
        $billing_address_2 = $request->getPost('billing_address_2');
        $billing_first_name = $request->getPost('billing_first_name');
        $billing_last_name = $request->getPost('billing_last_name');
        $billing_phone = $request->getPost('billing_phone');
        $email_address = $request->getPost('email_address');
        $billing_country = $request->getPost('billing_country');
        $billing_postcode = $request->getPost('billing_postcode');

        $address_body = new \Square\Models\Address();

        $address_body->setAddressLine1($billing_address_1);
        $address_body->setAddressLine2($billing_address_2);
        $address_body->setCountry($billing_country);
        $address_body->setPostalCode($billing_postcode);

        if($customerID && $customer->retrieveCustomer($customerID)->isSuccess()) {
            $customer_body = new \Square\Models\UpdateCustomerRequest();

            $customer_body->setNickname($billing_first_name.' '.$billing_last_name);
            $customer_body->setFamilyName($billing_first_name.' '.$billing_last_name);
            $customer_body->setAddress($address_body);
            $customer_body->setEmailAddress($email_address);

            $isCustomer = $customer->updateCustomer($customerID, $customer_body);
        }else {
            $customer_body = new \Square\Models\CreateCustomerRequest();

            $customer_body->setIdempotencyKey($idempotencyKey);
            $customer_body->setNickname($billing_first_name.' '.$billing_last_name);
            $customer_body->setFamilyName($billing_first_name.' '.$billing_last_name);

            $customer_body->setAddress($address_body);
            $customer_body->setEmailAddress($email_address);

            $isCustomer = $square->getCustomersApi()->createCustomer($customer_body);
        }

        return $isCustomer;
    }

    public function getCard($cardID, $user_id) {
        $card_arr = [];
        $db_exists = $this->masterModel->query("SELECT id,value,status FROM tbl_card_meta WHERE user_id='$user_id' AND card_id='$cardID'",true,true);
        if(!empty($db_exists) && !$db_exists['status']) {
            return $card_arr;
        }
        if(empty($db_exists)) {
            $CardApi = $this->squareClient->getCardsApi()->retrieveCard($cardID);
            if($CardApi->isSuccess()) {
                $result = $CardApi->getBody();
                $card_arr = json_decode($result,true);
                $db_data = json_encode($card_arr);
                $this->masterModel->query("INSERT INTO tbl_card_meta SET user_id='$user_id', card_id='$cardID', value='$db_data'");
                $insert_id = $this->masterModel->last_insert_id();
                $card_arr['db_id'] = $insert_id;
            }
        }else {
           $card_arr = json_decode($db_exists['value'],true);
            $card_arr['db_id'] = $db_exists['id'];
        }
        return $card_arr;
    }

    public function disableCard($cardID, $user_id) {
        $this->squareClient->getCardsApi()->disableCard($cardID);
        $this->masterModel->query("UPDATE tbl_card_meta SET status=0 WHERE card_id='$cardID' AND user_id='$user_id'");
    }

    public function SquareupCard($sq_customerID='', $idempotencyKey='', $customer_id=0, $cardholdername='') {

        $card = new \Square\Models\Card();

       // $cardID = $this->UserModel->get_user_meta($customer_id,'squareup_customer_card',true);

        if(!$cardholdername) {
            $customer = $this->UserModel->get_user($customer_id);
            $cardholdername = display_name($customer);
        }

        $square = $this->squareClient;

        $cardExists = false;

        $card->setCardholderName($cardholdername);
        $card->setCustomerId($sq_customerID);
        $card->setReferenceId('user-id-'.$customer_id);

        $token = $this->request->getPost('token');

        $createCard = new \Square\Models\CreateCardRequest(
            $idempotencyKey,
            $token,
            $card
        );

        $CardApi = $square->getCardsApi()->createCard($createCard);

        if($CardApi->isSuccess()) {
            $cardData = $CardApi->getBody();
            $res = json_decode($cardData,true);
            $cardID = $res['card']['id'];
            $userCards = $this->UserModel->get_user_meta($customer_id,'squareup_customer_cards', true);
            if(empty($userCards)) {
                $userCards = [];
            }
            if(!empty($userCards)) {
                $userCards = json_decode($userCards, true);
                $userCards[] = $cardID;
            }else {
                $userCards[] = $cardID;
            }
            $userCards = json_encode($userCards);
            $this->UserModel->update_meta(['squareup_customer_cards'=>$userCards], $customer_id);
        }

        return $CardApi;
    }

    public function chargeCard($idempotencyKey, $customer_id='', $cardID='', $amount='', $note='') {

        $subtotal_ = round($amount * 100);

        $amount_money = new \Square\Models\Money();
        $amount_money->setAmount($subtotal_);
        $amount_money->setCurrency(get_setting('currency'));

        $body = new \Square\Models\CreatePaymentRequest($cardID, $idempotencyKey);
        $body->setAmountMoney($amount_money);
        if($note) {
            $body->setNote($note);
        }

        if($customer_id) {
            $body->setCustomerId($customer_id);
        }

        return $this->squareClient->getPaymentsApi()->createPayment($body);
    }

    public function refundOrder($order_meta, $refund_amount, $reason='') {
        $payment_id = $order_meta['transaction_id'];
        $currency = $order_meta['order_currency'];

        $amount = round($refund_amount * 100);

        $amount_money = new \Square\Models\Money();
        $amount_money->setAmount($amount);
        $amount_money->setCurrency($currency);

        $idempotencyKey = uniqid();

        $body = new \Square\Models\RefundPaymentRequest($idempotencyKey, $amount_money);
        $body->setPaymentId($payment_id);
        $body->setReason($reason);

        $square = $this->squareClient;

        return $square->getRefundsApi()->refundPayment($body);
    }

    public function sq_errors($errors=[]) {
        $error_list = [
            'success' => 0,
            'message' => []
        ];
        foreach ($errors as $error) {
            $category = $error->getCategory();
            $code = $error->getCode();
            $detail = $error->getDetail();
            $error_list['message'][] = [
                'category' => $category,
                'code' => $code,
                'detail' => $detail,
            ];
        }
        return $error_list;
    }
}