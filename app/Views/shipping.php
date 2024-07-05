
<?php $session = session(); ?>

<?php echo view( 'includes/header');?>

    <style>
        .row>* {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: unset;
            padding-left: unset;
            margin-top: unset;
        }
    </style>




<div class="underbanner" style="background: url('<?php echo base_url('assets/images'); ?>/banner.jpg');"></div>

<!-- wrapper -->
<div class="wrapper">
        <!-- title -->
	<h1 class="pagetitle">My account</h1>
			<div class="container">
			    <div class="woocommerce">

                    <!-- menue user dashboard -->
                    <nav class="woocommerce-MyAccount-navigation">
                    	<ul>
                    		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--dashboard ">
                    			<a href="<?php echo base_url('cuser_login') ?>">Dashboard</a>
                    		</li>
                    		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--orders ">
                    			<a href="<?php echo base_url('orders') ?>">Orders</a>
                    		</li>
                    		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--subscriptions">
                    			<a href="<?php echo base_url('shop') ?>">Subscriptions</a>
                    		</li>
                    		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--downloads">
                    			<a href="<?php echo base_url('downloads') ?>">Downloads</a>
                    		</li>
                    		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-address is-active">
                    			<a href="<?php echo base_url('edit_address') ?>">Address</a>
                    		</li>
                    		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--payment-methods">
                    		    <a href="<?php echo base_url('payment_methods') ?>">Payment methods</a>
                    		</li>
                    		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--edit-account">
                    			<a href="<?php echo base_url('edit-account') ?>">Account details</a>
                    		</li>
                    		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--appointments">
                    			<a href="<?php echo base_url('appointments') ?>">Appointments</a>
                    		</li>
                    		<li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--customer-logout">
                    			<a href="<?php echo base_url('account/logout') ?>">Logout</a>
                    		</li>
                    	</ul>
                    </nav>
                    
          

                    <?php  $shipping_adr = json_decode($shipping_address[0]->shipping_info); ?>

                    <!--billing address edit -->
                    <div class="woocommerce-MyAccount-content">
                        
                            <form method="post" action="<?php echo base_url('edit_address/shipping') ?>" >  
                            <h3>Shipping address</h3>
                    		    <div class="woocommerce-address-fields"> 
                        			<div class="woocommerce-address-fields__field-wrapper">
				                            <p class="form-row form-row-first validate-required" id="shipping_first_name_field" data-priority="10">
				                                <label for="shipping_first_name">First name&nbsp;<abbr class="required" title="required">*</abbr></label>
				                                <span class="woocommerce-input-wrapper">
				                                    <input type="text" class="input-text" name="shipping_info[first_name]" id="shipping_first_name" placeholder=""  value="<?php echo $shipping_adr->first_name; ?>" autocomplete="given-name" /></span></p>
				                                    <p style="margin-top: -110px;" class="form-row form-row-last validate-required" id="shipping_last_name_field" data-priority="20">
				                                        <label for="shipping_last_name">Last name&nbsp;<abbr class="required" title="required">*</abbr></label>
				                                        <span class="woocommerce-input-wrapper">
				                                            <input type="text" class="input-text " name="shipping_info[last_name]" id="shipping_last_name" placeholder=""  value="<?php echo $shipping_adr->last_name; ?>" autocomplete="family-name" />
				                                            </span>
				                                            </p>
                        			   
                        			   
                        			   <!-- billing country -->
                            			<p class="form-row form-row-wide address-field update_totals_on_change validate-required" id="billing_country_field" data-priority="40">
                            			    <label for="billing_country">Country/Region&nbsp;<abbr class="required" title="required">*</abbr></label>
                            			<span class="woocommerce-input-wrapper$shipping_adr"><strong>United Kingdom (UK)</strong>
                            			<!--<input type="hidden" name="shipping_info[first_name]" id="billing_country" value="<?php echo $shipping_adr->first_name; ?>" autocomplete="country" class="country_to_state" readonly="readonly" /></span></p>-->
                            			<!--<input type="hidden" name="shipping_info[last_name]" id="billing_country" value="<?php echo $shipping_adr->last_name; ?>" autocomplete="country" class="country_to_state" readonly="readonly" /></span></p>-->
                            			<input type="hidden" name="shipping_info[company]" id="billing_country" value="<?php echo $shipping_adr->company; ?>" autocomplete="country" class="country_to_state" readonly="readonly" /></span></p>
                            			<input type="hidden" name="shipping_info[country_region]" id="billing_country" value="<?php echo $shipping_adr->country_region; ?>" autocomplete="country" class="country_to_state" readonly="readonly" /></span></p>
                            			<input type="hidden" name="user_id" id="billing_country" value="<?php echo $shipping_address[0]->user_id ?>" autocomplete="country" class="country_to_state" readonly="readonly" /></span></p>
                            			
                                			<!-- street address --> 
                                			<p class="form-row form-row-wide address-field validate-required" id="billing_address_1_field" data-priority="50">
                                				<label for="billing_address_1">Street address&nbsp;<abbr class="required" title="required">*</abbr></label>
                                				<span class="woocommerce-input-wrapper">
                                				    <input type="text" class="input-text" name="shipping_info[address_1]" id="billing_address_1" placeholder="House number and street name" value="<?php echo $shipping_adr->address_1; ?>" autocomplete="address-line1" />
                                				</span>
                                			</p>
                            				    
                            				<!--- flat, suit -->
                            				    <p class="form-row form-row-wide address-field" id="billing_address_2_field" data-priority="60">
                            				        <label for="billing_address_2" class="screen-reader-text">Flat, suite, unit, etc.&nbsp;<span class="optional">(optional)</span></label>
                            				        <input type="text" class="input-text" name="shipping_info[address_2]" id="billing_address_1" placeholder="House number and street name" value="<?php echo $shipping_adr->address_2; ?>" autocomplete="address-line1" /></span>
                            				    </p>
                            	
                            				
                            				<!-- town city -->
                            				<p class="form-row form-row-wide address-field validate-required" id="billing_city_field" data-priority="70">
                            				    <label for="billing_city">Town / City&nbsp;<abbr class="required" title="required">*</abbr></label>
                            				        <span class="woocommerce-input-wrapper">
                            				            <input type="text" class="input-text" name="shipping_info[city]" id="billing_city" placeholder="" value="<?php echo $shipping_adr->city; ?>" autocomplete="address-level2" />
                            				        </span>
                            				</p>
                            				
                            				<!-- optional country -->
                            				<p class="form-row form-row-wide address-field validate-state" id="billing_state_field" data-priority="80">
                            				    <label for="billing_state">Country&nbsp;<span class="optional">(optional)</span></label>
                                        		<span class="woocommerce-input-wrapper">
                            				        <input type="text" class="input-text" value="<?php echo $shipping_adr->state_county; ?>"  placeholder="" name="shipping_info[state_county]" id="billing_state" autocomplete="address-level1" data-input-classes=""/>
                            				    </span>
                            				</p>
                            				    
                            				<!--- postcode -->
                            				    <p class="form-row form-row-wide address-field validate-required validate-postcode" id="billing_postcode_field" data-priority="90">
                            				        <label for="billing_postcode">Postcode&nbsp;<abbr class="required" title="required">*</abbr></label>
                            				        <span class="woocommerce-input-wrapper">
                            				            <input type="text" class="input-text " name="shipping_info[postcode_zip]" id="billing_postcode" placeholder=""  value="<?php echo $shipping_adr->postcode_zip; ?>" autocomplete="postal-code" />
                            				        </span>
                            				    </p>
                            				    
                            				    <!--- phone -->
                            				    <p class="form-row form-row-wide validate-required validate-phone" id="billing_phone_field" data-priority="100">
                            				        <label for="billing_phone">Phone&nbsp;<abbr class="required" title="required">*</abbr></label>
                            				<span class="woocommerce-input-wrapper">
                            				    <input type="tel" class="input-text" name="shipping_info[phone]" id="billing_phone" placeholder=""  value="<?php echo $shipping_adr->phone; ?>" autocomplete="tel" /></span></p>
                            				
                            				<!-- email address -->
                            				<p class="form-row form-row-wide validate-required validate-email" id="billing_email_field" data-priority="110">
                            				    <label for="billing_email">Email address&nbsp;<abbr class="required" title="required">*</abbr></label>
                            				    <span class="woocommerce-input-wrapper">
                            				        <input type="email" class="input-text " name="shipping_info[email]" id="billing_email" placeholder=""  value="<?php echo $shipping_adr->email; ?>" autocomplete="email username" />
                            				    </span>
                            				</p>
                        			    
                        			 </div>
                        			<p>
                        				<button type="submit" class="button" >Save address</button>
                        			</p>
                    		    </div>
                    	    </form>    
                        </div>
                    </div>
                    <!--billing address edit end -->
 
          
            </div>
                        </div>
                                
                
                
         





<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


