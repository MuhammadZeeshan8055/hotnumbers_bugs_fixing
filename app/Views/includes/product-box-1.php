<div class="product-grid-container">
    <?php
    if(!empty($products)) {
        foreach($products as $data) {
            $img = $data->img ? json_decode($data->img,true) : [];
            $image = !empty($img[0]) ? $media->get_media_src($img[0]) : asset('images/placeholder.jpg');
            $variations = $productModel->product_variations($data->id);
            $price = $productModel->product_price($data->id);
            $price_text = is_array($price) ? number_format($price[0],2) : number_format($price,2);
            ?>
            <article>
                <a class="link" href="<?php echo base_url('shop/product/'.$data->slug) ?>" title="<?php echo $data->title; ?>">
                    <div class="featured">
                        <div class="bg"><img width="300" height="200" src="<?php echo $image ?>" class="aligncenter wp-post-image zoom" alt="<?php echo $data->title; ?>" loading="lazy"/></div>
                        <div class="details">
                            <h3><?php echo $data->title; ?></h3>
                            <h4>
                                <span class="woocommerce-Price-amount amount">
                                    <bdi>
                                        <span class="woocommerce-Price-currencySymbol"></span><?php echo _price($price_text) ?>
                                    </bdi>
                                </span>
                            </h4>
                        </div>
                        <div class="layer"></div>
                    </div>
                </a>
            </article>
            <?php
        }
    } ?>
</div>

