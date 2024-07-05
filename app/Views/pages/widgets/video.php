<section class="txt-block no_border_bottom wrapper <?php echo !empty($classes) ? $classes : '' ?>"<?php echo !empty($padding) ? ' style="padding:'.$padding.'"':'' ?>>

    <div class="container no_padding">
        <div class="video_wrapper full">
            <div class="video_container">
        <?php if(!empty($youtube_code)) {
            $code = strstr($youtube_code,'?v=');
            $code = str_replace('?v=','',$code);
            $autoplay = !empty($autoplay) ? $autoplay : 0;
            $loop = !empty($loop) ? $loop : 0;
            ?>
            <iframe src="https://www.youtube.com/embed/<?php echo $code ?>?autoplay=<?php echo $autoplay ?>&cc_load_policy=0&color=white&controls=0&disablekb=0&enablejsapi=0&fs=1&iv_load_policy=3&loop=<?php echo $loop ?>&modestbranding=0&playsinline=1&showinfo=0" allowfullscreen></iframe>
            <?php
        } ?>
            </div>
        </div>
    </div>

</section>