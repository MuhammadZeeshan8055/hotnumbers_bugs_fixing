

<?php echo view( 'includes/header');?>

<style>
#shop .body.woocommerce {
    position: relative;
    padding: 3em 0;
}
    .row>* {
        flex-shrink: 0;
        width: 100%;
        max-width: 100%;
        padding-right: unset;
        padding-left: unset;
         margin-top: unset;
    }
</style>

<!--main body ---->   

<div class="underbanner"  style="background-image:url(<?php echo base_url('./assets/images/shop/banner.jpg') ?>);" ></div>

<!-- wrapper content area --->
<div id="shop" class="woocommerce wrapper content-area">
    <div class="container home-container" role="main">
        <nav class="woocommerce-breadcrumb">
            <a href="<?php echo base_url() ?>">Home</a>&nbsp;&#47;&nbsp;Coffee
        </nav>    

    <!--- shop Container ---->
    <div class="shop_container">
        <section class="category_description">
            <div class="container text-center">
                <p>We want our coffee to be the best it can be. In order to achieve this we sample roast small quantities from a range of green coffee suppliers across the globe. This allows us the pick of the most exciting seasonal coffee available! Simply click on one of our coffees below to find out more.</p>
            </div>
        </section>
        
        <!-- coffee categories body ---> 
        <div class="body woocommerce">
            <ul class="products columns-3">
                <div class="container">
                    <ul class="products column-2">
                        <li class="">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>"><img src="<?php echo base_url('./assets/images/shop_coffee/RFP_HN-2598-1-1024x768.jpg') ?>"> 
                                <span>Colombia - El Diviso</span>  
                            </a>
                        </li>
                        
                        <li class="">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>"><img src="<?php echo base_url('./assets/images/shop_coffee/Bwenda-2.jpg') ?>"> 
                                <span>Rwanda - Bwenda 441</span>  
                            </a>
                        </li>
                                                                
                        <li class="">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>"><img src="<?php echo base_url('./assets/images/shop_coffee/Fredy-Pineda-1024x683.jpg') ?>"> 
                                <span>Honduras - Fredy Pineda</span>  
                            </a>
                        </li>

                        <li class="">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>"><img src="<?php echo base_url('./assets/images/shop_coffee/Roger-Chilcon-1024x683.jpg') ?>"> 
                                <span>Peru - Roger Chilcon</span>  
                            </a>
                        </li>

                        <li class="">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>"><img src="<?php echo base_url('./assets/images/shop_coffee/Rocko-Mountain-1024x768.jpg') ?>"> 
                                <span>Ethiopia - Rocko Mountain: Tariku Lot #1</span>  
                            </a>
                        </li>


                        <li class="">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>"><img src="<?php echo base_url('./assets/images/shop_coffee/20210112-IMG_0044-1024x682.jpg') ?>"> 
                                <span>Coffee Pack (3 x 250g)</span>  
                            </a>
                        </li>

                        <li class="">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>"><img src="<?php echo base_url('./assets/images/shop_coffee/20201009-_DSC1342-1024x683.jpg') ?>"> 
                                <span>Brazil - Fazenda Pantano</span>  
                            </a>
                        </li>


                        <li class="">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>"><img src="<?php echo base_url('./assets/images/shop_coffee/20200422-_DSC0094ElPlanadastrial-1024x683.jpg') ?>"> 
                                <span>Colombia - El Planadas</span>  
                            </a>
                        </li>

                        <li class="">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>"><img src="<?php echo base_url('./assets/images/shop_coffee/20210318-_DSC7531-1024x683.jpg') ?>"> 
                                <span>The Thumper Decaf - Colombia Cafe Del Micay</span>  
                            </a>
                        </li>


                        <li class="">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>#"><img src="<?php echo base_url('./assets/images/shop_coffee/20200701-_DSC52912000-2-1024x682.jpg') ?>"> 
                                <span>Body and Soul Blend</span>  
                            </a>
                        </li>


                        <li class="">
                            <a href="<?php echo base_url('rwanda_bwenda_441') ?>"><img src="<?php echo base_url('./assets/images/shop_coffee/20201110-IMG_0009-1024x682.jpg') ?>"> 
                                <span>Breakfast Wine Blend</span>  
                            </a>
                        </li>
                    </ul>
            </ul>

            <div class="bottom_arrow">
                <img src="<?php echo base_url('./assets/images/shop/icon-coffee-small.png') ?>" width="32"> 
            </div>
         </div>
    </div>
</div>
<!--- end main body --->

<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->

