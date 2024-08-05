<?php echo view("includes/header"); ?>

    <!-- section 1 --->
    <?php echo view("pages/widgets/banner", [
            "title" => 'THE ROASTERY, DUNSBRIDGE TURNPIKE, SHEPRETH, SG8 6RB',
            "content" => 'OPEN FOR FOOD & DRINKS 7AM - 5PM. KITCHEN 8AM-3PM. SOURDOUGH PIZZAS SERVED UNTIL 8PM ON FRIDAYS & SATURDAYS.',
            "images" =>'2573',
            "button_title" => "OUR MENU",
            "button_link" => site_url()."hotnumbers-menu",
    ]); ?>
    <!-- section 1 end --->


    <!--- section 2 --->
    <?php echo view("pages/widgets/carousel", ['images'=>'2644,2643,20241,22208,22216,22210,22217,2642,2640,29052,20738,22214,20245,20736','title'=>'']); ?>
    <!--- section 2 end --->


    <!--- section 3 --->
    <section class="margins_top">
        <div class="container">
            <?php echo view("pages/widgets/content", [
                'title'=>'Shepreth HQ, SG8 6RB',
                'subtitle' => 'Open daily 7am - 5pm',
                'classes' => 'text-center two_col',
                'textcontent' => 'Hot Numbers’ new HQ – The roastery comprises a cafe, bakery and coffee training facility providing the general public with a window into the coffee journey from bean to cup. Our creative brunch menu is served daily by our passionate chefs from an open kitchen with fresh sourdough bread baked on site alongside a delicious selection of cakes. The buzzing interior is paired with a tranquil outdoor seating area, shaded by trees, featuring unique reclaimed furniture.'
            ]); ?>
        </div>
    </section>
    <!--- section 3 end --->

    <section class="margins_top">
        <div class="container">
            <?php
            echo view("pages/widgets/video", [
                'youtube_code'=>'_r04oq_R1Bo'
            ]);
            ?>
        </div>
    </section>


    <!--- section 4 --->
    <section class=" testimonial  margins_top no_padding "  style="" >
	  <div class="container">
            <?php
                echo view("pages/widgets/carousel", [
                        'title' => 'Our Roasting Process',
                        'subtitle' => 'From seed to harvest to processing, Coffee requires a lot of labour and hard-work to produce a high quality, distinct flavour.',
                        'images' => '3116,3117,2345',
                    'per_view' => 1
                ]);
            ?>
	    </div>
    </section>
    <!--- section 4 end --->


    <section class="testimonial margins_top no_padding">
        <div class="container">
            <?php
                echo view("pages/widgets/content", [
                    'title' => '',
                    'subtitle' => '',
                    'classes' => 'three_col',
                    'textcontent' => '<b>We try to respect this effort at the roastery, carefully controlling the many variables so we can share the coffee we’re excited to taste and talk about. <br><br></b>

<p>We roast using a 30Kg Giesen roaster which is linked to the Giesen Roasting programme. This software gives us a lot of feedback, plotting charts for all the variables, such as drum temperature, airflow, flame temperature, speed of the drum etc. This essentially maps out how the batch has been roasted, we taste the results and adjust the course of the roast accordingly until we get the flavour we’re looking for.</p>

<p>With all this useful data we can closely monitor how the beans develop and accurately control the roast, ensuring the same level of quality with each batch. We enjoy exploring single-origin coffees from around the world; each origin will produce a crop with its own unique profile, the result of many different compounds being produced during its life cycle, which are then altered in the roast process to become the aromatics and flavours we enjoy. So, when we roast we’re aiming to develop those flavours just enough to bring out their full potential, without masking them with roasted flavours which occur in darker roasting styles.</p>'
                ]);
            ?>
        </div>
    </section>


    <section class="testimonial margins_top no_padding">
        <div class="container">
            <?php
            echo view("pages/widgets/product_boxes", [
                'title' => '...become a professional barista for the day!',
                'subtitle' => 'Choose below from our coffee curriculum!',
                'classes' => '',
                'products' => [5340],
                'button_label' => 'All barista training',
                'button_link' => site_url().'shop/category/barista-training'
            ]);
            ?>
        </div>
    </section>

    <div class="margins_top"></div>

    <div class="line-break inherit">
        <div class="container no_padding no_margins">	<hr></div>
    </div>

    <section class="testimonial margins_top no_padding">
        <div class="container">
            <?php
            echo view("pages/widgets/content", [
                'title' => 'The Kitchen',
                'subtitle' => '',
                'classes' => 'two_col',
                'textcontent' => 'Well-travelled food worth travelling for.  Carefully sourced, creative and contemporary, our menus feature brunch classics alongside our special take on dishes from around the world, sometimes on the same plate! Early Hot Numbers food offerings had been hearty and comforting but in 2016 we challenged ourselves to bring our food on par with our coffee. With a lot of hard work and chefs who put their heart and soul into each dish we got there. Generous portions, vibrant vegan offerings and gluten free goodness, Hot Numbers is now a food destination. Join us for brunch and taste for yourself.'
            ]);
            ?>
        </div>
    </section>

    <div class="margins_top"></div>
    <div class="line-break inherit">
        <div class="container no_padding no_margins">	<hr></div>
    </div>

    <section class="testimonial margins_top no_padding">
        <div class="container">
            <?php
            echo view("pages/widgets/content", [
                'title' => 'The Bakery',
                'subtitle' => '',
                'classes' => 'three_col',
                'textcontent' => 'As our reputation for brunch grew, we found ourselves getting through more and more bread. We needed focaccia and sourdough seven days a week and knew we wouldn’t keep up baking from the micro bakery we’d established at Gwydir St. So, we decided to invest in the kind of equipment a Parisian boulanger would be proud of, to hire a baker, and buy an alarm clock. We’re very proud indeed of the bread we’ve created, but why just take our word for it?! Sourdough bread and baguettes are available to buy from all 3 locations daily or if you’d like us to take care of your brunch / lunch, all of the bread on our menus is baked by us.'
            ]);
            ?>
        </div>
    </section>
<div class="margins_top"></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="<?php echo site_url() ?>assets/javascript/map-innit.js"></script>

    <?php echo view("pages/widgets/map") ?>

 </div>
<!--- main body end---->



<!------------footer ---------------------------------------->

<?php echo view("includes/footer")?>

<!--------------- footer end -------------------------------->

