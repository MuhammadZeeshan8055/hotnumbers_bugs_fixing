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
                            <?php if(order_editable($order)) { ?>
                            <a href="<?php echo admin_url().'orders/edit/'.$order['order_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>


            </div>
            <?php
        }
    }

    public function get_email_preview($mail_id='') {
        $email = model('MailModel');
        $master = model('MasterModel');
        $mail = $master->getRow('tbl_mail_templates',['id'=>$mail_id]);
        if($mail) {
            $content = base64_decode($mail['content']);
            if($content) {
                echo $email->get_mail_header();
                echo '<div style="padding-bottom: 1em; padding-top: 1em;">';
                echo $content;
                echo '</div>';
                echo $email->get_mail_footer();
            }
        }
    }

    public function user_list_json() {
        $user = model('UserModel');
        $s = !empty($_GET['term']) ? $_GET['term'] : '';
        $users = $user->get_users("user.username,user.user_id,user.fname,user.lname,user.display_name,user.email",'any','any',false,$s);
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
        $tax_settings = get_address_tax([
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

    public function email_template_sort_order() {
        $master = model('MasterModel');
        if(!empty($_POST)) {
            foreach(json_decode($_POST['data'],true) as $post) {
                $order = $post['order'];
                $id = $post['id'];
                $master->query("UPDATE tbl_mail_templates SET sort_order='$order' WHERE id='$id'");
            }
            echo json_encode(['success'=>1]);
        }
        exit;
    }

    public function wholesale_customer_notes($id='') {
        $uri = service('uri');
        $uid = $uri->getSegment(4);
        $master = model('MasterModel');
        $getComments = $master->query("SELECT * FROM tbl_comments WHERE comment_type='wholesale_user_note' AND comment_post_ID='$uid' ORDER BY comment_ID DESC", true);
        $comments_html = '';

        if(!empty($getComments)) {
            $comments_html = '<div class="chat-list">';
            foreach($getComments as $comment) {
                $comment_date = _date_full($comment['comment_date']);
                $comment_author = $comment['comment_author'];
                $comment_visibility = $comment['comment_visibility'];
                $text = urldecode($comment['comment_content']);
                $commentID = $comment['comment_ID'];

                $comments_html.= '<div class="chat-box text-left">
                                    <div class="chat-meta">
                                        <div class="date">'.$comment_date.'</div> by '.$comment_author.'</div>
                                    <div class="chat-text">'.$text.'</div>
                                    <div class="chat-actions">
                                       
                                        <button name="order_note_delete" class="btn-primary btn btn-sm" value="'.$commentID.'" onclick="return confirm(\'Are you sure to delete this customer note?\')">Delete</button>
                                    </div>
                                </div>';
            }
            $comments_html .= '</div>';
        }
        echo json_encode(['success'=>1, 'data'=>$comments_html]);
        exit;
    }

    public function check_product_slug() {
        $slug = $this->request->getGet('slug');
        $res = ['exists'=>0];
        if($slug) {
            $productModel = model('ProductsModel');
            $check = $productModel->product_by_slug($slug,'product.id');
            if(!empty($check)) {
                $res = ['exists'=>1];
            }
        }
        echo json_encode($res);
        exit;
    }

    private function shipping_rules_array() {
        $rules_data = [];
        $shipment_rules = $this->request->getPost('shipment_rule');
        $shipment_rules = !empty($shipment_rules) ? $shipment_rules : [];

        foreach($shipment_rules as $idx=>$rules) {
            if(!empty($rules['subject'])) {
                $option_data = [];
                foreach ($rules['subject'] as $i => $subject) {
                    $condition = !empty($rules['condition'][$i]) ? $rules['condition'][$i] : '';
                    $value = !empty($rules['value'][$i]) ? $rules['value'][$i] : '';
                    $option_data[] = [
                        'subject' => $subject,
                        'condition' => $condition,
                        'value' => $value
                    ];
                }
                $rules_data[$idx] = [
                    'option_name' => $rules['option_name'],
                    'option_value' => $rules['option_value'],
                    'option_type' => !empty($rules['option_type']) ? $rules['option_type'] : '',
                    'option_data' => $option_data
                ];
            }
        }
        return $rules_data;
    }

    public function add_shipping_rule() {
        $rules_data = $this->shipping_rules_array();
        $arr_temp = [
            'option_name' => '',
            'option_value' => '',
            'option_type' => '',
            'option_data' => []
        ];

        $rules_data[] = $arr_temp;
        $rules_data = array_values($rules_data);
        $shipment_rules_json = json_encode($rules_data);
        $masterModel = model('MasterModel');
        $masterModel->insertOrUpdate('tbl_settings', ['title'=>'shipment_rule','value'=>$shipment_rules_json], 'title', 'shipment_rule');
        echo json_encode(['success'=>1]);

        exit;
    }

    public function remove_shipping_rule() {
        $idx = $this->request->getPost('remove_rule_id');
        $shipment_rules = $this->shipping_rules_array();

        if(!empty($shipment_rules[$idx])) {
            unset($shipment_rules[$idx]);
        }

        $shipment_rules = array_values($shipment_rules);
        $shipment_rules_json = json_encode($shipment_rules);
        $masterModel = model('MasterModel');
        $masterModel->insertOrUpdate('tbl_settings', ['title'=>'shipment_rule','value'=>$shipment_rules_json], 'title', 'shipment_rule');
        echo json_encode(['success'=>1]);
        exit;
    }

    public function add_product_attribute() {
        $product_id = $this->request->getGet('pid');
        $productModel = model('ProductsModel');
        $masterModel = model('MasterModel');
        $product = $productModel->product_by_id($product_id,'attributes');

        if(!empty($product)) {
            $attrs = !empty($product->attributes) ? json_decode($product->attributes,true) : [];
            $attrs[] = [
                'label'=>'',
                'value'=>[],
                'attribute_visibility'=>0,
                'attribute_variation'=>0,
            ];
            $attrs = json_encode($attrs);
            $masterModel->insertData('tbl_products',['attributes'=>$attrs],'id',$product_id);
        }

        $attribes = $productModel->get_attributes($product_id);
        $attrib_body = $productModel->product_attribute_body($attribes);

        echo $attrib_body;
        exit;
    }

    public function add_product_variation() {
        if (!empty($attribes)) {
            foreach ($attribes as $attribute) {
                if(!empty($attribute['attribute_variation'])) {
                    $label_id = strtolower($attribute['label']);
                    $label_id = str_replace(' ','-',$label_id);
                    $label_id = 'attribute_'.$label_id;
                    $variations_arr[$label_id] = $attribute['value'];
                }
            }
        }
        ?>
        <div class="table-box">
            <label>Variations 1
                <span class="pull-right"><a href="" class="color-base"><i class="lni lni-close"></i></a> </span>
            </label>
            <div class="content">
                <div id="variation_list">
                    <div class="input_variations">
                        <div class="d-inline-block input_field">
                            <label>Variation 1</label>
                            <div>
                                <select class="select2" name="">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        &nbsp;&nbsp;
                        <div class="d-inline-block input_field">
                            <label>Variation 1</label>
                            <div>
                                <select class="select2" name="">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12"></div>

                    <div class="variation_options">
                        <div class="row">
                            <div class="col-md-3 input_field">
                                <label>SKU</label>
                                <div>
                                    <input type="text" name="">
                                </div>
                            </div>
                            <div class="col-md-3 input_field">
                                <label>Regular price (<?php echo currency_symbol ?>)</label>
                                <div>
                                    <input type="number" name="">
                                </div>
                            </div>
                            <div class="col-md-3 input_field">
                                <label>Sale price (<?php echo currency_symbol ?>)</label>
                                <div>
                                    <input type="number" name="">
                                </div>
                            </div>
                        </div>

                        <div class="mt-20"></div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-inline-block input_field inline-checkbox">
                                    <label>
                                        <input type="checkbox">
                                        Stock Management</label>
                                </div>
                                &nbsp;&nbsp;
                                <div class="d-inline-block input_field inline-checkbox">
                                    <label>
                                        <input type="checkbox">
                                        Subscription plan</label>
                                </div>

                                <div class="mt-20"></div>
                            </div>


                            <div class="col-md-3 input_field">
                                <label>Stock Quantity</label>
                                <input name="">
                            </div>
                            <div class="col-md-3 input_field">
                                <label>Low Stock Threshold</label>
                                <input name="">
                            </div>
                            <div class="col-md-3 input_field">
                                <label>Stock Status</label>
                                <div>
                                    <select class="select2">
                                        <option value="instock">In Stock</option>
                                        <option value="outofstock">Out of Stock</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-20"></div>

                        <div class="row">
                            <div class="col-md-3 input_field">
                                <label>Weight (Kg)</label>
                                <input name="" type="number">
                            </div>

                            <div class="col-md-3 input_field">
                                <label>Tax Status</label>
                                <div>
                                    <select class="select2">
                                        <option value="instock">Same as parent</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 input_field">
                                <label>Tax Class</label>
                                <div>
                                    <select class="select2">
                                        <option value="instock">Same as parent</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-20"></div>

                        <div class="row">
                            <div class="col-md-12 input_field">
                                <label>Description</label>
                                <textarea name="" cols="2" style="min-height: initial"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary">Add Variation</button>
        <?php
    }

}