<section id="tab-shop">

    <form action="<?php echo base_url(ADMIN . '/settings') ?>"
          method="post"
          enctype="multipart/form-data">

        <div class="table-box">
            <label>Product selling options</label>
            <div class="row">

                <div class="col-lg-4">
                    <div class="input_field">
                        <label>Selling location(s) </label>
                        <div class="mt-4">
                            <select class="select2" name="selling_location" data-search="false" value="<?php echo get_setting('selling_location') ?>">
                                <option value="all">Sell to all countries</option>
                                <option value="except">Sell to all countries, except</option>
                                <option value="specific">Sell to specific countries</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class=" col-lg-4">
                    <div class="input_field">
                        <label>Sell to specific countries </label>
                        <div class="mt-4">
                            <select class="select2" name="selling_countries[]" data-search="true" multiple>
                                <?php
                                $db_data = get_setting('selling_countries',true);
                                foreach(get_countries() as $code=>$country) {
                                    $selected = is_array($db_data) && in_array($code,$db_data) ? 'selected':'';
                                    ?>
                                    <option <?php echo $selected ?> value="<?php echo $code ?>"><?php echo $country ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="input_field">
                        <label>Currency</label>
                        <div class="mt-4">
                            <select class="select2" name="currency" value="<?php echo get_setting('currency') ?>">
                                <?php foreach(get_currencies() as $key=>$currency) {
                                    $symbol = $currency['symbol'];
                                    $name = $currency['name'];
                                    ?>
                                    <option value="<?php echo $key ?>"><?php echo $name." ($symbol)" ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="table-box">
            <label>Order options</label>
            <div class="row">
                <div class="col-lg-3 col-sm-12">
                    <div class="input_field">
                        <label>Default listing status</label>
                        <div class="mt-3">
                            <select class="select2" name="default_order_listing_status" data-search="false" value="<?php echo get_setting('default_order_listing_status') ?>">
                                <option value="">All orders</option>
                                <?php foreach(order_statuses() as $k=>$status) {
                                    ?>
                                    <option value="<?php echo $k ?>"><?php echo $status ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-12">
                    <div class="input_field">
                        <label>Order status on payment</label>
                        <div class="mt-4">
                            <select class="select2" name="default_status_on_payment" data-search="false" value="<?php echo get_setting('default_status_on_payment') ?>">
                                <option value="">All orders</option>
                                <?php foreach(order_statuses() as $k=>$status) {
                                    ?>
                                    <option value="<?php echo $k ?>"><?php echo $status ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="table-box">
            <h4 class="heading">Discount options</h4>

            <?php /*  <h4 class="heading" style="margin-bottom: 14px;">Reduce price</h4> <div>
                <div>
                    <?php
                    $reduce_price = get_setting('product_reduce_price',true);
                    ?>
                    <div class="d-inline-block input_field" style="vertical-align: middle;">
                        <label>Discount</label>
                        <div class="mt-4">
                            <input class="form-control" min="0" type="number" name="product_reduce_price[price]" value="<?php echo @$reduce_price['price']?>">
                        </div>
                    </div>
                    <div class="d-inline-block input_field" style="vertical-align: middle;">
                        <label>Discount type</label>
                        <div class="mt-4">
                            <select class="form-control select2" name="product_reduce_price[type]" style="min-width: 150px" value="<?php echo @$reduce_price['type']?>">
                                <option value="percent">Percentage</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="pt-30 flex_space"></div>
            </div>*/ ?>

            <h4 class="heading" style="margin-bottom: 14px;">Global discount</h4>
            <div>
                <div>
                    <?php
                    $global_discount = get_setting('global_discount',true);
                    ?>
                    <div class="d-inline-block input_field" style="vertical-align: middle;">
                        <label>Discount</label>
                        <div class="mt-4">
                            <input class="form-control" <?php echo @$global_discount['type'] == 'off' ? 'readonly':'' ?> min="0" type="number" name="global_discount[price]" value="<?php echo @$global_discount['price']?>">
                        </div>
                    </div>
                    <div class="d-inline-block input_field" style="vertical-align: middle;">
                        <label>Discount type</label>
                        <div class="mt-4">
                            <select class="form-control select2" name="global_discount[type]" style="min-width: 150px" value="<?php echo @$global_discount['type']?>">
                                <option value="percent">Percentage</option>
                                <option value="fixed">Fixed</option>
                                <option value="off">Off</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="pt-30 flex_space"></div>
            </div>

            <div class="mt-15">
                <div class="input_field">
                    <h4 class="heading" style="margin-bottom: 14px;">Discount by user role</h4>
                    <div class="pt-20">
                        <?php
                        foreach($user_roles as $role) {
                            if($role->status) {
                                $get_discount = get_setting('user_discount',true);
                                if($get_discount) {
                                    foreach ($get_discount as $discount) {
                                        if ($discount['role_id'] === $role->id) {
                                            $get_discount = $discount;
                                            break;
                                        }
                                    }
                                }
                                ?>
                                <div class="pb-20 flex" style="gap: 30px; align-items: center">
                                    <div class="d-inline-block input_field" style="vertical-align: top; min-width: 150px">
                                        <label style="color: var(--color-2)"><?php echo $role->name ?></label>
                                    </div>
                                    <div class="d-inline-block input_field" style="vertical-align: top;">
                                        <label>
                                            <?php 
                                                // Change label to "Amount" if the role is "Wholesale Min Amt"
                                                echo $role->name === 'Wholesale Min Amt' ? 'Amount' : 'Discount';
                                            ?>
                                        </label>
                                        <div class="mt-4">
                                            <input type="hidden" name="user_discount[<?php echo $role->id ?>][role_id]" value="<?php echo $role->id ?>">
                                            <input class="form-control" min="0" <?php echo $get_discount['role_discount_type'] == 'off' ? 'readonly':'' ?> type="number" name="user_discount[<?php echo $role->id ?>][role_discount]" value="<?php echo @$get_discount['role_discount']?>">
                                        </div>
                                    </div>
                                    <div class="d-inline-block input_field" style="vertical-align: top; <?php echo $role->name === 'Wholesale Min Amt' ? 'display: none;' : ''; ?>">
                                        <label>Discount type</label>
                                        <div class="mt-4">
                                            <select class="form-control select2" name="user_discount[<?php echo $role->id ?>][role_discount_type]" style="min-width: 150px" value="<?php echo @$get_discount['role_discount_type']?>">
                                                <option value="percent">Percentage</option>
                                                <option value="fixed">Fixed</option>
                                                <option value="off">Off</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-22"></div>

        <div class="row footer">
            <div class="col-lg-12 btn_bar flex_space">
                <input data-tab-current-url type="hidden" name="current_url" value="<?php echo current_url() ?>">
                <button type="submit" class=" btn save btn-sm">Save changes</button>
            </div>
        </div>

    </form>

</section>