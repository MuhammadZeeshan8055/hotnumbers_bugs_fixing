<?php echo view("includes/header"); ?>

    <!-- section 1 --->
    <?php echo view("pages/widgets/banner", [
        "title" => 'Hot Numbers <br/>Coffee Roasters',
        "content" => 'Coffee | Music | Conversation',
        "images" =>'22210'
    ]); ?>
    <!-- section 1 end --->


    <!--- section 2 --->
    <section class="txt-block no_border_bottom margins_inherit " style="  ">
    	<div class="container padding_inherit">
    		<div class="text nearly align_center">
			    <h2 class="column_title">Calling all Coffee Lovers!</h2>			
                <h3 class="column_subtitle">Why choose speciality coffee...</h3>
			    <div class="text-block single_col align_center">
					<p>Speciality coffee is a movement – a drive for better coffee and a better living for coffee farmers.</p>
                    <p>For coffee to be qualified as speciality, it must be graded between 80-100 points on a 100 point scale. To achieve this, coffee producers painstakingly hand pick and sort fully ripe coffee cherries.</p>
                    <p>With all this extra effort in the supply chain to enhance flavour, it is our responsibility as a speciality roaster to select exciting yet ethical Arabica coffee for your cup. We sample roast weekly at the Hot Numbers Roastery.</p>
                    <p>A great cup of coffee is a simple pleasure, but it is the product of many hands and a lot of passion.</p>
				</div>
			</div>
	    </div>
    </section>
    <!--- section 2 end --->


    <!--- section 3 --->
    <section class="flex-text small_margins " style=" background-color: #efece8; ">
        <div class="container padding_large margins_top">
    	    <h2 class="pre_title">How does our coffee taste?</h2>    	
            <p class="subtitle">Each coffee bean has distinct and varied tasting notes. The flavours that we taste in the roastery are unique to specialty grade coffee.</p>
			<div class="cont">

		        <article class="flex-block">
					<h3>CITRUS & FLORAL</h3>			
                    <p>Sharp citrus and floral flavours can dominate the cup in washed Ethiopian coffees. The best will be incredibly sweet and complex with lime, jasmine and bergamot notes.</p>
		        </article>
     
		        <article class="flex-block">
					<h3>FRUIT</h3>			
                    <p>Fruit flavours are present in a lot of speciality coffee. Usually red ripe fruit dominate the cup, with natural processed coffee being the most intense in flavour. African coffees tend to have this flavour profile.</p>
		        </article>
     
			    <article class="flex-block">
					<h3>CARAMEL & CHOCOLATE</h3>			
                    <p>Usually found more in washed South American coffees that have a slightly darker roast profile.</p>
		        </article>
     	
		        <article class="flex-block">
					<h3>NUT</h3>			
                    <p>This flavour profile tends to be present in Brazilian coffee and continues to be an incredibly popular taste profile.</p>
		        </article>
     	    </div>
        </div>
    </section>
    <!--- section 3 end --->


    <!--- section 4 --->
    <section id="testimonial" class=" testimonial  margins_top no_padding "  style="" >
	  <div class="container">
		<div class="blockquote-wrapper">
			<div class="blockquote">
				<h3>I am still on a journey, one I'll never tire of. Working as a barista, making great drinks, having great discussions as we share a morning coffee ...that's what I live for! </h3>
    			<h4>&mdash;Simon Fraser, Founder Hot Numbers</h4>
			</div>
		</div>
	</div>
    </section>
    <!--- section 4 end --->


    <div class="line-break inherit">
        <div class="container no_padding no_margins">	<hr></div>
    </div>


    <!--- section 5 --->
    <section class="flex-text margins_inherit " style="  ">
        <div class="container padding_inherit">
        	<h2 class="pre_title">The Roasting Process</h2>    	
            <p class="subtitle">When roasting, we want the coffee to be the best it can be. We sample roast small quantities from a range of green coffee suppliers weekly which allows us the pick of the most exciting coffee seasonally available at that time to buy against. We then roast this chosen coffee on our Giesen roasters which form the heart of the Hot Numbers Roastery and chosen as a quality focused workhorse. </p>	
			
            <div class="cont">
				<article class="flex-block">
					<h3>QUALITY & CONSISTENCY</h3>			
                    <p>Our roast-master meticulously tracks temperatures within the roast drum and from this we develop a repeatable profile that lets us know when to apply heat to different stages of the roast over a set time for each coffee, Tasting batches after roast allows us to interpret how this information relates to flavour, ensuring we bring out the best in every coffee we roast, every time. To finish the process we run all roasted coffee through a de-stoner to remove any stones present in imported green coffee. It is then stored in re-usable tubs, or bags with one-way valves to keep your coffee as fresh as it can be.</p>
                    <h3>SEASONAL BLENDS</h3>
                    <p>A good reason to blend coffee is for consistency and balance in the cup. We could for example choose to blend a punchy fruity coffee such as a natural Ethiopian with a smooth chocolatey coffee like a Brazilian to create an exciting yet familiar and accessible flavour profile. We offer a range of blends to suite your tastes.</p>
		        </article>
     	
		        <article class="flex-block">
					<h3>CUSTOM BLENDS</h3>			
                    <p>We understand everyone has their own vision for what a perfect coffee should be. We would love to work with you to create your perfect blend and we can even print your logo on the bag!</p>
                    <h3>SINGLE ORIGINS</h3>
                    <p>Coffee from one particular region/farm is often referred to as a Single Origin (unblended) coffee. We buy this coffee seasonally in both larger quantities and exciting micro-lots to showcase the diversity and flavour that exists from around the world.</p>
                    <p>We serve this ever-changing menu within our cafes as this is what excites us and we are keen to share these offerings with you.</p>
		        </article>
	        </div>
        </div>
    </section>
    <!--- section 5 end --->

    <!--- section 6 --->
    <section id="triple_footer_box" class="triple_footer_box no_margins no_padding ">
		<a href="<?php echo base_url('gwydir-st') ?>" style="background-color: #e8003d; background-image:url('<?php echo base_url('./assets/images/about-us-cafe/gwydir-street.jpg') ?>');">
			<div class="info">
				<div class="title">
					<h3 >Gwydir St.</h3>
					<h4>Coffee Shop</h4>
                    <h5>CB1 2LJ</h5>
				</div>
                <div class="read_more">
					<p>View Cafe</p>  
				</div>        
			</div>
            <div class="overlay red"></div>
		</a>

		<a href="<?php echo base_url('the-roastery') ?>" style="background-color: #555555; background-image:url('<?php echo base_url('./assets/images/about-us-cafe/roastery_.jpg') ?>');">
			<div class="info">
				<div class="title">
					<h3 >The Roastery</h3>
					<h4>Shepreth</h4>
                    <h5>SG8 6RB</h5>
				</div>
				<div class="read_more">
					<p>View Roastery</p>  
				</div>        
			</div>
            <div class="overlay dark"></div>	
		</a>

		<a href="<?php echo base_url('hotnumbers-menu') ?>" style="background-color: #777777; background-image:url('<?php echo base_url('./assets/images/about-us-cafe/background-1.jpg') ?>');">
			<div class="info">
				<div class="title">
					<h3 >The Menu</h3>
				</div>
				<div class="read_more">
					<p>Read More</p>  
				</div>        
			</div>
            <div class="overlay dark"></div>
		</a>
    </section>
    <!--- section 6 end --->
 </div>
<!--- main body end---->



<!------------footer ---------------------------------------->

<?php echo view("includes/footer")?>

<!--------------- footer end -------------------------------->

