<?php echo view("includes/header"); ?>
<style>
    html body .c-1, html body .c-2 {
        float: left;
    }

    html body .c-1 {
        width: 100%;
    }

    html body .wrapper input[type="submit"]:hover, html body .wrapper input[type="submit"]:focus {
        background-color: #030405;
    }
</style>
<div class="underbanner" style="background: url('<?php echo base_url('./assets/images/banner.jpg') ?>');"></div>
<br>
<div class="wrapper">
    <h1 class="pagetitle">Contact Us</h1>
    <!-- section 1 -->
    <section class="flex-text no_margins " style="  ">
        <div class="container no_padding">
            <div class="cont">
                <!-- article 1 --->
                <article class="flex-block">
                    <img src="<?php echo base_url('./assets/images/contact-us/phone-e1562946267439.jpg') ?>" alt=""/>
                    <h3>Call</h3>
                    <p>Accounts // <a href="tel:01223612207">(01223) 612207</a><br/>
                        Wholesale Coffee &amp; Tea // (<a href="tel:01223612208">01223) 612208</a></p>
                    <p>Shepreth Cafe // <a href="tel:01223612209">(01223) 612209</a><br/>
                        Gwydir St Cafe // <a href="tel:01223359966">(01223) 359966</a><br/>
                        Trumpington St Cafe // <a href="tel:01223751266">(01223) 751266</a></p>
                </article>

                <!-- article 2 --->
                <article class="flex-block">
                    <img src="<?php echo base_url('./assets/images/contact-us/email-e1562946283974.jpg') ?>" alt=""/>
                    <h3>Email</h3>
                    <p>General enquiries // <a href="mailto:info@hotnumberscoffee.co.uk">info@hotnumberscoffee.co.uk</a><br/>
                        Coffee &amp; Wholesale enquiries // <a href="mailto:roastery@hotnumberscoffee.co.uk">roastery@hotnumberscoffee.co.uk</a><br/>
                        Live Music enquiries // <a
                                href="mailto:gigs@hotnumberscoffee.co.uk">gigs@hotnumberscoffee.co.uk</a><br/>
                        Food enquiries // <a
                                href="mailto:kitchen@hotnumberscoffee.co.uk">kitchen@hotnumberscoffee.co.uk</a></p>
                </article>

                <!-- article 3 --->
                <article class="flex-block">
                    <img src="<?php echo base_url('./assets/images/contact-us/social-e1562946299301.jpg') ?>" alt=""/>
                    <h3>Social</h3>
                    <p>Facebook // <a
                                href="https://facebook.com/hotnumberscoffee">facebook.com/hotnumberscoffee</a><br/>
                        Twitter // <a href="https://twitter.com/hotnumbers">twitter.com/hotnumbers</a><br/>
                        Instagram // <a href="http://instagram.com/hotnumberscoffee">instagram.com/hotnumberscoffee</a>
                    </p>
                </article>

            </div>
        </div>
    </section>


    <div class="line-break inherit">
        <div class="container no_padding no_margins">
            <hr>
        </div>
    </div>

    <br>

    <!-- section 2 -->
    <section id="contact" class="txt-block no_border_bottom margins_inherit " style="  ">
        <div class="container padding_inherit" id="message">
            <div class="text three_quarter align_center">
                <h2 class="column_title">Drop us a message...</h2>
                <div class="text-block single_col align_center">
                    <div role="form" class="wpcf7" id="wpcf7-f2298-o1" lang="en-US" dir="ltr">
                        <div class="screen-reader-response">
                            <p role="status" aria-live="polite" aria-atomic="true"></p>
                            <ul></ul>
                        </div>

                        <div class="message d-table" style="width: 100%">
                            <?php echo get_message() ?>
                        </div>

                        <br>

                        <form action="" method="post" class="validate">
                            <div></div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row row-fluid">
                                        <div class="col-md-12 pb-15">
                                             <span class="wrap your-name">
                                                <input type="text" name="name" size="40" placeholder="Name*" data-message="Your name is required" value="<?php echo old('name') ?>" required>
                                                 <?php echo error_message(@$form_error['name']) ?>
                                             </span>
                                        </div>
                                        <div class="col-md-12 pb-15">
                                        <span class="wrap company-name">
                                            <input type="text" name="company-name" value="<?php echo old('company-name') ?>" size="40" placeholder="Company name">
                                            <?php echo error_message(@$form_error['company-name']) ?>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row row-fluid">
                                        <div class="col-md-12 pb-15">
                                        <span class="wrap your-number">
                                            <input type="text" name="number" value="<?php echo old('number') ?>" size="40" placeholder="Number">
                                             <?php echo error_message(@$form_error['number']) ?>
                                        </span>
                                        </div>
                                        <div class="col-md-12 pb-15">
                                         <span class="wrap your-email">
                                            <input type="text" name="email" value="<?php echo old('email') ?>" size="40" required placeholder="Email*">
                                              <?php echo error_message(@$form_error['email']) ?>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 pb-10 pt-15">
                                    <textarea name="message" rows="6" placeholder="Your message*"><?php echo old('message') ?></textarea>
                                    <?php echo error_message(@$form_error['message']) ?>
                                </div>
                            </div>


                            <div class="mt-20">
                                <div class="text-center">
                                    <input type="hidden" value="1" name="submit">
                                    <button type="submit" class="button m-auto">Submit</button>
                                </div>
                            </div>



                        </form>

                        <div class="clear clearfix"></div>

                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!------------footer ---------------------------------------->
<?php echo view("includes/footer"); ?>

<!--------------- footer end -------------------------------->

