


 <!--header-------------->
 <?php echo view('includes/header.php');?>
    <!---headder end-------->



	<style>
        b {
            font-weight: bold;
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




<!---main body ----->

<div class="underbanner" style="background-image:url(<?php echo base_url('./assets/images/shop/banner.jpg') ?>);"></div>
<div id="shop" class="woocommerce wrapper content-area">
    <div class="container home-container" role="main">
        <nav class="woocommerce-breadcrumb">
            <a href="<?php echo base_url() ?>">Home</a>&nbsp;&#47;&nbsp;Barista Training
        </nav>    
     
        <div class="shop_container">
        <section class="category_description">
            <div class="container text-center">
                <?php echo $barista['description'] ?>
            </div>
        </section>
        
        <div class="body woocommerce">
            <ul class="products columns-3">
                <div class="container">
                    <ul class="products column-2">
                        <?php foreach($products as $product) {
                            $image = $media->get_media_src($product->img,'','medium');
                            ?>
                        <li>
                            <a href="<?php echo base_url('shop/product/'.$product->slug) ?>">
                                <img src="<?php echo $image ?>">
                                <span><?php echo $product->title ?> </span>
                            </a>
                        </li>
                        <?php }?>
                    </ul>
            </ul>
                    
            <div class="bottom_arrow">
                <img src="<?php echo base_url('./assets/images/shop/icon-coffee-small.png') ?>" width="32"> 
            </div>
        </div>
    </div>
</div>
<!---- main body end ----->




<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


