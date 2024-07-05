

<?php echo view("includes/header");

?>


    <div class="underbanner" style="background: url('<?php echo base_url('./assets/images/coffee-club-subscription/banner.jpg') ?>');"></div>
        <div class="wrapper post">
			<div class="container">

                <?php
                $ref = !empty($_SERVER['HTTP_REFERER']) ? explode('/',$_SERVER['HTTP_REFERER']):[];
                $ref_url = end($ref) === 'gigs-events' ? 'gigs-events':'blog';
                $ref_title = end($ref) === 'gigs-events' ? 'Gigs and Events':'Hot News blog';
                ?>
			    <a href="<?php echo base_url()."/".$ref_url ?>">
                    <h3 class="go_back"><< Back to <?php echo $ref_title ?></h3>
                </a>

				<?php
         		 	foreach($blog_post as $blog_posts){
         		 	    $image = $media->get_media_src($blog_posts->img);
         		 	    ?>
          
			    <h1><?php echo $blog_posts->title; ?></h1>
			    <p><em>Written by Admin99, published <?php echo $blog_posts->post_date; ?></em></p>
				<div data-elementor-type="wp-post" data-elementor-id="35434" class="elementor elementor-35434" data-elementor-settings="[]">
					<div class="elementor-section-wrap">
						   
                       <!--section 1 --->
						<section class="elementor-section elementor-top-section elementor-element elementor-element-10084a5 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="10084a5" data-element_type="section">
						    <div class="elementor-container elementor-column-gap-default">
					            <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-f9f7fe5" data-id="f9f7fe5" data-element_type="column">
			                        <div class="elementor-widget-wrap elementor-element-populated">
								        <div class="elementor-element elementor-element-b205799 elementor-widget elementor-widget-image" data-id="b205799" data-element_type="widget" data-widget_type="image.default">
				                            <div class="elementor-widget-container">
												<img src="<?php echo $image?>" title="<?php $blog_posts->img ?>" alt="Live music in Cambridge" /></div>
				                            </div>
				                            <div class="elementor-element elementor-element-f89dc04 elementor-widget-divider--view-line elementor-widget elementor-widget-divider" data-id="f89dc04" data-element_type="widget" data-widget_type="divider.default">
				                                <div class="elementor-widget-container">
					                                <div class="elementor-divider">
			                                            <span class="elementor-divider-separator"> </span>
		                                            </div>
				                                </div>
				                            </div>
					                    </div>
		                            </div>
							    </div>
		                </section>
                        <!--section 1 end--->
					
                        
                        <!--section 2 --->
				        <section class="elementor-section elementor-top-section elementor-element elementor-element-9622d7c elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="9622d7c" data-element_type="section">
						    <div class="elementor-container elementor-column-gap-default">
					            <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-7a478f3" data-id="7a478f3" data-element_type="column">
			                        <div class="elementor-widget-wrap elementor-element-populated">
								        <div class="elementor-element elementor-element-b7cef3d elementor-widget elementor-widget-text-editor" data-id="b7cef3d" data-element_type="widget" data-widget_type="text-editor.default">
				                            <div class="elementor-widget-container">
							                    <div>
													<?php echo $blog_posts->content; ?>
				                                </div>
					                        </div>
		                                </div>
							        </div>
		                </section>
                        <!--section 2 end --->

                        <!--section 3 --->
				        <section class="elementor-section elementor-top-section elementor-element elementor-element-d19f5eb elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="d19f5eb" data-element_type="section">
						    <div class="elementor-container elementor-column-gap-default">
					            <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-090be20" data-id="090be20" data-element_type="column">
			                        <div class="elementor-widget-wrap elementor-element-populated">
								        <div class="elementor-element elementor-element-aba107d elementor-widget elementor-widget-google_maps" data-id="aba107d" data-element_type="widget" data-widget_type="google_maps.default">
				                            <div class="elementor-widget-container">
					                            <div class="elementor-custom-embed">
			                                        <iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
					                                        src="https://maps.google.com/maps?q=hot%20numbers%20coffee%20gwydir%20st&#038;t=m&#038;z=10&#038;output=embed&#038;iwloc=near"
					                                        title="hot numbers coffee gwydir st"
					                                        aria-label="hot numbers coffee gwydir st">
                                                    </iframe>
		                                        </div>
				                            </div>
				                        </div>
					                </div>
		                        </div>
							</div>
		                </section>
                        <!--section 3 end --->



						</div>
					</div>
					
					<?php } ?>



                    <!-- previous button -->
			        <div class="nav-posts">
						<div id="prev">
		 					<a href="<?php echo base_url('blog') ?>" rel="prev">Previous</a>						
                        </div>					
					</div>

		        </div>
            </div>



            <!------------footer ---------------------------------------->
<?php echo view("includes/footer");  ?>
            <!--------------- footer end -------------------------------->





