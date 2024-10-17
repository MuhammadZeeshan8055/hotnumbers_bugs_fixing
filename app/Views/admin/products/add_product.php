<style>
    .variable-product-active{
        display:block !important;
    }
</style>
<div class="container ">
    <div class="admin_title_row">
        <?php
        $titlePrefix = 'Add new';
        $btnlabel = 'Add Product';
        $data = [
            'slug'=>'',
            'id'=>'',
            'img'=>'',
            'title'=>'',
            'description'=>'',
            'additional_desc'=>'',
            'attributes'=>'',
            'stock_managed'=>'no',
            'stock'=>'',
            'stock_status'=>'',
            'stock_threshold'=>0,
            'sold_individually'=>0,
            'price'=>'',
            'sale_price'=>'',
            'type'=>'',
            'tax_status'=>'',
            'tax_class'=>'',
            'sku'=>'',
            'address'=>'',
            'status'=>'draft',
            'product_url' => '',
            'button_text' => ''
        ];

        if(!empty($product_row['id'])) {
            $data = $product_row;
            $titlePrefix = 'Edit ';
            $btnlabel = 'Save Changes';
        }

        admin_page_title($titlePrefix.' product');

        if(empty($data['type'])) {
            $data['type'] = 'simple';
        }
    

        ?>
    </div>

    <div class="datatable featured featured_page ">

        <form action="<?php echo base_url(ADMIN . '/products/add') ?><?php echo $data['id'] ? '/' . $data['id'] : '' ?>" method="post" enctype="multipart/form-data">

            <div class="d-flex mb-30" style="gap: 20px">
                <?php if(!empty($data['slug'])) { ?>
                    <a class="btn btn-sm btn-secondary" href="<?php echo site_url() ?>shop/product/<?php echo $data['slug'] ?>" target="_blank">View Product</a>
                <?php } ?>
            </div>

            <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
            <?php if (session('msg')) :
                message_notice(session('msg'));
            endif ?>

            <div class="row">
                <div class="col-md-9">
                    <div class="table-box">
                        <label>Product Data</label>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-md-12 mb-15">
                                        <div class="input_field">
                                            <label>Product title</label>
                                            <input type="text" name="title" data-slug="#product-slug" onblur="checkSlug()" value="<?php echo $data['title'] ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-15">
                                        <div class="input_field">
                                            <label>Product URL</label>
                                            <input id="product-slug" onblur="checkSlug()" type="text" name="product_url" value="<?php echo $data['slug']; ?>" required>
                                        </div>
                                    </div>

                                    <script>
                                        const checkSlug = function() {
                                            let slug = document.getElementById('product-slug').value;
                                            if(slug) {
                                                const url = '<?php echo admin_url() ?>ajax/check-product-slug?slug='+slug;
                                                $.get(url, function(res) {
                                                    const res_json = JSON.parse(res);
                                                    if(res_json.exists) {
                                                        if(slug.slice(-2) !== "-1") {
                                                            slug = slug + '-1';
                                                        }
                                                        document.getElementById('product-slug').value = slug;
                                                    }
                                                });
                                            }
                                        }
                                    </script>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6 mb-15">
                                                <div class="input_field">
                                                    <label>SKU</label>
                                                    <input type="text" name="sku"
                                                           value="<?php echo $data['sku'] ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-15">
                                                <div class="input_field">
                                                    <label>Product Type</label>
                                                    <div>
                                                        <select onchange="product_type_switch(this)" class="select2" data-search="false" name="product_type" value="<?php echo empty($data['type']) ? 'simple':$data['type'] ?>">
                                                            <?php foreach(product_types() as $type=>$label) {
                                                                ?>
                                                                <option value="<?php echo $type ?>"><?php echo $label ?></option>
                                                                <?php
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="regular_price" class="col-md-6 mb-15 simple_product_field" <?php echo $data['type'] == "simple" || $data['type'] == "external" ? '':'hidden' ?>>
                                                <div class="input_field">
                                                    <label>Regular Price (<?php echo currency_symbol?>)</label>
                                                    <input type="number" min="0" step="0.01" name="price" value="<?php echo $data['price'] ?>">
                                                </div>
                                            </div>

                                            <div id="sale_price" class="col-md-6 mb-15 simple_product_field" <?php echo $data['type'] == "simple" || $data['type'] == "external" ? '':'hidden' ?>>
                                                <div class="input_field">
                                                    <label>Sale Price (<?php echo currency_symbol?>)</label>
                                                    <input type="number" min="0" step="0.01" name="sale_price" value="<?php echo $data['sale_price'] ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-15 external_product" <?php echo $data['type'] == "external" ? '':'hidden' ?>>
                                                <div class="input_field">
                                                    <label>External URL</label>
                                                    <input type="text" name="external_url" value="<?php echo @$data['external_url'] ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-15 external_product" <?php echo $data['type'] == "external" ? '':'hidden' ?>>
                                                <div class="input_field">
                                                    <label>Button Text</label>
                                                    <input type="text" name="button_text" placeholder="View Product" value="<?php echo @$data['button_text'] ?>" maxlength="100">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-15 external_product" <?php echo $data['type'] == "external" ? '':'hidden' ?>>
                                                <div class="input_field">
                                                    <label>Override Text</label>
                                                    <input type="text" name="override_text" placeholder="Override Text" value="<?php echo @$data['override_text'] ?>" maxlength="100">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-15" id="tax_status" <?php echo $data['type'] == "external" ? 'hidden' : ''; ?>>
                                                <div class="input_field">
                                                    <label>Tax Status <i class="lni lni-question-circle" data-tooltip title="Define whether or not the entire product is taxable, or just the cost of shipping it."></i> </label>
                                                    <div>
                                                        <select class="select2" data-search="false" name="tax_status" value="<?php echo !empty($data['tax_status']) ? $data['tax_status'] : 'none' ?>">
                                                            <?php foreach(product_tax_statuses() as $type=>$label) {
                                                                ?>
                                                                <option value="<?php echo $type ?>"><?php echo $label ?></option>
                                                                <?php
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-4 mb-15" id="tax_class" <?php echo $data['type'] == "external" ? 'hidden' : ''; ?>>
                                                <div class="input_field">
                                                    <label>Tax Class <?php echo help_text('Choose a tax class for this product. Tax classes are used to apply different tax rates specific to certain types of product.') ?></label>
                                                    <div>
                                                        <select class="select2" data-search="false" name="tax_class" value="<?php echo @$data['tax_class'] ?>">
                                                            <option name="standard_tax_rate">Standard</option>
                                                            <?php
                                                            if(!empty($tax_classes)) {
                                                                foreach(explode("\n",$tax_classes) as $tax_class) {
                                                                    $key = strtolower($tax_class);
                                                                    $key = str_replace(' ','_',$key);
                                                                    $key = trim($key);
                                                                    ?>
                                                                    <option value="<?php echo $key ?>"><?php echo $tax_class ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- <div id="sort_order" class="col-md-4 mb-15 simple_product_field" <?php echo $data['sort_order'];?> <?php echo $data['type'] == "external" ? 'hidden' : ''; ?>>
                                                <div class="input_field">
                                                    <label>Menu Order</label>
                                                    <input type="number" name="sort_order" value="<?php echo $data['sort_order'] ?>">
                                                </div>
                                            </div> -->

                                            <div class="col-md-4 mb-15">
                                                <div class="input_field">
                                                    <label>Xero Code</label>
                                                    <input type="text" name="" value="" placeholder="Xero Code">
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-15" id="">
                                                <div class="input_field">
                                                    <label>Xero Category <i class="lni lni-question-circle" data-tooltip title="Define whether or not the entire product is taxable, or just the cost of shipping it."></i> </label>
                                                    <div>
                                                        <select class="select2"  name="" value="">
                                                            
                                                                <option value=""></option>
                                                               
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>



                                </div>


                            </div>
                            <div class="col-lg-6">
                                <div class="row">

                                    <div class="col-md-12 mb-15 input_field">
                                        <label for="id_label_multiple">Select Category</label>
                                        <div>
                                            <select class="select2 form-control" multiple="multiple" name="category[]">
                                                <option disabled>Select Category:</option>
                                                <?php
                                                if(!empty($categories)) {
                                                    $cate_ids = $prod_cat_ids;
                                                    foreach ($categories as $category) {
                                                        ?>
                                                        <option value="<?php echo $category->id ?>" <?php echo (in_array($category->id, $cate_ids)) ? 'selected' : ''; ?>><?php echo $category->name ?></option>
                                                        <?php
                                                    }
                                                }else {
                                                    ?>
                                                    <option disabled>No category found</option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-15">
                                        <div class="input_field">
                                            <label>Location</label>
                                            <input type="text" name="address"
                                                   value="<?php echo $data['address'] ?>">
                                        </div>
                                    </div>


                                    <div id="stock_management_check" class="col-lg-12 pt-30" style="display:<?php echo $data['type'] == "external" ? 'none':'block' ?>">
                                        <div>
                                            <div class="input_field inline-checkbox mb-15">
                                                <label><input type="checkbox" class="checkbox" name="stock_managed" value="yes" <?php echo !empty($data['stock_managed']) && $data['stock_managed'] === 'yes' ? 'checked':'' ?> onchange="this.checked ? $('.stock_manage_input').prop('disabled',false) : $('.stock_manage_input').prop('disabled',true)">
                                                    Stock Management</label>
                                            </div>
                                        </div>
                                    </div>



                                    <div id="stock_control" class="col-md-12" style="display:<?php echo $data['type'] == "external" ? 'none':'block' ?>">
                                        <div class="row">
                                            <div class="col-md-4 mb-15">
                                                <div class="input_field">
                                                    <label>Stock status</label>
                                                    <div>
                                                        <select class="select2 form-control" name="stock_status_input" value="<?php echo $data['stock_status'] ?>">
                                                            <option value="instock">In Stock</option>
                                                            <option value="outofstock">Out of Stock</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-15">
                                                <div class="input_field">
                                                    <label>Stock quantity</label>
                                                    <input type="number" name="stock" <?php echo empty($data['stock_managed']) || $data['stock_managed'] == 'no' ? 'disabled':'' ?> class="stock_manage_input" min="0" value="<?php echo !empty($data['stock']) ? $data['stock'] : 0 ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-15">
                                                <div class="input_field mb-20">
                                                    <label>Low stock threshold</label>
                                                    <input type="number" name="stock_threshold" <?php echo empty($data['stock_managed']) || $data['stock_managed'] == 'no' ? 'disabled':'' ?> class="stock_manage_input" min="0" value="<?php echo !empty($data['stock_threshold']) ? $data['stock_threshold'] : 0 ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12" <?php echo $data['type'] == "external" ? 'hidden' : ''; ?>>
                                <?php if(get_setting('subscription_enabled')) { ?>
                                    <div title="Enable subscription on this product" class="input_field mb-15 mr-15 inline-checkbox">
                                        <label><input type="checkbox" class="checkbox" name="subscribe" value="1" <?php echo !empty($data['subscription']) && $data['subscription'] ? 'checked':'' ?>>
                                            Enable Subscription</label>
                                    </div>
                                <?php } ?>

                                <div class="input_field mb-15 mr-15 inline-checkbox" <?php echo $data['type'] == "external" ? 'hidden' : ''; ?>>
                                    <label><input type="checkbox" class="checkbox" name="sold_individually" value="1" <?php echo !empty($data['sold_individually']) && $data['sold_individually'] ? 'checked':'' ?>>
                                        Sold Individually <?php echo help_text('Enable this to only allow one of this item to be bought in a single order') ?></label>
                                </div>

                                <div class="input_field mb-15 mr-15 inline-checkbox" <?php echo $data['type'] == "external" ? 'hidden' : ''; ?>>
                                    <label><input type="checkbox" class="checkbox" name="free_shipping" value="1" <?php echo !empty($data['free_shipping']) && $data['free_shipping'] ? 'checked':'' ?>>
                                        Free Shipping <?php echo help_text('Disable shipping charges for this product') ?></label>
                                </div>

                                <div class="input_field mb-15 mr-15 inline-checkbox" <?php echo $data['type'] == "external" ? 'hidden' : ''; ?>>
                                    <label><input type="checkbox" class="checkbox" name="no_shipping" value="1" <?php echo !empty($data['no_shipping']) && $data['no_shipping'] ? 'checked':'' ?>>
                                        No Shipping <?php echo help_text('Disable shipment requirement for this product') ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-15">

                            <div id="add-media-btn-1" hidden>
                                <?php
                                $label =  !empty($media_button_label) ? $media_button_label : 'Add Media';
                                upload_media_box([
                                    'textarea'=>'#media-input',
                                    'buttonText' => '<i class="lni lni-image"></i>&nbsp;&nbsp;Add Media',
                                    'multiple'=>true,
                                    'editor'=>'#editor1'
                                ],false);
                                ?>
                            </div>

                            <div id="add-media-btn-2" hidden>
                                <?php
                                $label =  !empty($media_button_label) ? $media_button_label : 'Add Media';
                                upload_media_box([
                                    'textarea'=>'#media-input',
                                    'buttonText' => '<i class="lni lni-image"></i>&nbsp;&nbsp;Add Media',
                                    'multiple'=>true,
                                    'editor'=>'#editor2'
                                ],false);
                                ?>
                            </div>

                            <div class="input_field">
                                <div class="table-box">
                                    <label>Product Description</label>
                                    <textarea id="editor1" data-media-btn="#add-media-btn-1" name="description"
                                              rows="10" class="editor ckeditor"><?php echo $data['description'] ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-15">
                            <div class="table-box">
                                <label>Additional Description</label>
                                <div class="input_field">
                            <textarea id="editor2" data-media-btn="#add-media-btn-2" name="additional_desc"
                                      rows="10" class="editor ckeditor"><?php echo $data['additional_desc'] ?></textarea>
                                </div>
                            </div>
                        </div>

                        <br>
                        <br>

                        <div class="col-md-12 variable_product_field" <?php echo empty($data['id']) ? 'hidden':'' ?> style="<?php echo !empty($data['id']) && $data['type'] === "variable" ? "display:block":"display:none" ?>">
                            <?php
                                echo view(ADMIN . '/products/tab_att');
                            ?>
                        </div>




                    </div>
                </div>
                <div class="col-md-3 sticky-container">
                    <div class="sticky-element">

                        <div class="table-box product-images">
                            <label>Product images <i class="lni lni-question-circle" data-tooltip="" title="First image is the product thumbnail"></i></label>
                            <div class="input_field mb-20">
                                <div>
                                    <?php
                                    upload_media_box([
                                        'images'=>json_decode($data['img'],true),
                                        'input_name'=>'product_images',
                                        'multiple'=>true,
                                        'replacemedia'=>1,
                                        ''
                                    ]);
                                    ?>
                                </div>
                                <style>
                                    .upload_media_images.multiple .gallery-images > div {
                                        width: 33.33%;
                                    }
                                </style>


                            </div>
                        </div>

                        <div class="table-box">
                            <div class="mb-15">
                                <div class="input_field">
                                    <label>Product Status</label>
                                    <div>
                                        <select class="select2" name="status" value="<?php echo $data['status'] ?>">
                                            <?php
                                            foreach(post_statuses() as $status) {
                                                ?>
                                                <option value="<?php echo $status ?>"><?php echo ucfirst($status) ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm" onclick="preventNavigation = false">&nbsp; <?php echo $btnlabel ?></button>
                        </div>

                        <br>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="cloneProduct()">Clone Product</button>



                    </div>
                </div>


        </form>

        <script>
            function cloneProduct() {
                let form = document.querySelector('form');
                form.action += '?clone=true'; // Add a query parameter to indicate cloning
                form.submit(); // Submit the form
            }
        </script>


        <!-- showing variation options when Product Type is variable -->
        <!-- <script>
            function product_type_switch(selectElement) {
                var selectedValue = selectElement.value;
                var variableProductField = document.querySelector('.variable_product_field');

                if (selectedValue === 'variable') {
                    variableProductField.style.display = 'block';
                } else {
                    variableProductField.style.display = 'none';
                }
            }
        </script> -->


        <script>
            const product_type_switch = (ele)=> {
                $('.simple_product_field,.variable_product_field,.external_product').hide();
                $('#stock_management_check, #stock_control').show();

                if(ele.value === "simple") {
                    $('.simple_product_field').show();
                    $('.input_field.mb-15.mr-15.inline-checkbox').show();
                    $('#tax_status').show();
                    $('#tax_class').show();
                    $('#sort_order').show();
                    // Remove 'variable-product-active' class if it exists
                    $('.variable_product_field').removeClass('variable-product-active');
                }
                if(ele.value === "variable") {
                    $('.simple_product_field').hide();
                    $('.input_field.mb-15.mr-15.inline-checkbox').show();
                    $('#tax_status').show();
                    $('#tax_class').show();
                    // Add a class 'variable-product-active' for variable products
                    $('.variable_product_field').addClass('variable-product-active');
                }
                if(ele.value === "external") {
                    $('.external_product').show();
                    $('.simple_product_field').show();
                    $('#stock_management_check, #stock_control').hide();
                    $('#stock_management_check, #stock_control').hide();
                    $('.input_field.mb-15.mr-15.inline-checkbox').hide();
                    $('#tax_status').hide();
                    $('#tax_class').hide();
                    $('#sort_order').hide();
                    // Remove 'variable-product-active' class if it exists
                    $('.variable_product_field').removeClass('variable-product-active');
                }
            }

            $(function() {
                window.preventNavigation = false;
                $('form').find('input,textarea,select').on('change blur', function() {
                    preventNavigation = true;
                });
                window.onbeforeunload = function() {
                    if(preventNavigation) {
                        return true;
                    }
                };
            })
        </script>


    </div>
</div>
