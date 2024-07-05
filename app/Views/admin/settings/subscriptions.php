<section id="tab-subscriptions">
    <form action="<?php echo base_url(ADMIN . '/settings') ?>" method="post" enctype="multipart/form-data">
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
                $subscriptionForm = get_setting('subscriptionForm',true);
                ?>

                <div class="row mt-30">
                    <div class="col-md-12">

                        <div class="input_field inline-checkbox">
                            <label><input type="checkbox" name="subscription_enabled" value="1" <?php echo get_setting('subscription_enabled') ? 'checked':'' ?>>
                                Enable Subscriptions</label>
                        </div>
                    </div>
                </div>

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
                                                <input type="text" name="subscriptionForm[subscription-type][value][]" value="" placeholder="Key">
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="position: relative">
                                            <div class="input_field">
                                                <input type="text" name="subscriptionForm[subscription-type][name][]" value="" placeholder="Name">
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
                                                    <input type="text" name="subscriptionForm[subscription-type][value][]" value="<?php echo $value ?>" placeholder="Key">
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="position: relative">
                                                <div class="input_field">
                                                    <input type="text" name="subscriptionForm[subscription-type][name][]" value="<?php echo $name ?>" placeholder="Name">
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

                   <?php /* <div class="col-lg-6">
                        <div class="table-box">
                            <label>Subscription Duration</label>

                            <div id="subscription-duration">

                                <?php
                                if (empty($subscriptionForm['duration'])) {
                                    ?>

                                    <div class="row method-row">
                                        <div class="col-md-4">
                                            <div class="input_field">
                                                <input type="text" name="subscriptionForm[duration][value][]" value="" placeholder="Value">
                                            </div>
                                        </div>

                                        <div class="col-md-4" style="position: relative">
                                            <div class="input_field">
                                                <input type="text" name="subscriptionForm[duration][name][]" value="" placeholder="Name">
                                            </div>
                                        </div>

                                    </div>


                                    <?php
                                }else {
                                    if(!empty($subscriptionForm['duration'])) {
                                        foreach($subscriptionForm['duration'] as $value=>$name) {
                                            ?>

                                            <div class="row method-row">
                                                <div class="col-md-4">
                                                    <div class="input_field">
                                                        <input type="text" name="subscriptionForm[duration][value][]" value="<?php echo $value ?>" placeholder="Value">
                                                    </div>

                                                </div>

                                                <div class="col-md-4" style="position: relative">
                                                    <div class="input_field">
                                                        <input type="text" name="subscriptionForm[duration][name][]" value="<?php echo $name ?>" placeholder="Label">
                                                    </div>
                                                    <span class="remove_method" onclick="$(this).closest('.method-row').remove()"><i class="lni lni-close"></i></span>
                                                </div>
                                            </div>

                                            <?php
                                        }
                                    }

                                }
                                ?>
                            </div>

                            <br>

                            <button type="button" class="btn save btn-sm bg-black" onclick="append_form('#subscription-duration')">Add Duration</button>
                        </div>
                    </div>*/ ?>

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

                <br>


                <div class="table-box">
                    <label>Subscription Reminder</label>
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
        </div>

        <div class="mt-22"></div>

        <div class="row footer">
            <div class="col-lg-12 btn_bar flex_space">
                <input data-tab-current-url type="hidden" name="current_url" value="<?php echo current_url() ?>">
                <button type="submit" name="subscription_changes" value="1" class=" btn save btn-sm">Save changes</button>
            </div>
        </div>
    </form>

</section>