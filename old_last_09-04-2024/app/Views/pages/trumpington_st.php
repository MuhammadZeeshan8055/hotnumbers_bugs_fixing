<?php echo view("includes/header"); ?>

<!-- section 1 --->
<?php echo view("pages/widgets/banner", [
    "title" => '4 TRUMPINGTON ST, CAMBRIDGE, CB2 1QA',
    "content" => 'OPEN FOR FOOD & DRINKS 7AM - 6PM DAILY. KICHEN 8AM - 3PM. GARDEN OPEN.',
    "images" =>'1666',
    "button_title" => "VIEW MENU",
    "button_link" => site_url()."hotnumbers-menu",
]); ?>
<!-- section 1 end --->


<!--- section 2 --->
<?php echo view("pages/widgets/carousel", ['images'=>'26526,31306,20241,22208,22216,22210,22217,2642,2640,29052,20738,22214,20245,20736','title'=>'']); ?>
<!--- section 2 end --->


<!--- section 3 --->
<section class="margins_top">
    <div class="container">
        <?php echo view("pages/widgets/content", [
            'title'=>'NO. 4 TRUMPINGTON ST. CAMBRIDGE CB2 1QA',
            'subtitle' => 'Open 7am - 5pm daily. Kitchen closes 3pm. No table bookings needed - just turn up!',
            'classes' => 'text-center two_col',
            'textcontent' => 'We condensed all the best bits of Gwydir Street without compromising on coffee, brunch or atmosphere.
Pick up a take-away coffee and stroll down iconic Kings Parade, grab lunch after browsing the Fitzwilliam Museum opposite, or spend some time in the sun trap of our garden hideaway.
With the local student population bringing an academic buzz to the long sharing tables and a steady stream of take-away coffees, changing art on the walls tops up the culture and puts the coffee shop on any Cambridge bucket list.'
        ]); ?>
    </div>
</section>
<!--- section 3 end --->

<div class="margins_top"></div>

<!--- section 4 --->
<section id="testimonial" class=" testimonial  margins_top no_padding "  style="" >
    <div class="container">
        <div class="blockquote-wrapper">
            <div class="blockquote">
                <h3>One of the best cafés in Cambridge! Their brunch is also fantastic - I really recommend this!
                </h3>
                <h4>&mdash;Nattanicha Norén</h4>
            </div>
        </div>
    </div>
</section>
<!--- section 4 end --->

<div class="margins_top"></div>

<!--- section 2 --->
<section class="txt-block no_border_bottom margins_inherit " style="  ">
    <div class="container padding_inherit">
        <div class="text nearly align_center">
            <h2 class="column_title">HOST YOUR EVENT WITH US!</h2>
            <div class="text-block single_col align_center">
                <p>By night both cafes double as a beautiful space in which to host your celebration, professional or social meetup, book-launch, reunion or any other occasion. Both fully licensed venues provide an intimate setting ideal for low-key parties where you will be well looked after by our friendly team. Delicious Middle Eastern Buffet food can also be provided.</p>

            </div>
        </div>
    </div>
</section>
<!--- section 2 end --->

<div class="margins_top"></div>

<?php echo view("pages/widgets/map") ?>

</div>
<!--- main body end---->



<!------------footer ---------------------------------------->

<?php echo view("includes/footer")?>

<!--------------- footer end -------------------------------->

