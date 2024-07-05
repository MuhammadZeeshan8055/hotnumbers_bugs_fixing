<div class="container">
    <div>
        <div>
            <?php

            if(!empty($shipping_methods->value)) {
                $shipping_methods = json_decode($shipping_methods->value, true);
            }else {
                $shipping_methods = [];
            }

            ?>



            <form class="mt-60" action="<?php echo base_url(ADMIN . '/settings') ?>" method="post"
                  enctype="multipart/form-data">

                <h4 class="mt-40">Shipping methods</h4>
                <input type="hidden" name="setting_id" value="shippingmethods">

                <div class="row mt-30" style="width:80%">
                    <div id="shippiongmethods" class="col-lg-12">
                        <?php
                        if (empty($shipping_methods)) {
                            ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input_field">
                                        <label>Name</label>
                                        <input type="text" name="shipping_method[name][]" value="">
                                    </div>
                                </div>
                                <div class="col-md-4" style="position: relative">
                                    <div class="input_field">
                                        <label>Amount</label>
                                        <input type="text" name="shipping_method[amount][]" value="">
                                    </div>
                                </div>
                                <div class="col-md-3" style="position: relative">
                                    <div class="input_field">
                                        <label>VAT%</label>
                                        <input type="number" name="shipping_method[vat][]" value="0">
                                    </div>

                                </div>
                            </div>
                            <?php
                        } else {
                            foreach ($shipping_methods['name'] as $i => $method_name) {
                                $method_amount = $shipping_methods['amount'][$i];
                                $method_vat = $shipping_methods['vat'][$i];
                                ?>
                                <div class="row method-row">
                                    <div class="col-md-3">
                                        <div class="input_field">
                                            <label>Name</label>
                                            <input type="text" name="shipping_method[name][]"
                                                   value="<?php echo $method_name ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="position: relative">
                                        <div class="input_field">
                                            <label>Amount</label>
                                            <input type="text" name="shipping_method[amount][]"
                                                   value="<?php echo $method_amount ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="position: relative">
                                        <div class="input_field">
                                            <label>VAT%</label>
                                            <input type="number" name="shipping_method[vat][]"
                                                   value="<?php echo floatval($method_vat) ?>">
                                        </div>

                                        <span class="remove_method" onclick="$(this).closest('.method-row').remove()"><i class="lni lni-close"></i></span>
                                    </div>
                                </div>
                            <?php }
                        }
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 btn_bar flex_space">
                        <button type="button" class="btn save" onclick="append_methods()">Add Method</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 btn_bar flex_space">
                        <button type="submit" name="save_method" value="1" class="btn save">Save changes</button>
                    </div>
                </div>


                <script>
                    function append_methods() {
                        jQuery(function () {
                            const first_el = $('#shippiongmethods').children(':first-child').clone();
                            first_el.find('*').val('');
                            $('#shippiongmethods').append(first_el);
                        })
                    }
                </script>
            </form>
        </div>
    </div>
</div>
