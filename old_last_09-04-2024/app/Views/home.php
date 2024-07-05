<?php use Config\Services;

$session = session(); ?>

    <!--header-------------->
	<?php echo view('includes/header.php');?>
    <!---headder end-------->

<!--------main body-------------------->

<!--section 1 -------------------->
<section class=" heading_box no_margins no_padding high_box home_banner" style="background-color:#0a0000; ">
    <div class="overlay" style="opacity:0.3"></div>
    <div class="bg" style="background: url('<?php echo base_url('./assets/images/home_banner.jpg') ?>') no-repeat;  "></div>
    <div class="container">
    	<div class="heading ">
	  		<h1><span>•</span>Coffee<span>•</span>Music<span>•</span><br/><span>•</span>Conversation<span>•</span> </h1>
        	<h2>OPEN DAILY - GWYDIR ST & THE ROASTERY 7AM-5PM, TRUMPINGTON ST 7AM-6PM. ROASTERY LAST ORDERS 8PM FRI & SAT - PIZZA NIGHTS!!! *NO TABLE BOOKINGS, JUST TURN UP. WELL BEHAVED DOGS WELCOME*</h2>
		</div>

      <div class="has_btn_bottom has_btn2_bottom" style="text-align: center;">
        <a class="button button_lrg  button_bottom" href="<?php echo base_url('shop') ?>"> Shop Now </a>
        <a class="button button_lrg  button_bottom" href="<?php echo base_url('workwithus') ?>"> Work with us! </a>
      </div>
    </div>
</section>
<!--section 1 end -------------->

<?php
    $sectionBlocks = [
            [
                'title'=>'Gwydir St.',
                'link'=>base_url('gwydir-st'),
                'image'=>'gwydir-street.jpg'
            ],
        [
            'title'=>'The Roastery',
            'link'=>base_url('the-roastery'),
            'image'=>'roastery_.jpg'
        ],
        [
            'title'=>'Trumpington St.',
            'link'=>base_url('trumpington-st'),
            'image'=>'20210121-IMG_0142.jpg'
        ],
        [
            'title'=>'Our Menu',
            'link'=>base_url('hotnumbers-menu'),
            'image'=>'20210223-_DSC6275.jpg'
        ],
        [
            'title'=>'About Us',
            'link'=>base_url('about-us-cafe'),
            'image'=>'HotNumbersCoffee_CopyrightRichardFraser-0040.jpg'
        ],
        [
            'title'=>'Wholesale Customers',
            'link'=>base_url('become-a-wholesale-customer'),
            'image'=>'20200520-_DSC3640.jpg'
        ],
        [
            'title'=>'Gigs & Events',
            'link'=>base_url('gigs-events'),
            'image'=>'events.jpg'
        ],
        [
            'title'=>'Barista Training',
            'link'=>base_url('barista-training'),
            'image'=>'Hot-Numbers-Coffee-Body-and-Soul.jpg'
        ],
        [
            'title'=>'Subscriptions',
            'link'=>base_url('coffee-club-subscription'),
            'image'=>'HotNumbersCoffeeRoasters_RichardFraserPhotography-2142-1.jpg'
        ]
    ]
?>

<!--section 2 -------------------->
<section id="flex_box_nav" class="flex_box_nav margins_inherit no_padding home-featured-boxes" style="background-color: #0a0202;">

	<!--first box ---------------------------->
	<div class="nav_box first_box">

        <?php foreach($sectionBlocks as $block) {  ?>
		<article style="background-color: #ededed; background-image:url('<?php echo base_url('./assets/images/'.$block['image']) ?>');">
			<a class="link" href="<?php echo $block['link'] ?>">
				<div class="info">
					<div class="title">
						<h3><?php echo $block['title'] ?></h3>
					</div>
					<div class="read_more">
						<p>Read More</p>
					</div>
				</div>
				<div class="overlay"></div>
			</a>
		</article>
        <?php } ?>

	</div>
	<!--first box end ----------------------------->


</section>
<!--section 2 end -------------->



<!--section 3 start------------------------>
<section class="trio-product margins_top">
    <?php if(!empty($coffee_products)){ ?>
    <div class="container padding_inherit">
		<h2 class="title">OUR COFFEE</h2>
		<p class="subtitle">Carefully selected great tasting coffees from around the world.</p>
		
		
       	<?php
        echo view('includes/product-box-1',['products'=>$coffee_products]);
        ?>
        
        <a class="button" href="<?php echo base_url('shop/category/coffee') ?>">Shop Coffee</a>
    </div>
    <?php } ?>
</section>
<!--section 3 end------------------------>


<!--section 4 start------------------------>
<section id="testimonial" class="  testimonial  margins_inherit padding_inherit "  >
	<div class="container">
		<div class="blockquote-wrapper">
			<div class="blockquote">
				<h3>Through the past years we have built a great supplier - customer relationship. We are proud of working with such passionate people. The coffee is always consistently roasted, delicious and sourced ethically. What a great choice. </h3>
    			<h4>&mdash;Magda, Hermitage Road Coffee Shop.</h4>
			</div>
		</div>
	</div>
</section>
<!--section 4 end------------------------>


<!-------------- break line ------------------->
<div class="line-break inherit">
	<div class="container no_padding no_margins"> <hr> </div>
</div>
<!-------------- break line end ------------------->


<!--- blogs start section 5--------------------------------------->
<style>
    .details p {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 340px;
    }
</style>

<section id="trio-post" class="trio-post margins_inherit padding_inherit " style=" ">
        <div class="container">
            <h2 class="title">BLOG</h2>
			<p class="subtitle">What we're talking about </p>
		
			<div class="flex-wrap">
			<?php
            foreach($blog_posts as $blog_post){
			    $image = $media->get_media_src($blog_post->img);
			    $content = strip_tags($blog_post->content);
                $content = substr($content,0,200);
                $content = strlen($blog_post->content) > 200 ? $content.'...':'';
			    ?>
                <article>
                    <a href="<?php echo base_url('blog/details/'.$blog_post->slug) ?>" title="<?php echo $blog_post->title; ?>">
                        <div class="featured">
                            <img width="300" height="300" src="<?php echo $image ?>" class="attachment-300x300 size-300x300 wp-post-image" alt="" loading="lazy" />
							<div class="overlay"></div>
                        </div>
                    </a>
                    <div class="details">
                        <h3><?php echo $blog_post->title; ?></h3>
                        <p><?php echo $content; ?></p>
                     </div>
                    <div class="layer"></div>
                </article>
			<?php 
            }
            ?>
            </div>
			

            <a class="button" href="<?php echo base_url('blog') ?>"> More from our Blog  </a>
    	</div>
    </section>
<!--- section 5 blogs end--------------------------------------->

<?php

/*$routes = Services::routes();
$url_arr = [];
foreach($routes->getRoutes() as $k=>$v) {
    $url_arr[] = site_url($k);
}
?>
<script>
    let urls = [<?php echo json_encode($url_arr) ?>];
    async function loadFetch(url) {
        await fetch(url).then(response=>response.text()).then((html)=>{
            $(html).find('.body_wrap').children().each(function() {
                if(this.tagName !== "HEADER" || this.tagName !== "SCRIPT") {
                    $('.body_wrap').append(this);
                }
            });
        });
    }

    urls.forEach((url)=>{
        loadFetch(url);
        console.log(url);
    });
</script>
<?php exit;*/


?>

<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->



