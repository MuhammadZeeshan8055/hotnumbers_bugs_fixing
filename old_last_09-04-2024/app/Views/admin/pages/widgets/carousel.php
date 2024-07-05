<fieldset>
    <div class="header">
        <div class="field-title"><?php echo !empty($widget_label) ? $widget_label : '{{label}}' ?></div>
        <div class="controls">
            <label>Order: <input type="number" style="width: 50px;text-align: center" class="form-control widget_order" min="0" name="widget_order[]"value="<?php echo isset($idx) ? $idx : '' ?>"></label>
            <a href="" onclick="closeWidget(this);return false;"><i class="lni lni-cross-circle"></i></a>
        </div>
    </div>
    <?php
        $id = !empty($id) ? 'content['.$index.']['.$id.']' : '{{id}}';
    ?>
    <div class="input_field">
        <label>Title</label>
        <input type="text" name="<?php echo $id ?>[title]" value="<?php echo !empty($title) ? $title : '' ?>">
    </div>

    <div class="input_field">
        <label>Subtitle</label>
        <input type="text" name="<?php echo $id ?>[subtitle]" value="<?php echo !empty($subtitle) ? $subtitle : '' ?>">
    </div>

    <div class="row">
        <div class="col-3">
            <div class="input_field">
                <label>Per view</label>
                <input type="number" name="<?php echo $id ?>[per_view]" value="<?php echo !empty($per_view) ? $per_view : '' ?>">
            </div>
        </div>
        <div class="col-3">
            <div class="input_field">
                <label>Arrows</label>
                <select name="<?php echo $id ?>[arrows]" value="<?php echo !empty($arrows) ? $arrows : '' ?>">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
        </div>
        <div class="col-3">
            <div class="input_field">
                <label>Gap</label>
                <input type="text" name="<?php echo $id ?>[slider_gap]" value="<?php echo !empty($slider_gap) ? $slider_gap : '' ?>">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input_field">
                <label>Padding</label>
                <input type="text" name="<?php echo $id ?>[padding]" value="<?php echo !empty($padding) ? $padding : '' ?>">
            </div>
        </div>
        <div class="col-3">
            <div class="input_field">
                <label>Container wrap</label>
                <select name="<?php echo $id ?>[container_wrap]" value="<?php echo !empty($container_wrap) ? $container_wrap : '' ?>">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
        </div>
    </div>

    <br>
    <br>

    <div class="image-carousel-3">
        <?php
        $images = !empty($images) ? array_values(explode(',',$images)) : [];
        upload_media_box([
            'input_name'=>$id.'[images]',
            'images'=>$images,
            'multiple'=>true,
        ],false);
        ?>
    </div>
    <input type="hidden" name="<?php echo $id ?>[widget_label]" value="<?php echo !empty($widget_label) ? $widget_label:'{{label}}' ?>">
    <br>
</fieldset>