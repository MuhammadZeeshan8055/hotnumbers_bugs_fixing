<div class="container">
    <?php echo admin_page_title(!empty($categories_row['id']) ? 'Edit Category':'Add Category') ?>
    <div>

        <form
                action="<?php echo base_url(ADMIN . '/product-categories/add') ?><?php echo !empty($categories_row['id']) ? '/' . $categories_row['id'] : '' ?>"
                method="post"
                enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?php echo !empty($categories_row['id']) ? $categories_row['id'] : '' ?>">


            <?php if (session('msg')) :
                message_notice(session('msg'));
            endif;
            ?>

            <div class="no-gutters">

                <div class="table-box">

                    <div class="row">

                        <div class="col-md-12 mt-19">
                            <div style="max-width: 600px">
                                <?php
                                $images = !empty($categories_row['img']) ? array_values(explode(',',$categories_row['img'])) : [];
                                upload_media_box([
                                    'input_name'=>'img',
                                    'replacemedia'=>1,
                                    'images' =>  $images
                                ]);
                                ?>
                            </div>
                            <br>
                            <br>
                        </div>

                        <div class="col-md-6">

                            <div>
                                <div class="input_field mb-10">
                                    <label>Category Name</label>
                                    <input type="text" data-slug="#slug" name="name" value="<?php echo !empty($categories_row['name']) ? $categories_row['name'] : '' ?>" required>
                                </div>
                            </div>
                            <div>
                                <div class="input_field mb-10">
                                    <label>Slug</label>
                                    <input id="slug" type="text" name="slug" value="<?php echo !empty($categories_row['slug']) ? $categories_row['slug'] : '' ?>">
                                </div>
                            </div>

                            <div>
                                <div class="input_field mb-10">
                                    <label>Description</label>
                                    <textarea class="input_field" name="description" style="width: 100%" rows="5"><?php echo !empty($categories_row['description']) ? $categories_row['description'] : '' ?></textarea>
                                </div>

                            </div>

                            <input type="hidden" name="group_name" value="<?php echo !empty($group) ? $group : '' ?>">

                            <?php

                            $roles = shop_roles();
                            $allowed_permissions = [];
                            $deny_permissions = [];
                            if(!empty($categories_row['allow_permission'])) {
                                $allowed_permissions = explode(',',$categories_row['allow_permission']);
                            }
                            if(!empty($categories_row['deny_permission'])) {
                                $deny_permissions = explode(',',$categories_row['deny_permission']);
                            }
                            ?>


                            <div class="input_field mb-15">
                                <label>Show in menu</label>
                                <div>
                                    <select name="show_in_menu" class="select2">
                                        <option value="1" <?php echo (!empty($categories_row['show_in_menu']) && $categories_row['show_in_menu'] == '1') ? 'selected' : '' ?>>
                                            Yes
                                        </option>
                                        <option value="0" <?php echo (isset($categories_row['show_in_menu']) && $categories_row['show_in_menu'] == '0') ? 'selected' : '' ?>>
                                            No
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="input_field mb-15">
                                <label>Status</label>
                                <div>
                                    <select name="status" class="select2">
                                        <option value="1" <?php echo (!empty($categories_row['status']) && $categories_row['status'] == '1') ? 'selected' : '' ?>>
                                            Active
                                        </option>
                                        <option value="0" <?php echo (isset($categories_row['status']) && $categories_row['status'] == '0') ? 'selected' : '' ?>>
                                            Inactive
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
                </div>
        </form>

    </div>
</div>