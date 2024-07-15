<?php  echo view( 'includes/header'); ?>
<!---headder end-------->
<!-- section 1 --->
<section class=" heading_box no_margins no_padding medium_box " style="background-color: #000000;">
    <style>
        .flickity-button:focus {
            box-shadow:unset !important;
        }
    </style>
    <div class="bg" style="background: url('<?php echo base_url('./assets/images/the_roastery/hnroastery.jpg') ?>') no-repeat;"></div>
    <div class="container">
        <div class="heading top-page">
            <h1>Wholesale<br/>Customers</h1>
            <?php
            if(is_wholesaler()){
            ?>
                 
            <?php
            }else{
            ?>
                <h2>Login in to your Wholesale Account</h2>
            <?php
            }
            ?>
           
        </div>
        <?php
        if(is_wholesaler()){
        ?>
            <div class="has_btn_bottom ">
                <a class="button button_lrg button_red button_bottom" href="<?php echo base_url('account') ?>"> View Account </a>
            </div>
        <?php
        }else{
         
        ?>
            <div class="has_btn_bottom ">
                <a class="button button_lrg button_red button_bottom" href="<?php echo base_url('account') ?>"> Login </a>
            </div>
        <?php
        }
        ?>
        
    </div>
</section>
<!-- section 1 end --->
<!-- section 2 --->
<section class="txt-block no_border_bottom no_margins white_out" style=" background-color: #d62135; ">
    <div class="container padding_inherit">
        <div class="text three_quarter align_center">
            <h2 class="column_title">Proud to supply your business with coffee, training, servicing and support</h2>
            <div class="text-block single_col align_center">
                <p><strong><u>WHY CHOOSE US?</u></strong></p>
                <p>Hot Numbers built its business around coffee in 2011 and opened the roastery soon after to control the process from bean to cup so it can taste as splendid as possible. We are excited and proud to offer what we feel is some of the best coffee available in and around Cambridge. We would love to work with your business to support you for a with all things coffee and deliver the best service we can to you.</p>
                <p><strong>&nbsp; </strong></p>
                <p><strong><u>WHAT RANGE OF COFFEE DO YOU OFFER AND CAN I CREATE MY OWN CUSTOM BLEND?</u></strong></p>
                <p>We stock at least six or more single origin coffees from the popular regions of Ethiopia, Brazil, Colombia, Kenya, Guatemala, El Salvador and Sumatra.  We also blend these exciting coffees such as “Breakfast Wine” or “Body and Soul”, each with its unique characteristics. Our decaffeinated coffee is also not to be dismissed and is very popular, using naturally processed methods which retain great taste.</p>
                <p>Why not create your own unique blend? We will happily work with you to develop a great tasting coffee specific to your requirements with your name on! Please contact our team and make an appointment to call into our Roastery in Shepreth to taste anything you may be interested in to help you decide – we are always happy to help discuss your needs.</p>
                <p>&nbsp;</p>
                <p><strong><u>TIME FOR KANDULA TEA?</u></strong></p>
                <p>We recently acquired the wonderful Kandula Tea company and stock a range of delicious teas and infusions fresh from Sri Lanka. Teas from Ceylon whole-leaf to include English Breakfast, Earl Grey and Assam to spiced fruit and fresh peppermint herbal infusions. All teas and infusions have been carefully blended to use only high quality natural ingredients and are ethically traded. Come and enjoy a cup of tea with us and discover the range.</p>
                <p>&nbsp;</p>
                <p><strong><u>HOW DO I BECOME A WHOLESALE CUSTOMER?</u></strong></p>
                <p>Simply spend £100 or more with us to benefit for a 25% discount on your orders with free delivery and 30-day payment terms.</p>
                <p>Send us an email with your requirements, we can have a chat and then send you an invite to test-drive our website as a wholesale customer to see our range and pricing. If you are happy, we will set you up with your own account.</p>
                <p><strong>&nbsp;  </strong></p>
                <p><strong><u>DO YOU DELIVER AND HOW OFTEN?</u></strong></p>
                <p>We deliver daily in the Hot Numbers van and operate a reusable tub collection service around the Cambridge area for zero packaging waste. For customers further afield we will send your coffee in bags using a first class tracked courier service.</p>
                <p><strong> </strong></p>
                <p><strong><u>DO YOU OFFER BARISTA TRAINING?</u></strong></p>
                <p>It is important our coffee is served well, so complimentary coffee training is offered both at our roastery or at your location and is encouraged to all our wholesale customers. If you are not a wholesale customer, we can also provide barista training for your business. We also offer coffee tasting experiences to groups who may be interested.</p>
                <p>&nbsp;</p>
                <p><strong><u>HOW LONG DOES YOUR COFFEE STAY FRESH?</u></strong></p>
                <p>Our coffee is roasted weekly in small batches and packed soon after. Carbon dioxide given off during the roast process replaces the oxygen in the bag which can stale the coffee. This oxygen degasses out of the one-way valve fitted onto every coffee bag. We recommend our coffee is at its optimum within six weeks of roasting when stored correctly and deteriorates slowly after this time.</p>
                <p><strong>&nbsp;  </strong></p>
                <p><strong> <u>DO YOU SUPPLY MACHINES AND INSTALL AND SERVICE THEM?</u></strong></p>
                <p>We stock a variety of espresso machines from La Marzocco, La Spaziale and Conti and grinders from Anfim, Mahlkonig and Compak, both new and reconditioned for sale or lease hire. Contact us with any specific requirements and we would be happy to help.</p>
                <p>&nbsp;</p>
                <p><strong><u>WHAT OTHER BUSINESSES DO YOU WORK WITH?</u></strong></p>
                <p>Our customers range from Independent coffee shops and retail spaces to technology companies and offices. Many have been loyal customers of Hot Numbers for a long time and we love supporting them.</p>
                <p><strong>&nbsp;  </strong></p>
                <p><strong><u>IN WHAT WAYS ARE HOT NUMBERS SOCIALLY RESPONSIBLE?</u></strong></p>
                <p><strong><u>THE ENVIRONMENT</u></strong></p>
                <p>How we can improve our carbon footprint as a business is high priority. We operate reusable tubs for all coffee delivered in our van and use recyclable/compostable packaging for almost all our range of takeaway products across our three sites. Our mountain of coffee grinds are dried and compressed to repurpose into coffee-logs to burn which we sell in-store. We are currently working on a project with Cambridgeshire Environmental Waste to ensure we are separating our waste correctly and efficiently across all sites.</p>
                <p>&nbsp;</p>
                <p><strong><u>ETHICALLY SOURCED COFFEE</u></strong></p>
                <p>Our coffee is purchased in green bean form from our suppliers that base their business on sustainable and ethical values. Coffee we buy is scored on its quality and the farmer is awarded a price consummate with quality, which is significantly higher than the Fair-Trade alternatives. We have a good working relationship with our importers who work hard to improve the lives of the farmers they trade with from housing projects to improved farming and packaging processes. We also buy direct from two farmers in Colombia and El Salvador to go direct to source for a reduced supply chain. We are lucky to work with such passionate people and it is important we preserve these values for all concerned.</p>
                <p>&nbsp;</p>
                <p><strong><u>CONTACT US:</u></strong></p>
                <p>Please contact our team below for questions relating to wholesale coffee and tea, deliveries, training, equipment and support. Just Ask for David, Matt, Sophie or Simon.</p>
                <p>We look forward to hearing from you:</p>
                <p>Hot Numbers Roastery, Shepreth, SG8 6RB</p>
                <p><a href="mailto:roastery@hotnumberscoffee.co.uk">roastery@hotnumberscoffee.co.uk</a></p>
                <p>(01223)612208</p>
            </div>
        </div>
    </div>
</section>
<!-- section 2 end --->
<!-- section 3 --->
<section class="page_title margins_inherit no_padding">
    <div class="container">
        <h2 class="align_center nearly">Why become a Wholesale Customer?</h2>
    </div>
</section>
<!-- section 3 end --->
<!-- section 4 --->
<section class="flex-text margins_inherit " style="  ">
    <div class="container no_padding">
        <div class="cont">
            <article class="flex-block">
                <img src="<?php echo base_url('./assets/images/become-a-wholesale-customer/20200528-_DSC4178.jpg') ?>" alt="" />			<h3>Who we are</h3>			<p>Hot Numbers is an Independent speciality coffee company established in 2011 with three successful coffee shops in Cambridgeshire. We began Roasting our own coffee in 2012 as the next step in our quest to produce the best coffee possible.</p>
                <p>We can now offer a range of single origin speciality Coffees and espresso blends, our high quality organic Kandula Tea as well as technical and training support for you and your team.</p>
            </article>

            <article class="flex-block">
                <img src="<?php echo base_url('./assets/images/become-a-wholesale-customer/HotNumbersCoffeeRoasters_RichardFraserPhotography-2284.jpg') ?>" alt="" />			<h3>Why us?</h3>			<p>We roast ethically sourced, speciality grade coffee (100% Coffea Arabica). The aim with our roast process is to tease out all the flavour that the green bean has to offer, without masking any of those notes with bitter burnt or charred flavours you get from darker roasting.</p>
            </article>

            <article class="flex-block">
                <img src="<?php echo base_url('./assets/images/become-a-wholesale-customer/20200520-_DSC3640.jpg') ?>" alt="" />			<h3>Precision Processing</h3>			<p>We use two state-of-the-art Giesen Coffee Roasters, feeding into our Giesen software. This means we can precisely control every one of the hundreds of variables which affect flavour and always have a record to replicate our results and produce consistent and high quality coffee.</p>
            </article>
        </div>
    </div>
</section>
<!-- section 4 end --->

<!-- section 4 --->
<section class="flickity-carousel margins_inherit">
    <div class="carousel">
        <!-- slider draggable included -->
        <?php echo view('sections/slider_draggable_wholesale_customer.php');?>
    </div>
</section>
<!-- section 4 end --->

<!-- section 5 --->
<section class="flex-text margins_inherit " style="  ">
    <div class="container padding_inherit">
        <div class="cont">
            <article class="flex-block">
                <h3>Machinery, Equipment & Support</h3>
                <p>A well-chosen coffee machine, grinder and barista equipment is key to making a great cup of coffee, alongside quality fresh roasted beans and a well-trained barista that is! The exciting yet challenging part is that all of these elements must be understood and embraced in order to be rewarded with liquid gold.</p>
                <p>We are here to guide you on your coffee journey and impart our knowledge within this exciting and ever growing industry to make this happen. Our wholesale team are here to help you with any coffee related questions you may have and we are fortunate to have a very busy proving ground in our Cambridge cafes with which to put equipment through its paces. We favour machinery and equipment we believe in and which has proven itself. If our Baristas don’t rate it, we don’t sell it!</p>
            </article>

            <article class="flex-block">
                <h3>Training</h3>
                <p>It is very important to us at the roastery that we work closely with you to maintain your coffee interest and knowledge and to form a trusted working relationship with your business over time.</p>
                <p>As a wholesale customer you will benefit from our inclusive barista training courses to get the best from your equipment and to help your staff communicate coffee both confidently and with passion.</p>
                <p>We want you to enjoy your experience with us and to get the best from our coffee in a realistic service setting, so we can come to you or you can visit our roastery to talk machinery or cup coffee with us. Our constant curiosity will serve to keep you current with the latest equipment offerings, industry trends and brewing know-how.</p>
            </article>
        </div>
    </div>
</section>
<!-- section 5 end --->

<div class="line-break inherit">
    <div class="container no_padding no_margins"><hr> </div>
</div>

<!-- section 6 request an account --->
<section id="contact-form" class="txt-block no_border_bottom margins_inherit" style="">
    <div class="container">
        <div class="text three_quarter align_center">
            <?php
            if(is_wholesaler()){
            ?>
                 
            <?php
            }else{
            ?>
                 <h2>Request an account</h2>

                <div class="message d-table" style="width: 100%">
                    <?php echo get_message('register_success') ?>
                </div>
                <br>
                <div class="text-block">
                    <div role="form" lang="en-US" dir="ltr">
                        <div class="screen-reader-response">
                            <p role="status" aria-live="polite" aria-atomic="true"></p> <ul></ul>
                        </div>

                        <form action="<?php echo base_url('wholesale-request') ?>" onsubmit="this.send.disabled=true" method="post" class="validate">

                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <!-- your name --->
                                    <input type="text" name="your_name" required="required" data-error="Your name is required" data-type="name" value="<?php echo old('your_name') ?>" size="40" class="form-control" aria-required="true" aria-invalid="false" placeholder="Full name*" />
                                    <?php echo error_message(@$form_error['your_name']) ?>
                                </div>
                                <div class="col-md-6">
                                    <!-- company name --->
                                    <input type="text" name="company_name" value="<?php echo old('company_name') ?>" size="40" class="form-control" aria-invalid="false" placeholder="Company name" />
                                    <?php echo error_message(@$form_error['company_name']) ?>
                                </div>
                            </div>

                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <!-- your number --->
                                    <input type="text" name="your_number" value="<?php echo old('your_number') ?>" size="40" class="form-control" aria-invalid="false" placeholder="Telephone number*" />
                                    <?php echo error_message(@$form_error['your_number']) ?>
                                </div>
                                <div class="col-md-6">
                                    <!-- email --->
                                    <input type="text" name="your_email" required="required" data-error="Your email address is required" value="<?php echo old('your_email') ?>" size="40" class="form-control" aria-required="true" aria-invalid="false" placeholder="Email*" />
                                    <?php echo error_message(@$form_error['your_email']) ?>
                                </div>
                            </div>

                            <div class="row pt-3">
                                <div class="col-md-12">
                                    <!-- your message -->
                                    <textarea name="your_message" cols="40" rows="10" class="form-control" aria-invalid="false" placeholder="Your message (optional)"><?php echo old('your_message') ?></textarea>
                                    <?php echo error_message(@$form_error['your_message']) ?>
                                </div>
                            </div>

                            <div class="c-1">
                                <!-- submit --->
                                <button type="submit" class="form-control btn btn-primary" name="send" style="font-size: 20px;">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php
            }
            ?>
           
        </div>
    </div>
</section>

<br>
<br>
<!------------footer ---------------------------------------->

<?php echo view( 'includes/footer');?>

<!--------------- footer end -------------------------------->

