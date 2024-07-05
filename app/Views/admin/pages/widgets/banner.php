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
        <div class="col-md-5">
            <div class="input_field">
                <label>Images</label>
                <?php
                    $images = !empty($images) ? array_values(explode(',',$images)) : [];
                    upload_media_box([
                        'input_name'=>$id.'[images]',
                        'replacemedia'=>1,
                        'images' =>  $images
                    ]);
                ?>
            </div>
            <br>
        </div>

        <div class="col-md-12">
            <div class="input_field">
                <label>Title</label>
                <input name="<?php echo $id ?>[title]" value="<?php echo !empty($title) ? $title : '' ?>">
            </div>

            <div class="input_field">
                <label>Content</label>
                <div>
                    <textarea name="<?php echo $id ?>[content]" rows="4"><?php echo !empty($content) ? $content : '' ?></textarea>
                </div>
            </div>

           <div class="row">
               <div class="col-md-4">
                   <div class="input_field">
                       <label>Padding</label>
                       <input type="text" name="<?php echo $id ?>[padding]" value="<?php echo !empty($padding) ? $padding : '' ?>">
                   </div>
               </div>

               <div class="col-md-4">
                   <div class="input_field">
                       <label>Button title</label>
                       <input name="<?php echo $id ?>[button_title]" value="<?php echo !empty($button_title) ? $button_title : '' ?>">
                   </div>
               </div>

               <div class="col-md-4">
                   <div class="input_field">
                       <label>Button link</label>
                       <input name="<?php echo $id ?>[button_link]" value="<?php echo !empty($button_link) ? $button_link : '' ?>">
                   </div>
               </div>
           </div>

            <input type="hidden" name="<?php echo $id ?>[widget_label]" value="{{label}}">
        </div>
    </div>
</fieldset>