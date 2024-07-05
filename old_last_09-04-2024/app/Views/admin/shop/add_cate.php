<div class="container">
    <div class="datatable featured featured_page ">
        <div class="flex_space">
            <h4>Shop Categories</h4>
            <a class="btn back" href="#" onclick="history.back()" class="add_banner"><i
                        class="icon-left-small"></i> Back</a>
        </div>
        <form class="mt-60"
              action="<?php echo base_url(ADMIN . '/shop/add_cate') ?><?php echo $categories_row['id'] ? '/' . $categories_row['id'] : '' ?>"
              method="post"
              enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?php echo $categories_row['id'] ?>">


            <?php if (session('msg')) :
                message_notice(session('msg'));
            endif ?>

            <div class="no-gutters">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input_field">
                            <div class="upload_img_banner flex-center"
                                 style="background-image: url('<?php echo base_url('assets/images/site-images/categories') . '/' . $categories_row['img'] ?>')">
                                <div class="input_file">
                                    <input type="file"
                                           name="img" <?php echo(!empty($categories_row['img']) ? '' : 'required') ?> >
                                    <i class="icon-up-circled2"></i>
                                    <span> Upload image</span>
                                    <input type="hidden" name="img"
                                           value="<?php echo $categories_row['img'] ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-15">
                        <div class=" col-md-12">
                            <div class="input_field mb-50">
                                <label>Category Name</label>
                                <input type="text" name="name"
                                       value="<?php echo $categories_row['name'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input_field mb-50">
                                <label>Slug</label>
                                <input type="text"
                                       value="<?php echo $categories_row['slug'] ?>" disabled>
                            </div>

                        </div>
                        <div class=" col-md-12">
                            <div class="input_field mb-50">
                                <label>Parent</label>
                                <select name="parent">
                                    <option value="0" selected>Select Parent</option>
                                    <option value="0">none</option>
                                    <?php

                                    foreach ($parent_categories as $p_categories) {

                                        if ($categories_row['id'] != $p_categories->id) {
                                            ?>
                                            <option value="<?php echo $p_categories->id ?>" <?php echo ($categories_row['parent'] == $p_categories->id) ? 'selected' : '' ?>><?php echo $p_categories->name ?></option>
                                        <?php }
                                    }
                                    ?>

                                </select>
                            </div>

                        </div>
                        <div class=" col-md-12">
                            <div class="input_field mb-50">
                                <label>Status</label>
                                <select name="status">
                                    <option value="0"
                                            selected <?php echo ($categories_row['status'] == '0') ? 'selected' : '' ?>>
                                        No
                                    </option>
                                    <option value="1" <?php echo ($categories_row['status'] == '1') ? 'selected' : '' ?>>
                                        Yes
                                    </option>
                                </select>
                            </div>

                        </div>

                    </div>


                </div>
                <div class="row">
                    <div class="col-lg-12 btn_bar flex_space">
                        <button type="submit" class=" btn save">Save changes</button>
                    </div>
                </div>


        </form>

    </div>
</div>