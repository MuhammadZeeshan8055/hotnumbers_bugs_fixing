

<?php echo view("includes/header")?>

            <!--section 1 --->
			<section class="full_container heading_box no_margins no_padding high_box " style="background-color: #000000;">
                <div class="overlay" style="opacity:0.3"></div>
                <div class="bg" style="background: url('<?php echo base_url('./assets/images/gigs-events/20191128-_DSC5924-scaled.jpg') ?>') no-repeat;"></div>
                <div class="heading top-page">
                    <h1>Gigs & Events</h1>
                </div>
                <div class=" "> </div>       
            </section>
            <!--section 1 end --->


            <!-- toggle GIgs articles -->
            <script>
                function showArtical(){
                  var elms = document.getElementsByClassName("container");
                  Array.from(elms).forEach((x) => {
                    x.style.display = "block";
                  })
                }
            </script>
            

            <!--section 2 --->
            <div>
                <section class="txt-block no_border_bottom margins_inherit gigs_articles">
                    <?php foreach($gigs_posts as $gigs_post){ ?>
                        <div class="container padding_inherit">
                            <div class="text full align_left">
                                <h2 class="column_title"><?php echo $gigs_post->title; ?> </h2>			
                                <h3 class="column_subtitle"><?php echo $gigs_post->date; ?></h3>
                                <div class="text-block single_col align_left">
                                    <p><?php echo $gigs_post->description; ?> </p>
                                    <p><a href="<?php echo $gigs_post->url; ?>"><?php echo $gigs_post->url; ?></a></p>
                                    <p>Location: <?php echo $gigs_post->location; ?><br />Time: <?php echo $gigs_post->time; ?><br />Price: <?php echo $gigs_post->price; ?></p>
                                    <p>Hope to see you there!</p>
                                    <img aria-describedby="caption-attachment-34084" loading="lazy" src="<?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?>" alt="" width="226" height="300" class="size-medium wp-image-34084" srcset="<?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 226w, <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 771w,  <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 768w, <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 1156w, <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 1542w, <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 300w, <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 600w, <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 1882w" sizes="(max-width: 226px) 100vw, 226px" />
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                </section>
            </div>
            <!--section 2 end --->
            
           
            <!--section 3 --->
            <section id="events_section" class="events_section">
                <div class="container">
                    <div class="clear"></div>
			        <a type="button" class="button" id="showMoreArtical" style="width: fit-content;" onclick="showMoreArtical()" title="Load More">LOAD MORE</a>
                </div>
            </section>
            <!--section 3 end --->


            <!--section 4 --->
            <section class="flex-text no_margins " style="  ">
                <div class="container no_padding">
			        <div class="cont">

		                <article class="flex-block">
			                <img src="<?php echo base_url('./assets/images/gigs-events/Trumpet.png') ?>" alt="" />			
                            <h3>Music on Gwydir St.</h3>			
                            <p>All events (unless otherwise stated) take place at our Gwydir St. location just off Mill Road where you’ll find on street parking in adjacent streets and a car park across the road from the shop (free after 5pm)</p>
		                </article>
     
			            <article class="flex-block">
			                <img src="<?php echo base_url('./assets/images/gigs-events/Bass.png') ?>" alt="" />			
                            <h3>Pay what you feel</h3>			
                            <p>We take a collection for the musicians and suggest £10 per head but it’s a pay-what-you-please policy! All proceeds go directly to the musicians. </p>
		                </article>
     	
		                <article class="flex-block">
			                <img src="<?php echo base_url('./assets/images/gigs-events/sax.png') ?>" alt="" />			
                            <h3>Book a gig</h3>			
                            <p>If you’re interested in playing at Hot Numbers we’d love to hear from you! please send recordings of your work/links to your website/soundcloud etc to: <a>gigs@hotnumberscoffee.co.uk</a></p>
		                </article>
     	            </div>
                </div>
            </section>
            <!--section 4 end --->

            <div class="line-break desktop">
                <div class="container no_padding no_margins"><hr></div>
            </div>


            <script>
            showMoreArtical = function() {
                const curr_count = document.querySelector('.gigs_articles').childElementCount;
                const limit = curr_count;
              
                const curr_scroll = $(window).scrollTop();

                $.post('<?php echo site_url() ?>events/ajaxlist',{start:curr_count, limit: limit},(data)=>{
                    data = JSON.parse(data);
                    console.log(data);
                    if(typeof data == "object") {
                        const total_posts = data.total;
                        $('.gigs_articles').find('.new_articles').removeClass('new_articles');
                        let new_html = '';
                        if(typeof data.posts!='undefined'){
                        data.posts.forEach((item)=>{
                            const html = `
                            <div class="container padding_inherit new_articles">
                                <div class="text full align_left">
                                    <h2 class="column_title">${item.title}</h2>			
                                    <h3 class="column_subtitle">${item.date}</h3>
                                    <div class="text-block single_col align_left">
                                        <p>${item.description}</p>
                                        <p><a href="${item.url}">${item.url}</a></p>
                                        <p>Location: ${item.location} <br />Time: ${item.time}<br />Price:${item.price}</p>
                                        <p>Hope to see you there!</p>
                                        <img aria-describedby="caption-attachment-34084" loading="lazy" src="<?php echo base_url('assets/images/site-images/gigs') ?>/${item.img}" alt="" width="226" height="300" class="size-medium wp-image-34084" srcset="<?php echo base_url('assets/images/site-images/gigs') ?>/${item.img} 771w,  <?php echo base_url('assets/images/site-images/gigs') ?>/${item.img} 768w, <?php echo base_url('assets/images/site-images/gigs') ?>/${item.img} 1156w, <?php echo base_url('assets/images/site-images/gigs') ?>/${item.img} 1542w, <?php echo base_url('assets/images/site-images/gigs') ?>/${item.img} 300w, <?php echo base_url('assets/images/site-images/gigs') ?>/${item.img} 600w, <?php echo base_url('assets/images/site-images/gigs') ?>/${item.img} 1882w" sizes="(max-width: 226px) 100vw, 226px" />
                                    </div>
                                </div>
                            </div>
                            `;
                                                        
                            document.querySelector('.gigs_articles').innerHTML += html;
                            new_html += html;
                            
                          });
                        }

                        $('.gigs_articles').find('.new_articles').show();

                        $(document).scrollTop(curr_scroll);
                        setTimeout(()=>{
                            if(document.querySelector('.gigs_articles').childElementCount >= total_posts) {
                                $('#showMoreArtical');
                            }
                        },50);
                    }

                });
            }

            showMoreArtical = ()=> {

            }
        </script>








<!------------footer ---------------------------------------->
<?php echo view("includes/footer")?>
<!--------------- footer end -------------------------------->
