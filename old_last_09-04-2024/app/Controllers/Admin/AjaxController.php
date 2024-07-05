<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class AjaxController extends BaseController
{
    public function __construct()
    {

    }

    public function admin_product_info_json($pid=0) {
        $productModel = model('ProductsModel');
        $product = $productModel->product_by_id($pid);
        if (!empty($product)) {
            $variations = $productModel->product_variations($pid);
            $product->variations = $variations;

            $attributes = $productModel->get_attributes($pid);
            $product->attributes = $attributes;
        }

        echo json_encode($product);
    }

    public function product_list_json() {
        $productModel = model('ProductsModel');
        $s = !empty($_GET['term']) ? "AND title LIKE '%".$_GET['term']."%'" : '';
        $fields = !empty($_GET['fields']) ? $_GET['fields'] : '*';
        $products = $productModel->get_products($fields,$s);
        echo json_encode($products);
    }

    public function product_categories_json() {
        $productModel = model('ProductsModel');
        $categories = $productModel->get_shop_categories();
        echo json_encode($categories);
    }

    public function get_order_preview($oid='') {
        $orderModel = model('OrderModel');
        $order = $orderModel->get_order_by_id($oid);
        if(!empty($order)) {
            $billing_address = $order['billing_address'];
            $shipping_address = $order['shipping_address'];
            $meta = $order['order_meta'];

            $billing_addr = @$billing_address['billing_address_1'].' '.@$billing_address['billing_address_2'];
            $shipping_addr = @$shipping_address['shipping_first_name'].' '.@$shipping_address['shipping_last_name'].' '.@$shipping_address['shipping_address_1'].' '.@$shipping_address['shipping_address_2'].' '.@$shipping_address['shipping_city'].' '.@$shipping_address['shipping_postcode'];

            $items = $order['order_items'];

            ?>
            <div class="order-preview bootstrap-wrapper">

                <div class="head">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-5"><h1>Order #<?php echo $order['order_id'] ?></h1></div>
                        <div class="col-md-7">
                            <div class="pull-right order-status status-<?php echo $order['status'] ?>">Status: <?php echo ucfirst($order['status']) ?></div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="row align-items-center content justify-content-center pt-20">
                    <div class="col-md-6">
                        <h2>Billing details</h2>
                        <div>
                            <p><?php echo @$billing_address['billing_first_name'] ?> <?php echo @$billing_address['billing_last_name'] ?></p>
                            <?php if(!empty($billing_addr)){ ?>
                                <p><a href="https://maps.google.com/maps?&q=<?php echo urlencode($billing_addr) ?>" target="_blank"><?php echo $billing_addr ?></a> </p>
                            <?php } ?>
                        </div>
                        <div class="pt-10">
                            <label>Email</label>
                            <p><?php echo @$billing_address['billing_email'] ?></p>
                        </div>
                        <?php if(!empty($billing_address['payment_method_title'])) { ?>
                            <div class="pt-10">
                                <label>Payment via</label>
                                <p><?php echo @$billing_address['payment_method_title'] ?></p>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-6">
                        <h2>Shipping details</h2>

                        <div class="pt-0">
                            <?php if(!empty($shipping_addr)){ ?>
                                <p><a href="https://maps.google.com/maps?&q=<?php echo urlencode($shipping_addr) ?>" target="_blank"><?php echo $shipping_addr ?></a> </p>
                            <?php } ?>
                        </div>
                        <?php if(!empty($meta['order_shipping_title'])) { ?>
                            <div class="pt-10">
                                <label>Shipping method</label>
                                <p><?php echo @$meta['order_shipping_title'] ?></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="content">

                    <div class="clear pt-20"></div>

                    <?php echo view('checkout/order_receipt', ['order' => $order]); ?>

                    <div class="clear"></div>


                </div>
                <div class="foot">
                    <div class="row">
                        <div class="col-md-6">
                            <?php if($order['status'] !== "completed") {
                                ?>
                                <a href="<?php echo admin_url().'change-order-status/completed?orders='.$order['order_id'] ?>" class="btn btn-sm btn-primary mark-completed">Complete Order</a>
                                <?php
                            }?>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="<?php echo admin_url().'orders/view/'.$order['order_id'] ?>" class="btn btn-sm btn-primary">View</a>
                            <a href="<?php echo admin_url().'orders/edit/'.$order['order_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        </div>
                    </div>
                </div>


            </div>
            <?php
        }
    }

    public function user_list_json() {
        $user = model('UserModel');
        $s = !empty($_GET['term']) ? $_GET['term'] : '';
        $users = $user->get_users("user.user_id,user.fname,user.lname,user.display_name,user.email",'any','any',false,$s);
        echo json_encode($users);
    }

    public function user_roles_json() {
        $roles = shop_roles('','any');
        echo json_encode($roles);
    }

    public function coupon_info_json($coupon_id) {
        $master = model('MasterModel');
        $coupon = $master->getRow('tbl_coupons',['id'=>$coupon_id]);
        echo json_encode($coupon);
    }

    public function address_tax_json() {
        $post = $this->request->getPost();
        $tax_settings = get_tax_rate([
            'country' => $post['country'],
            'postcode' => $post['postcode'],
            'city' => $post['city']
        ]);

        $output = [];

        foreach(get_setting('shippingmethods',true) as $i=>$method) {

            $method_amount = $method['value'];

            $vat_price = 0;

            if (!empty($tax_settings['amount']) && get_setting('tax_on_shipping')) {
                $method_vat = $tax_settings['amount'];
                $tax_name = $tax_settings['tax_name'];
                if ($tax_settings['type'] === "percent") {
                    $vat_price = ($method_vat / 100) * $method_amount;
                    $method_amount += ($method_vat / 100) * $method_amount;
                } else {
                    $vat_price = $method_vat;
                    $method_amount += $method_vat;
                }
            }

            $method_amount = number_format($method_amount, 2);

            $output[] = [
                'name' => $method['name'],
                'value' => $method['value'],
                'tax' => $vat_price,
                'method_amount' => $method_amount
            ];
        }

        echo json_encode($output);

    }

    public function edit_product_form_table() {
        echo view('admin/orders/edit_product_form');
    }
}