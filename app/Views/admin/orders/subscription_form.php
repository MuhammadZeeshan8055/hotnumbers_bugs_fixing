<div class="container">
    <div>
        <div>


            <style>
                .remove_method {
                    position: absolute;
                    top: 50%;
                    right: -10px;
                    transform: translateY(-50%);
                    color: red;
                    font-size: 15px;
                    cursor: pointer;
                }

                #shippiongmethods > div:first-child .remove_method {
                    display: none;
                }
                #shippiongmethods .remove_method i {
                    font-size: 14px;
                    margin-top: 35px;
                    display: block;
                }
            </style>

            <?php
            $subscriptionForm = !empty($subscriptionForm->value) ? json_decode($subscriptionForm->value,true) : [];

            ?>

            <form class="mt-60" action="<?php echo base_url('admin/subscription-settings') ?>" method="post"
                  enctype="multipart/form-data">
                  
                <!-- <input type="hidden" name="subscription_form_submitted" value="1"> -->
                
                <h4 class="mt-40">Subscription Settings</h4>


                    <div class="row mt-30" style="width:80%">
                        <div class="col-lg-6">
                            <div class="table-box">
                                <label>Subscription Types</label>

                                <div id="subscription-types">
                                    <?php
                                    if (empty($subscriptionForm['subscription-type'])) {
                                        ?>

                                        <div class="row">
                                            <div class="col-md-4" style="position: relative">
                                                <div class="input_field">
                                                    <input type="text" name="subscription-type[name][]" value="" placeholder="Name">
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="position: relative">
                                                <div class="input_field">
                                                    <input type="text" name="subscription-type[value][]" value="" placeholder="Value">
                                                </div>
                                            </div>

                                        </div>
                                        <?php
                                    }else {
                                        foreach($subscriptionForm['subscription-type'] as $value=>$name) {
                                            ?>

                                            <div class="row method-row">
                                                <div class="col-md-4" style="position: relative">
                                                    <div class="input_field">
                                                        <input type="text" name="subscription-type[name][]" value="<?php echo $name ?>" placeholder="Name">
                                                    </div>

                                                </div>
                                                <div class="col-md-4" style="position: relative">
                                                    <div class="input_field">
                                                        <input type="text" name="subscription-type[value][]" value="<?php echo $value ?>" placeholder="Value">
                                                    </div>
                                                    <span class="remove_method" onclick="$(this).closest('.method-row').remove()"><i class="lni lni-close"></i></span>
                                                </div>
                                            </div>

                                            <?php
                                        }
                                    }
                                    ?>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 btn_bar flex_space">
                                        <button type="button" class="btn save btn-sm bg-black" onclick="append_form('#subscription-types')">Add Type</button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="table-box">
                                <label>Subscription Duration</label>

                                <div id="subscription-duration">

                                    <?php
                                    if (empty($subscriptionForm)) {
                                        ?>

                                        <div class="row method-row">
                                            <div class="col-md-4" style="position: relative">
                                                <div class="input_field">
                                                    <input type="text" name="duration[name][]" value="" placeholder="Name">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input_field">
                                                    <input type="text" name="duration[value][]" value="" placeholder="Value">
                                                </div>

                                            </div>

                                        </div>


                                        <?php
                                    }else {
                                        foreach($subscriptionForm['duration'] as $value=>$name) {
                                            ?>

                                            <div class="row method-row">
                                                <div class="col-md-4" style="position: relative">
                                                    <div class="input_field">
                                                        <input type="text" name="duration[name][]" value="<?php echo $name ?>" placeholder="Label">
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input_field">
                                                        <input type="text" name="duration[value][]" value="<?php echo $value ?>" placeholder="Value">
                                                    </div>
                                                    <span class="remove_method" onclick="$(this).closest('.method-row').remove()"><i class="lni lni-close"></i></span>
                                                </div>


                                            </div>

                                            <?php
                                        }
                                    }
                                    ?>
                                </div>

                                <br>

                                <button type="button" class="btn save btn-sm bg-black" onclick="append_form('#subscription-duration')">Add Duration</button>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <button type="submit" name="save_method" value="1" class="btn save">Save changes</button>
                        </div>
                    </div>



                <script>
                    function append_form(form_div) {
                        jQuery(function () {
                            const first_el = $(form_div).children(':first-child').clone();
                            first_el.find('*').val('');
                            $(form_div).append(first_el);
                        })
                    }
                </script>
            </form>
            <br>

            <form class="mt-20" action="<?php echo base_url('admin/subscription-settings') ?>" method="post"
                  enctype="multipart/form-data">
                  <input type="hidden" name="form_submitted" value="1">
                <div class="row" style="width:80%">
                        <div class="col-lg-12">

                            <div class="row">
                                <div class="col-md-12" style="padding: 18px 20px 0">
                                    <h4 style="font-size: 18px">Subscription Reminder</h4>
                                    <p>Reminder to take action on subscription expiration before days:</p>

                                    <div class="row">
                                        <div class="col-md-4" style="position: relative">
                                            <div class="input_field">
                                                <input type="text" name="subscription_reminder" style="margin: 2px" value="<?php echo get_setting('subscriptionReminderDay') ?>" placeholder="XX Days">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-lg-12 btn_bar flex_space">
                                    <button type="submit" name="subscription_reminder_save" value="1" class="btn save">Save changes</button>
                                </div>
                            </div>

                        </div>



                    </div>

            </form>
        </div>
    </div>
</div>
