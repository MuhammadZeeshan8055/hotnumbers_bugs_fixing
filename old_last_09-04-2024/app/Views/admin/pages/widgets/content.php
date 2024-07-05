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
                        <label>Title</label>
                        <input type="text" name="<?php echo $id ?>[title]" value="<?php echo !empty($title) ? $title : '' ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input_field">
                        <label>Subtitle</label>
                        <input type="text" name="<?php echo $id ?>[subtitle]" value="<?php echo !empty($subtitle) ? addslashes($subtitle) : '' ?>">
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
                <div class="col-md-4">
                    <div class="input_field">
                        <label>Container wrap</label>
                        <select name="<?php echo $id ?>[container_wrap]" value="<?php echo !empty($container_wrap) ? $container_wrap : '' ?>">
                            <option value="yes" <?php echo !empty($container_wrap) && $container_wrap==='yes' ? 'checked' : '' ?>>Yes</option>
                            <option value="no" <?php echo !empty($container_wrap) && $container_wrap==='no' ? 'checked' : '' ?>>No</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input_field">
                        <label>Section background color</label>
                        <input type="text" name="<?php echo $id ?>[section_bg_color]" value="<?php echo !empty($section_bg_color) ? $section_bg_color : '' ?>">
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
                        <textarea style="height: 500px" class="editor" data-plugins="autoresize,visualblocks" name="<?php echo $id ?>[textcontent]"><?php echo !empty($textcontent) ? $textcontent : '' ?></textarea>
                    </div>
                </div>
                <input type="hidden" name="<?php echo $id ?>[widget_label]" value="<?php echo !empty($widget_label) ? $widget_label:'{{label}}' ?>">
            </div>
        </div>
    </div>

</fieldset>