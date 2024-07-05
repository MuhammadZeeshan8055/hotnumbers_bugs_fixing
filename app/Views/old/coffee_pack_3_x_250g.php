




 <!--header-------------->
 <?php echo view('includes/header.php');?>
    <!---headder end-------->



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

    <!--main body ---> 
    

    <!--- banner -->
    <div class="underbanner" style="background: url('<?php echo base_url('./assets/images/coffee-club-subscription/banner.jpg') ?>') no-repeat;  "></div>
      <div id="shop" class="woocommerce wrapper content-area">
        <div class="container home-container" role="main" style="padding-top: 2em !important;">
          <nav class="woocommerce-breadcrumb">
            <a href="<?php echo base_url() ?>">Home</a>
              &nbsp;&#47;&nbsp;
            <a href="<?php echo base_url('shop_coffee') ?>">Coffee</a>
                &nbsp;&#47;&nbsp;Coffee Pack (3 x 250g)
          </nav>        
        


        <style>
.woocommerce-Price-amount {
    font-size: 24px !important;
}

.flexslider .slides img {
  width: 100%;
  display: block;
}


.flexslider .slides > li {
  display: none;
  -webkit-backface-visibility: hidden;
}


.flexslider .slides img {
  height: auto;
  -moz-user-select: none;
}

.flex-control-thumbs img {
  width: 100%;
  height: auto;
  display: block;
  opacity: .7;
  cursor: pointer;
  -moz-user-select: none;
  
  -webkit-transition: all 1s ease;
  -moz-transition: all 1s ease;
  -ms-transition: all 1s ease;
  -o-transition: all 1s ease;
  transition: all 1s ease;
}

        #shop > .container {
    max-width: initial;
    padding-left: 0;
    padding-right: 0;
}

#shop .woocommerce-breadcrumb {
    max-width: 1200px;
    margin: auto;
    margin-bottom:4em;
}

#shop .product_content {
    position: relative;
    color: #fff;
}

#shop .product_content h4 {
    font-weight: 300;
}

#shop .product_content table:not(.ui-datepicker-calendar) {
    text-align: left;
    width: 100%;
}

#shop .product_content table:not(.ui-datepicker-calendar) th {
    min-width: 85px;
    vertical-align: top;
}

#shop .product_content .p_title {
    margin-top: 0.5em;
}

#shop .product_content hr {
    padding-bottom: 0.1em;
}

#shop .product_content .product_info {
    padding: 0 24px;
}

#shop .product_image img {
    height: auto;
    width: 100%;
    max-width: initial;
    position: relative;
}

.add_to_cart_form {
    border: 1px solid #fff;
    padding-bottom: 22px;
    margin-top: 20px;
}

.add_to_cart_form .header {
    padding: 18px;
    background-color: #fff;
    color: #000;
}

.add_to_cart_form[class] h3.pull-left {
    margin: 0;
    font-size: 32px;
}

.add_to_cart_form .header > div {
    color: #C01E30;
    font-weight: 600;
    font-size: 24px;
}

.add_to_cart_form[class] form label {
    font-size: 18px;
    font-weight: 600;
    margin-top: 0;
    padding-top: 10px;
    display: inline-block;
    vertical-align: middle
}

#shop .product_content table span {
    display: inline-block;
    position: relative;
    margin: 3px 6px;
    cursor: pointer;
}

#shop .product_content table span img {
    width: 22px;
    position: absolute;
    top: 50%;
    left: 0;
    margin-top: 5px;
    transform: translateY(-50%);
}

.add_to_cart_form[class] form td.label {
    min-width: 110px;
    margin-top: 0;
    padding-top: 0;
    display: table-caption;
}

.add_to_cart_form[class] select {
    border: 1px solid #fff;
    color: #fff;
    padding: 10px;
    display: block;
    width: 100%;
    font-size: 16px;
    font-weight: 400;
    font-family: inherit;
    margin-top: 0;
    background-color: transparent;
}

.add_to_cart_form[class] select option {
    background-color: #000;
    color: #fff;
}

.add_to_cart_form[class] .screen-reader-text {
    display: none;
}

.add_to_cart_form[class] div.qty {
    width: 40%;
    display: inline-block;
}

.add_to_cart_form[class] .qty + div.quantity {
    width: 59%;
    display: inline-block;
    text-align: left;
}

.add_to_cart_form[class] .qty + div.quantity input.qty {
    width: 100%;
    text-align: left;
    background-color: transparent;
    color: white;
}

/* Chrome, Safari, Edge, Opera */
.add_to_cart_form[class] .qty::-webkit-outer-spin-button,
.add_to_cart_form[class] .qty::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.add_to_cart_form[class] .box_content {
    padding: 22px;
}

.add_to_cart_form[class] .box_content > .stock {
    display: none;
}

/* Firefox */
.add_to_cart_form[class] .qty[type=number] {
    -moz-appearance: textfield;
}

.add_to_cart_form[class] .button {
    margin: 0;
    float: right;
    display: table;
    margin-top: 1.5em;
    padding: 16px 32px;
    font-size: 16px;
    text-transform: capitalize;
}

.popup_container {
    visibility: hidden;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
    opacity: 0;
}

.popup_container.open {
    opacity: 1;
    visibility: visible;
}

.variation_popup {
    position: fixed;
    top: 50%;
    left: 50%;
    background-color: #fff;
    transform: translate(-50%, -50%);
    padding: 30px 45px;
    z-index: 100000;
}

.variation_popup h3 {
    font-size: 28px;
    margin-bottom: 0;
}

.variation_popup .popup_close {
    position: absolute;
    right: 1em;
    top: 1em;
}

.variation_popup .content h4 {
    font-size: 18px;
    text-transform: uppercase;
    margin-bottom: 0;
    margin-top: 0px;
}

.variation_popup .content p {
    margin-bottom: 0;
    line-height: 10px;
    font-size: 16px;
}

.variation_popup .content span {
    font-size: 16px;
    color: #aaa;
    display: block;
    padding-top: 6px;
}

.variation_popup .content .thumb img {
    margin: 0 0.8em 0 0;
}

.variation_popup .content td {
    padding-bottom: 30px;
}

.variation_popup .content td.thumb + td {
    width: 210px;
}

.popup_container .popup_backdrop {
    position: fixed;
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
    background-color: #000;
    z-index: 10000;
    opacity: 0.4;
}

.product_footer {
    padding-top: 3em;
}

.product_footer .related h2 {
    font-size: 34px;
    margin-bottom: 20px;
}

.product_footer .related ul.products li.product a img {
    margin: 0 0 5px;
}

.product_footer ul.products li.product:last-child {
    margin-right: 0;
}

.product_footer ul.products li.product .woocommerce-loop-product__title {
    font-size: 16px;
}

.product_footer ul.products li.product .price {
    font-size: 20px;
    margin-top: 8px;
}

.shop_container .product_footer + .store_footer {
    padding-top: 1em;
}

/*.woocommerce ul.products li.product .price {*/
/*    display: none;*/
/*}*/
.variations a.reset_variations {
    float: right;
}
.variations_button .woocommerce-variation {
    padding-top: 1.5em;
}
.variations_button .woocommerce-variation .woocommerce-variation-title {
    display: inline-block;
    float: left;
    width: auto;
    padding-top: 54px;
}
.variations_button .woocommerce-variation .woocommerce-variation-box {
    display: inline-block;
    float: right;
    width: auto;
    text-align: right;
}
.variations_button .woocommerce-variation .woocommerce-variation-box p {
    margin-bottom: 0;
}
.variations_button .woocommerce-variation .woocommerce-Price-amount {
    font-size: 42px;
    font-weight: 600;
}
.woocommerce-variation-availability .in-stock:before {
    content: '✓';
    margin-right: 5px;
}
.add_to_cart_form[class] select {
margin-right: 0;
}

 .gallery-imgs {
    position: relative;
    margin-bottom: 3em;
}
.gallery-imgs > .slides {
    opacity: 0;
}
.gallery-imgs .flex-viewport > .slides {
    opacity: 0;
    animation: fadeIn 0.3s ease forwards;
}
.gallery-imgs .flex-control-nav {
    bottom: 15px;
}
.gallery-imgs .flex-control-nav li a {
    background-color: #fff;
}
.gallery-imgs .flex-control-nav li a.flex-active {
    background-color: #D62135;
}

html body h5 {
    font-weight: 500 !important;
}
        </style>

        <!--- shop container --->
        <div class="shop_container">
            <div class="header">
                <div class="container text-center">
                    <div class="pull-left">
                      <h1 style="text-transform: none;">Coffee Pack (3 x 250g)</h1>
                    </div>
                    <div class="pull-right">
                      <h5 style="margin-top: 26px;">
                        <img style="width: 12px; margin-right: 5px;" src="<?php echo base_url('assets/images'); ?>/location.png">
                        <span id="region"></span>
                      </h5>
                    </div>
                    <div class="clear"></div>
                </div>
              </div>

            <!--- body container --->
            <div class="body woocommerce wrapper">
              <div class="flex-text">
                  <div class="container no_padding">
                    <!-- gallery pics --->
                    <?php include 'sliderflex_coffee_pack.php';?>
                    <!-- gallery pics --->

                    <div class="product_content">
                        <div class="cont">
                            <article class="flex-block">
                            <h4 class="p_title">Coffee Pack (3 x 250g)</h4>
                                <p>A selection of 3 of our 250g bags of coffee. We will send you a delicious selection of 3 different coffees from our range. We will try to include a range of coffees from the flavour range from an easy drinking coffee, to a fruity coffee to a blend, to give you range of flavours to try.  Alternatively, if you have any special requests for particular coffee to want, please feel free to leave a message in the notes section when ordering and we will try to accommodate (stock dependant)  </p>
                                    <table class="attributes">
                                        <tbody>                                            
                                        </tbody>
                                    </table>
                            </article>
                                    
                            <article class="flex-block">
                                <div class="add_to_cart_form">
                                    <div class="header">
                                    <h3 class="pull-left">Purchase</h3>
                                    <div class="pull-right">                                                                                                           
                                        <span class="woocommerce-Price-amount amount">
                                            <bdi><span class="woocommerce-Price-currencySymbol">&pound;</span>25.00</bdi></span> per item </div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="box_content">
                                                <p class="stock in-stock">In stock</p>



                                                <!----Script------>
	                                <script>if(flycart_woo_discount_rules_strikeout_script_executed == undefined){jQuery( document ).ready( function() {jQuery( ".single_variation_wrap" ).on( "show_variation", function ( event, variation, purchasable ) {        var container = jQuery(".single_variation .woocommerce-variation-price");        var current_object = jQuery(this);
                                    current_object.trigger("woo_discount_rules_before_variant_strikeout");/*container.hide("slow");*/        jQuery.ajax({
                                    url: woo_discount_rules.ajax_url,
                                    dataType: "json",
                                    type: "POST",
                                    data: {action: "loadWooDiscountedPriceForVariant", id: variation.variation_id, price_html: variation.price_html},
                                    beforeSend: function() {
                                    },
                                    complete: function() {
                                    },
                                    success: function (response) {
                                        if(response.status == 1){
                                            jQuery(".single_variation .woocommerce-variation-price").html(response.price_html);
                                        }
                                        current_object.trigger("woo_discount_rules_after_variant_strikeout");
                                        /*container.show("slow");*/
                                    }
                                });    });    if(jQuery(".woo_discount_rules_variant_table").length > 0){
                                var p_id = jQuery( ".woo_discount_rules_variant_table" ).attr("data-id");        var already_exists = 0;        var last_storage_time = "";        setTimeout(function(){
                                        jQuery.ajax({
                                            url: woo_discount_rules.ajax_url,
                                            type: "POST",
                                            data: {action: "loadWooDiscountedDiscountTable", id: p_id, loaded: already_exists, time: last_storage_time},
                                            beforeSend: function() {
                                            },
                                            complete: function() {
                                            },
                                            success: function (response) {
                                                responseData = jQuery.parseJSON(response);
                                                if(responseData.cookie == "1" && already_exists){                    } else {
                                                    jQuery(".woo_discount_rules_variant_table").html(responseData.html);                    }
                                            }
                                        });
                                    }, 1);    }});var flycart_woo_discount_rules_strikeout_script_executed = 1; }</script>

                                    <!----Script end ------>


	                                            <form class="cart" action="https://hotnumberscoffee.co.uk/product/coffee-pack-3-x-250g/" method="post" enctype='multipart/form-data'>
		                                            <div class="wc-pao-addon-container  wc-pao-addon wc-pao-addon-grind" data-product-name="Coffee Pack (3 x 250g)">
							                            <label class="wc-pao-addon-name" data-addon-name="Grind" data-has-per-person-pricing="" data-has-per-block-pricing="">Grind </label>
	                                                    <p class="form-row form-row-wide wc-pao-addon-wrap wc-pao-addon-25445-grind-0">
	                                                        <select class="wc-pao-addon-field wc-pao-addon-select" name="addon-25445-grind-0" >
					                                            <option value="">None</option>
							                                        <option
				                                            data-raw-price=""
				                                            data-price=""
				                                            data-price-type="flat_fee"
				                                            data-raw-duration=""
				                                            data-duration=""
				                                            data-duration-type="flat_time"
				                                            value="wholebean-1"
				                                            data-label="Wholebean">
                                                            Wholebean 
                                                                    </option>
					                                                <option
				                                            data-raw-price=""
				                                            data-price=""
				                                            data-price-type="flat_fee"
				                                            data-raw-duration=""
				                                            data-duration=""
				                                            data-duration-type="flat_time"
				                                            value="french-press-2"
				                                            data-label="French Press">
                                                            French Press 
                                                                    </option>
					                                                <option
				                                            data-raw-price=""
				                                            data-price=""
				                                            data-price-type="flat_fee"
				                                            data-raw-duration=""
				                                            data-duration=""
				                                            data-duration-type="flat_time"
				                                            value="filter-3"
				                                            data-label="Filter">
                                                            Filter 
                                                                    </option>
					                                                <option
				                                            data-raw-price=""
				                                            data-price=""
				                                            data-price-type="flat_fee"
				                                            data-raw-duration=""
				                                            data-duration=""
				                                            data-duration-type="flat_time"
				                                            value="aeropress-4"
				                                            data-label="Aeropress">
                                                            Aeropress 
                                                                    </option>
					                                                <option
				                                            data-raw-price=""
				                                            data-price=""
				                                            data-price-type="flat_fee"
				                                            data-raw-duration=""
				                                            data-duration=""
				                                            data-duration-type="flat_time"
				                                            value="moka-pot-5"
				                                            data-label="Moka Pot">
                                                            Moka Pot 
                                                                    </option>
					                                                <option
				                                            data-raw-price=""
				                                            data-price=""
				                                            data-price-type="flat_fee"
				                                            data-raw-duration=""
				                                            data-duration=""
				                                            data-duration-type="flat_time"
				                                            value="espresso-6"
				                                            data-label="Espresso">
                                                            Espresso 
                                                                    </option>
					                                                <option
				                                            data-raw-price=""
                                                            data-price=""
				                                            data-price-type="flat_fee"
				                                            data-raw-duration=""
				                                            data-duration=""
				                                            data-duration-type="flat_time"
				                                            value="turkish-7"
				                                            data-label="Turkish">
                                                            Turkish 
                                                                </option>
			                                                </select>
                                                        </p>
	                                                    <div class="clear"></div>
                                                    </div>

                                                    <div id="product-addons-total" data-show-sub-total="1" data-type="simple" data-tax-mode="incl" data-tax-display-mode="incl" data-price="25" data-raw-price="25" data-product-id="25445">
                                                        <div class="product-addon-totals" >
                                                            <ul>
                                                                <li>
                                                                    <div class="wc-pao-col1">
                                                                        <strong>1x Coffee Pack (3 x 250g)</strong>
                                                                    </div>
                                                                
                                                                    <div class="wc-pao-col2">
                                                                        <strong>£25.00</strong>
                                                                    </div>
                                                                </li>
                                                                <li class="wc-pao-subtotal-line">
                                                                   <p class="price">Subtotal 
                                                                       <span class="amount"> £25.00</span>
                                                                    </p>
                                                                </li>


                                                             </ul>                                                                       
                                                        </div>
                                                    </div>
		                                                <div class="qty">Quantity: </div>	
                                                            <div class="quantity">
				                                                <label class="screen-reader-text" for="quantity_6267877a10b3a">Coffee Pack (3 x 250g) quantity</label>
		                                                            <input
			                                                            type="number"
			                                                            id="quantity_6267877a10b3a"
			                                                            class="input-text qty text"
			                                                            step="1"
			                                                            min="1"
			                                                            max="773"
			                                                            name="quantity"
			                                                            value="1"
			                                                            title="Qty"
			                                                            size="4"
			                                                            placeholder=""
			                                                            inputmode="numeric" />
			                                                </div>
	                                                        <div class="woocommerce-variation single_variation"></div>
		                                                    <button type="submit" name="add-to-cart" value="25445" class="single_add_to_cart_button button alt">Add to basket</button>
			                                    </form>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </article>
                                </div>

                                <hr>
                                    <div class="product_info">
                                        <h4>Additional Information</h4>
                                        <p>
                                            <p>A selection of 3 of our 250g bags of coffee. We will send you a delicious selection of 3 different coffees from our range. We will try to include a range of coffees from the flavour range from an easy drinking coffee, to a fruity coffee to a blend, to give you range of flavours to try.  Alternatively, if you have any special requests for particular coffee to want, please feel free to leave a message in the notes section when ordering and we will try to accommodate (stock dependant)</p>
                                            <p>&nbsp;</p>
                                            <p>&nbsp;</p>
                                        </p>
                                    </div>
                        </div>
                    </div>
                </div>              
                </div>
              </div>
                


              <!--- bottom arrows --->
              <div class="bottom_arrow">
                <img src="<?php echo base_url('assets/images'); ?>/icon-coffee-small.png" width="32"></div>
              </div>

              <!-- related products --->
              <div class="product_footer">
                <div class="container">
                  <section class="related products">
		                <h2>Related products</h2>
		                <ul class="products columns-4">                             		
                            <li class="product type-product post-34820 status-publish first instock product_cat-coffee product_tag-cambridge product_tag-coffee product_tag-colombia product_tag-hot-numbers-coffee-roasters product_tag-online product_tag-speciality-coffee has-post-thumbnail taxable shipping-taxable purchasable product-type-variable">
                                <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                                    <img width="300" height="225" src="<?php echo base_url('./assets/images/rwanda-bwenda-441/Bwenda-2.jpg') ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy" srcset="<?php echo base_url('./assets/images/rwanda-bwenda-441/Bwenda-2.jpg') ?> 300w, <?php echo base_url('./assets/images/rwanda-bwenda-441/Bwenda-2.jpg') ?> 768w, <?php echo base_url('./assets/images/rwanda-bwenda-441/Bwenda-2.jpg') ?> 600w, <?php echo base_url('./assets/images/rwanda-bwenda-441/Bwenda-2.jpg') ?> 1024w" sizes="(max-width: 300px) 100vw, 300px" />
                                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">    </a>
                                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                                            <div class="details">
                                                <div class="content">
                                                    <h2 class="woocommerce-loop-product__title">Rwanda &#8211; Bwenda 441</h2>                
                                                    <span class="price">
                                                        <span class="woocommerce-Price-amount amount">From <span class="woocommerce-Price-currencySymbol">&pound;</span>10.00</span>                
                                                    </span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
			
				                <li class="product type-product post-18257 status-publish instock product_cat-coffee has-post-thumbnail taxable shipping-taxable purchasable product-type-variable">
                                    <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                                        <img width="300" height="200" src="<?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy" srcset="<?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?> 300w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?> 1024w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?> 768w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?> 1536w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?> 600w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?> 2000w" sizes="(max-width: 300px) 100vw, 300px" />
                                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">    </a>
                                                <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                                                <div class="details">
                                                    <div class="content">
                                                        <h2 class="woocommerce-loop-product__title">Body and Soul Blend</h2>                
                                                            <span class="price">
                                                                <span class="woocommerce-Price-amount amount">From <span class="woocommerce-Price-currencySymbol">&pound;</span>8.75</span>               
                                                            </span>
                                                    </div>
                                                </div>
                                            </a>
                                    </li>
							
                                    <li class="product type-product post-28145 status-publish instock product_cat-coffee has-post-thumbnail taxable shipping-taxable purchasable product-type-variable">
                                        <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                                            <img width="300" height="225" src="<?php echo base_url('./assets/images/rwanda-bwenda-441/Rocko-Mountain.jpg') ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy" srcset="<?php echo base_url('./assets/images/rwanda-bwenda-441/Rocko-Mountain.jpg') ?> 300w, <?php echo base_url('./assets/images/rwanda-bwenda-441/Rocko-Mountain.jpg') ?> 1024w, <?php echo base_url('./assets/images/rwanda-bwenda-441/Rocko-Mountain.jpg') ?> 768w, <?php echo base_url('./assets/images/rwanda-bwenda-441/Rocko-Mountain.jpg') ?> 600w, <?php echo base_url('./assets/images/rwanda-bwenda-441/Rocko-Mountain.jpg') ?> 1280w" sizes="(max-width: 300px) 100vw, 300px" />
                                                <a href="<?php echo base_url('rwanda_bwenda_441') ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">    </a>
                                                    <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                                                        <div class="details">
                                                            <div class="content">
                                                                <h2 class="woocommerce-loop-product__title">Ethiopia &#8211; Rocko Mountain: Tariku Lot #1</h2>                
                                                                    <span class="price">
                                                                        <span class="woocommerce-Price-amount amount">From <span class="woocommerce-Price-currencySymbol">&pound;</span>10.65</span>                
                                                                    </span>
                                                            </div>
                                                        </div>
                                                    </a>
                                    </li>		
			
                                    <li class="product type-product post-30596 status-publish last instock product_cat-coffee product_tag-cambridge-coffee-roastery product_tag-cambridge-decaf-coffee-roasters product_tag-coffee product_tag-decaf-coffee product_tag-hot-numbers-cofee-shop product_tag-independent-coffee-roasters has-post-thumbnail taxable shipping-taxable purchasable product-type-variable">
                                        <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                                            <img width="300" height="200" src="<?php echo base_url('./assets/images/rwanda-bwenda-441/20210318-_DSC7531-300x200.jpg') ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="The Thumper Decaf Coffee" loading="lazy" srcset="<?php echo base_url('./assets/images/rwanda-bwenda-441/20210318-_DSC7531-300x200.jpg') ?> 300w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20210318-_DSC7531-300x200.jpg') ?> 1024w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20210318-_DSC7531-300x200.jpg') ?> 768w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20210318-_DSC7531-300x200.jpg') ?> 1536w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20210318-_DSC7531-300x200.jpg') ?> 2048w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20210318-_DSC7531-300x200.jpg') ?> 600w" sizes="(max-width: 300px) 100vw, 300px" />
                                                <a href="<?php echo base_url('rwanda_bwenda_441') ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">    </a>
                                                    <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                                                        <div class="details">
                                                            <div class="content">
                                                                <h2 class="woocommerce-loop-product__title">The Thumper Decaf &#8211; Colombia Cafe Del Micay</h2>                
                                                                <span class="price">
                                                                    <span class="woocommerce-Price-amount amount">From <span class="woocommerce-Price-currencySymbol">&pound;</span>10.25</span>                
                                                                </span>
                                                            </div>
                                                        </div
                                                    ></a>
                                    </li>		
			            </ul>
	                </section>
                  <hr style="padding-bottom: 0;padding-top: 0;margin-top: -0.5em;">
                </div>
            </div>
             <!-- related products end --->


            <div id="popup_coffee_grind" class="popup_container">
                <div class="variation_popup">
                    <div class="header">
                        <h3>Coffee Grind</h3>
                        <p>Choose a grind size to suit your brew method:</p>
                        <a class="pull-right popup_close"><img
                                    src="<?php echo base_url('assets/images'); ?>/cross.png" width="30"></a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="content">
                        <table>
                            <tr>
                                <td class="thumb">
                                    <img src="<?php echo base_url('assets/images'); ?>/coffee-beans.png"
                                         width="55">
                                </td>
                                <td>
                                    <h4>Whole Bean</h4>
                                </td>
                                <td class="thumb">
                                    <img src="<?php echo base_url('assets/images'); ?>/french-press.png"
                                         width="40">
                                </td>
                                <td>
                                    <h4>French Press</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="thumb">
                                    <img style="margin-bottom: -12px"
                                         src="<?php echo base_url('assets/images'); ?>/filter.png"
                                         width="60">
                                </td>
                                <td>
                                    <h4>Filter</h4>
                                </td>
                                <td class="thumb">
                                    <img src="<?php echo base_url('assets/images'); ?>/moka-pot.png"
                                         width="40">
                                </td>
                                <td>
                                    <h4>Moka Pot</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="thumb">
                                    <img src="<?php echo base_url('assets/images'); ?>/espresso.png"
                                         width="60">
                                </td>
                                <td>
                                    <h4>Espresso</h4>
                                </td>
                                <td class="thumb">
                                    <img src="<?php echo base_url('assets/images'); ?>/aeropress_icon_01.jpg"
                                         width="35">
                                </td>
                                <td>
                                    <h4>AEROPRESS</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="thumb">
                                    <img src="<?php echo base_url('assets/images'); ?>/turkish_coffee_icon_01.jpg"
                                         width="60">
                                </td>
                                <td>
                                    <h4>Turkish Press</h4>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="popup_backdrop"></div>
            </div>

            <div id="popup_weight" class="popup_container">
                <div class="variation_popup">
                    <div class="header">
                        <h3 style="margin-bottom: 1em">Weight</h3>
                        <a class="pull-right popup_close"><img src="<?php echo base_url('assets/images'); ?>/cross.png" width="30"></a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="content">
                        <table>
                            <tr>
                                <td class="thumb" style="padding: 8px 0 27px 3px;">
                                    <img src="<?php echo base_url('assets/images'); ?>/coffee_bag_icon_01.jpg"
                                         width="48">
                                </td>
                                <td>
                                    <h4>250g</h4>
                                    <p>Approximately 15 cups</p>
                                </td>
                                <td class="thumb">
                                    <img src="<?php echo base_url('assets/images'); ?>/coffee_bag_icon_01.jpg"
                                         width="55">
                                </td>
                                <td>
                                    <h4>500g</h4>
                                    <p>Approximately 30 cups</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="thumb">
                                    <img src="<?php echo base_url('assets/images'); ?>/coffee_bag_icon_01.jpg"
                                         width="65" style="margin-left: -6px;">
                                </td>
                                <td>
                                    <h4>1 KG</h4>
                                    <p>Approximately 60 cups</p>
                                </td>
                                <td class="thumb">
                                    <img src="<?php echo base_url('assets/images'); ?>/coffee_bag_icon_01.jpg"
                                         width="80" style="margin-left: -14px;">
                                </td>
                                <td>
                                    <h4>3 KG</h4>
                                    <p>Approximately 180 cups</p>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="popup_backdrop"></div>
            </div>

            <!--- script --->
            <script>
                jQuery(function ($) {
                    $('.variations td.label').each(function () {
                        var label_for = $(this).children().attr('for');
                        switch (label_for) {
                            case 'weight':
                                $(this).append('<span class="popup_open" data-popup="#popup_weight"><img src="<?php echo base_url('assets/images'); ?>/question-white.png" width="100"></span>');
                                break;
                            case 'grind':
                                $(this).append('<span class="popup_open" data-popup="#popup_coffee_grind"><img src="<?php echo base_url('assets/images'); ?>/question-white.png" width="100"></span>');
                                break;
                        }
                    });
                    jQuery('.popup_close').bind('click', function () {
                        $('.popup_container').removeClass('open');
                    });

                    jQuery('.popup_open').click('click', function () {
                        var id = jQuery(this).data('popup');
                        $(id).addClass('open');
                    });
                });
            </script>

        </div>
        </div></div>



<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->



