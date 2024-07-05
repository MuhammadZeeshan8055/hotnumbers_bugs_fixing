<section class="triple_footer_box no_margins no_padding ">

    <?php if(!empty($box_title)) {
        foreach($box_title as $i=>$title) {
            $subtitle = !empty($box_subtitle[$i]) ? nl2br($box_subtitle[$i]) : '';
            $link_text = !empty($box_link_text[$i]) ? ($box_link_text[$i]) : '';
            $link = !empty($box_link[$i]) ? ($box_link[$i]) : '';
            $bgimage = !empty($bg_image[$i]) ? $media->get_media_src($bg_image[$i]) : '';
        ?>
    <a href="<?php echo $link ?>" style="<?php echo $i===0 ? 'background-color: #e8003d;':'background-color: #5e5e5e;' ?> background-image:url('<?php echo $bgimage ?>');">
        <div class="info">
            <div class="title">
                <h3><?php echo $title ?></h3>
                <h4><?php echo $subtitle ?></h4>
            </div>
            <div class="read_more">
                <p><?php echo $link_text ?></p>
            </div>
        </div>

        <?php if($i === 0) { ?>
            <div class="overlay red"></div>
        <?php } ?>
    </a>

    <?php }
    } ?>



</section>