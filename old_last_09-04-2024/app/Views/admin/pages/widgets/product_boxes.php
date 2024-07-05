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
        function product_box_template($id=0,$prod=0) {
            ob_start();
            ?>
            <div class="col-md-4">
                <div class="box">
                    <div class="input_field">
                        <label>Box Product</label>
                        <a href="#" onclick="$(this).closest('.col-md-4').remove(); return false;" class="fr">Remove</a>
                        <?php
                        $products = model('ProductsModel');
                        $products = $products->get_products('id,title');
                        ?>
                        <select name="<?php echo $id ?>[products][]" class="select2" value="<?php echo $prod ?>">
                            <option value="0">Select product</option>
                            <?php foreach($products as $product) {
                                ?>
                                <option value="<?php echo $product->id ?>"><?php echo $product->title ?></option>
                                <?php
                            } ?>
                        </select>
                    </div>
                </div>
            </div>
            <?php
            return ob_get_clean();
        }
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="input_field">
                <label>Title</label>
                <input type="text" name="<?php echo $id ?>[title]" value="<?php echo !empty($title) ? $title : '' ?>">
            </div>
        </div>
        <div class="col-md-12">
            <div class="input_field">
                <label>Subtitle</label>
                <input type="text" name="<?php echo $id ?>[subtitle]" value="<?php echo !empty($subtitle) ? $subtitle : '' ?>">
            </div>
        </div>

        <div class="col-md-12">
            <div class="input_field">
                <label>Button label</label>
                <input type="text" name="<?php echo $id ?>[button_label]" value="<?php echo !empty($button_label) ? $button_label : '' ?>">
            </div>
        </div>

        <div class="col-md-12">
            <div class="input_field">
                <label>Button link</label>
                <input type="text" name="<?php echo $id ?>[button_link]" value="<?php echo !empty($button_link) ? $button_link : '' ?>">
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
                <input type="text" name="<?php echo $id ?>[classes]" value="<?php echo !empty($classes) ? $classes : '' ?>">
            </div>
        </div>

        <hr style="border-top: 1px solid #ffffff;
    position: relative;
    width: 100%;
    margin-top: 35px;
    margin-bottom: 25px;">

        <div class="col-md-12">
            <div class="product_boxes row">
                <?php
                    if(!empty($products)) {
                        foreach($products as $i=>$product) {
                            echo product_box_template($id, $product);
                        }
                    }
                ?>
            </div>

            <hr style="border-top: 1px solid #ffffff;
    position: relative;
    width: 100%;
    margin-top: 35px;
    margin-bottom: 25px;">

           <div class="text-center">
               <button onclick="add_btn_box()" type="button" class="btn back">Add Box</button>
           </div>
        </div>



        <script>
            function add_btn_box() {
                $('.product_boxes').append(`<?php echo product_box_template($id) ?>`);
            }
        </script>


        <input type="hidden" name="<?php echo $id ?>[widget_label]" value="<?php echo !empty($widget_label) ? $widget_label:'{{label}}' ?>">
    </div>

</fieldset>

<script>

</script>