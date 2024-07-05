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
                        <input name="<?php echo $id ?>[location_map_title]" value="<?php echo !empty($location_map_subtitle) ? $location_map_subtitle : '' ?>">
                    </div>
                </div>

                <div class="col-md-4">

                    <div class="input_field">
                        <label>Subtitle</label>
                        <input name="<?php echo $id ?>[location_map_subtitle]" value="<?php echo !empty($location_map_subtitle) ? $location_map_subtitle : '' ?>">
                    </div>

                </div>

                <div class="col-md-4">
                    <div class="input_field">
                        <label>Padding</label>
                        <input type="text" name="<?php echo $id ?>[padding]" value="<?php echo !empty($padding) ? $padding : '' ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="input_field">
                        <label>Container wrap</label>
                        <select name="<?php echo $id ?>[location_map_cont_wrap]" value="<?php echo !empty($location_map_cont_wrap) ? $location_map_cont_wrap : '' ?>">
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>
                </div>
            </div>

            <input type="hidden" name="<?php echo $id ?>[widget_label]" value="{{label}}">
        </div>
    </div>
</fieldset>