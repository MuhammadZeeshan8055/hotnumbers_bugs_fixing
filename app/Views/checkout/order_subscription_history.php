<?php
if(!empty($order)) {
    $subscriptionModel = model('SubscriptionModel');
    $orderModel = model('OrderModel');

    $order_meta = $order['order_meta'];

    $parent_id = $order['parent_id'];

    if($parent_id) {
        $sub_orders = $subscriptionModel->sub_orders($order['order_id']);
        $_order = $order;

        if(!empty($sub_orders)) {
            ?>
            <table class="order_receipt table">
                <thead>
                <tr>
                    <th class="text-left">Order number</th>
                    <th class="text-left">Subscription</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total=0;
                $i = 1;
                if(!empty($sub_orders)) {
                    foreach($sub_orders as $order) {

                        $sub_order_meta = $order['order_meta'];
                        $relation_name = '';
                        if(!empty($sub_order_meta['subscription_renewal'])) {
                            $relation_name = 'Renewal Order';
                        }
                        $order_date = _date_full($order['order_date']);
                        $status = ucfirst($order['status']);
                        $order_total = $sub_order_meta['order_total'];
                        $total += $order_total;

                        $order_item_html = !empty($order['order_items'][0]) ? $order['order_items'][0]['item_meta']['display_price_html'] : $total;

                        ?>
                        <tr>
                            <td><a class="color-base underline" href="<?php echo base_url('admin/orders/view').'/'. $order['order_id'] ?>">#<?php echo $order['order_id'] ?></a> </td>
                            <td><?php echo $order_item_html ?></td>
                            <td class="text-center"><?php echo $order_date ?></td>
                            <td class="text-center"><?php echo $status ?></td>
                            <td width="150" class="text-center"><?php echo _price($order_total) ?></td>
                        </tr>
                        <?php

                    }
                }
                ?>
                </tbody>
                <tfoot class="text-center">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Order Total:</th>
                    <td><div class="pt-10 pb-10"><?php echo _price($total) ?></div></td>
                </tr>
                </tfoot>
            </table>
            <?php
        }
    }
}?>