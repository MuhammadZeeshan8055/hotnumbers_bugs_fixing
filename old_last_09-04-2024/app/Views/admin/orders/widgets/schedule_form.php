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

    foreach($sub_plans as $key=>$p) {
        if(strstr($key,'_interval')) {
            $min_intervals[$p['min']] = $p['min'];
            $max_intervals[$p['max']] = $p['max'];
            $period = strstr($key,'_interval',true);
            if(!empty($period_options) && !in_array($period,$period_options)) {
                continue;
            }
            $intervals[$p['min']] = $p['min'];
            $intervals[$p['max']] = $p['max'];

            if($p['max'] > 0) {
                $periods[] = $period;
            }
        }
    }

    $min_intervals = array_values($min_intervals);
    $max_intervals = array_values($max_intervals);

    $next_payment = $order_meta['schedule_next_payment'];
    $schedule_end = $order_meta['schedule_end'];
    $start_date = $order_meta['schedule_start'];

    ?>
    <form method="post" action="">
        <div style="font-size: 14px"> Start date: <?php echo _date($start_date) ?></div>
        <br>
        <div class="row shop_subscription_form no-gutters">
            <div class="col-md-6">
                <div>
                    <select class="subscription-interval form-control select2" data-search="false" name="subscription_setting[interval]" onchange="plan_expire_options(this)">
                        <option value="">Select interval</option>
                        <?php
                        foreach($max_intervals as $max_interval) {
                            $selected = !empty($subscription['interval']) && $subscription['interval'] === $max_interval ? 'selected':'';
                            ?>
                            <option <?php echo $selected ?> value="<?php echo $max_interval ?>"><?php echo $max_interval == 0 ? 'Every':'Every '.number_position($max_interval) ?></option>
                            <?php
                        }?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="">
                    <select class="subscription-period select2" data-search="false" name="subscription_setting[period]" onchange="plan_expire_options(this)">
                        <option value="">Select period</option>
                        <?php foreach($period_options as $period) {
                            $selected = !empty($subscription['period']) && $subscription['period'] === $period ? 'selected':'';
                            ?>
                            <option <?php echo $selected ?> value="<?php echo $period ?>"><?php echo ucfirst($period) ?></option>
                            <?php
                        }?>
                    </select>
                </div>
            </div>
            <div class="col-md-12 pt-15">
                <div class="subscription-plan-expire">
                    <select class="select2" value="<?php echo !empty($subscription['expire']) ? $subscription['expire'] : '' ?>" data-search="false" name="subscription_setting[expire]">
                        <option value="">Select expire</option>

                    </select>
                </div>
            </div>
        </div>

        <div class="row pt-15">
            <div class="col-md-12">
                <div class="input_field">
                    <label class="pb-4">Next payment date</label>
                    <input type="text" name="subscription_setting[next_payment_date]" class="datepicker form-control" value="<?php echo date('d/m/Y',strtotime($next_payment)) ?>">
                </div>
            </div>
        </div>
        <div class="row pt-15">
            <div class="col-md-12">
                <div class="input_field inline-checkbox">
                    <label class="pb-4"><input type="checkbox" value="1" <?php echo $next_payment ? 'checked':'' ?> onchange="switch_expire_date(this)">
                    Enable expire date</label>
                </div>
            </div>
        </div>
        <div class="row pt-15">
            <div class="col-md-12">
                <div class="input_field">
                    <label class="pb-4">Expire date</label>
                    <input type="text" name="subscription_setting[schedule_end]" class="datepicker form-control" value="<?php echo $schedule_end ? date('d/m/Y',strtotime($schedule_end)) : '' ?>">
                </div>
            </div>
        </div>

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