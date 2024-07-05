<?php
namespace App\Models;

use CodeIgniter\Config\Services;
use CodeIgniter\Model;
use PHPMailer\PHPMailer\Exception;
use Square\Environment;
use Square\SquareClient;


class SquareupModel extends Model {

    protected $request;
    private $UserModel;
    private $CartModel;
    private $OrderModel;

    private $squareClient;

    private $successMessage = 'Order completed successfully';

    public function __construct()
    {
        $this->request = Services::request();
        $this->CartModel = model('CartModel');
        $this->UserModel = model('UserModel');
        $this->OrderModel = model('OrderModel');

        $this->squareClient = new SquareClient([
            'accessToken' => getenv('squareup.token'),
            'environment' => Environment::SANDBOX
        ]);
    }

    public function process_squareup($customer_id='') {

        $square = $this->squareClient;

        $request = $this->request;
        $checkout = model('CheckoutModel');

        $idempotencyKey = uniqid();

        try {

            $squareupCustomerID = $this->UserModel->get_user_meta($customer_id,'squareup_customer_id',true);

            if(empty($squareupCustomerID)) {
                $createCustomer = $this->createSquareupCustomer($idempotencyKey);
            }else {
                $createCustomer = $this->createSquareupCustomer($idempotencyKey, $squareupCustomerID);
                $cardID = $this->UserModel->get_user_meta($customer_id,'squareup_customer_card',true);
            }

            if ($createCustomer->isSuccess()) {
                $result = $createCustomer->getBody();
                $res = json_decode($result,true);
                $squareupCustomerID = $res['customer']['id'];
                $this->UserModel->update_meta([
                    'squareup_customer_id'=>$squareupCustomerID
                ], $customer_id);

                $Card = $this->SquareupCard($squareupCustomerID, $idempotencyKey, $customer_id);

                if($Card->isSuccess()) {
                    $cardData = $Card->getBody();
                    $res = json_decode($cardData,true);
                    $cardID = $res['card']['id'];
                    $this->UserModel->update_meta([
                        'squareup_customer_card'=>$cardID
                    ], $customer_id);
                }else {
                    $errors = $this->sq_errors($Card->getErrors());
                    echo json_encode($errors);
                    exit;
                }
            }

            if($createCustomer->isSuccess()) {
                $idempotencyKey = uniqid();

                $getCart = $this->CartModel->get_cart();
                $subtotal = $getCart['subtotal'];

                $payment = $this->chargeCard($idempotencyKey, $squareupCustomerID, $cardID, $subtotal);

                if ($payment->isSuccess()) {
                    $result = json_decode($payment->getBody(),true);
                    $paymentData = $result['payment'];
                    $transaction_id = $paymentData['id'];

                    $orderPost = $request->getPost();
                    $orderPost['post_status'] = 'completed';

                    $orderID = $this->CartModel->create_order($request->getPost());

                    $this->OrderModel->add_order_meta($orderID,'transaction_id',$transaction_id);

                    $this->OrderModel->add_order_meta($orderID,'squareup_body',$payment->getBody());

                    $checkout->orderCompleteActions($orderID, $customer_id);

                    return ['success'=>1,'message'=>$this->successMessage,'orderID'=>$orderID];

                } else {
                    $errors = $this->sq_errors($payment->getErrors());
                    echo json_encode($errors);
                    exit;
                }
            }

        }catch (Exception $e) {
            return ['success'=>0,'message'=>$e->errorMessage(),'orderID'=>0];
        }

    }

    private function createSquareupCustomer($idempotencyKey, $customerID='') {

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

    public function getCard($cardID) {
        $cardExists = false;
        $CardApi = $this->squareClient->getCardsApi()->retrieveCard($cardID);
    }

    private function SquareupCard($sq_customerID='', $idempotencyKey='', $customer_id=0) {

        $card = new \Square\Models\Card();

        $cardID = $this->UserModel->get_user_meta($customer_id,'squareup_customer_card',true);
        $customer = $this->UserModel->get_user($customer_id);

        $square = $this->squareClient;

        $cardExists = false;

        if($cardID) {
            $CardApi = $square->getCardsApi()->retrieveCard($cardID);
            if($CardApi->isSuccess()) {
                $cardExists = true;
            }
        }



        if(!$cardExists) {
            $card->setCardholderName($customer->fname.' '.$customer->lname);
            $card->setCustomerId($sq_customerID);
            $card->setReferenceId('user-id-'.$customer_id);

            $token = $this->request->getPost('token');

            $createCard = new \Square\Models\CreateCardRequest(
                $idempotencyKey,
                $token,
                $card
            );

            $CardApi = $square->getCardsApi()->createCard($createCard);
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

    private function sq_errors($errors=[]) {
        $error_list = [
            'success' => 0,
            'message'
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