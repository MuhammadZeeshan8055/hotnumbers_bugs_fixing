<?php echo view('admin/includes/sidebar_left'); ?>

<div id="main">
    <div class="container">
        <div class="admin_title_row">
            <?php admin_page_title('Discount Coupons'); ?>
        </div>

        <div class="datatable">
            <?php get_message() ?>

            <?php
            $form_data = [
                'coupon_code'=>'',
                'coupon_amount'=>'',
                'coupon_type'=>'',
                'coupon_valid_from'=>'',
                'coupon_valid_to'=>'',
                'coupon_usage_limit'=>'',
                'coupon_unlimited'=>'',
                'status'=>'',
                'has_expiration'=>''
            ];
            if(!empty($coupon)) {
                $form_data = [
                    'coupon_code'=>$coupon['code'],
                    'coupon_amount'=>$coupon['amount'],
                    'coupon_type'=>$coupon['type'],
                    'coupon_valid_from'=>$coupon['valid_from'],
                    'coupon_valid_to'=>$coupon['valid_to'],
                    'coupon_usage_limit'=>(int)$coupon['use_limit'],
                    'status'=>(int)$coupon['status'],
                    'has_expiration'=>(int)$coupon['has_expiration'],
                    'coupon_unlimited'=>(int)$coupon['is_unlimited'],
                ];
            }else {
                ?>

                <a href="#" onclick="$('#coupon_add_form').slideDown();return false;" class="add_btn">+ Add Coupon</a>
                <?php
            }
            ?>


            <div class="pull-right">
                <form method="post" action="<?php echo base_url(ADMIN.'/coupons/toggle') ?>">
                    <div class="input_field inline-checkbox">
                        <label>Enable Coupons</label>
                        <input type="checkbox" value="1" <?php echo get_setting('enable_product_coupons') ? 'checked':''; ?> name="enable_coupon" onchange="this.form.submit()">

                    </div>
                </form>
            </div>

            <div class="clearfix clear"></div>

            <div class="coupon_form">

                <form method="post" id="coupon_add_form" class="form" style="display: <?php echo !empty($coupon) ? 'block' : 'none' ?>" action="<?php echo base_url(ADMIN.'/coupons') ?>">
                    <br>
                    <br>
                    <div class="table-box d-inline-block">
                        <label>Add new coupon</label>
                        <div class="form-row input_field d-inline-block">
                            <label>Coupon code:</label>
                            <input type="text" name="coupon_code" required value="<?php echo $form_data['coupon_code'] ?>">
                        </div>
                        <div class="form-group input_field d-inline-block">
                            <label>Coupon amount:</label>
                            <input type="number" name="coupon_amount" min="0" required value="<?php echo $form_data['coupon_amount'] ?>">
                        </div>
                        <div class="form-group input_field d-inline-block">
                            <label>Coupon Type:</label>
                            <select type="text" name="coupon_type" value="<?php echo $form_data['coupon_type'] ?>">
                                <option value="fixed">Fixed</option>
                                <option value="percent">Percent</option>
                            </select>
                        </div>

                        <?php
                        $has_expiration = !empty($coupon) && (!empty($form_data['coupon_valid_from']) && !empty($form_data['coupon_valid_to']));
                        ?>
                        <div>
                            <div class="form-group input_field inline-checkbox" style="padding: 18px 0 8px;">
                                <label>Expiration:</label>
                                <input style="display: inline-block" name="has_expiration" type="checkbox" onchange="if(this.checked) {$('#cpn_validity').slideDown()}else {$('#cpn_validity').slideUp();$('#cpn_validity *').val('')}" value="1" <?php echo $form_data['has_expiration'] ? 'checked':'' ?>>

                            </div>
                            <div id="cpn_validity" style="display: <?php echo $form_data['has_expiration'] ? 'block':'none' ?>">
                                <div class="form-group input_field d-inline-block" style="width: 200px;display: inline-block">
                                    <label>Coupon validity from:</label>
                                    <input id="coupon_valid_to_from" type="text" name="coupon_valid_from" autocomplete="off" onchange="$('#coupon_valid_to_input').data('min-date',this.value)" value="<?php echo $form_data['coupon_valid_from'] ? date('m/d/Y',strtotime($form_data['coupon_valid_from'])) : '' ?>">
                                </div>
                                <div class="form-group input_field d-inline-block" style="width: 200px;display: inline-block">
                                    <label>Coupon validity to:</label>
                                    <input id="coupon_valid_to_input" type="text" name="coupon_valid_to" autocomplete="off" value="<?php echo $form_data['coupon_valid_to'] ? date('m/d/Y',strtotime($form_data['coupon_valid_to'])) : '' ?>">
                                </div>
                            </div>
                        </div>

                        <script type="text/javascript">
                            $(function() {
                                $('#coupon_valid_to_from').datepicker();

                                $('#coupon_valid_to_from').on('pick.datepicker', function(e) {
                                    const from_date = e.date;
                                    $('#coupon_valid_to_input').val("");
                                    $('#coupon_valid_to_input').datepicker({
                                        filter: function(date, view) {
                                            if(date < from_date) {
                                                return false;
                                            }
                                        }
                                    });
                                });

                                $('#coupon_valid_to_input').datepicker({
                                    filter: function(date, view) {
                                        const from_date = new Date($('#coupon_valid_to_from').val());
                                        if(date < from_date) {
                                            return false;
                                        }
                                    }
                                });
                            })
                        </script>

                        <div>
                            <div class="form-group input_field mb-15 mt-15">
                                <label>Usage limit:</label>
                                <input id="coupon_usage_limit_input" <?php echo $form_data['coupon_unlimited'] ? 'readonly':'' ?> type="number" name="coupon_usage_limit" style="max-width: 150px" value="<?php echo $form_data['coupon_usage_limit'] ?>" min="0">
                            </div>

                           <div class="form-group">
                               <div class="form-group input_field inline-checkbox">
                                   <label> <input onchange="$('#coupon_usage_limit_input').prop('readonly',this.checked)" type="checkbox" name="coupon_unlimited" style="max-width: 150px" value="1" <?php echo $form_data['coupon_unlimited'] ? 'checked':'' ?>>
                                       Unlimited</label>
                               </div>

                               <div class="form-group input_field  inline-checkbox">
                                   <label>Active</label>
                                   <input type="checkbox" name="status" value="1" <?php echo $form_data['status'] ? 'checked':'' ?>>
                               </div>
                           </div>
                        </div>

                        <br>

                        <?php if(!empty($coupon)) { ?> <input type="hidden" name="cpn_update" value="<?php echo $coupon['id'] ?>"> <?php } ?>
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn save"><?php echo !empty($coupon) ? 'Update' : 'Save' ?></button>
                        </div>

                    </div>
                </form>
            </div>


            <div class="books_listing">
                <table class="ui celled table responsive nowrap unstackable" style="width:100%">
                    <thead>
                    <tr>
                        <th>Coupon ID</th>
                        <th>Code</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Valid From</th>
                        <th>Valid To</th>
                        <th>Usage limit</th>
                        <th>Use Count</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($coupons as $coupon) {
                        $status = coupon_status($coupon);
                        ?>
                        <tr>
                            <td><?php echo $coupon->id ?></td>
                            <td title="Click to copy" class="coupon_code copyText text-center" data-text="<?php echo $coupon->code ?>"><?php echo $coupon->code ?></td>
                            <td class="text-center"><?php echo $coupon->amount ?></td>
                            <td class="text-center"><?php echo ucfirst($coupon->type) ?></td>
                            <td class="text-center"><?php echo $coupon->valid_from ? _date($coupon->valid_from) : '' ?></td>
                            <td  class="text-center"><?php echo $coupon->valid_to ? _date($coupon->valid_to) : '' ?></td>
                            <td class="text-center"><?php echo !$coupon->is_unlimited ? $coupon->use_limit : '∞ unlimited' ?></td>
                            <td class="text-center"><?php echo $coupon->use_count ?></td>
                            <td class="text-center"><?php echo ucfirst($status) ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a class="btn btn-primary btn-sm" href="?edit=<?php echo $coupon->id ?>">Edit</a>
                                    <a class="btn btn-primary btn-sm bg-black" href="?delete=<?php echo $coupon->id ?>" onclick="return confirm('Delete this coupon?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php }?>
                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
                <style>
                    .coupon_code {
                        cursor: pointer;
                    }
                    .coupon_code:hover {
                        background-color: rgba(0,0,0,0.1);
                    }
                </style>
            </div>
        </div>
    </div>
</div>

<?php echo view('admin/includes/footer'); ?>
