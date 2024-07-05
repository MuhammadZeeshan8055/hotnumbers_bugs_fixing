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
                        <input type="text" name="<?php echo $id ?>[subtitle]" value="<?php echo !empty($subtitle) ? $subtitle : '' ?>">
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
                        <label>Post type</label>
                        <input type="text" name="<?php echo $id ?>[post_type]" value="<?php echo !empty($post_type) ? $post_type : 'post' ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input_field">
                        <label>Posts per page</label>
                        <input type="text" name="<?php echo $id ?>[posts_per_page]" value="<?php echo !empty($posts_per_page) ? $posts_per_page : 6 ?>">
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
        </div>
    </div>

</fieldset>