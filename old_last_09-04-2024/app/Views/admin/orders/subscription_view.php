
<div class="container">
    <?php
    if(empty($order)) {
        echo message_notice('<h5 class="text-center">Invalid order</h5>');
        return;
    }

    $order_meta = $order['order_meta'];


    $order_ids = !empty($order_meta['subscription_renewal_order_ids_cache']) ? unserialize($order_meta['subscription_renewal_order_ids_cache']) : [];
    ?>
    <div class="d-inline-block ">
        <div class="admin_title_row">
            <?php admin_page_title('Subscription #'.$order['order_id']); ?>
        </div>
    </div>

    <br>

    <?php if (session('msg')) :
        message_notice(session('msg'));
    endif ?>

    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">

                    <?php echo view('admin/orders/order_info_box',['order'=>$order,'title'=>'Subscription information', 'allowedit'=>false]) ?>

                    <div class="table-box">
                        <label>Product details</label>
                        <br>
                        <?php echo view('checkout/order_receipt',['order'=>$order]) ?>
                    </div>

                    <div class="table-box">
                        <label>Subscription history</label>
                        <br>
                        <?php echo view('checkout/order_subscription_history',['order'=>$order]) ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="table-box">
                <label>Subscription schedule</label>
                <?php echo view('admin/orders/widgets/schedule_form'); ?>
            </div>
            <div class="table-box">
                <label>Subscription actions</label>
                <?php echo view('admin/orders/widgets/order_actions'); ?>
            </div>
            <div class="table-box">
                <label>Subscription notes</label>
                <?php echo view('admin/orders/widgets/order_notes'); ?>
            </div>
        </div>


    </div>

</div>
