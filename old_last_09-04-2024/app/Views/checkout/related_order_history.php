<?php
if(!empty($order)) {
    $order_meta = $order['order_meta'];

    $orderModel = model('OrderModel');;

    $oid = $order['order_id'];

    $orders = $orderModel->get_order_by_customer($order['customer_user']," AND o.order_id!=$oid");

    if(!empty($orders)) {
        ?>
        <div style="max-height: 500px; overflow: auto">
        <table class="order_receipt table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th class="text-left">Order</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order) {
                ?>
                <tr>
                    <td><div class="text-center"><?php echo $order['order_id'] ?></div></td>
                    <td class="text-left"><div class="text-center"><?php echo $order['order_title'] ?></div></td>
                    <td><div class="text-center"><?php echo _date_full($order['order_date'] ) ?></div></td>
                    <td><div class="text-center"><?php echo !empty($order['status']) ? ucfirst($order['status']) : '' ?></div></td>
                    <td>
                        <div class="text-center">
                            <a href="<?php echo admin_url().'orders/edit/'.$order['order_id'] ?>" class="btn btn-primary btn-sm">View order</a>
                        </div>
                    </td>
                </tr>
                <?php
            }?>
        </tbody>
    </table>
        </div>
        <?php
    }
}?>