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
                <?php admin_page_title('Order #'._order_number($order['order_id'])); ?>
            </div>
        </div>
        &nbsp;
        &nbsp;
        &nbsp;
        <?php
        if(!empty($order) && order_editable($order)) { ?>
        <div class="d-inline-block" style="margin-bottom: -5px;">
            <a href="<?php echo admin_url().'orders/edit/'.$order['order_id'] ?>" style="padding: 8px 11px 8px" class="btn btn-sm pull-right btn-primary bg-black"><i class="lni lni-pencil"></i> &nbsp; Edit Order</a>
        </div>
        <?php } ?>

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
                            if(!empty($order_meta['payment_method_title'])) {
                                echo ' | &nbsp; <span style="display: inline-block">'.$link.'</span> &nbsp;&nbsp;';
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
                    <?php
                        if(!empty($order_meta['order_comments'])){
                        ?>
                            <div id="products" class="table-box">
                                
                                    
                                    <!-- <label>Order Notes :</label>  <?=$order_meta['order_comments']?> -->
                                    <p><strong>Order Notes : </strong> <?=$order_meta['order_comments']?></p>

                                    
                            </div>
                    <?php
                        }
                    ?>
                <div id="products" class="table-box">

                    <label>Product details
                    </label>
                    <br>

                        <?php
                        if(isset($_GET['edit_products'])) {
                            ?>
                        <form method="post" action="<?php echo admin_url() ?>orders/add" autocomplete="off">
                            <?php
                            echo view('admin/orders/edit_order_form',['order_data'=>$order, 'productsModel'=>$ProductsModel]);
                            ?>
                        </form>
                            <?php
                        }else {
                            echo view('checkout/order_receipt',['order'=>$order]);
                        }
                        ?>


                </div>

                <div id="order_refund_box" class="table-box" style="display: none;">
                    <?php
                    $order_refunds = !empty($order_meta['order_refund']) ? json_decode($order_meta['order_refund'],true) : [];
                    $refund_total = 0;
                    foreach($order_refunds as $refund) {
                        $k = key($refund);
                        $v = $refund[$k];
                        $refund_total += $v;
                    }
                    $refund_balance = $order_meta['order_total'] - $refund_total;

                    ?>
                    <form method="post" style=" color: #000">
                        <table width="100%" cellspacing="10" cellpadding="5" style="font-size: 15px; ">
                            <tr align="top">
                                <th class="text-left" style="vertical-align: top;">Total amount</th>
                                <td class="text-left"><?php echo _price($order_meta['order_total']) ?></td>
                            </tr>
                            <tr align="top">
                                <th class="text-left" style="vertical-align: top;">Refunded amount</th>
                                <td class="text-left">
                                    <?php
                                    if(!empty($order_refunds)) {
                                        foreach ($order_refunds as $refund) {
                                            $k = key($refund);
                                            $v = $refund[$k];
                                            $date = date('d/m/Y h:i A');
                                            echo  '<div class="mb-10"><div>'._price($v).'</div>'.'<div><small>'.$date.'</small></div></div>';
                                        }
                                    }else {
                                        echo _price();
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-left">Amount available for refund</th>
                                <td class="text-left"><?php echo _price($refund_balance) ?></td>
                            </tr>
                            <tr>
                                <th class="text-left">Refund amount</th>
                                <td class="text-left">
                                    <div class="input_field">
                                        <input type="number" <?php echo !$refund_balance ? 'disabled':'' ?> required step="0.01" min="0" name="refund_amount" value="" max="<?php echo $refund_balance ?>" class="form-control">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-left">Restock refunded items</th>
                                <td class="text-left">
                                    <div class="input_field inline-checkbox">
                                        <label>
                                            <input type="checkbox" <?php echo !$refund_balance ? 'disabled':'' ?> name="refund_stock" value="1">
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                               <td colspan="2">
                                   <div class="input_field">
                                       <textarea name="refund_reason" placeholder="Reason for refund (optional)"></textarea>
                                   </div>
                               </td>
                            </tr>
                        </table>



                        <div class="pt-15">
                            <input type="hidden" name="refund_order" value="1">
                            <button type="submit" class="btn btn-secondary">Refund Order <?php if($order_meta['payment_method_title'] !== "direct") {echo 'via '.$order_meta['payment_method_title'];} ?></button>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?php if(get_session('refund_failed')) {
                            $refund_values = get_session('refund_values');
                            ?>
                              <form id="order_form_refund" method="post" action="<?php echo admin_url() ?>order/force_refund_status">
                                  <input type="hidden" name="order_id" value="<?php echo _order_number($order['order_id']) ?>">
                                  <?php foreach($refund_values as $k=>$value) {
                                      ?>
                                  <input type="hidden" value="<?php echo $value ?>" name="<?php echo $k ?>">
                                  <?php
                                  }?>
                              </form>
                        <script>
                            $(function() {
                                Swal.fire({
                                    title: "Order refund failed",
                                    text: "<?php echo get_session('refund_failed') ?>. Proceed with setting order status?",
                                    showConfirmButton: true,
                                    showCancelButton: true,
                                    showClass: {
                                        popup: 'animated windowIn'
                                    },
                                    hideClass: {
                                        popup: 'animated windowOut'
                                    }
                                }).then((res)=>{
                                    if(res.isConfirmed) {
                                        document.querySelector('#order_form_refund').submit();
                                    }
                                });
                            })
                        </script>
                        <?php
                            set_session('refund_failed',false);
                            set_session('refund_values',false);
                        }
                        if(!empty($refund_balance)) {
                        ?>
                        <a class=" btn save" onclick="order_refund_box()">Refund</a>
                        <?php } ?>
                    </div>

                    <script>
                        function order_refund_box() {
                            Swal.fire({
                                title: "Order Refund",
                                showConfirmButton: false,
                                showCloseButton: true,
                                width: '590px',
                                html: document.querySelector('#order_refund_box').innerHTML,
                                showClass: {
                                    popup: 'animated windowIn'
                                },
                                hideClass: {
                                    popup: 'animated windowOut'
                                }
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
                    <?php echo view('admin/orders/widgets/order_actions',['is_wholesaler'=>$is_wholesaler]); ?>
                </div>

                <div class="table-box">
                    <label>Order notes</label>
                    <?php echo view('admin/orders/widgets/order_notes'); ?>
                </div>

                <div class="table-box">
                    <label>Order Emails</label>
                    <?php echo view('admin/orders/widgets/order_emails',['order_id'=>$order['order_id']]); ?>
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