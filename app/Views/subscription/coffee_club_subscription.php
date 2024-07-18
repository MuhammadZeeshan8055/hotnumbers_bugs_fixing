<?php echo view('includes/header.php');?>
<!---headder end-------->

<?php
    $attributes = json_decode($coffee_product->attributes);
    $sizes = [];
    $flavours = [];
    $grinds = [];

    foreach($attributes as $attribute) {
        if($attribute->label == "Size") {
            foreach($attribute->value as $p) {
                $variation = $ProductsModel->product_variation($coffee_product->id,['attribute_size'=>$p]);
                if(!empty($variation)) {
                    $variation_price = !empty($variation['values']['sale_price']) ? $variation['values']['sale_price'] : $variation['values']['regular_price'];
                    $sizes[$variation_price] = $p;
                }
            }
        }
        if($attribute->label == "Flavour") {
            $flavours = $attribute->value;
        }
        if($attribute->label == "Grind") {
            $grinds = $attribute->value;
        }
    }

    function grind_info() {
        ?>
        <div>
            <table width="100%">
                <tbody><tr>
                    <td>
                        <p class="text-justify"><strong>Whole Bean</strong> — Beans ready to be ground per cup by you. A grinder is always our recommendation to get the best from each cup. If you haven’t already got a grinder, get one! It is a superb investment to improve your coffee ritual and you will thank us for it!</p>
                    </td>
                    <td width="80" class="text-right">
                        <img src="https://hotnumberscoffee.co.uk/wp-content/themes/workshop/assets/img/coffee-beans.png" width="48">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="text-justify"><strong>French Press/Cafetiere</strong> — Our coarsest grind for a full immersion brew method where the coffee is in solution with the water for the longest time. Probably the most common home brew method we encounter.  Simple yet effective.</p>
                    </td>
                    <td width="80" class="text-right">
                        <img src="https://hotnumberscoffee.co.uk/wp-content/themes/workshop/assets/img/french-press.png" width="40">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="text-justify"><strong>Filter</strong> — Medium grind akin to coarse sand, suitable for paper filter pour over methods where water passes through the coffee bed for between 3-5 minutes.</p>
                    </td>
                    <td width="80" class="text-right">
                        <img src="https://hotnumberscoffee.co.uk/wp-content/themes/workshop/assets/img/filter.png" width="50">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="text-justify"><strong>Aeropress</strong> — A clever brew method invented by Alan Adler, the inventor of the Aerobie. This grind resembles fine sand, ground between filter and Moka Pot settings. This popular brew method gets closer to the espresso experience by allowing extraction from a finer grind using a hand pumped ‘coffee syringe’.</p>
                    </td>
                    <td width="80" class="text-right">
                        <img src="https://hotnumberscoffee.co.uk/wp-content/themes/workshop/assets/img/aeropress_icon_01.jpg" width="40">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="text-justify"><strong>Moka Pot</strong> — A grind between filter and espresso size and suitable for stove top Moka Pot machines. A fine grind is required to extract coffee oils under the flow of hot water for a more intense coffee compared to filter methods.</p>
                    </td>
                    <td width="80" class="text-right">
                        <img src="https://hotnumberscoffee.co.uk/wp-content/themes/workshop/assets/img/moka-pot.png" width="40">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="text-justify"><strong>Espresso</strong> — Fine ground coffee suitable for pump driven coffee machines operating under 9 bar of pump pressure. The coffee needs to be fine in order to extract oils quickly from a large surface area under great pressure. One of the most demanding and intense brew methods but worth the perseverance!</p>
                    </td>
                    <td width="80" class="text-right">
                        <img src="https://hotnumberscoffee.co.uk/wp-content/themes/workshop/assets/img/espresso.png" width="60">
                    </td>
                </tr>
                </tbody></table>
        </div>
        <?php
    }
?>

<div class="underbanner" style="background: url('<?php echo base_url('./assets/images/coffee-club-subscription/banner.jpg') ?>') no-repeat;  "></div>
<div class="woocommerce wrapper content-area">
    <div class="container" role="main">

        <nav class="woocommerce-breadcrumb">
            <a href="<?php echo base_url() ?>">Home</a>
            &nbsp;&#47;&nbsp;
            <a href="#">Coffee Subscriptions</a>
            &nbsp;&#47;&nbsp;
            <span>The Coffee Club1</span>
        </nav>

        <div class="shop_container coffee-club">
            <!--header box -->
            <div class="header">
                <div>
                    <div class="row-fluid">
                        <div class="col-9 col-xs-12 fl" >
                            <h1 style="text-transform: none;" class="f48"><?php echo $coffee_product->title ?></h1>
                            <div>
                               <?php echo $coffee_product->description ?>
                            </div>
                        </div>
                        <div class="col-3 col-xs-hide fl">
                            <div class="round_thumb fr">
                                <div>
                                    <h3>Free</h3>
                                    <h4>Delivery*</h4>
                                    <p>for all our subscribers</p>
                                </div>
                                <p>*Terms & Conditions Apply</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <!--header box end -->

            <?php
            if(!is_logged_in()) {
                ?>
                <article class="col-8 col-sm-12 options">
                    <p><strong>You must be logged in to purchase this product!</strong></p>
                </article>

                <div class="clearfix"></div>
                <?php
            }
            ?>

            <?php
                $form_fields = get_setting('subscriptionForm', true);
            ?>

            <div class="body <?php echo !is_logged_in() ? 'disabled':'' ?>">
                <h4 class="color-red f24">Please select your subscription options:</h4>
                <div class="subscription_form" >
                    <form id="payment_form" method="post" class="validation" autocomplete="off" action="<?php echo site_url('coffee-club-subscription/checkout') ?>">
                       <div class="area subscription-type">
                            <div class="title">1. SUBSCRIPTION TYPE</div>
                            <div class="content">
                                <div class="row-fluid">
                                    <article class="col-12 col-sm-12 flexbox fl options">
                                        <div>
                                            <?php
                                                if(!empty($form_fields['subscription-type'])) {
                                                    $type_key = array_keys($form_fields['subscription-type']);
                                                    foreach($form_fields['subscription-type'] as $value=>$name) {
                                                        $type_key_pos = array_search($value,$type_key);
                                                        ?>
                                                        <div class="input_button">
                                                            <input type="radio" name="subscription-type" value="<?php echo $type_key_pos ?>" required>
                                                            <label><?php echo $name ?></label>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </article>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>

                        <div class="area products">
                            <div class="title">2. BAG SIZE</div>
                            <div class="content">
                                <div class="row">

                                    <article class="col-sm-8 options">
                                        <div id="bagsize_check">
                                            <?php
                                            $size_list = [];
                                            foreach($sizes as $price=>$size) {
                                                $size_list[] = trim($size);
                                                ?>
                                                <div class="fields-wrapper">
                                                    <div class="input_button">
                                                        <input type="checkbox" required="" data-price="<?php echo $price ?>" name="attribute_bag-size[]" class="styled" value="<?php echo $size ?>" placeholder="<?php echo $size ?>">
                                                        <label><?php echo $size ?></label>
                                                        
                                                    </div>
                                                    <input type="number" name="quantity_<?php echo strtolower(str_replace(' ', '_', $size)); ?>" class="quantity-input" value="1" min="1">
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </article>

                                    <article class="col-sm-4 desc">
                                        <div>
                                            <table width="100%">
                                                <tbody>
                                                    <?php if(in_array('250g',$size_list)) { ?>
                                                    <tr>
                                                        <td>
                                                            <p class="text-justify"><strong>250g</strong> — Approximately 15 cups.</p>
                                                        </td>
                                                        <td width="65" class="text-center">
                                                            <img src="https://hotnumberscoffee.co.uk/wp-content/themes/workshop/assets/img/coffee_bag_icon_01.jpg" width="35">
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                    <?php if(in_array('500g',$size_list)) { ?>
                                                    <tr>
                                                        <td>
                                                            <p class="text-justify"><strong>500g</strong> — Approximately 30 cups.</p>
                                                        </td>
                                                        <td width="65" class="text-center">
                                                            <img src="https://hotnumberscoffee.co.uk/wp-content/themes/workshop/assets/img/coffee_bag_icon_01.jpg" width="40">
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                    <?php if(in_array('1kg',$size_list)) { ?>
                                                    <tr>
                                                        <td>
                                                            <p class="text-justify"><strong>1kg</strong> — Approximately 60 cups.</p>
                                                        </td>
                                                        <td width="65" class="text-center">
                                                            <img src="https://hotnumberscoffee.co.uk/wp-content/themes/workshop/assets/img/coffee_bag_icon_01.jpg" width="45">
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </article>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>

                        <div class="area products">
                            <div class="title">3. FLAVOUR</div>
                            <div class="content">
                                <div class="row">
                                    <article class="col-sm-8 options">
                                        <div>
                                            <?php
                                            foreach($flavours as $flavour) {
                                                ?>
                                                <div class="input_button" style="width: 32%;">
                                                    <input type="radio" required="" name="attribute_flavour" class="styled" value="<?php echo $flavour?>">
                                                    <label><?php echo $flavour?></label>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </article>
                                    <article class="col-sm-4 desc">
                                        <div>
                                           <p class="text-justify">
                                               Choose from a classic or fruity flavour profile or, if you cannot decide, then select “roasters choice” and leave it up to us! We will carefully select a roast and take you through an exciting and varied journey of coffee flavours during your subscription with us.
                                           </p>
                                        </div>
                                    </article>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>

                        <div class="area products">
                            <div class="title">5. GRIND</div>
                            <div class="content">
                                <div class="row">
                                    <article class="col-sm-8 options">
                                        <div>
                                            <?php foreach($grinds as $grind) { ?>
                                                <div class="input_button" style="width: 32.8%">
                                                    <input type="radio" required="" name="attribute_grind" class="styled" value="<?php echo $grind ?>">
                                                    <label><?php echo $grind ?></label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </article>
                                    <article class="col-sm-4 desc">
                                        <div>
                                            <?php grind_info() ?>
                                        </div>
                                    </article>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>

                        <div id="subscription-price" class="area products" style="display: none">
                            <div class="title">SUBSCRIPTION PRICE</div>
                            <div class="content">
                                <div class="row">
                                    <article class="col-md-8 col-sm-12 fl options">
                                        <p id="price_html" class="color-red f42"><span class="woocommerce-Price-amount amount"><bdi><?php echo currency_symbol ?><span id="subscription-price-text">0</span> </bdi></span></p>
                                        <p>*Terms &amp; Conditions Apply</p>

                                       <?php if(!is_logged_in()) {
                                           ?>
                                           <p><strong>You must be logged in to purchase this product!</strong></p>
                                           <a class="button" href="<?php echo site_url('account') ?>">SIGN IN</a>
                                            <?php
                                       }else {
                                           ?>
                                           <button style="margin-top: 1em;min-width: 190px;" type="submit" class="button">CONTINUE</button>
                                            <?php
                                       } ?>

                                        <br>
                                    </article>
                                    <article class="col-md-4 col-sm-12 fl desc">
                                        <p><strong>Subscription Price</strong> — This is your specific Coffee Club subscription price, relating to the subscription options that you have selected above.</p>
                                    </article>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>

                        <div class="clear"></div>
                    </form>
                    <div class="clear"></div>
                </div>
            </div>
        </div>

        <script>
            $(function() {

                $('#bagsize_check').find('input').on('change', function() {
                    if($('#bagsize_check').find('input:checked').length) {
                        $('#bagsize_check').find('input').each(function() {
                            $(this).prop('required',false);
                        });
                    }else {
                        $('#bagsize_check').find('input').each(function() {
                            $(this).prop('required',true);
                        });
                    }
                });

                $('#payment_form').find('input').on('change', function() {
                    const form = this.closest('form');
                    if(form.checkValidity()) {
                        let sub_price = 0;
                        document.querySelectorAll('#bagsize_check input:checked').forEach((input)=>{
                            sub_price += parseFloat(input.dataset.price);
                        });
                        document.querySelector('#subscription-price-text').innerHTML = sub_price.toFixed(2);
                        $('#subscription-price').slideDown();
                    }else {
                        $('#subscription-price').slideUp();
                    }
                });
            })
        </script>

        <style>
            .select2-container .select2-selection {
                background-color: #fff;
                border: 1px solid #000;
                border-radius: 2px;
                padding: 5px 5px;
                height: auto;
            }
            .select2-container .select2-selection .select2-selection__rendered {
                color: #000;
            }
            .select2-container .select2-selection .select2-selection__arrow {
                height: 92%;
                width: 25px;
            }
            .select2-container .select2-selection .select2-selection__arrow b {
                color: #000;
            }
            .select2-container .select2-results__option--highlighted.select2-results__option--selectable {
                background-color: #d8262f;
                color: white;

            }

            .select2-container .select2-dropdown {
                border: 1px solid #000;
            }

            .select2-container .select2-results__option--selectable,
            .select2-container--default .select2-results__option--disabled {
                padding: 6px 15px;
                margin-bottom: 4px;
            }

            .select2-search--dropdown {
                padding: 7px 12px;
            }

            #payment_form .select2-container {
                width: 100% !important;
            }

        </style>

    </div>
</div>




<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


