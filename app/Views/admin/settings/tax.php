<section id="tab-tax">
    <form action="<?php echo base_url(ADMIN . '/settings') ?>"
          method="post"
          enctype="multipart/form-data">

        <div>
            <div class="table-box">
                <label>Tax calculation</label>

                <table class="field_table">
                    <tr>
                        <th>Enable tax calculations</th>
                        <td>
                            <div class="input_field inline-checkbox">
                                <label><input type="checkbox" name="enable_tax_rates" <?php echo get_setting('enable_tax_rates') ? 'checked':'' ?> value="1" class="d-inline-block w-auto" style="width: auto;">
                                    Yes</label>
                            </div>
                        </td>
                    </tr>
                    
                    
                    <tr>
                        <th>Prices entered with tax</th>
                        <td>
                            <div class="d-inline-block input_field">
                                <div>
                                    <div class="input_field inline-checkbox">
                                        <label><input type="radio" value="inclusive" name="price_with_tax" <?php echo empty(get_setting('price_with_tax')) || get_setting('price_with_tax') === "inclusive" ? 'checked':'' ?>>
                                            Yes, I will enter prices inclusive of tax</label>
                                    </div>
                                </div>
                                <div>
                                    <div class="input_field inline-checkbox">
                                        <label><input type="radio" value="exclusive" name="price_with_tax" <?php echo get_setting('price_with_tax') === "exclusive" ? 'checked':'' ?>>
                                            No, I will enter prices exclusive of tax</label>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <th>Display prices in the shop</th>
                        <td>
                            <div class="d-inline-block input_field">
                                <div class="input_field inline-checkbox">
                                    <label><input type="radio" value="inclusive" name="shop_price_with_tax" <?php echo get_setting('shop_price_with_tax') === "inclusive" ? 'checked':'' ?>>
                                        Including Tax</label>
                                </div>
                                &nbsp;&nbsp;
                                <div class="input_field inline-checkbox">
                                    <label><input type="radio" value="exclusive" name="shop_price_with_tax" <?php echo get_setting('shop_price_with_tax') === "exclusive" ? 'checked':'' ?>>
                                        Excluding Tax</label>
                                </div>
                            </div>
                        </td>
                    </tr>
                    

                    <tr>
                        <th>Display prices during cart and checkout</th>
                        <td>
                            <div class="input_field inline-checkbox">
                                <label><input type="radio" name="display_tax_price" <?php echo get_setting('display_tax_price') === 'including_tax' ? 'checked':'' ?> value="including_tax" class="d-inline-block w-auto" style="width: auto;">Including Tax</label>
                            </div>
                            &nbsp;&nbsp;
                            <div class="input_field inline-checkbox">
                                <label><input type="radio" name="display_tax_price" <?php echo get_setting('display_tax_price') === 'excluding_tax' ? 'checked':'' ?> value="excluding_tax" class="d-inline-block w-auto" style="width: auto;">Excluding Tax</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th>Calculate tax based on</th>
                        <td>
                            <div>
                                <div class="d-inline-block input_field inline-checkbox">
                                    <label> <input type="radio" value="billing" name="tax_based_on" <?php echo empty(get_setting('tax_based_on')) || get_setting('tax_based_on') === "billing" ? 'checked':'' ?>>
                                        Customer billing address</label>
                                </div>
                            </div>
                            <div>
                                <div class="d-inline-block input_field inline-checkbox">
                                    <label> <input type="radio" value="shipping" name="tax_based_on" <?php echo get_setting('tax_based_on') === "shipping" ? 'checked':'' ?>>
                                        Customer shipping address</label>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th>
                            Additional tax classes &nbsp; <i class="lni lni-question-circle" data-tooltip title="List additional tax classes you need below (1 per line, e.g. Reduced Rates). These are in addition to 'Standard rate' which exists by default."></i>
                        </th>
                        <td>
                            <div class="input_field">
                                <textarea class="form-control" name="tax_classes" rows="3"><?php echo get_setting('tax_classes') ?></textarea>
                            </div>
                        </td>
                    </tr>
                    <?php
                    $tax_classes = get_setting('tax_classes');
                    ?>
                    <tr>
                        <th>
                            Shipping tax class &nbsp; <i class="lni lni-question-circle" data-tooltip title="Optionally control which tax class shipping gets, or leave it so shipping tax is based on the cart items themselves."></i>
                        </th>
                        <td>

                            <div class="input_field">
                                <div>
                                    <select class="select2" name="tax_shipping_class" value="<?php echo get_setting('tax_shipping_class') ?>">
                                        <option value="cart_items" title="If there are products with different tax rates in the cart, the highest rate will be applied to shipment.">Based on cart items</option>
                                        <option value="standard_tax_rate">Standard</option>
                                        <?php
                                        if(!empty($tax_classes)) {
                                            foreach(explode("\n",$tax_classes) as $tax_class) {
                                                $key = strtolower($tax_class);
                                                $key = str_replace(' ','_',$key);
                                                $key = trim($key);
                                                ?>
                                                <option value="<?php echo $key ?>"><?php echo $tax_class ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <?php
            function tax_rate_row($tax_data=[]) {
                $class_name = !empty($tax_data['tax_class_name']) ? $tax_data['tax_class_name'] : '';
                unset($tax_data['tax_class_name']);

                if(empty($tax_data['values'][0])) {
                    $tax_rows = [];
                }else {
                    $tax_rows = $tax_data['values'];
                }
                foreach($tax_rows as $tax) {
                    $country_code = !empty($tax['country']) ? $tax['country'] : '';
                    $state = !empty($tax['state']) ? $tax['state'] : '';
                    $postcode = !empty($tax['postcode']) ? $tax['postcode'] : '';
                    $city = !empty($tax['city']) ? $tax['city'] : '';
                    $amount = !empty($tax['amount']) ? $tax['amount'] : '';
                    $type = !empty($tax['type']) ? $tax['type'] : "";
                    $tax_name = !empty($tax['tax_name']) ? $tax['tax_name'] : '';
                    $tax_shipping = !empty($tax['tax_shipping']) ? $tax['tax_shipping'] : '';
                    ?>
                    <tr>
                        <td>
                            <select class="select2" name="<?php echo $class_name ?>_tax_rate[country][]" value="<?php echo $country_code ?>">
                                <option value="">*</option>
                                <?php
                                foreach(get_countries() as $code=>$name) {
                                    ?>
                                    <option value="<?php echo $code ?>"><?php echo $name ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="<?php echo $class_name ?>_tax_rate[state][]" value="<?php echo $state ?>">
                        </td>
                        <td>
                            <input type="text" name="<?php echo $class_name ?>_tax_rate[postcode][]" value="<?php echo $postcode ?>">
                        </td>
                        <td>
                            <input type="text" name="<?php echo $class_name ?>_tax_rate[city][]" value="<?php echo $city ?>">
                        </td>
                        <td>
                            <input type="text" name="<?php echo $class_name ?>_tax_rate[amount][]" value="<?php echo $amount ?>">
                        </td>
                        <td>
                            <select name="<?php echo $class_name ?>_tax_rate[type][]" data-search="false" value="<?php echo $type ?>">
                                <option value="percent">%</option>
                                <option value="equals">=</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="<?php echo $class_name ?>_tax_rate[tax_name][]" value="<?php echo $tax_name ?>">
                        </td>
                        <td class="text-center">
                            <div class="input_field inline-checkbox">
                                <label>
                                    <input type="checkbox" name="<?php echo $class_name ?>_tax_rate[tax_shipping][]" value="1" <?php echo $tax_shipping ? 'checked':'' ?>>
                                </label>
                            </div>
                        </td>
                        <td>
                            <a href="#" onclick="remove_tax_rate(this);return false" class="color-base"><i class="lni lni-cross-circle"></i> </a>
                        </td>
                    </tr>
                    <?php
                }
            }

            function tax_table($tax_rates=[]) {
                if(!empty($tax_rates['tax_class_name'])) {
                    $label_id = strtolower($tax_rates['tax_class_name']);
                    $label_id = str_replace(' ','_',$label_id);
                    $label_id = trim($label_id);
                    ?>
                    <div class="input_field">
                        <table class="table">
                            <thead>
                            <tr>
                                <th width="200">Country</th>
                                <th>State</th>
                                <th>Postcode / Zipcode</th>
                                <th>City</th>
                                <th width="100">Amount</th>
                                <th>Type</th>
                                <th>Tax Name</th>
                                <th>Shipping <i title="Choose whether or not this tax rate also gets applied to shipping." class="lni lni-question-circle"></i></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="tax_rate_list">
                            <?php
                            if(!empty($tax_rates)) {
                                tax_rate_row($tax_rates);
                            }else {
                                tax_rate_row();
                            }
                            ?>
                            </tbody>
                        </table>

                        <br>

                        <a href="#" data-name="<?php echo !empty($label_id) ? $label_id : '' ?>" onclick="append_tax_rate(this);return false" class="btn btn-sm save text-center d-block btn-secondary">Add Tax Rate</a>
                    </div>
                    <?php
                }
            }

            $tax_class_settings = tax_rates();

            //pr($tax_class_settings);

            $standard_tax_rates = !empty($tax_class_settings['standard_tax_rate']) ? $tax_class_settings['standard_tax_rate'] : [];
            $standard_tax_rates['tax_class_name'] = 'standard';

            ?>
            <!-- <div class="table-box">
                <label>Standard Tax Rates</label>
                <?php tax_table($standard_tax_rates) ?>
            </div>

            <?php

            if(!empty($tax_classes)) {
                $tax_classes = explode("\n",$tax_classes);
                foreach($tax_classes as $tax_class) {
                    $tax_class_ = str_replace(' ','_',$tax_class);
                    $tax_class_ = strtolower($tax_class_);
                    $tax_class_ = trim($tax_class_);
                    if($tax_class_) {
                        $tax_rate_vals = !empty($tax_class_settings[$tax_class_.'_tax_rate']) ? $tax_class_settings[$tax_class_.'_tax_rate'] : [];
                        $tax_rate_vals['tax_class_name'] = $tax_class_;
                        ?>
                        <div class="table-box">
                            <label><?php echo $tax_class?> </label>
                            <?php tax_table($tax_rate_vals) ?>
                        </div>
                        <?php
                    }
                }
            }
            ?> -->
        </div>

        <div class="mt-22"></div>

        <div class="row footer">
            <div class="col-lg-12 btn_bar flex_space">
                <input data-tab-current-url type="hidden" name="current_url" value="<?php echo current_url() ?>">
                <button type="submit" class=" btn save btn-sm" name="tax_update" value="1">Save changes</button>
            </div>
        </div>

    </form>
</section>

<template id="tax_rows_table">
    <tr>
        <td>
            <select class="select2" name="xxx_tax_rate[country][]">
                <option value="">*</option>
                <?php
                foreach(get_countries() as $code=>$name) {
                    ?>
                    <option value="<?php echo $code ?>"><?php echo $name ?></option>
                    <?php
                }
                ?>
            </select>
        </td>
        <td>
            <input type="text" name="xxx_tax_rate[state][]">
        </td>
        <td>
            <input type="text" name="xxx_tax_rate[postcode][]">
        </td>
        <td>
            <input type="text" name="xxx_tax_rate[city][]">
        </td>
        <td>
            <input type="text" name="xxx_tax_rate[amount][]">
        </td>
        <td>
            <select name="xxx_tax_rate[type][]" data-search="false">
                <option value="percent">%</option>
                <option value="equals">=</option>
            </select>
        </td>
        <td>
            <input type="text" name="xxx_tax_rate[tax_name][]">
        </td>
        <td class="text-center">
            <div class="input_field inline-checkbox">
                <label>
                    <input type="checkbox" name="xxx_tax_rate[tax_shipping][]" value="1">
                </label>
            </div>
        </td>
        <td>
            <a href="#" onclick="remove_tax_rate(this);return false" class="color-base"><i class="lni lni-cross-circle"></i> </a>
        </td>
    </tr>
</template>

<script>
    append_tax_rate = (ele)=> {
        const rate_class = $(ele).data('name');
        const tax_rows_table = $('#tax_rows_table').clone().html();

        $(ele).closest('.input_field').find('.tax_rate_list').append(tax_rows_table);

        setTimeout(()=>{
            $(ele).closest('.input_field').find('[name^=xxx_]').each(function() {
                this.name = this.name.replaceAll('xxx_',rate_class+'_');
                console.log(this.name);
            });
        },50);

        select2_init();
    }
    remove_tax_rate = (ele)=>{
        ele.closest('tr').remove();
    }
</script>