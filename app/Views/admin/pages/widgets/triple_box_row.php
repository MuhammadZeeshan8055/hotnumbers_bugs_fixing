<fieldset>
    <div class="header">
        <div class="field-title"><?php echo !empty($widget_label) ? $widget_label : '{{label}}' ?></div>
        <div class="controls">
            <label>Order: <input type="number" style="width: 50px;text-align: center" class="form-control widget_order" min="0" name="widget_order[]" value="<?php echo isset($idx) ? $idx : '' ?>"></label>
            <a href="" onclick="closeWidget(this);return false;"><i class="lni lni-cross-circle"></i></a>
        </div>
    </div>
    <?php
    $id = !empty($id) ? 'content['.$index.']['.$id.']' : '{{id}}';
    ?>
    <div class="row">

        <div class="col-md-12">
            <div class="product_boxes row">
                <?php for($i = 0; $i<=2; $i++) { ?>
                <div class="col-md-4">
                    <div class="box">

                        <div class="input_field">
                            <label>Title</label>
                            <input name="<?php echo $id ?>[box_title][]" value="<?php echo !empty($box_title[$i]) ? $box_title[$i] : '' ?>">
                        </div>

                        <div class="input_field">
                            <label>Subtitle</label>
                            <textarea name="<?php echo $id ?>[box_subtitle][]"><?php echo !empty($box_subtitle[$i]) ? $box_subtitle[$i] : '' ?></textarea>
                        </div>

                        <div class="input_field">
                            <label>Background image</label>
                            <?php
                            $bg_images = !empty($bg_image[$i]) ? [$bg_image[$i]] : [];

                            $label = 'Add Media';
                            upload_media_box([
                                'buttonText' => '<i class="lni lni-image"></i>&nbsp;&nbsp;'. $label . '</i>',
                                'multiple'=>false,
                                'input_name'=>$id.'[bg_image][]',
                                'images'=>$bg_images
                            ],false);
                            ?>
                        </div>

                        <br>

                        <div class="input_field">
                            <label>Link</label>
                            <input name="<?php echo $id ?>[box_link][]" value="<?php echo !empty($box_link[$i]) ? $box_link[$i] : '' ?>">
                        </div>

                        <div class="input_field">
                            <label>Link text</label>
                            <input name="<?php echo $id ?>[box_link_text][]" value="<?php echo !empty($box_link_text[$i]) ? $box_link_text[$i] : '' ?>">
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>

            <hr style="border-top: 1px solid #ffffff;
    position: relative;
    width: 100%;
    margin-top: 35px;
    margin-bottom: 25px;">


        </div>



        <input type="hidden" name="<?php echo $id ?>[widget_label]" value="<?php echo !empty($widget_label) ? $widget_label:'{{label}}' ?>">
    </div>

</fieldset>