<div class="container">
    <div>

        <?php

        function getLastSegmentOfURL()
        {
            $url = $_SERVER['REQUEST_URI'];
            $urlParts = explode('/', rtrim($url, '/')); // Explode URL by '/' and remove trailing '/'
            return end($urlParts); // Return the last element in the array
        }

        $lastSegment = getLastSegmentOfURL();


        if (session('msg')) :
            message_notice(session('msg'));
        endif;
        ?>

        <div class="d-inline-block ">
            <?php
            $title = !empty($order_data) ? 'Edit order# ' . _order_number($order_data['order_id']) : 'Add new order';
            admin_page_title($title); ?>
        </div>
        &nbsp;
        &nbsp;
        &nbsp;
        <?php if (!empty($order_data)) { ?>
            <div class="d-inline-block" style="margin-bottom: -5px;">
                <a href="<?php echo admin_url() . 'orders/view/' . $lastSegment ?>" style="padding: 8px 11px 6px" class="btn btn-sm pull-right btn-primary bg-black">View Order</a>
            </div>
        <?php } ?>

        <br>

        <div class="row">
            <?php
            $col = !empty($order_data) ? 'col-md-12' : 'col-md-9';
            ?>
            <div class="<?= $col ?>">
                <?php
                $order = [
                    'billing_address' => '',
                    'shipping_address' => '',
                    'order_date' => date('Y-m-d'),
                    'order_type' => 'shop_order',
                    'order_meta' => [],
                    'status' => 'pending',
                    'customer_user' => '',
                    'edit_mode' => 1
                ];

                $order_items = false;
                if (!empty($order_data)) {
                    $order = [
                        'billing_address' => $order_data['billing_address']['billing_address_1'] . ' ' . $order_data['billing_address']['billing_address_2'],
                        'shipping_address' => $order_data['shipping_address']['shipping_address_1'] . ' ' . $order_data['shipping_address']['shipping_address_2'],
                        'order_date' => date('Y-m-d', strtotime($order_data['order_date'])),
                        'order_type' => 'shop_order',
                        'order_meta' => $order_data['order_meta'],
                        'status' => $order_data['status'],
                        'customer_user' => $order_data['customer_user'],
                        'edit_mode' => 1
                    ];
                    $order_items = $order_data['order_items'];
                    $form_action = admin_url() . 'orders/edit/' . $order_data['order_id'];
                } else {
                    $form_action = admin_url() . 'orders/add';
                }

                if (!empty($order_data)) {
                    if (!order_editable($order_data)) {
                ?>
                        <h4>Cannot edit this product</h4>
                <?php
                        return;
                    }
                }
                ?>
                <form method="post" action="<?php echo $form_action ?>" autocomplete="off">

                    <?php echo view('admin/orders/order_info_box', ['order' => $order]) ?>

                    <?php echo view('admin/orders/edit_order_form', ['order' => $order]) ?>

                </form>


            </div>

            <div class="col-md-3">

            </div>
        </div>



    </div>
</div>