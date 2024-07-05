<div class="container">
    <div class="d-inline-block">
        <?php admin_page_title('Subscription plan'); ?>
    </div>

    <?php pr($plan,false); ?>

    <form method="post" action="">
        <div class="datatable">
            <div class="table-box">
                <table class="field_table input_field">
                    <tr>
                        <th>Enable subscription plan</th>
                        <td>
                            <div class="input_field inline-checkbox">
                                <label>
                                <input type="checkbox" name="plan_enable" value="1" <?php echo @$plan['plan_enable'] ? 'checked':'' ?>>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Subscription Period Options to be Shown for Customer</th>
                        <td>
                            <label>
                                <select name="plan_period_options[]" multiple class="select2 form-control" value="<?php echo !empty($plan['plan_period_options']) ? implode(',',$plan['plan_period_options']):'' ?>">
                                    <option value="day">Day</option>
                                    <option value="week">Week</option>
                                    <option value="month">Month</option>
                                    <option value="year">Year</option>
                                </select>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>Subscription price (<?php echo currency_symbol ?>)</th>
                        <td>
                            <div class="mb-10"><label>% of Regular Price/Sale Price</label></div>
                            <input type="text" class="form-control" name="discount_percent" value="<?php echo @$plan['discount_percent'] ? $plan['discount_percent']:'' ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>Billing Interval Options to be Shown for Customer</th>
                        <td>
                            <table width="100%">
                                <thead>
                                <tr>
                                    <td></td>
                                    <td>Min</td>
                                    <td>Max</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Days</td>
                                    <td>
                                        <div>
                                            <select name="day_interval[min]" class="select2" data-search="false" value="<?php echo @$plan['day_interval'] ? $plan['day_interval']['min']:'' ?>">
                                                <option value="0">Every day</option>
                                                <option value="2">Every 2nd day</option>
                                                <option value="3">Every 3rd day</option>
                                                <option value="4">Every 4th day</option>
                                                <option value="5">Every 5th day</option>
                                                <option value="6">Every 6th day</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <select name="day_interval[max]" class="select2" data-search="false" value="<?php echo @$plan['day_interval'] ? $plan['day_interval']['max']:'' ?>">
                                                <option value="0">Every day</option>
                                                <option value="2">Every 2nd day</option>
                                                <option value="3">Every 3rd day</option>
                                                <option value="4">Every 4th day</option>
                                                <option value="5">Every 5th day</option>
                                                <option value="6">Every 6th day</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Weeks</td>
                                    <td>
                                        <div>
                                            <select name="week_interval[min]" class="select2" data-search="false" value="<?php echo @$plan['week_interval'] ? $plan['week_interval']['min']:'' ?>">
                                                <option value="0">Every week</option>
                                                <option value="2">Every 2nd week</option>
                                                <option value="3">Every 3rd week</option>
                                                <option value="4">Every 4th week</option>
                                                <option value="5">Every 5th week</option>
                                                <option value="6">Every 6th week</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <select name="week_interval[max]" class="select2" data-search="false" value="<?php echo @$plan['week_interval'] ? $plan['week_interval']['max']:'' ?>">
                                                <option value="0">Every week</option>
                                                <option value="2">Every 2nd week</option>
                                                <option value="3">Every 3rd week</option>
                                                <option value="4">Every 4th week</option>
                                                <option value="5">Every 5th week</option>
                                                <option value="6">Every 6th week</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Months</td>
                                    <td>
                                        <div>
                                            <select name="month_interval[min]" class="select2" data-search="false" value="<?php echo @$plan['month_interval'] ? $plan['month_interval']['min']:'' ?>">
                                                <option value="0">Every month</option>
                                                <option value="2">Every 2nd month</option>
                                                <option value="3">Every 3rd month</option>
                                                <option value="4">Every 4th month</option>
                                                <option value="5">Every 5th month</option>
                                                <option value="6">Every 6th month</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <select name="month_interval[max]" class="select2" data-search="false" value="<?php echo @$plan['month_interval'] ? $plan['month_interval']['max']:'' ?>">
                                                <option value="0">Every month</option>
                                                <option value="2">Every 2nd month</option>
                                                <option value="3">Every 3rd month</option>
                                                <option value="4">Every 4th month</option>
                                                <option value="5">Every 5th month</option>
                                                <option value="6">Every 6th month</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Years</td>
                                    <td>
                                        <div>
                                            <select name="year_interval[min]" class="select2" data-search="false" value="<?php echo @$plan['year_interval'] ? $plan['year_interval']['min']:'' ?>">
                                                <option value="0">Every year</option>
                                                <option value="2">Every 2nd year</option>
                                                <option value="3">Every 3rd year</option>
                                                <option value="4">Every 4th year</option>
                                                <option value="5">Every 5th year</option>
                                                <option value="6">Every 6th year</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <select name="year_interval[max]" class="select2" data-search="false" value="<?php echo @$plan['year_interval'] ? $plan['year_interval']['max']:'' ?>">
                                                <option value="0">Every year</option>
                                                <option value="2">Every 2nd year</option>
                                                <option value="3">Every 3rd year</option>
                                                <option value="4">Every 4th year</option>
                                                <option value="5">Every 5th year</option>
                                                <option value="6">Every 6th year</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th>Expire After Options to be Shown for Customer</th>
                        <td>
                            <table width="100%">
                                <thead>
                                <tr>
                                    <td></td>
                                    <td>Min</td>
                                    <td>Max</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Days</td>
                                    <td>
                                        <div>
                                            <select name="day_expire[min]" class="select2" data-search="false" value="<?php echo @$plan['day_expire'] ? $plan['day_expire']['min']:'' ?>">
                                                <option value="0">Never expire</option>
                                                <?php for($i = 1; $i<=90; $i++) {
                                                    ?>
                                                    <option value="<?php echo $i ?>"><?php echo $i ?> day<?php echo $i==1 ? '':'s' ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <select name="day_expire[max]" class="select2" data-search="false" value="<?php echo @$plan['day_expire'] ? $plan['day_expire']['max']:'' ?>">
                                                <option value="0">Never expire</option>
                                                <?php for($i = 1; $i<=90; $i++) {
                                                    ?>
                                                    <option value="<?php echo $i ?>"><?php echo $i ?> day<?php echo $i==1 ? '':'s' ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Weeks</td>
                                    <td>
                                        <div>
                                            <select name="week_expire[min]" class="select2" data-search="false" value="<?php echo @$plan['week_expire'] ? $plan['week_expire']['min']:'' ?>">
                                                <option value="0">Never expire</option>
                                                <?php for($i = 1; $i<=52; $i++) {
                                                    ?>
                                                    <option value="<?php echo $i ?>"><?php echo $i ?> week<?php echo $i==1 ? '':'s' ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <select name="week_expire[max]" class="select2" data-search="false" value="<?php echo @$plan['week_expire'] ? $plan['week_expire']['max']:'' ?>">
                                                <option value="0">Never expire</option>
                                                <?php for($i = 1; $i<=52; $i++) {
                                                    ?>
                                                    <option value="<?php echo $i ?>"><?php echo $i ?> week<?php echo $i==1 ? '':'s' ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Months</td>
                                    <td>
                                        <div>
                                            <select name="month_expire[min]" class="select2" data-search="false" value="<?php echo @$plan['month_expire'] ? $plan['month_expire']['min']:'' ?>">
                                                <option value="0">Never expire</option>
                                                <?php for($i = 1; $i<=24; $i++) {
                                                    ?>
                                                    <option value="<?php echo $i ?>"><?php echo $i ?> month<?php echo $i==1 ? '':'s' ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <select name="month_expire[max]" class="select2" data-search="false" value="<?php echo @$plan['month_expire'] ? $plan['month_expire']['max']:'' ?>">
                                                <option value="0">Never expire</option>
                                                <?php for($i = 1; $i<=24; $i++) {
                                                    ?>
                                                    <option value="<?php echo $i ?>"><?php echo $i ?> month<?php echo $i==1 ? '':'s' ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Years</td>
                                    <td>
                                        <div>
                                            <select name="year_expire[min]" class="select2" data-search="false" value="<?php echo @$plan['year_expire'] ? $plan['year_expire']['min']:'' ?>">
                                                <option value="0">Never expire</option>
                                                <?php for($i = 1; $i<=6; $i++) {
                                                    ?>
                                                    <option value="<?php echo $i ?>"><?php echo $i ?> year<?php echo $i==1 ? '':'s' ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <select name="year_expire[max]" class="select2" data-search="false" value="<?php echo @$plan['year_expire'] ? $plan['year_expire']['max']:'' ?>">
                                                <option value="0">Never expire</option>
                                                <?php for($i = 1; $i<=6; $i++) {
                                                    ?>
                                                    <option value="<?php echo $i ?>"><?php echo $i ?> year<?php echo $i==1 ? '':'s' ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="update_plan" value="1">
                <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
            </div>
        </div>
    </form>
</div>
