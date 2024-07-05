<?php
if(!empty($images)) {
    $media = model('Media');
    $images = explode(',',$images);
    ?>
    <section class="no_border_bottom wrapper"<?php echo !empty($padding) ? ' style="padding:'.$padding.'"':'' ?>>
        <div class="<?php echo !empty($container_wrap) && $container_wrap == 'yes' ? 'container':'' ?> widget-carousel">
            <div class="text-center">
                <?php if(!empty($title)) { ?>
                <h2 class="column_title"><?php echo $title ?></h2>
                <?php }
                if(!empty($subtitle)) {
                ?>
                <p class="subtitle"><?php echo $subtitle ?></p>
                    <?php } ?>
            </div>

            <div class="page-image-carousel" data-perview="<?php echo !empty($per_view) ? $per_view : '' ?>">
                <?php
                foreach($images as $image) {
                    $src = $media->get_media_src($image);
                    ?>
                    <div class="carousel-cell" <?php echo !empty($slider_gap) ? 'style="margin-right:'.$slider_gap.'"':'' ?>><img src="<?php echo $src ?>"></div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
<?php }?>