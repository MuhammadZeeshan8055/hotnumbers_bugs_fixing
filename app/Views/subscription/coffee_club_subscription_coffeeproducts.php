<?php echo view('includes/header.php');?>
<!---headder end-------->



<div class="underbanner" style="background: url('<?php echo base_url('./assets/images/coffee-club-subscription/banner.jpg') ?>') no-repeat;  "></div>
<div class="woocommerce wrapper content-area">
    <div class="container" role="main">

        <nav class="woocommerce-breadcrumb">
            <a href="<?php echo base_url() ?>">Home</a>
            &nbsp;&#47;&nbsp;
            <a href="#">Coffee Subscriptions</a>
            &nbsp;&#47;&nbsp;
            <span>The Coffee Club</span>
        </nav>

        <div class="shop_container coffee-club">
            <!--header box -->
            <div class="header">
                <div>
                    <div class="row-fluid">
                        <div class="col-9 col-xs-12 fl" >
                            <h1 style="text-transform: none;" class="f48">The Coffee Club</h1>
                            <div>
                                <h3 class="f24">Welcome to the Hot Numbers coffee club!</h3>
                                <p class="f16">We are excited to introduce you to fresh, ethically sourced coffee roasted with love in small batches at our roastery.</p>
                                <p class="f16">Coffee is seasonal and we are privileged to work directly with a handful of farmers and importers, with high ethical standards at their heart, to bring a menu of no less than six diverse coffees for you to taste from around the world.</p>
                                <p class="f16">We deliver weekly or monthly with free delivery on every order.</p>
                                <p class="f16">It is our job as coffee roasters to bring out the best in every cup of coffee you drink and celebrate the flavours of the world with you. That is what gets us up and out of bed bright and early each and every day.</p>
                                <p class="f16">Enjoy!</p>
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

                        <?php
                        /* <div class="area duration">
                            <div class="title">2. DURATION</div>
                            <div class="content">
                                <div class="row-fluid">
                                    <article class="col-12 col-sm-12 flexbox fl options">
                                        <div>
                                            <?php
                                            if(!empty($form_fields['duration'])) {
                                                $dur_key = array_keys($form_fields['duration']);
                                                foreach($form_fields['duration'] as $value=>$name) {
                                                    $dur_key_pos = array_search($value,$dur_key);
                                                    ?>
                                                    <div class="input_button">
                                                        <input type="radio" name="duration" value="<?php echo $dur_key_pos ?>" required>
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
                        </div>*/ ?>

                        <div class="area products">
                            <div class="title">2. FLAVOUR</div>
                            <div class="content">
                                <div class="row-fluid">
                                    <article class="col-12 col-sm-12 flexbox fl options">
                                        <select id="subscription_coffee" name="subscription_coffee" class="select2 w-100" required>
                                            <option value="" selected disabled>Select a Coffee</option>
                                            <?php
                                                if(!empty($coffees)) {
                                                    foreach($coffees as $coffee) {
                                                        $title = $coffee['title'];
                                                        $pid = $coffee['id'];
                                                        $selected = !empty($_GET['product']) && intval($_GET['product']) == $pid ? 'selected':'';
                                                        ?>
                                                        <option <?php echo $selected ?> value="<?php echo $pid ?>"><?php echo $title ?></option>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </article>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>

                        <div id="coffee_variations">

                        </div>

                        <div id="subscription-price" class="area products" style="display: none">
                            <div class="title">SUBSCRIPTION PRICE</div>
                            <div class="content">
                                <div class="row">
                                    <article class="col-md-8 col-sm-12 fl options">
                                        <p id="price_html" class="color-red f42"><span class="woocommerce-Price-amount amount"><bdi><span id="subscription-price-text"></span> </bdi></span></p>
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

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(function() {
                $('.select2').select2();
                if($('#subscription_coffee').val()) {
                    setTimeout(()=>{
                        $('#subscription_coffee').trigger('change');
                    },500);
                };
                $('#subscription_coffee').on('change', function() {
                    const pid = this.value;
                    const url = 'coffee-club-subscription/coffee-variation-data';
                    const varDiv = document.querySelector('#coffee_variations');
                    varDiv.innerHTML = '';
                    const data = "pid="+pid+"";
                    const Descriptions = {
                        'bag-size||size||weight': `<article class="col-md-4 col-sm-12 fl desc">
                                        <div>
                                              <table width="100%">
                                                        <tbody><tr>
                                                            <td>
                                                                <p class="text-justify"><strong>250g</strong> — Approximately 15 cups.</p>
                                                            </td>
                                                            <td width="65" class="text-center">
                                                                <img src="<?php echo asset('images/icons/coffee_bag_icon_01.jpg') ?>" width="35">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p class="text-justify"><strong>500g</strong> — Approximately 30 cups.</p>
                                                            </td>
                                                            <td width="65" class="text-center">
                                                                <img src="<?php echo asset('images/icons/coffee_bag_icon_01.jpg') ?>" width="40">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p class="text-justify"><strong>1kg</strong> — Approximately 60 cups.</p>
                                                            </td>
                                                            <td width="65" class="text-center">
                                                                <img src="<?php echo asset('images/icons/coffee_bag_icon_01.jpg') ?>" width="45">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p class="text-justify"><strong>3kg</strong> — Approximately 180 cups.</p>
                                                            </td>
                                                            <td width="65" class="text-center">
                                                                <img src="<?php echo asset('images/icons/coffee_bag_icon_01.jpg') ?>" width="50">
                                                            </td>
                                                        </tr>
                                                    </tbody></table>
                                                                                            </div>
                                    </article>`,
                        'flavor': `<article class="col-md-4 col-sm-12 fl desc">
                                        <div>
                                              <p class="text-justify">
                                                 Choose from a classic or fruity flavour profile or, if you cannot decide, then select “roasters choice” and leave it up to us! We will carefully select a roast and take you through an exciting and varied journey of coffee flavours during your subscription with us.
                                               </p>
                                          </div>
                                    </article>`,
                        'grind': `<article class="col-md-4 col-sm-12 fl desc">
                                        <div>
                                                    <table width="100%">
                                                        <tbody><tr>
                                                            <td>
                                                                <p class="text-justify"><strong>Whole Bean</strong> — Beans ready to be ground per cup by you. A grinder is always our recommendation to get the best from each cup. If you haven’t already got a grinder, get one! It is a superb investment to improve your coffee ritual and you will thank us for it!</p>
                                                            </td>
                                                            <td width="80" class="text-center">
                                                                <img src="<?php echo asset('images/icons/coffee-beans.png') ?>" width="48">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p class="text-justify"><strong>French Press/Cafetiere</strong> — Our coarsest grind for a full immersion brew method where the coffee is in solution with the water for the longest time. Probably the most common home brew method we encounter.  Simple yet effective.</p>
                                                            </td>
                                                            <td width="80" class="text-center">
                                                                <img src="<?php echo asset('images/icons/french-press.png') ?>" width="40">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p class="text-justify"><strong>Filter</strong> — Medium grind akin to coarse sand, suitable for paper filter pour over methods where water passes through the coffee bed for between 3-5 minutes.</p>
                                                            </td>
                                                            <td width="80" class="text-center">
                                                                <img src="<?php echo asset('images/icons/filter.png') ?>" width="50">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p class="text-justify"><strong>Aeropress</strong> — A clever brew method invented by Alan Adler, the inventor of the Aerobie. This grind resembles fine sand, ground between filter and Moka Pot settings. This popular brew method gets closer to the espresso experience by allowing extraction from a finer grind using a hand pumped ‘coffee syringe’.</p>
                                                            </td>
                                                            <td width="80" class="text-center">
                                                                <img src="<?php echo asset('images/icons/aeropress_icon_01.jpg') ?>" width="40">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p class="text-justify"><strong>Moka Pot</strong> — A grind between filter and espresso size and suitable for stove top Moka Pot machines. A fine grind is required to extract coffee oils under the flow of hot water for a more intense coffee compared to filter methods.</p>
                                                            </td>
                                                            <td width="80" class="text-center">
                                                                <img src="<?php echo asset('images/icons/moka-pot.png') ?>" width="40">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p class="text-justify"><strong>Espresso</strong> — Fine ground coffee suitable for pump driven coffee machines operating under 9 bar of pump pressure. The coffee needs to be fine in order to extract oils quickly from a large surface area under great pressure. One of the most demanding and intense brew methods but worth the perseverance!</p>
                                                            </td>
                                                            <td width="80" class="text-center">
                                                                <img src="<?php echo asset('images/icons/espresso.png') ?>" width="60">
                                                            </td>
                                                        </tr>

                                                    </tbody></table>
                                                                                            </div>
                                    </article>`
                    };
                    $('#subscription-price').hide();

                    $.post(url, data, function(res) {
                        const results = JSON.parse(res);
                        if(results) {
                            let idx = 4;
                            results.forEach(result=>{
                                const label = result.label;
                                const values = result.value;
                                let label_id = label.toLowerCase();
                                label_id = label_id.replaceAll(' ','-');
                                let options = ``;
                                values.forEach(val=>{
                                    options += `<div class="input_button">
                                                <input type="radio" name="variation[attribute_${label_id}]" value="${val}" required>
                                                <label>${val}</label>
                                            </div>`;
                                });

                               let desc = '';
                               for(const d in Descriptions) {
                                   let dKeys = d.split('||');
                                   if(dKeys.indexOf(label_id) > -1) {
                                       desc = Descriptions[d];
                                   }
                               }
                               const w = desc ? '8':'12';

                                varDiv.innerHTML += `<div class="area">
                                <div class="title">${idx}. ${label}</div>
                                <div class="content">
                                <div class="row-fluid">
                                    <article class="col-md-${w} col-sm-12 flexbox fl options">

                                        <div>${options}</div><br>

                                    </article>
                                    ${desc}
                                    <div class="clear"></div>
                                </div>
                                </div>
                                </div>`;
                                idx++;
                            });
                            $('.select2').select2();
                        }
                    });
                });

                $(document).on('change','#payment_form input', function() {
                    const payment_form = document.getElementById('payment_form');
                    if(payment_form.checkValidity() && $('input[name^=variation]:radio').length) {
                        let variation_arr = [];
                        $('#coffee_variations').find('input:checked').each(function() {
                            let variation = $(this).attr('name');
                            variation = variation.replaceAll('variation[','');
                            variation = variation.replaceAll(']','');
                            variation_arr[''+variation] = this.value;
                        }).promise().done(function() {
                            let url = '';
                            const pid = $('#subscription_coffee').val();
                            for(const v in variation_arr) {
                                url += 'variations[attribute_'+v+']='+variation_arr[v]+'&';
                            }
                            url += 'quantity=1&type=product&product_id='+pid;
                            url = '<?php echo site_url() ?>shop/product/getvariation/?'+url;
                            url = encodeURI(url);

                            $.get(url,function(res) {
                               const result = JSON.parse(res);
                               if(typeof result !== "undefined") {
                                   const price = result.calculated_price_html;
                                   $('#subscription-price-text').html(price);
                               }
                            })

                        });
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


