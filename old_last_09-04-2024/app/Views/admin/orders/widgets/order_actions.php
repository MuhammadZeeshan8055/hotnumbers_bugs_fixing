<div class="btn_list">
    <a href="<?php echo admin_url() ?>generate-pdf-slip/<?php echo $order['order_id'] ?>" target="_blank" class="btn btn-secondary" style="display: block">Packing Slip PDF</a>
    <a href="<?php echo admin_url() ?>xero-invoice/<?php echo $order['order_id'] ?>" class="btn btn-secondary" style="display: block">Send Invoice to Xero</a>
    <a href="<?php echo admin_url() ?>xero-payment/<?php echo $order['order_id'] ?>" class="btn btn-secondary" style="display: block">Send Payment to Xero</a>
</div>