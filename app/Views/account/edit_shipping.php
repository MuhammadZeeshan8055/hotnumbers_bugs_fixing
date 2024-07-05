
<?php $session = session(); ?>

<?php echo view( 'includes/header');?>

<div class="underbanner" style="background: url('<?php echo base_url('assets/images'); ?>/banner.jpg');"></div>

<!-- wrapper -->
<div class="wrapper">
    <!-- title -->
    <h1 class="pagetitle">My account</h1>
    <div class="container">
        <div class="woocommerce">

            <!-- menue user dashboard -->
            <?php echo view('account/menu');?>

            <?php
            $data = [
                'first_name'=>'',
                'last_name'=>'',
                'email'=>'',
                'company'=>'',
                'country_region'=>'',
                'address_1'=>'',
                'address_2'=>'',
                'city'=>'',
                'state'=>'',
                'postcode'=>'',
                'phone'=>''
            ];

            if(!empty($shipping_address)) {
                $data = array_merge($data,[
                    'first_name'=>!empty($shipping_address['shipping_first_name']) ? $shipping_address['shipping_first_name'] : '',
                    'last_name'=>!empty($shipping_address['shipping_last_name']) ? $shipping_address['shipping_last_name'] : '',
                    'company'=>!empty($shipping_address['shipping_company']) ? $shipping_address['shipping_company'] : '',
                    'country_region'=>!empty($shipping_address['shipping_country']) ? $shipping_address['shipping_country'] : '',
                    'address_1'=>!empty($shipping_address['shipping_address_1']) ? $shipping_address['shipping_address_1'] : '',
                    'address_2'=>!empty($shipping_address['shipping_address_2']) ? $shipping_address['shipping_address_2'] : '',
                    'city'=>!empty($shipping_address['shipping_city']) ? $shipping_address['shipping_city'] : '',
                    'state'=>!empty($shipping_address['shipping_state']) ? $shipping_address['shipping_state'] : '',
                    'postcode'=>!empty($shipping_address['shipping_postcode']) ? $shipping_address['shipping_postcode'] : '',
                    'phone'=>!empty($shipping_address['shipping_phone']) ? $shipping_address['shipping_phone'] : '',
                    'email'=>!empty($shipping_address['shipping_email']) ? $shipping_address['shipping_email'] : ''
                ]);
            }

            ?>

            <?php
            $billing_data = [
                'first_name'=>'',
                'last_name'=>'',
                'company'=>'',
                'country_region'=>'',
                'address_1'=>'',
                'address_2'=>'',
                'city'=>'',
                'county'=>'',
                'postcode'=>'',
                'phone'=>'',
                'email'=>'',
            ];

            if(!empty($billing_address)) {
                $billing_data = array_merge($billing_data,[
                    'first_name'=>!empty($billing_address['billing_first_name']) ? $billing_address['billing_first_name'] : '',
                    'last_name'=>!empty($billing_address['billing_last_name']) ? $billing_address['billing_last_name'] : '',
                    'company'=>!empty($billing_address['billing_company']) ? $billing_address['billing_company'] : '',
                    'country_region'=>!empty($billing_address['billing_country']) ? $billing_address['billing_country'] : '',
                    'address_1'=>!empty($billing_address['billing_address_1']) ? $billing_address['billing_address_1'] : '',
                    'address_2'=>!empty($billing_address['billing_address_2']) ? $billing_address['billing_address_2'] : '',
                    'city'=>!empty($billing_address['billing_city']) ? $billing_address['billing_city'] : '',
                    'county'=>!empty($billing_address['billing_state']) ? $billing_address['billing_state'] : '',
                    'postcode'=>!empty($billing_address['billing_postcode']) ? $billing_address['billing_postcode'] : '',
                    'phone'=>!empty($billing_address['billing_phone']) ? $billing_address['billing_phone'] : '',
                    'email'=>!empty($billing_address['billing_email']) ? $billing_address['billing_email'] : ''
                ]);
            }
            ?>

            <!--billing address edit -->
            <div class="woocommerce-MyAccount-content">

                <?php echo get_message() ?>

                <form method="post" class="form" action="<?php echo base_url('account/edit-address/shipping') ?>" >
                    <div>
                        <div class="pull-left">
                            <h3>Shipping address</h3>
                        </div>
                        <div class="pull-right">
                            <br>
                            <a href="#" onclick="copy_billing_address(); return false" class="btn-secondary btn btn-sm">Copy from billing address</a>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="woocommerce-address-fields">
                        <div class="pt-30">
                            <!-- billing country -->

                                    <div class="row">
                                        <?php
                                        $selling_countries = get_selling_countries();

                                        if(count($selling_countries) > 1) {
                                            ?>
                                            <div class="col-md-12">
                                                <label>Country/Region</label>
                                                <?php echo form_dropdown('shipping_country',$selling_countries,set_value('billing_country',$data['billing_country']),['required'=>'required', 'data-error'=>'Billing country is required','class'=>'input-text']) ?>
                                            </div>
                                            <?php
                                        }else {
                                            $selling_country_code = array_keys($selling_countries);
                                            $selling_country = $selling_countries[$selling_country_code[0]];
                                            ?>
                                        <div class="col-md-12">
                                            <label>Country/Region</label>
                                            <strong><?php echo $selling_country ?></strong>
                                            <input autocomplete="country" class="country_to_state form-control" id="billing_country"
                                                   name="shipping_country" readonly="readonly" type="hidden"
                                                   value="<?php echo $selling_country_code[0] ?>">
                                        </div>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <div class="row mt-15 d-flex">
                                        <div class="col-md-4">
                                            <label>First Name</label>
                                            <input name="first_name" value="<?php echo old('first_name',$data['first_name']); ?>" class="form-control">
                                            <?php echo error_message(@$form_error['first_name']) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Last Name</label>
                                            <input name="last_name" value="<?php echo old('last_name',$data['last_name']); ?>" class="form-control">
                                            <?php echo error_message(@$form_error['last_name']) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Company name</label>
                                            <input name="company" placeholder="" value="<?php echo old('company',$data['company']); ?>" class="form-control">
                                            <?php echo error_message(@$form_error['company']) ?>
                                        </div>
                                    </div>

                                    <div class="row mt-15 d-flex">
                                        <div class="col-md-6">
                                             <label>Address 1*</label>
                                            <input name="address_1" placeholder="Address 1" required value="<?php echo old('address_1',$data['address_1']); ?>" class="form-control">
                                            <?php echo error_message(@$form_error['address_1']) ?>
                                        </div>
                                        <div class="col-md-6">
                                             <label>Address 2</label>
                                            <input name="address_2" placeholder="Address 2" value="<?php echo old('address_2',$data['address_2']); ?>" class="form-control">
                                            <?php echo error_message(@$form_error['address_2']) ?>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="row d-flex">
                                            <div class="col-md-6">
                                                  <label>Town / City *</label>
                                                <input name="city" placeholder="Town / City *" required value="<?php echo old('city',$data['city']); ?>" class="form-control">
                                                <?php echo error_message(@$form_error['city']) ?>
                                            </div>
                                            <div class="col-md-6">
                                                  <label>County (optional)</label>
                                                <input name="county" placeholder="County" value="<?php echo old('county',$data['state']); ?>" class="form-control">
                                                <?php echo error_message(@$form_error['county']) ?>
                                            </div>
                                        </div>
                                    </div>

                                   <div class="row d-flex">
                                            <div class="col-md-6">
                                                  <label>Postcode *</label>
                                                <input name="postcode" placeholder="Postcode" value="<?php echo $data['postcode']; ?>" class="form-control">
                                                <?php echo error_message(@$form_error['postcode']) ?>
                                            </div>
                                            <div class="col-md-6">
                                                  <label>Phone</label>
                                                <input name="phone" placeholder="Phone" value="<?php echo old('phone',$data['phone']); ?>" class="form-control">
                                                <?php echo error_message(@$form_error['phone']) ?>
                                            </div>
                                        </div>

                                        <div class="row">
                                                <div class="col-md-12" style="padding: 0 15px;">
                                                      <label>Email address</label>
                                                    <input name="email" placeholder="Email address" value="<?php echo old('email',$data['email']); ?>" class="form-control">
                                                    <?php echo error_message(@$form_error['email']) ?>
                                                </div>
                                            </div>

                                        <br>

                        			    <button type="submit" class="button" value="1" name="save_address">Save address</button>
                        </div>
                </form>
            </div>

            <script>
                let copy_billing_address = ()=> {
                    <?php
                    if(!empty($billing_data)) {
                        foreach($billing_data as $key=>$value) {
                        ?>
                        $("[name='<?php echo $key ?>']").val('<?php echo $value ?>');
                        <?php
                        }
                    }
                    ?>
                }
            </script>

        </div>
        <!--billing address edit end -->


    </div>
</div>

<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


