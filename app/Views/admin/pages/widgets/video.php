<fieldset>
    <div class="header">
        <div class="field-title"><?php echo !empty($widget_label) ? $widget_label : '{{label}}' ?></div>
        <div class="controls">
            <label>Order: <input type="number" style="width: 50px;text-align: center" class="form-control" min="0" name="widget_order[]" class="widget_order" value="<?php echo isset($idx) ? $idx : '' ?>"></label>
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
                        <label>Padding</label>
                        <input type="text" name="<?php echo $id ?>[padding]" value="<?php echo !empty($padding) ? $padding : '' ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="input_field">
                        <label>Classes</label>
                        <input type="text" list="widget_classes" name="<?php echo $id ?>[classes]" value="<?php echo !empty($classes) ? $classes : '' ?>">
                    </div>
                </div>
            </div>

            <br>

            <fieldset style="width: 100%">
                <div class="field-title">Video options</div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="input_field">
                            <label>Youtube Code</label>
                            <input type="text" name="<?php echo $id ?>[youtube_code]" value="<?php echo !empty($youtube_code) ? $youtube_code : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input_field">
                            <label>Autoplay</label>
                            <select class="select2" name="<?php echo $id ?>[autoplay]" value="<?php echo !empty($autoplay) ? $autoplay : '' ?>">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input_field">
                            <label>Loop Video</label>
                            <select class="select2" name="<?php echo $id ?>[loop]" value="<?php echo !empty($loop) ? $loop : '' ?>">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
            </fieldset>
            <br>

            <input type="hidden" name="<?php echo $id ?>[widget_label]" value="value="<?php echo !empty($widget_label) ? $widget_label : '{{label}}' ?>"">

        </div>
    </div>

</fieldset>