<section class="heading_box medium_box banner_box"<?php echo !empty($padding) ? ' style="padding:'.$padding.'"':'' ?>>
    <div class="overlay" style="opacity:0.3"></div>
    <?php
    if(!empty($images)) {
        $media = model('Media');
        $images = explode(',',$images);
        $image_url = $media->get_media_src($images[0]);
        ?>
        <div class="bg" style="background: url('<?php echo $image_url ?>') no-repeat;"></div>
    <?php } ?>

    <div class="container">
        <div class="heading ">
            <h1><?php echo $title ?></h1>

        <div class="banner_content">
            <?php echo !empty($content) ? html_entity_decode($content) : '' ?>
        </div>

            <div class="buttons">
                <?php
                if(!empty($button_title) && !empty($button_link)) {
                    ?>
                    <div class="has_btn_bottom ">
                        <a class="button button_lrg button_red button_bottom" href="<?php echo $button_link ?>">
                            <?php echo $button_title ?></a>
                    </div>
                    <?php
                }

                if(!empty($buttons)) {
                    foreach ($buttons as $button) {
                        ?>
                        <div class="has_btn_bottom">
                            <a class="button button_lrg button_red button_bottom <?php echo !empty($button['classes']) ? $button['classes'] : '' ?>" href="<?php echo $button['link'] ?>">
                                <?php echo $button['label'] ?></a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>