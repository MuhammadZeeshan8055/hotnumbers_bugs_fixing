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
            <!-- <iframe src="https://www.youtube.com/embed/<?php echo $code ?>?autoplay=<?php echo $autoplay ?>&cc_load_policy=0&color=white&controls=0&disablekb=0&enablejsapi=0&fs=1&iv_load_policy=3&loop=<?php echo $loop ?>&modestbranding=0&playsinline=1&showinfo=0" allowfullscreen></iframe> -->
                <iframe src="https://www.youtube.com/embed/_r04oq_R1Bo?autoplay=0&amp;cc_load_policy=0&amp;color=white&amp;controls=0&amp;disablekb=0&amp;enablejsapi=0&amp;fs=1&amp;iv_load_policy=3&amp;loop=0&amp;modestbranding=0&amp;playsinline=1&amp;showinfo=0" allowfullscreen=""></iframe>
            
            <?php
        } ?>
            </div>
        </div>
    </div>

</section>