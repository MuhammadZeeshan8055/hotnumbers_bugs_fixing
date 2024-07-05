<div class="container">
    <div>
        <?php
        if($mode === "edit" && empty($order)) {
            echo message_notice('<h5 class="text-center">Invalid order</h5>');
            return;
        }
        ?>

        <div class="d-inline-block ">
            <div class="admin_title_row">
                <?php admin_page_title('Order #'.$order['order_id']); ?>
            </div>
        </div>
        &nbsp;
        &nbsp;
        &nbsp;
        <div class="d-inline-block" style="margin-bottom: -5px;">
            <a href="<?php echo admin_url().'orders/edit/'.$order['order_id'] ?>" style="padding: 8px 11px 8px" class="btn btn-sm pull-right btn-primary bg-black"><i class="lni lni-pencil"></i> &nbsp; Edit Order</a>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="flex align-items-center">
                    <?php
                    if(!empty($order_meta['payment_method_title'])) {
                        $link = !empty($order_meta['transaction_id']) ? ' '.transaction_link($order_meta['transaction_id'],$order_meta).' ' : '';
                        $title = "<p style='display: inline-block'>Paid on "._datetime_full(@$order['order_date'])."</p>";
                        ?>

                        <div>
                            <?php echo $title ?>
                        </div>
                        <div class="flex align-items-center" style="padding-top: 0;padding-left: 6px;display: flex;align-items: center;">
                            <?php
                            if($link) {
                                echo 'via &nbsp; <span style="display: inline-block">'.$link.'</span> &nbsp;&nbsp;';
                            }
                            ?>
                        </div>

                    <?php } ?>

                </div>
            </div>
            <div class="col-md-3">

            </div>
        </div>

        <br>


        <?php if (session('msg')) :
            message_notice(session('msg'));
        endif ?>


        <div class="row">
            <div class="col-md-9">
                <?php echo view('admin/orders/order_info_box',['order'=>$order]) ?>

                <div id="products" class="table-box">
                    <label>Product details
                    </label>
                    <br>
                    <form method="post" action="<?php echo admin_url() ?>orders/add" autocomplete="off">
                        <?php
                        if(isset($_GET['edit_products'])) {
                            echo view('admin/orders/edit_order_form',['order_data'=>$order, 'productsModel'=>$ProductsModel]);
                        }else {
                            echo view('checkout/order_receipt',['order'=>$order]);
                        }
                        ?>
                    </form>

                </div>

                <div id="order_refund_box" class="table-box" style="display: none;">
                    <label>Order Refund</label>
                    <?php
                    $refund_total = !empty($order_meta['order_refund_total']) ? $order_meta['order_refund_total'] : 0;
                    $refund_balance = $order_meta['order_total'] - $refund_total;
                    ?>
                    <form method="post" style=" color: #000">
                        <table width="100%" cellspacing="10" cellpadding="5" style="font-size: 15px; ">
                            <tr>
                                <th class="text-left">Total amount</th>
                                <td class="text-left"><?php echo _price($order_meta['order_total']) ?></td>
                            </tr>
                            <tr>
                                <th class="text-left">Refunded amount</th>
                                <td class="text-left"><?php echo _price($refund_total) ?></td>
                            </tr>
                            <tr>
                                <th class="text-left">Amount available for refund</th>
                                <td class="text-left"><?php echo _price($refund_balance) ?></td>
                            </tr>
                            <tr>
                                <th class="text-left">Refund amount</th>
                                <td class="text-left">
                                    <div class="input_field">
                                        <input type="number" step="0.1" name="refund_amount" value="" max="<?php echo $refund_balance ?>" class="form-control">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-left">Restock refunded items</th>
                                <td class="text-left">
                                    <div class="input_field inline-checkbox">
                                        <label>
                                            <input type="checkbox" name="refund_stock">
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                               <td colspan="2">
                                   <div class="input_field">
                                       <textarea placeholder="Reason for refund (optional)"></textarea>
                                   </div>
                               </td>
                            </tr>
                        </table>
                        <div class="pt-15">
                            <button type="submit" class="btn btn-primary btn-sm" disabled>Refund Amount</button>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <a class=" btn save" onclick="order_refund_box()">Refund</a>
                    </div>

                    <script>
                        function order_refund_box() {
                            Swal.fire({
                                title: "Order Refund",
                                showConfirmButton: false,
                                showCloseButton: true,
                                width: '590px',
                                html: document.querySelector('#order_refund_box').innerHTML
                            });
                        }
                    </script>
                </div>

                <br>


                <?php echo view('checkout/order_subscription_history',['order'=>$order]) ?>

                <div class="table-box">
                    <label>Related orders</label>
                    <br>
                    <?php echo view('checkout/related_order_history',['order'=>$order]) ?>
                </div>


            </div>

            <div class="col-md-3">
                <div class="table-box">
                    <label>Send order email</label>
                    <?php echo view('admin/orders/widgets/send_order_email'); ?>
                </div>

                <div class="table-box">
                    <label>Order actions</label>
                    <?php echo view('admin/orders/widgets/order_actions'); ?>
                </div>

                <div class="table-box">
                    <label>Order notes</label>
                    <?php echo view('admin/orders/widgets/order_notes'); ?>
                </div>
        </div>
    </div>

    <script>
        $(function() {
            if(location.hash) {
                const y = $(location.hash).position().top;
                $('html,body').animate({scrollTop:y});
            }
        })
    </script>



</div>
</div>