<?php
if(!empty($order)) { ?>
    <div class="table-box">
        <label>
            <div class="d-flex align-items-center justify-content-between">
                <?php echo !empty($title) ? $title: 'Order information'?>
                <?php if($order['order_type'] === "shop_subscription") {
                    if($order['status'] === "processing") {
                        ?>
                        <span><a onclick="return confirm('Change order status?')" href="<?php echo admin_url() ?>subscriptions?action=start&ID=<?php echo $order['order_id'] ?>" class="btn btn-primary btn-sm"><i class="lni lni-play" style="font-size: 11px"></i> &nbsp; Start subscription</a></span>
                        <?php
                    }
                    if($order['status'] === "active") {
                        ?>
                        <span>
                            <a onclick="return confirm('Change order status?')" href="<?php echo admin_url() ?>subscriptions?action=suspend&ID=<?php echo $order['order_id'] ?>" class="btn btn-primary btn-sm">
                                <i class="lni lni-pause" style="font-size: 11px"></i> &nbsp;Pause subscription</a>
                        </span>
                        <?php
                    }
                    if($order['status'] === "on-hold") {
                        ?>
                        <span><a onclick="return confirm('Change order status?')" href="<?php echo admin_url() ?>subscriptions?action=resume&ID=<?php echo $order['order_id'] ?>" class="btn btn-primary btn-sm">
                                <i class="lni lni-play" style="font-size: 11px"></i> &nbsp;Resume subscription</a></span>
                        <?php
                    }
                }?>
            </div>

            <div class="clearfix"></div>
        </label>


        <?php if(empty($order['edit_mode'])) { ?>
        <form id="save_order_information_form" method="post" autocomplete="off">
            <?php } ?>
            <div class="row">

                <div class="col-md-4">

                    <?php

                    if(empty($order['billing_address'])) {
                        $billing = $order['order_meta'];
                    }else {
                        $billing = $order['billing_address'];
                    }

                    if(empty($order['shipping_address'])) {
                        $shipping = $order['order_meta'];
                    }else {
                        $shipping = $order['shipping_address'];
                    }

                    if(!empty($order_data['order_meta'])) {
                        $billing = $shipping = $order_data['order_meta'];
                    }

                    $allowedit = isset($allowedit) ? $allowedit : true;

                    $userModel = model('UserModel');
                    if(!empty($order['customer_user'])) {
                        $user = $userModel->get_user($order['customer_user'],'fname,lname,username');
                    }else {
                        $user = 0;
                    }

                    ?>

                    <div style="max-width: 320px;">
                        <h4 class="title"></h4>
                        <div>
                            <div class="input_field">
                                <label>Date Created:</label>
                                <?php if($allowedit) { ?>
                                <div>
                                    <input type="text" name="date_created" class="form-control datepicker" value="<?php echo _date($order['order_date']) ?>" required>
                                </div>
                                <?php }else {
                                    echo _date($order['order_date']);
                                }?>

                            </div>

                            <div class="input_field mt-6">
                                <label>Status:</label>
                                <?php if($allowedit) { ?>
                                        <div>
                                            <select id="order_status" class="select2 form-control" data-search="false" name="order_status" value="<?php echo $order['status'] ?>">
                                                <option value="" selected disabled>Select status</option>
                                                <?php
                                                if($order['order_type'] === "shop_subscription") {
                                                    foreach(subscription_statuses() as $k=>$v) {
                                                        ?>
                                                        <option value="<?php echo $k ?>"><?php echo $v ?></option>
                                                        <?php
                                                    }
                                                }else {
                                                    foreach(order_form_statuses() as $k=>$v) {
                                                        ?>
                                                        <option value="<?php echo $k ?>"><?php echo $v ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <div id="status_text" style="font-size: 12px; color: var(--red)"></div>
                                            <input type="hidden" name="order_send_email" value="0">
                                            <input type="hidden" name="old_order_status" value="<?php echo $order['status'] ?>">
                                            <script>
                                                $(function() {
                                                    let old_order_status = $('#order_status').val();
                                                    $('#order_status').on('change', function() {
                                                        let curr_status = this.value;
                                                        $('#status_text').text('');
                                                        if(old_order_status !== curr_status) {
                                                            swal.fire({
                                                                title: 'Send order status change email to customer?',
                                                                showCancelButton: true,
                                                                showCloseButton: true,
                                                                cancelButtonText: 'No',
                                                                confirmButtonText: 'Yes'
                                                            }).then(res=>{
                                                                if(res.isConfirmed) {
                                                                    $('[name="order_send_email"]').val(1);
                                                                    $('#status_text').text('Status change notification will be sent to customer on save changes');
                                                                }else {
                                                                    $('[name="order_send_email"]').val(0);
                                                                    $('#status_text').text('');
                                                                }
                                                            });
                                                        }
                                                    });
                                                })
                                            </script>
                                        </div>
                                <?php }else {
                                    ?>
                                    <div><?php echo ucfirst($order['status']); ?></div>
                                <?php
                                }?>
                            </div>

                            <div class="input_field mt-6">
                                <label>Customer:</label>
                                <?php
                                if($allowedit) { ?>
                                <div>
                                    <select name="customer" class="form-control customers_autocomplete" placeholder="Guest">
                                        <option value="">Guest</option>
                                        <?php if($order['customer_user']) { ?>
                                        <option selected value="<?php echo $order['customer_user'] ?>"><?php echo $user->username ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <?php }else {
                                    ?>
                                    <div><?php echo $user->fname.' '.$user->lname ?></div>
                                <?php
                                }?>
                            </div>

                            <?php if(!empty($order['order_note'])) { ?>
                                <div class="mt-15">
                                    <div class="infolist input_field">
                                        <label>Customer provided note:</label>
                                        <p><?php echo $order['order_note'] ?></p>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>

                    </div>

                </div>

                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input_field billing_info">
                                <h4 class="title" style="padding-left: 0;">Billing Info
                                    <?php if(empty($order['edit_mode']) && $allowedit) { ?>
                                        <a href="#" onclick="this.closest('.billing_info').classList.add('edit');"><i style="font-size: 16px; padding: 0 8px" class="lni lni-pencil"></i> </a>
                                    <?php } ?>
                                </h4>
                                <div class="infolist">
                                    <div>
                                        <p class="input-view"><?php echo @$billing['billing_first_name'] ?> <?php echo @$billing['billing_last_name'] ?></p>

                                        <div class="input-edit input_field">
                                            <input type="text" style="width: 46%" class="d-inline-block" value="<?php echo @$billing['billing_first_name'] ?>" name="billing_first_name" placeholder="Billing first name">
                                            <input type="text" style="width: 50%" class="d-inline-block" value="<?php echo @$billing['billing_last_name'] ?>" name="billing_last_name" placeholder="Billing last name">
                                        </div>
                                    </div>
                                    <div>
                                        <p class="input-view"><?php echo @$billing['billing_address_1'] ?></p>
                                        <div class="input-edit input_field">
                                            <input type="text" value="<?php echo @$billing['billing_address_1'] ?>" name="billing_address_1" placeholder="Billing address 1">
                                        </div></div>
                                    <div><p class="input-view"><?php echo @$billing['billing_address_2'] ?></p>
                                        <div class="input-edit input_field">
                                            <input type="text" value="<?php echo @$billing['billing_address_2'] ?>" name="billing_address_2" placeholder="Billing address 2">
                                        </div></div>
                                    <div><p class="input-view"><?php echo @$billing['billing_city'] ?></p>
                                        <div class="input-edit input_field">
                                            <input type="text" value="<?php echo @$billing['billing_city'] ?>" name="billing_city" placeholder="Billing city">
                                        </div></div>
                                    <div><p class="input-view"><?php echo @$billing['billing_postcode'] ?></p>
                                        <div class="input-edit input_field">
                                            <input type="text" value="<?php echo @$billing['billing_postcode'] ?>" name="billing_postcode" placeholder="Billing postcode">
                                        </div></div>
                                    <div><p class="input-view"><?php echo @$billing['billing_phone'] ?></p>
                                        <div class="input-edit input_field">
                                            <input type="text" value="<?php echo @$billing['billing_phone'] ?>" name="billing_phone" placeholder="Billing phone">
                                        </div></div>
                                    <div><p class="input-view"><?php echo @$billing['billing_country'] ?></p>
                                        <div class="input-edit">
                                            <?php if(empty($billing['billing_country'])) {

                                                @$billing['billing_country']= 'GB';
                                            }
                                            ?>
                                            <select type="text" class="select2" value="<?php echo @$billing['billing_country'] ?>" name="billing_country">
                                                <option value="" selected disabled>Select billing country</option>
                                                <?php foreach(get_countries() as $code=>$country) {
                                                    ?>
                                                    <option value="<?php echo $code ?>"><?php echo $country ?></option>
                                                    <?php
                                                }?>
                                            </select>
                                        </div></div>
                                </div>
                                <br>
                                <div class="infolist">
                                    <div class="input-view">
                                        <h2>Email address</h2>

                                        <p class="input-view"><a class="color-base" href="mailto:<?php echo !empty($billing['billing_email']) ? $billing['billing_email'] : '' ?>"><?php echo !empty($billing['billing_email']) ? $billing['billing_email'] : '-' ?></a> </p>

                                    </div>

                                    <div class="input-edit input_field">
                                        <input type="text" placeholder="Billing Email address" value="<?php echo @$billing['billing_email'] ?>" name="billing_email">
                                    </div>
                                </div>

                                <div class="mt-15"></div>

                                <?php if(!empty($order['edit_mode']) && $allowedit) { ?>
                                    <div class="input-edit input_field inline-checkbox">
                                        <label> <input type="checkbox" id="billing_shipping_same_input" onchange="billing_shipping_same(this)"> Billing is same as shipping </label>
                                        <script>
                                            let billing_shipping_sync = ()=> {
                                                let t;
                                                let change = new Event('change');
                                                document.querySelectorAll('[name*=billing_]').forEach((input)=>{
                                                    if(document.querySelector('#billing_shipping_same_input').checked) {
                                                        let ship_name = input.name.replaceAll('billing_', 'shipping_');

                                                        if(document.querySelector("[name='" + ship_name + "']")) {
                                                            document.querySelector("[name='" + ship_name + "']").value = input.value;
                                                            document.querySelector("[name='" + ship_name + "']").dispatchEvent(change);
                                                        }

                                                        clearTimeout(t);
                                                        t = setTimeout(() => {
                                                            select2_init();
                                                        });
                                                    }
                                                })
                                            }
                                            let billing_shipping_same = (input)=> {
                                                if(input.checked) {
                                                    $('.shipping_info input, .shipping_info select').attr('readonly','readonly');
                                                    billing_shipping_sync();
                                                }else {
                                                    $('.shipping_info input,.shipping_info select,.shipping_info textarea').each(function() {
                                                        $(this).removeAttr('readonly');
                                                        this.value = '';
                                                    })
                                                }
                                            }
                                        </script>
                                    </div>
                                <?php } ?>

                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="input_field billing_info shipping_info">
                                <h4 class="title" style="padding-left: 0;">Shipping Info
                                    <?php if(empty($order['edit_mode']) && $allowedit) { ?>
                                        <a href="#" onclick="this.closest('.shipping_info').classList.add('edit');"><i style="font-size: 16px; padding: 0 8px" class="lni lni-pencil"></i> </a>
                                    <?php } ?>
                                </h4>



                                <div class="infolist">
                                    <div><p class="input-view"><?php echo @$billing['shipping_first_name'] ?> <?php echo @$billing['shipping_last_name'] ?></p>
                                        <div class="input-edit input_field">
                                            <input type="text" style="width: 46%" class="d-inline-block" value="<?php echo @$billing['shipping_first_name'] ?>" name="shipping_first_name" placeholder="Shipping first name">
                                            <input type="text" style="width: 50%" class="d-inline-block" value="<?php echo @$billing['shipping_last_name'] ?>" name="shipping_last_name" placeholder="Shipping last name">
                                        </div></div>
                                    <div><p class="input-view"><?php echo @$shipping['shipping_address_1'] ?></p>
                                        <div class="input-edit input_field">
                                            <input type="text" value="<?php echo @$shipping['shipping_address_1'] ?>" name="shipping_address_1" placeholder="Shipping address 1">
                                        </div>
                                    </div>
                                    <div><p class="input-view"><?php echo @$shipping['shipping_address_2'] ?></p>
                                        <div class="input-edit input_field">
                                            <input type="text" value="<?php echo @$shipping['shipping_address_2'] ?>" name="shipping_address_2" placeholder="Shipping address 2">
                                        </div></div>
                                    <div><p class="input-view"><?php echo @$shipping['shipping_city'] ?></p>
                                        <div class="input-edit input_field">
                                            <input type="text" value="<?php echo @$shipping['shipping_city'] ?>" name="shipping_city" placeholder="Shipping city">
                                        </div>
                                    </div>
                                    <div><p class="input-view"><?php echo @$shipping['shipping_postcode'] ?></p>
                                        <div class="input-edit input_field">
                                            <input type="text" value="<?php echo @$shipping['shipping_postcode'] ?>" name="shipping_postcode" placeholder="Shipping postcode">
                                        </div></div>
                                    <div><p class="input-view"><?php echo @$shipping['shipping_country'] ?></p>
                                        <div class="input-edit">
                                            <?php if(empty($shipping['shipping_country'])) {

                                                @$shipping['shipping_country']= env('default_region');
                                            }
                                            ?>
                                            <select type="text" class="select2" value="<?php echo @$shipping['shipping_country'] ?>" name="shipping_country">
                                                <option value="" selected disabled>Select shipping country</option>
                                                <?php foreach(get_countries() as $code=>$country) {
                                                    ?>
                                                    <option value="<?php echo $code ?>"><?php echo $country ?></option>
                                                    <?php
                                                }?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="infolist">
                                    <div class="input-view">
                                        <h2>Email address</h2>
                                        <p><a class="color-base" href="mailto:<?php echo !empty($shipping['shipping_email']) ? $shipping['shipping_email'] : '' ?>"><?php echo !empty($shipping['shipping_email']) ? $shipping['shipping_email'] : ' - ' ?></a> </p>
                                    </div>
                                    <div class="input-edit input_field">
                                        <input type="text" placeholder="Shipping Email address" value="<?php echo @$shipping['shipping_email'] ?>" name="shipping_email">
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    <?php if(!$allowedit) {
                        ?>
                    document.querySelectorAll(".infolist p").forEach((p)=>{
                        if(p.innerText.trim().length <= 1) {
                            p.parentElement.remove()
                        }
                    })
                    <?php
                    }?>
                </script>

                <style>
                    <?php if(empty($order['edit_mode'])) { ?>
                    .billing_info .input-edit {
                        display: none;
                    }
                    .billing_info.edit .input-edit {
                        display: block;
                    }
                    .billing_info.edit .input-view {
                        display: none;
                    }
                    .billing_info.edit .lni-pencil {
                        display: none;
                    }
                    <?php }else {
                        ?>
                    .input-view {
                        display: none;
                    }
                    <?php
                }?>
                </style>

            </div>
            <?php if(empty($order['edit_mode']) && $allowedit) { ?>
                <div class="row mt-25">
                    <div class="col-md-12">
                        <input type="hidden" name="save_order_information" value="1">
                        <button type="submit" class="btn btn-sm btn-primary">Save changes</button>
                        <br>
                        <br>
                    </div>
                </div>
            <?php  } ?>
            <?php if(empty($order['edit_mode'])) { ?>

        </form>
    <?php } ?>
    </div>

<?php } ?>