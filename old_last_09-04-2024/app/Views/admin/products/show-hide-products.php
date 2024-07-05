<div class="container">
    <div>
        <div class="admin_title_row">
            <?php admin_page_title('Product Visibility'); ?>
        </div>
        <div class="clearfix"></div>

        <form method="post" action="">

            <div class="row">
                <div class="col-md-6">
                    <div class="table-box">
                        <label>Category visibility by user role</label>
                        <?php
                        foreach($user_roles as $role) {
                            $existing_arr = [];
                            $existing_cats = $productModel->user_role_categories($role->id);
                            $existing_mode = '';

                            if(!empty($existing_cats)) {
                                $existing_arr = explode(',',$existing_cats['meta_value']);
                                $existing_mode = $existing_cats['role_category_mode'];
                            }
                            ?>
                            <div class="form-group input_field pb-10">
                                <label><?php echo ucfirst($role->name) ?></label>
                                <div class="row no-gutters">
                                    <div class="col-md-9">
                                        <div>
                                            <select name="user_role_categories[<?php echo $role->id ?>][data][]" class="select2 select" multiple>
                                                <?php foreach($categories as $category) {
                                                    $selected = '';
                                                    if(in_array($category->id,$existing_arr)) {
                                                        $selected = 'selected';
                                                    }
                                                    ?>
                                                    <option <?php echo $selected ?> value="<?php echo $category->id ?>"><?php echo $category->name ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div>
                                            <select name="user_role_categories[<?php echo $role->id ?>][mode]" class="select2" data-search="false" style="width: 100%">
                                                <option value="hide" <?php echo $existing_mode === "hide" ? 'selected':'' ?>>Hide selected</option>
                                                <option value="show" <?php echo $existing_mode === "show" ? 'selected':'' ?>>Show selected</option>
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>


                            </div>
                            <?php
                        }
                        ?>
                        <br>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="table-box">
                        <label>Product visibility by user role</label>
                        <?php
                        foreach($user_roles as $role) {
                            $existing_arr = [];
                            $existing_cats = $productModel->user_role_products($role->id);
                            $existing_mode = '';

                            if(!empty($existing_cats)) {
                                $existing_arr = explode(',',$existing_cats['meta_value']);
                                $existing_mode = $existing_cats['role_products_mode'];
                            }
                            ?>

                            <div class="form-group input_field">
                                <label><?php echo ucfirst($role->name) ?></label>
                                <div class="row no-gutters">
                                    <div class="col-md-9">
                                        <div>
                                            <select name="user_role_products[<?php echo $role->id ?>][data][]" class="select2 select" multiple>
                                                <?php foreach($products as $product) {
                                                    $selected = '';
                                                    if(in_array($product->id,$existing_arr)) {
                                                        $selected = 'selected';
                                                    }
                                                    ?>
                                                    <option <?php echo $selected ?> value="<?php echo $product->id ?>"><?php echo $product->title ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div>
                                            <select name="user_role_products[<?php echo $role->id ?>][mode]" class="select2" data-search="false" style="width: 100%">
                                                <option value="hide" <?php echo $existing_mode === "hide" ? 'selected':'' ?>>Hide selected</option>
                                                <option value="show" <?php echo $existing_mode === "show" ? 'selected':'' ?>>Show selected</option>
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>


                            </div>
                            <?php
                        }
                        ?>
                        <br>
                    </div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary" name="save_changes" value="1" type="submit">Save Changes</button>
                </div>
            </div>
        </form>

    </div>
</div>