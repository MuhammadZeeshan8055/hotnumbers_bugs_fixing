<div class="container">
    <div class="datatable featured featured_page ">
        <div class="flex_space">
            <h4 class="title">Shop</h4>
            <a class="btn back" href="#" onclick="history.back()" class="add_banner"><i
                        class="icon-left-small"></i> Back</a>
        </div>
        <form class="mt-30"
              action="<?php echo base_url(ADMIN . '/shop/add') ?><?php echo $product_row['id'] ? '/' . $product_row['id'] : '' ?>"
              method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $product_row['id'] ?>">
            <?php if (session('msg')) :
                message_notice(session('msg'));
                 endif ?>

            <div class="row " style="align-items: flex-end;">
                <div class="col-md-4">
                    <div class="input_field">
                        <div class="upload_img_banner flex-center"
                             style="background-image: url('<?php echo base_url('assets/images/site-images/products') . '/' . $product_row['img'] ?>')">
                            <div class="input_file">
                                <input type="file"
                                       name="img" <?php echo(!empty($product_row['img']) ? '' : 'required') ?> >
                                <i class="icon-up-circled2"></i>
                                <span> Upload image</span>
                                <input type="hidden" name="img"
                                       value="<?php echo $product_row['img'] ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input_field">
                                        <label>Product title</label>
                                        <input type="text" name="title"
                                               value="<?php echo $product_row['title'] ?>" required>
                                    </div>
                                </div>
                                <div class="mb-50 select2  col-md-12">
                                    <label for="id_label_multiple">Select Category</label>
                                    <select id="js-example-basic-hide-search-multi" class="js-example-placeholder-multiple js-states form-control" multiple="multiple" name="category[]">
                                        <option for="" disabled >Select Category:</option>
                                        <?php foreach ($categories  as $category) {
                                            ?>
                                            <optgroup label="<?php echo $category->name ?>">
                                                <?php $child_cate = get_child_category($category->id);

                                                foreach ($child_cate as $child_row){
                                                    $cate_ids = explode(" | ", $product_row['category']);
                                                    ?>
                                                    <option value="<?php echo $child_row->id ?>" <?php echo (in_array($child_row->id,$cate_ids)) ? 'selected' : ''; ?>><?php echo $child_row->name ?></option>
                                                <?php }?>
                                            </optgroup>
                                        <?php } ?>
                                    </select>

                                </div>



                                <div class=" col-lg-12
                                                ">
                                                <div class="input_field">
                                                    <label>Product URL </label>
                                                    <input type="text" name="url"
                                                           value="<?php echo '/shop/details/' . $product_row['slug']; ?>"
                                                           required
                                                           disabled>
                                                </div>
                                    </div>
                                </div>

                            </div>


                        </div>


                    </div>


                    <div class="col-lg-12 mt-20  ckeditor">
                        <div class="input_field ">
                            <label>Product Description</label>
                            <!--                        <div id="editor1"></div>-->
                            <textarea id="editor1" name="description"
                                      rows="10"><?php echo $product_row['description'] ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-12 btn_bar text_right">
                        <button type="submit" class=" btn save">Save changes</button>
                    </div>
        </form>


    </div>
</div>