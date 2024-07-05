<section class="quote_box <?php echo !empty($classes) ? $classes : '' ?>">
    <div class="container">
        <div>
            <div class="blockquote-wrapper" <?php echo !empty($padding) ? ' style="padding:'.$padding.'"':'' ?>>
                <div class="blockquote">
                    <h3>
                        <?php echo !empty($textcontent) ? $textcontent : '' ?>
                    </h3>
                    <h4><?php echo !empty($quote_author) ? '—'.$quote_author : '' ?></h4>
                </div>
            </div>
        </div>
    </div>
</section>