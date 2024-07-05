<?php
$style = '';
if(!empty($section_bg_color)) {
    $style .= 'background-color:'.$section_bg_color;
}
?>
<section class="widget-content txt-block no_border_bottom <?php echo !empty($classes) ? $classes :'' ?>" <?php echo !empty($style) ? 'style="'.$style.'"' : ''; ?>>
    <div class="text-center <?php echo !empty($container_wrap) && $container_wrap == 'yes' ? 'container':'' ?>">
        <div class="inner">
            <div  <?php echo !empty($padding) ? ' style="padding:'.$padding.'"':'' ?>>
                <?php if(!empty($title)) { ?>
                    <h2 class="column_title"><?php echo $title ?></h2>
                <?php } if(!empty($subtitle)) { ?>
                    <h3 class="column_subtitle"><?php echo $subtitle ?></h3>
                <?php } ?>
            <div class="textcontent text-left">
                <?php echo !empty($textcontent) ? $textcontent :'' ?>
            </div>
            </div>
        </div>
    </div>
</section>