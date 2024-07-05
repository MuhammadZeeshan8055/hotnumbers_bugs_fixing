<?php echo view("includes/header"); ?>

    <!-- section 1 --->
    <?php echo view("pages/widgets/banner", [
            "title" => 'UNITS 5/6 DALE\'S BREWERY, GWYDIR ST, CB1 2LJ',
            "content" => 'OPEN FOR FOOD & DRINKS 7AM - 5PM DAILY. KITCHEN 8AM - 3PM. GARDEN OPEN.',
            "images" =>'1652',
            "button_title" => "VIEW MENU",
            "button_link" => site_url()."hotnumbers-menu",
    ]); ?>
    <!-- section 1 end --->


    <!--- section 2 --->
    <?php echo view("pages/widgets/carousel", ['images'=>'31315,31312,31313,31311,31310','title'=>'']); ?>
    <!--- section 2 end --->


    <!--- section 3 --->
    <section class="margins_top">
        <div class="container">
            <?php echo view("pages/widgets/content", [
                'title'=>'Units 5/6 Dale\'s Brewery CB1 2LJ',
                'subtitle' => 'Open daily 7am - 5pm',
                'classes' => 'text-center two_col',
                'textcontent' => '<p>The original Hot Numbers café serving speciality coffee in a former Victorian brewery named in tribute of a lost record shop off the vibrant Mill Road.</p>
<p>
Communal tables and jazz on heavy rotation provide a laid-back family-friendly atmosphere for peerless coffee, complimented with a creative brunch menu and popular live music events on Sunday afternoons.</p>
<p>
Continuing the legacy of the original building is the gallery, which shows changing exhibitions by local artists. Cosy corners complete the experience providing a refuge for reading or studying, or those leaving and arriving from nearby Cambridge train station.</p>'
            ]); ?>
        </div>
    </section>
    <!--- section 3 end --->


    <!--- section 4 --->
    <section id="testimonial" class=" testimonial  margins_top no_padding "  style="" >
	  <div class="container">
		<?php
            echo view("pages/widgets/quote_box", [
                    'textcontent' => 'Just spent a year and half in Cambridge and your Café is by far the best around. Great coffee, great food and helpful, knowledgeable staff. Keep up the good work!',
                    'quote_author' => 'Eoin Hurst',
                    'classes' => ''
            ]);
        ?>
	    </div>
    </section>
    <!--- section 4 end --->

    <div class="margins_top"></div>

    <div class="line-break inherit">
        <div class="container no_padding no_margins">	<hr></div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="<?php echo site_url() ?>assets/javascript/map-innit.js"></script>

    <?php echo view("pages/widgets/map") ?>

 </div>
<!--- main body end---->



<!------------footer ---------------------------------------->

<?php echo view("includes/footer")?>

<!--------------- footer end -------------------------------->

