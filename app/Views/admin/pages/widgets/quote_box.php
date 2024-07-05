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
            <div class="row">
                <div class="col-md-4">
                    <div class="input_field">
                        <label>Author name</label>
                        <input type="text" name="<?php echo $id ?>[quote_author]" value="<?php echo !empty($quote_author) ? $quote_author : '' ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="input_field">
                        <label>Extra classes</label>
                        <input type="text" name="<?php echo $id ?>[classes]" list="widget_classes" value="<?php echo !empty($classes) ? $classes : '' ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input_field">
                        <label>Padding</label>
                        <input type="text" name="<?php echo $id ?>[padding]" list="widget_paddings" value="<?php echo !empty($padding) ? $padding : '' ?>">
                    </div>
                </div>
            </div>



            <br><br>
            <div class="input_field">
                <div>

                    <div class="text_left" style="display: inline-block">
                        <?php
                        $label =  !empty($media_button_label) ? $media_button_label : 'Add Media';
                        upload_media_box([
                            'textarea'=>!empty($textareaID) ? $textareaID : '#content-editor-'.rand(),
                            'buttonText' => '<i class="lni lni-image"></i>&nbsp;&nbsp;'. $label . '</i>',
                            'multiple'=>true,
                            'input_name'=>'{{id}}[media_files]'
                        ],false);
                        ?>
                    </div>
                    <br>
                    <br>
                    <div id="content-editor">
                        <textarea data-plugins="autoresize,visualblocks" cols="3" name="<?php echo $id ?>[textcontent]"><?php echo !empty($textcontent) ? $textcontent : '' ?></textarea>
                    </div>
                </div>
                <input type="hidden" name="<?php echo $id ?>[widget_label]" value="<?php echo !empty($widget_label) ? $widget_label:'{{label}}' ?>">
            </div>




        </div>
    </div>

</fieldset>