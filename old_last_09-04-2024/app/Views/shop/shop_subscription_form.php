<?php
$plan = get_setting('subscription_plans',true);
if(!empty($plan)) {
    $plan = $plan[0];
}
//pr($plan,false);
if(!empty($plan['plan_enable'])) {
    $min_intervals = [];
    $max_intervals = [];
    $intervals = [];
    $periods = [];
    $period_options = $plan['plan_period_options'];

    $plan_setting = !empty($_GET['plan']) ? json_decode($_GET['plan'],true) : [];

    $fieldname = !empty($_GET['fieldname']) ? $_GET['fieldname'] : 'subscription';

    foreach($plan as $key=>$p) {
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
    $intervals = array_values($intervals);

    if(empty($period_options)) {
        $period_options = ['day','week','month','year'];
    }
    ?>
    <div class="subscription_box_wrapper">
        <div>
            <table class="variations" width="100%">
                <tbody>
                <tr class="variation_input">
                    <td width="130" class="label">
                        <div style="width: 100%">
                            <label for="subscribe-check">Subscribe</label>
                        </div>
                    </td>
                    <td style="width: 60%" class="value relative">
                        <div>
                            <div class="input_field inline-checkbox">
                                <label>
                                <input type="checkbox" name="<?php echo $fieldname ?>[enable]" value="1" <?php echo !empty($plan_setting['enable']) ? 'checked':'' ?> onclick="subscription_switch(this)" style="width: auto;">
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="subscription_box_fields" data-price="<?php echo !empty($product_price) ? percent_reduce($product_price,$plan['discount_percent'],true) : 0 ?>" style="display:<?php echo !empty($plan_setting['enable']) ? 'block':'none' ?>;">
            <table class="variations" width="100%">
                <tr>
                    <td>Subscription billing interval</td>
                    <td>
                        <?php
                            $bi_id = rand();
                        ?>
                        <select id="_<?php echo $bi_id?>" class="subscription-interval" name="<?php echo $fieldname ?>[interval]" onchange="plan_expire_options(this)">
                            <option value="">Select interval</option>
                            <?php
                            foreach($max_intervals as $max_interval) {
                                $selected = !empty($plan_setting['interval']) && $plan_setting['interval'] === $max_interval ? 'selected':'';
                                ?>
                                <option <?php echo $selected ?> value="<?php echo $max_interval ?>"><?php echo $max_interval == 0 ? 'Every':'Every '.number_position($max_interval) ?></option>
                                <?php
                            }?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Subscription period</td>
                    <td>
                        <?php
                            $p_id = rand();
                        ?>
                        <div class="subscription-plan-period">
                            <select id="_<?php echo $p_id?>" class="subscription-period" name="<?php echo $fieldname ?>[period]" onchange="plan_expire_options(this);">
                                <option value="">Select period</option>
                                <?php foreach($period_options as $period) {
                                    $selected = !empty($plan_setting['period']) && $plan_setting['period'] === $period ? 'selected':'';
                                    ?>
                                    <option <?php echo $selected ?> value="<?php echo $period ?>"><?php echo ucfirst($period) ?></option>
                                    <?php
                                }?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Expire after</td>
                    <td>
                        <div class="subscription-plan-expire">
                            <select name="<?php echo $fieldname ?>[expire]" value="<?php echo !empty($plan_setting['expire']) ? $plan_setting['expire'] : '' ?>" onchange="set_price_display(this);"></select>
                        </div>
                    </td>
                </tr>
            </table>

            <?php if(!empty($plan_setting['period']) && !empty($plan_setting['interval'])) {
                ?>
                <script>
                    $(function() {
                        $('#_<?php echo $bi_id?>').trigger('change');
                        $('#_<?php echo $p_id?>').trigger('change');
                    })
                </script>
            <?php
            }?>

            <div class="text-right">
                <p class="subscription_plan_price"></p>
            </div>
        </div>
    </div>

<?php } ?>