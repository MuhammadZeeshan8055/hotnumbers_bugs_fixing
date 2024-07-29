<div class="col2-set" id="customer_details" style="width: 100%">
    <div class="col-1">
        <div class="woocommerce-billing-fields">
            <h3>Billing details</h3>
            <div class="billing_fields_wrapper">

                <div class="form-row">
                    <label for="billing_field_first">First Name&nbsp; <abbr>*</abbr>
                        <?php echo form_input('billing_first_name', set_value('billing_first_name',$form_data['billing_first_name']),['required'=>'required', 'data-error'=>'First name is required','class'=>'input-text']) ?></label>
                </div>

                <div class="form-row">
                    <label for="billing_last_name">Last Name&nbsp;<abbr>*</abbr></label>
                    <?php echo form_input('billing_last_name', set_value('billing_last_name',$form_data['billing_last_name']),['required'=>'required', 'data-error'=>'Last name is required','class'=>'input-text']) ?>
                </div>

                <?php
                $selling_countries = get_selling_countries();

                if(!empty($selling_countries)) {
                    ?>
                    <div class="form-row address-field update_totals_on_change"
                         id="billing_country_field">
                        <label for="billing_country">Country/Region&nbsp;<abbr
                            >*</abbr></label>

                        <?php
                        if(count($selling_countries) > 1) {
                            ?>
                            <div>
                                <?php echo form_dropdown('billing_country',$selling_countries,set_value('billing_country',$form_data['billing_country']),['required'=>'required', 'data-error'=>'Billing country is required','class'=>'input-text']) ?>
                            </div>
                            <?php
                        }else {
                            $selling_country_code = array_keys($selling_countries);
                            $selling_country = $selling_countries[$selling_country_code[0]];
                            ?>
                            <strong><?php echo $selling_country ?></strong>
                            <input autocomplete="country" class="country_to_state" id="billing_country"
                                   name="billing_country" readonly="readonly" type="hidden"
                                   value="<?php echo $selling_country_code[0] ?>">
                            <?php
                        }

                        ?>

                    </div>
                <?php } ?>

                <div class="form-row address-field"
                     id="billing_address_1"><label for="billing_address_1">Street address&nbsp;<abbr
                        >*</abbr></label>
                    <?php echo form_input('billing_address_1', set_value('billing_address_1',$form_data['billing_address_1']),['required'=>'required', 'data-error'=>'Address is required','placeholder'=>'House number and street name','data-placeholder'=>'House number and street name','class'=>'input-text']) ?>
                </div>

                <div class="form-row address-field" id="billing_address_2_field"><label
                            class="screen-reader-text" for="billing_address_2">Flat, suite, unit, etc.&nbsp;<span
                                class="optional">(optional)</span></label>
                    <?php echo form_input('billing_address_2', set_value('billing_address_2',$form_data['billing_address_2']),['placeholder'=>'Apartment, suite, unit, etc. (optional)','data-placeholder'=>'Apartment, suite, unit, etc. (optional)','class'=>'input-text']) ?>
                </div>



                <div class="form-row address-field" id="billing_city_field"><label for="billing_city">Town / City&nbsp;<abbr
                        >*</abbr></label>
                    <?php echo form_input('billing_city', set_value('billing_city',$form_data['billing_city']),['required'=>'required','data-error'=>'Town/City is required','class'=>'input-text']) ?>
                </div>

                <div class="form-row address-field validate-state" id="billing_state_field"><label for="billing_state">County&nbsp;<span
                                class="optional">(optional)</span></label>
                    <?php echo form_input('billing_state', set_value('billing_state',$form_data['billing_state']),['class'=>'input-text']) ?>
                </div>

                <div class="form-row address-field validate-postcode" id="billing_postcode_field"><label for="billing_postcode">Postcode&nbsp;<abbr
                        >*</abbr></label>
                    <?php echo form_input('billing_postcode', set_value('billing_postcode',$form_data['billing_postcode']),['class'=>'input-text','data-error'=>'Postcode is required','required'=>'required']) ?>
                </div>

                <div class="form-row validate-phone"
                     id="billing_phone_field"><label for="billing_phone">Phone&nbsp;<abbr
                        >*</abbr></label>
                    <?php echo form_input('billing_phone', set_value('billing_phone',$form_data['billing_phone']),['class'=>'input-text','data-error'=>'Contact phone number is required','type'=>'tel','required'=>'required']) ?>
                </div>
                <?php
                    if (is_logged_in()) {
                ?>
                <div class="form-row validate-email"
                     id="billing_email_field" style="display:none"><label for="billing_email">Email Address&nbsp;<abbr
                        >*</abbr></label>
                    <?php echo form_input('billing_email', set_value('billing_email',$form_data['billing_email']),['class'=>'input-text','data-error'=>'Your email address is required','type'=>'email','required'=>'required','id'=>'email_address_input']) ?>
                </div>
                <?php
                    }else{
                ?>
                    <div class="form-row validate-email"
                        id="billing_email_field"><label for="billing_email">Email Address&nbsp;<abbr
                            >*</abbr></label>
                        <?php echo form_input('billing_email', set_value('billing_email',$form_data['billing_email']),['class'=>'input-text','data-error'=>'Your email address is required','type'=>'email','required'=>'required','id'=>'email_address_input']) ?>
                    </div>

                <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="col-2">
        <div class="woocommerce-shipping-fields">
            <h3 id="ship-to-different-address">
                <label class="woocommerce-form__label input_button checkbox" style="padding-top: 0">

                    <?php echo form_checkbox('ship_to_different_address', 1, set_value('ship_to_different_address',''),['class'=>'d-inline-block','style'=>'width: auto','id'=>'ship-to-different-address-checkbox']) ?>
                    <span class="f24 d-inline-block">Deliver to a different address?</span>
                </label>
            </h3>

            <div class="shipping_address" style="display:none;">
                <div class="shipping_fields_wrapper">

                    <div class="form-row address-field update_totals_on_change"
                         id="shipping_country_field"><label for="shipping_country">Country/Region&nbsp;<abbr
                            >*</abbr></label>
                        <?php
                        if(count($selling_countries) > 1) {
                            ?>
                            <div>
                                <?php echo form_dropdown('shipping_country',$selling_countries,set_value('shipping_country',$form_data['shipping_country']),['required'=>'required', 'data-error'=>'Shipping country is required','class'=>'input-text']) ?>
                            </div>
                            <?php
                        }else {
                            $selling_country_code = array_keys($selling_countries);
                            $selling_country = $selling_countries[$selling_country_code[0]];
                            ?>
                            <strong><?php echo $selling_country ?></strong>
                            <input autocomplete="country" class="country_to_state" id="shipping_country"
                                   name="shipping_country" readonly="readonly" type="hidden"
                                   value="<?php echo $selling_country_code[0] ?>">
                            <?php
                        }

                        ?>
                    </div>

                    <div class="form-row address-field"
                         id="shipping_address_1_field"><label for="shipping_address_1">Street
                            address&nbsp;<abbr>*</abbr></label>

                        <?php echo form_input('shipping_address_1', set_value('shipping_address_1',$form_data['shipping_address_1']),['class'=>'input-text','data-error'=>'Address is required','required'=>'required','placeholder'=>'House number and street name','id'=>'shipping_address_1','data-placeholder'=>'House number and street name']) ?>
                    </div>

                    <div class="form-row address-field" id="shipping_address_2_field"><label
                                class="screen-reader-text" for="shipping_address_2">Flat, suite, unit,
                            etc.&nbsp;<span class="optional">(optional)</span></label>
                        <?php echo form_input('shipping_address_2', set_value('shipping_address_2',$form_data['shipping_address_2']),['class'=>'input-text','placeholder'=>'Apartment, suite, unit, etc. (optional)','id'=>'shipping_address_1','data-placeholder'=>'Apartment, suite, unit, etc. (optional)']) ?>
                    </div>

                    <div class="form-row address-field"
                         id="shipping_city_field"><label for="shipping_city">Town /
                            City&nbsp;<abbr>*</abbr></label>
                        <?php echo form_input('shipping_city', set_value('shipping_city',$form_data['shipping_city']),['class'=>'input-text','data-error'=>'Town/City is required','required'=>'required']) ?>
                    </div>

                    <div class="form-row address-field validate-state"
                         id="shipping_state_field"><label for="shipping_state">County&nbsp;<span
                                    class="optional">(optional)</span></label>
                        <?php echo form_input('shipping_state', set_value('shipping_state',$form_data['shipping_state']),['class'=>'input-text','data-error'=>'Town/City is required']) ?>
                    </div>

                    <div class="form-row address-field validate-postcode"
                         id="shipping_postcode_field"><label for="shipping_postcode">Postcode&nbsp;<abbr
                            >*</abbr></label>
                        <?php echo form_input('shipping_postcode', set_value('shipping_postcode',$form_data['shipping_postcode']),['class'=>'input-text','data-error'=>'Please enter your postcode','required'=>'required']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="woocommerce-additional-fields">
            <div class="woocommerce-additional-fields__field-wrapper">
                <div class="form-row notes"><label for="order_comments">Order
                        Notes&nbsp;<span class="optional">(optional)</span></label>
                    <?php echo form_textarea('order_comments',set_value('order_comments',$form_data['order_comments']),['placeholder'=>'Notes about your order, e.g. special notes for delivery.','rows'=>2,'cols'=>5,'id'=>'order_comments']) ?>
                </div>
            </div>
        </div>

        <?php if(is_wholesaler()) {
            ?>
            <div class="woocommerce-additional-fields">
                <div class="woocommerce-additional-fields__field-wrapper">
                    <div class="form-row notes"><label for="order_comments">Purchase Order Number</label>
                        <?php echo form_input('purchase_order_number',set_value('purchase_order_number',''),['id'=>'purchase_order_number','class'=>'hide-spinbox','maxlength'=>30]) ?>
                    </div>
                </div>
            </div>
        <?php
        }?>
    </div>
</div>

<script>

    $('#ship-to-different-address-checkbox').on('change', function () {
        if ($(this).is(':checked')) {
            $('.shipping_address').slideDown();
            $('.shipping_address').find('input,select,textarea').val('');

            <?php
            if(!empty($form_data)) {

            foreach($form_data as $k=>$v) {
            if(strstr($k,'shipping_')) {

            ?>
            $('[name="<?php echo $k ?>"]').val('<?php echo $v ?>');
            <?php
            }
            }}
            ?>
        } else {
            $('.shipping_address').find('input,select,textarea').val('');
            $('.shipping_address').slideUp();
            $('.billing_fields_wrapper').find('input,select,textarea').each(function() {
                let shipping_name = this.name.replaceAll('billing_','shipping_');
                $('[name="'+shipping_name+'"]').val(this.value);
            });
        }
    });


    // $('#email_address_input').on('blur', function() {
    //    const email = this.value;
    //    $.post('<?php echo site_url() ?>ajax/check-email-exists',{email: email,csrf:'<?php echo csrf_hash() ?>'}, function(data) {
    //        let data_ = JSON.parse(data);
    //        if(data_.exists && parseInt(data_.exists.role) !== 8) {
    //            $('#create_account_input').val('');
    //            $('#create_account_input').hide();
    //            const message_text = `An account with ${email} exists in our record. Please <a href="<?php echo site_url('account') ?>"  target='_blank'>login</a> to continue purchase`;

    //            if(typeof message !== "undefined") {
    //                message(message_text);
    //            }else {
    //                alert(message_text);
    //            }
    //            $('#submit-button').prop('disabled',true);
    //        }else {
    //            $('#create_account_input').show();
    //            $('#submit-button').prop('disabled',false);
    //        }
    //    });
    // });

    $('#email_address_input').on('blur', function() {
        const email = this.value;
        
        <?php $session = session(); if(!$session->user): ?>
        $.post('<?php echo site_url() ?>ajax/check-email-exists', { email: email, csrf: '<?php echo csrf_hash() ?>' }, function(data) {
            let data_ = JSON.parse(data);
            if (data_.exists && parseInt(data_.exists.role) !== 8) {
                $('#create_account_input').val('');
                $('#create_account_input').hide();
                const message_text = `An account with ${email} exists in our records. Please <a href="<?php echo site_url('account') ?>" target='_blank'>login</a> to continue purchase.`;
                
                if (typeof message !== "undefined") {
                    message(message_text);
                } else {
                    alert(message_text);
                }
                $('#submit-button').prop('disabled', true);
            } else {
                $('#create_account_input').show();
                $('#submit-button').prop('disabled', false);
            }
        });
        <?php else: ?>
        // User session is available, no need to check email existence via AJAX
        $('#create_account_input').show();
        $('#submit-button').prop('disabled', false);
        <?php endif; ?>
    });


    $('.billing_fields_wrapper').find('input,select,textarea').each(function() {
        $(this).on('change', function() {
            let shipping_name = this.name.replaceAll('billing_','shipping_');

            if(!$('#ship-to-different-address-checkbox').is(':checked')) {
                $('.shipping_fields_wrapper').find('[name="'+shipping_name+'"]').val(this.value);
            }
        })
    });
</script>