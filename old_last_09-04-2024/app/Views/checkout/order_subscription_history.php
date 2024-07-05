<?php
if(!empty($order)) {
    $subscriptionModel = model('SubscriptionModel');
    $orderModel = model('OrderModel');

    $order_meta = $order['order_meta'];
    $sub_orders = $subscriptionModel->sub_orders($order['order_id'],true,false);
    if(empty($order['parent_id'])) {
        unset($sub_orders[ $order['order_id']]);
        $order['parent_id'] = $order['order_id'];
    }

    $parent_order = $orderModel->get_order_by_id($order['parent_id'],'',true,false);

    $renewal_order = !empty($order_meta['subscription_renewal']) ? $orderModel->get_order_by_id($order_meta['subscription_renewal'],'',true,false) : [];

    if(!empty($sub_orders)) {
    ?>

    <div class="table-box">
    <label>Subscription history</label>
    <br>

    <table class="order_receipt table">
    <thead>
    <tr>
        <th class="text-left">Order number</th>
        <th class="text-left">Subscription</th>
        <th>Date</th>
        <th>Status</th>
        <th>Actions</th>
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
            ?>
            <tr>
                <td><a class="color-base underline" href="<?php echo base_url('admin/orders/edit').'/'. $order['order_id'] ?>">#<?php echo $order['order_id'] ?></a> </td>
                <td><?php echo $relation_name ?></td>
                <td class="text-center"><?php echo $order_date ?></td>
                <td class="text-center"><?php echo $status ?></td>
                <td width="150" class="text-center"><?php echo _price($order_total) ?></td>
            </tr>
            <?php
        }
    }

    if(!empty($renewal_order)) {

        $order_date = _date_full($renewal_order['order_date']);
        $status = ucfirst($renewal_order['status']);
        $order_meta = $renewal_order['order_meta'];
        $order_total = $order_meta['order_total'];
        $total += $order_total;
        $oid = $renewal_order['order_id'];
        ?>
        <tr>
            <td><a class="color-base underline" href="<?php echo base_url('admin/orders/edit').'/'. $oid ?>">#<?php echo $oid ?></a> </td>
            <td>Subscription</td>
            <td class="text-center"><?php echo $order_date ?></td>
            <td class="text-center"><?php echo $status ?></td>
            <td class="text-center"><?php echo _price($order_total) ?></td>
        </tr>
        <?php

    }

    if(!empty($parent_order)) {
        $order_date = _date_full($parent_order['order_date']);
        $status = ucfirst($parent_order['status']);
        $order_total = $order_meta['order_total'];
        $total += $order_total;
        ?>
        <tr>
            <td><a class="color-base underline" href="<?php echo base_url('admin/orders/edit').'/'. $parent_order['order_id'] ?>">#<?php echo $parent_order['order_id'] ?></a> </td>
            <td>Parent Order</td>
            <td class="text-center"><?php echo $order_date ?></td>
            <td class="text-center"><?php echo $status ?></td>
            <td class="text-center"><?php echo _price($order_total) ?></td>
        </tr>
        <?php
    }
    ?>
    </tbody>
    <tfoot class="text-center">
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th><strong>Order Total:<div><?php echo _price($total) ?></div></strong></th>
    </tr>
    </tfoot>
</table>

    </div>
<?php
    }
}?>