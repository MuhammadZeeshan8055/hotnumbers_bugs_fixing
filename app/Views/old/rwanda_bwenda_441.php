




 <!--header-------------->
 <?php echo view('includes/header.php');?>
    <!---headder end-------->



    <style>
   
   @font-face {
       font-family: 'flexslider-icon';
       font-weight: normal;
       font-style: normal;
       src:url('<?php echo base_url(); ?>/assets/flexslider-icon/flexslider-icon.eot');
       src:url('<?php echo base_url(); ?>/assets/flexslider-icon/flexslider-icon.eot?#iefix') format('embedded-opentype');
       src:url('<?php echo base_url(); ?>/assets/flexslider-icon/flexslider-icon.woff') format('woff');
       src:url('<?php echo base_url(); ?>/assets/flexslider-icon/flexslider-icon.ttf')  format('truetype');
    }
    </style>


    <style>
        .row>* {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: unset;
            padding-left: unset;
            margin-top: unset;
        }

/*
        .flex-direction-nav{
            display: none;
        }*/

        .flex-direction-nav .flex-prev {
          opacity: 0;
          left: 0px;
        }

    
        @media screen and (max-width: 860px){
          .flex-direction-nav .flex-prev {
            opacity: 1;
            left: 10px;
          }
        }

    </style>


    <!--main body ---> 
    

    <!--- banner -->
    <div class="underbanner" style="background: url('<?php echo base_url('./assets/images/coffee-club-subscription/banner.jpg') ?>') no-repeat;  "></div>
      <div id="shop" class="woocommerce wrapper content-area">
        <div class="container home-container" role="main" style="">
          <nav class="woocommerce-breadcrumb">
            <a href="<?php echo base_url() ?>">Home</a>
              &nbsp;&#47;&nbsp;
            <a href="<?php echo base_url('shop_coffee') ?>">Coffee</a>
              &nbsp;&#47;&nbsp;Rwanda &#8211; Bwenda 441
          </nav>        
        

<style>
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
    margin-bottom:2em;
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
   /* opacity: unset;*/
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


.woocommerce-Price-amount {
    font-size: unset;
    font-weight: unset;
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

      </style>

        <!--- shop container --->
        <div class="shop_container">
            <div class="header">
                <div class="container text-center">
                    <div class="pull-left">
                      <h1 style="text-transform: none;">Rwanda &#8211; Bwenda 441</h1>
                    </div>
                    <div class="pull-right">
                      <h5 style="margin-top:26px;">
                        <img style="width: 12px; margin-right: 5px;" src="<?php echo base_url('assets/images'); ?>/location.png">
                        <span id="region">Nyamagabe District, Southern Province</span>
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
                    <?php include 'viewport.php';?>
                    <!-- gallery pics --->


                    <div class="product_content">
                      <div class="cont">
                        <article class="flex-block">
                          <h4 class="p_title">Rwanda &#8211; Bwenda 441</h4>
                          <p>A Brand new coffee from Rwanda, this coffee has hints off smooth sweetness with a citrusy zing. We really loved this coffee during our cupping for its brightness and clarity in flavour. This coffee was processed in the Bwenda wet mill, which was built to help isolated farmers process their coffee to make a more high quality cup. Perfect for a pour over but will also make a sweet espresso perfect for milk pairing. </p>
                          <table class="attributes">
                            <tbody>
                              <tr>
                                <th>Tasting Notes                                                                                                                    </th>
                                  <td>Toffee Apple | Glace Cherry | Sweet</td>
                              </tr>
                              <tr>
                                <th>Region </th>
                                <td>Nyamagabe District, Southern Province</td>
                              </tr>
                              <tr>
                                <th>Producer                                                                                                                    </th>
                                  <td>Bernard Uwitije</td>
                              </tr>
                              <tr>
                                <th>Altitude                                                                                                                    </th>
                                  <td>1700 masl</td>
                              </tr>
                              <tr>
                                <th>Variety                                                                                                                    </th>
                                  <td>Red Bourbon</td>
                              </tr>
                              <tr>
                                <th>Process                                                                                                                    </th>
                                  <td>Natural</td>
                              </tr>
                            </tbody>
                          </table>           
                        </article>
                            
                        <!--- add to cart --->
                        <article class="flex-block">
                          <div class="add_to_cart_form">
                            <div class="header">
                              <h3 class="pull-left">Purchase</h3>
                              <div class="pull-right">from 
                                <span class="woocommerce-Price-amount amount"><bdi>
                                  <span class="woocommerce-Price-currencySymbol">&pound;</span>10.00</bdi>
                                </span>                                                
                              </div>
                              <div class="clear"></div>
                            </div>
                            <div class="box_content">
                              <div class="woo_discount_rules_variant_table" data-id="34820"></div>
                                                
                                                <!--script>
                                                if(flycart_woo_discount_rules_strikeout_script_executed == undefined){
                                                  jQuery( document ).ready( function() {jQuery( ".single_variation_wrap" ).bind( "show_variation", function ( event, variation, purchasable ) {        var container = jQuery(".single_variation .woocommerce-variation-price");        var current_object = jQuery(this);
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
                                    }, 1);    }});var flycart_woo_discount_rules_strikeout_script_executed = 1; }</script-->
    
                              <form class="variations_form cart" action="https://hotnumberscoffee.co.uk/product/rwanda-bwenda-441/" method="post" enctype='multipart/form-data' data-product_id="34820" data-product_variations="[{&quot;attributes&quot;:{&quot;attribute_weight&quot;:&quot;250g&quot;,&quot;attribute_grind&quot;:&quot;&quot;},&quot;availability_html&quot;:&quot;&lt;p class=\&quot;stock in-stock\&quot;&gt;In stock&lt;\/p&gt;\n&quot;,&quot;backorders_allowed&quot;:false,&quot;dimensions&quot;:{&quot;length&quot;:&quot;&quot;,&quot;width&quot;:&quot;&quot;,&quot;height&quot;:&quot;&quot;},&quot;dimensions_html&quot;:&quot;N\/A&quot;,&quot;display_price&quot;:10,&quot;display_regular_price&quot;:10,&quot;image&quot;:{&quot;title&quot;:&quot;Bwenda 2&quot;,&quot;caption&quot;:&quot;&quot;,&quot;url&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2.jpg&quot;,&quot;alt&quot;:&quot;&quot;,&quot;src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-600x450.jpg&quot;,&quot;srcset&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-600x450.jpg 600w, https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-300x225.jpg 300w, https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-768x576.jpg 768w, https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2.jpg 1024w&quot;,&quot;sizes&quot;:&quot;(max-width: 600px) 100vw, 600px&quot;,&quot;full_src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2.jpg&quot;,&quot;full_src_w&quot;:1024,&quot;full_src_h&quot;:768,&quot;gallery_thumbnail_src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-100x100.jpg&quot;,&quot;gallery_thumbnail_src_w&quot;:100,&quot;gallery_thumbnail_src_h&quot;:100,&quot;thumb_src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-300x225.jpg&quot;,&quot;thumb_src_w&quot;:300,&quot;thumb_src_h&quot;:225,&quot;src_w&quot;:600,&quot;src_h&quot;:450},&quot;image_id&quot;:34843,&quot;is_downloadable&quot;:false,&quot;is_in_stock&quot;:true,&quot;is_purchasable&quot;:true,&quot;is_sold_individually&quot;:&quot;no&quot;,&quot;is_virtual&quot;:false,&quot;max_qty&quot;:199,&quot;min_qty&quot;:1,&quot;price_html&quot;:&quot;&lt;span class=\&quot;price\&quot;&gt;&lt;span class=\&quot;woocommerce-Price-amount amount\&quot;&gt;&lt;bdi&gt;&lt;span class=\&quot;woocommerce-Price-currencySymbol\&quot;&gt;&amp;pound;&lt;\/span&gt;10.00&lt;\/bdi&gt;&lt;\/span&gt;&lt;\/span&gt;&quot;,&quot;sku&quot;:&quot;&quot;,&quot;variation_description&quot;:&quot;&quot;,&quot;variation_id&quot;:34821,&quot;variation_is_active&quot;:true,&quot;variation_is_visible&quot;:true,&quot;weight&quot;:&quot;.250&quot;,&quot;weight_html&quot;:&quot;.250 kg&quot;},{&quot;attributes&quot;:{&quot;attribute_weight&quot;:&quot;500g&quot;,&quot;attribute_grind&quot;:&quot;&quot;},&quot;availability_html&quot;:&quot;&lt;p class=\&quot;stock in-stock\&quot;&gt;In stock&lt;\/p&gt;\n&quot;,&quot;backorders_allowed&quot;:false,&quot;dimensions&quot;:{&quot;length&quot;:&quot;&quot;,&quot;width&quot;:&quot;&quot;,&quot;height&quot;:&quot;&quot;},&quot;dimensions_html&quot;:&quot;N\/A&quot;,&quot;display_price&quot;:16.199999999999999289457264239899814128875732421875,&quot;display_regular_price&quot;:16.199999999999999289457264239899814128875732421875,&quot;image&quot;:{&quot;title&quot;:&quot;Bwenda 2&quot;,&quot;caption&quot;:&quot;&quot;,&quot;url&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2.jpg&quot;,&quot;alt&quot;:&quot;&quot;,&quot;src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-600x450.jpg&quot;,&quot;srcset&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-600x450.jpg 600w, https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-300x225.jpg 300w, https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-768x576.jpg 768w, https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2.jpg 1024w&quot;,&quot;sizes&quot;:&quot;(max-width: 600px) 100vw, 600px&quot;,&quot;full_src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2.jpg&quot;,&quot;full_src_w&quot;:1024,&quot;full_src_h&quot;:768,&quot;gallery_thumbnail_src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-100x100.jpg&quot;,&quot;gallery_thumbnail_src_w&quot;:100,&quot;gallery_thumbnail_src_h&quot;:100,&quot;thumb_src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-300x225.jpg&quot;,&quot;thumb_src_w&quot;:300,&quot;thumb_src_h&quot;:225,&quot;src_w&quot;:600,&quot;src_h&quot;:450},&quot;image_id&quot;:34843,&quot;is_downloadable&quot;:false,&quot;is_in_stock&quot;:true,&quot;is_purchasable&quot;:true,&quot;is_sold_individually&quot;:&quot;no&quot;,&quot;is_virtual&quot;:false,&quot;max_qty&quot;:197,&quot;min_qty&quot;:1,&quot;price_html&quot;:&quot;&lt;span class=\&quot;price\&quot;&gt;&lt;span class=\&quot;woocommerce-Price-amount amount\&quot;&gt;&lt;bdi&gt;&lt;span class=\&quot;woocommerce-Price-currencySymbol\&quot;&gt;&amp;pound;&lt;\/span&gt;16.20&lt;\/bdi&gt;&lt;\/span&gt;&lt;\/span&gt;&quot;,&quot;sku&quot;:&quot;&quot;,&quot;variation_description&quot;:&quot;&quot;,&quot;variation_id&quot;:34822,&quot;variation_is_active&quot;:true,&quot;variation_is_visible&quot;:true,&quot;weight&quot;:&quot;.500&quot;,&quot;weight_html&quot;:&quot;.500 kg&quot;},{&quot;attributes&quot;:{&quot;attribute_weight&quot;:&quot;1kg&quot;,&quot;attribute_grind&quot;:&quot;&quot;},&quot;availability_html&quot;:&quot;&lt;p class=\&quot;stock in-stock\&quot;&gt;In stock&lt;\/p&gt;\n&quot;,&quot;backorders_allowed&quot;:false,&quot;dimensions&quot;:{&quot;length&quot;:&quot;&quot;,&quot;width&quot;:&quot;&quot;,&quot;height&quot;:&quot;&quot;},&quot;dimensions_html&quot;:&quot;N\/A&quot;,&quot;display_price&quot;:28.949999999999999289457264239899814128875732421875,&quot;display_regular_price&quot;:28.949999999999999289457264239899814128875732421875,&quot;image&quot;:{&quot;title&quot;:&quot;Bwenda 2&quot;,&quot;caption&quot;:&quot;&quot;,&quot;url&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2.jpg&quot;,&quot;alt&quot;:&quot;&quot;,&quot;src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-600x450.jpg&quot;,&quot;srcset&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-600x450.jpg 600w, https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-300x225.jpg 300w, https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-768x576.jpg 768w, https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2.jpg 1024w&quot;,&quot;sizes&quot;:&quot;(max-width: 600px) 100vw, 600px&quot;,&quot;full_src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2.jpg&quot;,&quot;full_src_w&quot;:1024,&quot;full_src_h&quot;:768,&quot;gallery_thumbnail_src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-100x100.jpg&quot;,&quot;gallery_thumbnail_src_w&quot;:100,&quot;gallery_thumbnail_src_h&quot;:100,&quot;thumb_src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-300x225.jpg&quot;,&quot;thumb_src_w&quot;:300,&quot;thumb_src_h&quot;:225,&quot;src_w&quot;:600,&quot;src_h&quot;:450},&quot;image_id&quot;:34843,&quot;is_downloadable&quot;:false,&quot;is_in_stock&quot;:true,&quot;is_purchasable&quot;:true,&quot;is_sold_individually&quot;:&quot;no&quot;,&quot;is_virtual&quot;:false,&quot;max_qty&quot;:199,&quot;min_qty&quot;:1,&quot;price_html&quot;:&quot;&lt;span class=\&quot;price\&quot;&gt;&lt;span class=\&quot;woocommerce-Price-amount amount\&quot;&gt;&lt;bdi&gt;&lt;span class=\&quot;woocommerce-Price-currencySymbol\&quot;&gt;&amp;pound;&lt;\/span&gt;28.95&lt;\/bdi&gt;&lt;\/span&gt;&lt;\/span&gt;&quot;,&quot;sku&quot;:&quot;&quot;,&quot;variation_description&quot;:&quot;&quot;,&quot;variation_id&quot;:34823,&quot;variation_is_active&quot;:true,&quot;variation_is_visible&quot;:true,&quot;weight&quot;:&quot;1&quot;,&quot;weight_html&quot;:&quot;1 kg&quot;},{&quot;attributes&quot;:{&quot;attribute_weight&quot;:&quot;2.5kg&quot;,&quot;attribute_grind&quot;:&quot;&quot;},&quot;availability_html&quot;:&quot;&lt;p class=\&quot;stock in-stock\&quot;&gt;In stock&lt;\/p&gt;\n&quot;,&quot;backorders_allowed&quot;:false,&quot;dimensions&quot;:{&quot;length&quot;:&quot;&quot;,&quot;width&quot;:&quot;&quot;,&quot;height&quot;:&quot;&quot;},&quot;dimensions_html&quot;:&quot;N\/A&quot;,&quot;display_price&quot;:66.599999999999994315658113919198513031005859375,&quot;display_regular_price&quot;:66.599999999999994315658113919198513031005859375,&quot;image&quot;:{&quot;title&quot;:&quot;Bwenda 2&quot;,&quot;caption&quot;:&quot;&quot;,&quot;url&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2.jpg&quot;,&quot;alt&quot;:&quot;&quot;,&quot;src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-600x450.jpg&quot;,&quot;srcset&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-600x450.jpg 600w, https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-300x225.jpg 300w, https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-768x576.jpg 768w, https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2.jpg 1024w&quot;,&quot;sizes&quot;:&quot;(max-width: 600px) 100vw, 600px&quot;,&quot;full_src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2.jpg&quot;,&quot;full_src_w&quot;:1024,&quot;full_src_h&quot;:768,&quot;gallery_thumbnail_src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-100x100.jpg&quot;,&quot;gallery_thumbnail_src_w&quot;:100,&quot;gallery_thumbnail_src_h&quot;:100,&quot;thumb_src&quot;:&quot;https:\/\/hotnumberscoffee.co.uk\/wp-content\/uploads\/2022\/03\/Bwenda-2-300x225.jpg&quot;,&quot;thumb_src_w&quot;:300,&quot;thumb_src_h&quot;:225,&quot;src_w&quot;:600,&quot;src_h&quot;:450},&quot;image_id&quot;:34843,&quot;is_downloadable&quot;:false,&quot;is_in_stock&quot;:true,&quot;is_purchasable&quot;:true,&quot;is_sold_individually&quot;:&quot;no&quot;,&quot;is_virtual&quot;:false,&quot;max_qty&quot;:200,&quot;min_qty&quot;:1,&quot;price_html&quot;:&quot;&lt;span class=\&quot;price\&quot;&gt;&lt;span class=\&quot;woocommerce-Price-amount amount\&quot;&gt;&lt;bdi&gt;&lt;span class=\&quot;woocommerce-Price-currencySymbol\&quot;&gt;&amp;pound;&lt;\/span&gt;66.60&lt;\/bdi&gt;&lt;\/span&gt;&lt;\/span&gt;&quot;,&quot;sku&quot;:&quot;&quot;,&quot;variation_description&quot;:&quot;&quot;,&quot;variation_id&quot;:34824,&quot;variation_is_active&quot;:true,&quot;variation_is_visible&quot;:true,&quot;weight&quot;:&quot;2.5&quot;,&quot;weight_html&quot;:&quot;2.5 kg&quot;}]">
                                <table class="variations" cellspacing="0" style="">
                                  <tbody>
                                    <tr>
                                      <td class="label"><label for="weight">Weight</label></td>
                                      <td class="value">
                                        <select id="weight" class="" name="attribute_weight" data-attribute_name="attribute_weight" data-show_option_none="yes">
                                          <option value="">Choose an option</option>
                                          <option value="250g" >250g</option>
                                          <option value="500g" >500g</option>
                                          <option value="1kg" >1kg</option>
                                          <option value="2.5kg" >2.5kg</option>
                                        </select>                            
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="label"><label for="grind">Grind</label></td>
                                        <td class="value">
                                          <select id="grind" class="" name="attribute_grind" data-attribute_name="attribute_grind" data-show_option_none="yes">
                                            <option value="">Choose an option</option>
                                            <option value="Whole bean" >Whole bean</option>
                                            <option value="French press" >French press</option>
                                            <option value="Filter" >Filter</option>
                                            <option value="Espresso" >Espresso</option>
                                            <option value="Moka pot" >Moka pot</option>
                                            <option value="Turkish Press" >Turkish Press</option>
                                          </select>
                                          <a class="reset_variations" href="#">Clear</a>                            
                                        </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <!--- add to cart end --->

                                <div class="single_variation_wrap">
                                  <div class="woocommerce-variation-add-to-cart variations_button">
                                    <div class="qty">Quantity: </div>	
                                    <div class="quantity">
				                              <label class="screen-reader-text" for="quantity_62624048b82fa">Rwanda - Bwenda 441 quantity</label>
		                                  <input
			                                  type="number"
			                                  id="quantity_62624048b82fa"
			                                  class="input-text qty text"
			                                  step="1"
			                                  min="1"
			                                  max="999"
			                                  name="quantity"
			                                  value="1"
			                                  title="Qty"
			                                  size="4"
			                                  placeholder=""
			                                  inputmode="numeric" />
			                              </div>
	                                  <div class="woocommerce-variation single_variation"></div>
                                    <button type="submit" class="single_add_to_cart_button button alt">Add to basket</button>   
                                    <input type="hidden" name="add-to-cart" value="34820" />
                                    <input type="hidden" name="product_id" value="34820" />
                                    <input type="hidden" name="variation_id" class="variation_id" value="0" />
                                  </div>            
                                </div>
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
                            <p>Bernard Uwitije is the owner of Bwenda. Coming from a region where coffee is the dominant crop, he began trading in ordinary home processed coffee before realising the value of of a fully processed crop. In 2016 he built a wet mill to set up a sustainable business. He used his first year learning how to operate a wet mill successfully before building a second mill the next season, where he mastered processing large volumes without compromising quality. </p>
                            <p>Bwenda is his third wet mill, built in 2018 after realising a group of farmers who were a bit isolated had no access to a nearby washing station to process their cherries into high quality coffees. 2019 was the first operating season for Bwenda where they only processed one container of cherries. Bernard is already developing various programs to help farmers delivering to Bwenda with extension services and he is expanding the capacity of the mill. </p>
                          </p>
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
                          <li class="product type-product post-28145 status-publish first instock product_cat-coffee has-post-thumbnail taxable shipping-taxable purchasable product-type-variable">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                            <img width="300" height="225" src="<?php echo base_url('./assets/images/rwanda-bwenda-441/Fredy-Pineda-300x200.jpg') ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy" srcset="<?php echo base_url('./assets/images/rwanda-bwenda-441/Fredy-Pineda-300x200.jpg') ?> 300w, <?php echo base_url('./assets/images/rwanda-bwenda-441/Fredy-Pineda-300x200.jpg') ?> 1024w, <?php echo base_url('./assets/images/rwanda-bwenda-441/Fredy-Pineda-300x200.jpg') ?> 768w, <?php echo base_url('./assets/images/rwanda-bwenda-441/Fredy-Pineda-300x200.jpg') ?> 600w, <?php echo base_url('./assets/images/rwanda-bwenda-441/Fredy-Pineda-300x200.jpg') ?> 1280w" sizes="(max-width: 300px) 100vw, 300px" />
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">    </a>
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                              <div class="details">
                                <div class="content">
                                  <h2 class="woocommerce-loop-product__title">Ethiopia &#8211; Rocko Mountain: Tariku Lot #1</h2>                
                                  <span class="price">
                                    <span class="woocommerce-Price-amount amount">From 
                                      <span class="woocommerce-Price-currencySymbol">&pound;</span>10.65
                                    </span>                
                                  </span>
                                </div>
                              </div>
                            </a>
                          </li>
                          <li class="product type-product post-33564 status-publish instock product_cat-coffee has-post-thumbnail taxable shipping-taxable purchasable product-type-variable">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                              <img width="300" height="200" src="<?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy" srcset="<?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?> 300w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?> 1024w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?> 768w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?> 1536w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?> 600w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20200701-_DSC52912000-2-300x200.jpg') ?> 1600w" sizes="(max-width: 300px) 100vw, 300px" />
                              <a href="<?php echo base_url('rwanda_bwenda_441') ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">    </a>
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                              <div class="details">
                                <div class="content">
                                  <h2 class="woocommerce-loop-product__title">Peru &#8211; Roger Chilcon</h2>                
                                  <span class="price">
                                  <span class="woocommerce-Price-amount amount">From 
                                    <span class="woocommerce-Price-currencySymbol">&pound;</span>10.30 </span>                
                                  </span>
                                </div>
                              </div>
                            </a>
                          </li>
							            <li class="product type-product post-12374 status-publish instock product_cat-coffee has-post-thumbnail taxable shipping-taxable purchasable product-type-variable">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                            <img width="300" height="200" src="<?php echo base_url('./assets/images/rwanda-bwenda-441/20201009-_DSC1342-300x200.jpg') ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy" srcset="<?php echo base_url('./assets/images/rwanda-bwenda-441/20201009-_DSC1342-300x200.jpg') ?> 300w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20201009-_DSC1342-300x200.jpg') ?> 1024w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20201009-_DSC1342-300x200.jpg') ?> 768w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20201009-_DSC1342-300x200.jpg') ?> 1536w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20201009-_DSC1342-300x200.jpg') ?> 600w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20201009-_DSC1342-300x200.jpg') ?> 1600w" sizes="(max-width: 300px) 100vw, 300px" />
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">  </a>
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                              <div class="details">
                                <div class="content">
                                  <h2 class="woocommerce-loop-product__title">Colombia &#8211; El Planadas</h2>                
                                  <span class="price">
                                    <span class="woocommerce-Price-amount amount">From 
                                      <span class="woocommerce-Price-currencySymbol">&pound;</span>8.95</span> 
                                  </span>
                                </div>
                              </div>
                            </a>
                          </li>
							          <li class="product type-product post-3488 status-publish last instock product_cat-coffee has-post-thumbnail taxable shipping-taxable purchasable product-type-variable">
                          <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                            <img width="300" height="200" src="<?php echo base_url('./assets/images/rwanda-bwenda-441/20201110-IMG_0009-300x200.jpg') ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="Hot Numbers Coffee Breakfast Wine" loading="lazy" srcset="<?php echo base_url('./assets/images/rwanda-bwenda-441/20201110-IMG_0009-300x200.jpg') ?> 300w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20201110-IMG_0009-300x200.jpg') ?> 1024w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20201110-IMG_0009-300x200.jpg') ?> 768w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20201110-IMG_0009-300x200.jpg') ?> 1536w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20201110-IMG_0009-300x200.jpg') ?> 600w, <?php echo base_url('./assets/images/rwanda-bwenda-441/20201110-IMG_0009-300x200.jpg') ?> 2000w" sizes="(max-width: 300px) 100vw, 300px" />
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">  </a>
                              <a href="<?php echo base_url('rwanda_bwenda_441') ?>">
                                <div class="details">
                                  <div class="content">
                                    <h2 class="woocommerce-loop-product__title">Breakfast Wine Blend</h2>                
                                    <span class="price">
                                      <span class="woocommerce-Price-amount amount">From 
                                        <span class="woocommerce-Price-currencySymbol">&pound;</span>9.15</span>  
                                    </span>
                                  </div>
                                </div>
                              </a>
                          </li>
			                </ul>
	                </section>
                  <hr style="padding-bottom: 0;padding-top: 0;margin-top: -0.5em;">
                </div>
            </div>
            <!-- related products end--->


            <div id="popup_coffee_grind" class="popup_container">
                <div class="variation_popup">
                    <div class="header">
                        <h3>Coffee Grind</h3>
                        <p>Choose a grind size to suit your brew method:</p>
                        <a class="pull-right popup_close">
                          <img src="<?php echo base_url('assets/images'); ?>/cross.png" width="30"></a>
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
                        <a class="pull-right popup_close">
                          <img src="<?php echo base_url('assets/images'); ?>/cross.png" width="30"></a>
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


