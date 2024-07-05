<?php
if(!empty($order['order_items'][0])) {
    $sub_plans = get_setting('subscription_plans',true);
    if(!empty($sub_plans)) {
        $sub_plans = $sub_plans[0];
    }

    $item = $order['order_items'][0];
    $meta = $item['item_meta'];
    $order_meta = $order['order_meta'];
    $subscription = !empty($meta['subscription']) ? json_decode($meta['subscription'],true) : [];

    $period_options = $sub_plans['plan_period_options'];

    $subscription['period_'] = !empty($subscription['expire']) ? $subscription['expire'] : 0;
    $subscription['period'] = !empty($subscription['period']) ? $subscription['period'] : 0;

    if(!empty($subscription['period']) && $subscription['period'] === "week" && strstr($subscription['expire'],'months')) {
        $subscription['period_'] = round(intval($subscription['expire']) * 4.348125).' weeks';
    }

    $next_payment = $order_meta['schedule_next_payment'];
    $schedule_end = $order_meta['schedule_end'];
    $start_date = $order_meta['schedule_start'];

    $sub_form_fields = get_setting('subscriptionForm', true);

    $item_meta = $item['item_meta'];

    ?>
    <form method="post" action="">
        <div style="font-size: 14px"> Order date: <?php echo _date($order_meta['paid_date']) ?></div>
        <div style="font-size: 14px; margin-top: 4px"> Start date: <?php echo _date($start_date) ?></div>
        <br>
        <div class="row shop_subscription_form no-gutters">
            <div class="col-md-6">
                <div class="input_field">
                    <label>Period</label>
                    <div>
                        <select class="subscription-interval form-control select2" data-search="false" name="subscription_setting[period]"
                                onchange="plan_expire_options(this)" value="<?php echo $subscription['period'] ?>">
                            <option value="">Select interval</option>
                            <?php
                            if(!empty($sub_form_fields['subscription-type'])) {
                                foreach($sub_form_fields['subscription-type'] as $key=>$field) {
                                    ?>
                                    <option value="<?php echo $key ?>"><?php echo $field ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!empty($next_payment)) { ?>
        <div class="row pt-15">
            <div class="col-md-12">
                <div class="input_field">
                    <label class="pb-4">Next payment date</label>
                    <h5 style="padding-left: 0"><?php echo !empty($next_payment) ? date(env('date_full_format'),strtotime($next_payment)) : '' ?></h5>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php /*<div class="row pt-15">
            <div class="col-md-12">
                <div class="input_field inline-checkbox">
                    <label class="pb-4"><input type="checkbox" value="1" <?php echo $schedule_end ? 'checked':'' ?> onchange="switch_expire_date(this)">
                    Enable expire date</label>
                </div>
            </div>
        </div>
        <div class="row pt-15">
            <div class="col-md-12">
                <div class="input_field">
                    <label class="pb-4">Expire date</label>
                    <input autocomplete="off" type="text" name="subscription_setting[schedule_end]" <?php echo !$schedule_end ? 'disabled':'' ?> class="datepicker form-control" value="<?php echo $schedule_end ? date('d/m/Y',strtotime($schedule_end)) : '' ?>">
                </div>
            </div>
        </div>*/ ?>

        <div class="row pt-20">
            <div class="col-md-12">
                <input type="hidden" name="action" value="subscription_schedule">
                <input type="hidden" name="order_id" value="<?php echo $order['order_id'] ?>">
                <button type="submit" class="btn btn-secondary btn-sm">Save changes</button>
            </div>
        </div>
    </form>
    <script>
        $(function() {
            $('.subscription-interval').trigger('change');
        })
        function switch_expire_date(input) {
            if(input.checked) {
                $('[name="subscription_setting[schedule_end]"]').prop('disabled',false);
            }else {
                $('[name="subscription_setting[schedule_end]"]').prop('disabled',true);
            }
        }
    </script>
    <?php
    init_subscription_form_script();
}
?>