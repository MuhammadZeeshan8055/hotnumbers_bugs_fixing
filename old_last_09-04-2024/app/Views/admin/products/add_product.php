<div class="container">
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
            'status'=>'draft'
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
                <button type="submit" class=" btn save btn-sm"><?php echo $btnlabel ?></button>
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
                                            <input type="text" name="title" data-slug="#product-slug" value="<?php echo $data['title'] ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-15">
                                        <div class="input_field">
                                            <label>Product URL</label>
                                            <input id="product-slug" type="text" name="product_url"
                                                   value="<?php echo $data['slug']; ?>" required>
                                        </div>
                                    </div>

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

                                            <div id="regular_price" class="col-md-6 mb-15 simple_product_field" <?php echo $data['type'] == "simple" ? '':'hidden' ?>>
                                                <div class="input_field">
                                                    <label>Regular Price (<?php echo currency_symbol?>)</label>
                                                    <input type="number" step="0.01" name="price" value="<?php echo $data['price'] ?>">
                                                </div>
                                            </div>

                                            <div id="sale_price" class="col-md-6 mb-15 simple_product_field" <?php echo $data['type'] == "simple" ? '':'hidden' ?>>
                                                <div class="input_field">
                                                    <label>Sale Price (<?php echo currency_symbol?>)</label>
                                                    <input type="number" step="0.01" name="sale_price" value="<?php echo $data['sale_price'] ?>">
                                                </div>
                                            </div>

                                            <?php if(get_setting('enable_tax_rates') && get_setting('price_with_tax') === "inclusive") { ?>
                                                <div class="col-md-6 mb-15">
                                                    <div class="input_field">
                                                        <label>Tax Status <i class="lni lni-question-circle" data-tooltip title="Define whether or not the entire product is taxable, or just the cost of shipping it."></i> </label>
                                                        <div>
                                                            <select class="select2" data-search="false" name="tax_status" value="<?php echo $data['tax_status'] ?>">
                                                                <?php foreach(product_tax_statuses() as $type=>$label) {
                                                                    ?>
                                                                    <option value="<?php echo $type ?>"><?php echo $label ?></option>
                                                                    <?php
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-6 mb-15">
                                                    <div class="input_field">
                                                        <label>Tax Class <?php echo help_text('Choose a tax class for this product. Tax classes are used to apply different tax rates specific to certain types of product.') ?></label>
                                                        <div>
                                                            <select class="select2" data-search="false" name="tax_class" value="<?php echo @$data['tax_class'] ?>">
                                                                <option name="standard">Standard</option>
                                                                <?php
                                                                if(!empty($tax_classes)) {
                                                                    foreach($tax_classes as $key=>$tax_class) {
                                                                        ?>
                                                                        <option value="<?php echo $key ?>"><?php echo $tax_class['label'] ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                            <?php } ?>

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

                                    <div class="col-lg-4 mb-15">
                                        <div class="input_field">
                                            <label>Status</label>
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

                                    <div class="col-lg-12 pt-30">
                                        <div>
                                            <div class="input_field inline-checkbox mb-15">
                                                <label><input type="checkbox" class="checkbox" name="stock_managed" value="yes" <?php echo !empty($data['stock_managed']) && $data['stock_managed'] === 'yes' ? 'checked':'' ?> onchange="this.checked ? $('.stock_manage_input').prop('disabled',false) : $('.stock_manage_input').prop('disabled',true)">
                                                    Stock Management</label>
                                            </div>

                                            &nbsp;
                                            &nbsp;
                                            &nbsp;

                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4 mb-15">
                                                <div class="input_field">
                                                    <label>Stock quantity</label>
                                                    <input type="number" name="stock" <?php echo empty($data['stock_managed']) ? 'disabled':'' ?> class="stock_manage_input" value="<?php echo $data['stock'] ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-15">
                                                <div class="input_field mb-20">
                                                    <label>Low stock threshold</label>
                                                    <input type="number" name="stock_threshold" <?php echo empty($data['stock_managed']) ? 'disabled':'' ?> class="stock_manage_input" value="<?php echo !empty($data['stock_threshold']) ? $data['stock_threshold'] : 0 ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <?php if(get_setting('subscription_enabled')) { ?>
                                    <div title="Enable subscription on this product" class="input_field mb-15 mr-15 inline-checkbox">
                                        <label><input type="checkbox" class="checkbox" name="subscribe" value="1" <?php echo !empty($data['subscription']) && $data['subscription'] ? 'checked':'' ?>>
                                            Enable Subscription</label>
                                    </div>
                                <?php } ?>

                                <div class="input_field mb-15 mr-15 inline-checkbox">
                                    <label><input type="checkbox" class="checkbox" name="sold_individually" value="1" <?php echo !empty($data['sold_individually']) && $data['sold_individually'] ? 'checked':'' ?>>
                                        Sold Individually <?php echo help_text('Enable this to only allow one of this item to be bought in a single order') ?></label>
                                </div>

                                <div class="input_field mb-15 mr-15 inline-checkbox">
                                    <label><input type="checkbox" class="checkbox" name="free_shipping" value="1" <?php echo !empty($data['free_shipping']) && $data['free_shipping'] ? 'checked':'' ?>>
                                        Free Shipping <?php echo help_text('Disable shipping charges for this product') ?></label>
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

                        <div class="col-md-12 variable_product_field" <?php echo empty($data['id']) ? 'hidden':'' ?>>
                            <?php
                            echo view(ADMIN . '/products/tab_att'); ?>
                        </div>




                    </div>
                </div>
                <div class="col-md-3 sticky-container">
                    <div class="sticky-element">

                        <div class="table-box">
                            <label>Product images</label>
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
                            <div class="col-md-12" style="display: inline-block">
                                <button type="submit" class=" btn save btn-sm"><?php echo $btnlabel ?></button>
                            </div>
                        </div>
                    </div>
                </div>


        </form>



        <script>
            const product_type_switch = (ele)=> {
                if(ele.value === "simple") {
                    $('.simple_product_field').show();
                    $('.variable_product_field').hide();
                }
                if(ele.value === "variable") {
                    $('.simple_product_field').hide();
                    $('.variable_product_field').show();
                }
            }
        </script>


    </div>
</div>
