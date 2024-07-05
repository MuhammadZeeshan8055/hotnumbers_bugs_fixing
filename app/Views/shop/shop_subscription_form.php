<?php
$plan = get_setting('subscription_plans',true);
$subscription_enabled = get_setting('subscription_enabled');
if(!empty($plan)) {
    $plan = $plan[0];
}
//pr($plan,false);
if(!empty($plan['plan_enable']) && $subscription_enabled) {
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
                            <label for="subscribe-check">Subscribe:</label>
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

        <?php
        $form_fields = get_setting('subscriptionForm', true);
        ?>

        <div class="mb-2"></div>

        <div class="subscription_box_fields" data-price="<?php echo !empty($product_price) ? percent_reduce($product_price,$plan['discount_percent'],true) : 0 ?>" style="display:<?php echo !empty($plan_setting['enable']) ? 'block':'none' ?>;">
            <table class="variations" width="100%">
                <tr>
                    <td width="183">Delivered every</td>
                    <td>
                        <?php $bi_id = rand(); ?>
                        <select id="_<?php echo $bi_id?>" style="margin-bottom: 2px;" class="subscription-interval form-control" name="<?php echo $fieldname ?>[interval]">
                            <option value="">Select interval</option>
                            <?php
                                if(!empty($form_fields['subscription-type'])) {
                                    $type_key = array_keys($form_fields['subscription-type']);
                                    foreach($form_fields['subscription-type'] as $value=>$name) {
                                        $type_key_pos = array_search($value,$type_key);
                                        ?>
                                        <option value="<?php echo $type_key_pos ?>"><?php echo $name ?></option>
                                        <?php
                                    }
                                }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>

            <?php
            if(!empty($plan_setting['period']) && !empty($plan_setting['interval'])) {
                ?>
                <script type="text/javascript">
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