<section class="trio-product <?php echo !empty($classes) ? $classes : '' ?>"<?php echo !empty($padding) ? ' style="padding:'.$padding.'"':'' ?>>

    <div class="container no_padding">

        <div>
            <?php if(!empty($title)) { ?>
            <h2 class="title"><?php echo $title ?></h2>
            <?php } ?>
            <?php if(!empty($subtitle)) { ?>
            <p class="subtitle"><?php echo $subtitle ?></p>
            <?php } ?>

            <?php
            if(!empty($products)) {
                $media = model("Media");
                foreach($products as $product) {
                    if(!empty($product)) {
                        $productModel = model('ProductsModel');
                        $getprod = $productModel->product_by_id($product);
                        $img = json_decode($getprod->img,true);
                        $img = $img[0];

                        $image_url = $media->get_media_src($img);
                        ?>

                        <article>
                            <a class="link" href="<?php echo site_url().'shop/product/'.$getprod->slug ?>" title="<?php echo $getprod->title ?>">
                                <div class="featured">
                                    <img src="<?php echo $image_url ?>">
                                    <div class="details">
                                        <h3><?php echo $getprod->title ?></h3>
                                        <h4><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php echo currency_symbol ?></span><?php echo number_format($getprod->price,2) ?></bdi></span></h4>
                                    </div>

                                </div>
                            </a>
                        </article>

                    <?php }
                }
            }?>

            <?php if(!empty($button_label)) {
                ?>
                <a class="button" href="<?php echo $button_link ?>"><?php echo $button_label ?></a>
                <?php
            }?>
            <br>

        </div>

    </div>

</section>