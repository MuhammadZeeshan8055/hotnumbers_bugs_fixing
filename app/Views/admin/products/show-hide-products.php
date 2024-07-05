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
            <br>

            <?php
            function user_cat_fields($data=[], $categories=[]) {
                $user_id = !empty($data['user_id']) ? $data['user_id'] : '';
                $cat_ids = !empty($data['category_id']) ? explode(',',$data['category_id']) : [];
                $perm = !empty($data['permission']) ? $data['permission'] : '';
                ?>
                <div class="row">
                    <div class="col-md-4 input_field">
                        <label>User</label>
                        <div>
                            <select name="user_cat_visibility[user_id][<?php echo $user_id ?>]" style="width: 100%" class="form-control w-100 customers_autocomplete">
                                <?php if($user_id) {
                                    ?>
                                <option value="<?php echo $user_id ?>"><?php echo display_name($data['user']) ?></option>
                                <?php
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 input_field">
                        <label>Product categories</label>
                        <div>
                            <select multiple name="user_cat_visibility[category_id][<?php echo $user_id ?>][]" class="form-control select2" style="width: 100%">
                                <option value="">--</option>
                                <?php
                                foreach($categories as $category) {
                                    $selected = in_array($category->id,$cat_ids) ? 'selected':'';
                                    ?>
                                    <option <?php echo $selected ?> value="<?php echo $category->id ?>"><?php echo $category->name ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 input_field">
                        <label>&nbsp;</label>
                        <div>
                            <select name="user_cat_visibility[permission][<?php echo $user_id ?>]" class="form-control select2" style="width: 100%">
                                <option value="allow" <?php echo $perm == 'allow' ? 'selected':'' ?>>Show only selected</option>
                                <option value="disallow" <?php echo $perm == 'disallow' ? 'selected':'' ?>>Hide selected</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <?php if($user_id) { ?>
                        <a type="button" class="btn" style="background-color: transparent" data-confirm="Remove user category visibility?" data-href="<?php echo admin_url() ?>show-hide-products?rm_user_cat=<?php echo $user_id ?>"><i class="lni lni-close color-base"></i></a>
                        <?php } ?>
                    </div>
                </div>
                <?php
            }

            function user_prod_fields($data=[], $products=[]) {
                $user_id = !empty($data['user_id']) ? $data['user_id'] : '';
                $prod_ids = !empty($data['product_id']) ? explode(',',$data['product_id']) : [];
                $perm = !empty($data['permission']) ? $data['permission'] : '';
                ?>
                <div class="row">
                    <div class="col-md-4 input_field">
                        <label>User</label>
                        <div>
                            <select name="user_prod_visibility[user_id][<?php echo $user_id ?>]" style="width: 100%" class="form-control w-100 customers_autocomplete">
                                <?php if($user_id) {
                                    ?>
                                    <option value="<?php echo $user_id ?>"><?php echo display_name($data['user']) ?></option>
                                    <?php
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 input_field">
                        <label>Products</label>
                        <div>
                            <select multiple name="user_prod_visibility[product_id][<?php echo $user_id ?>][]" class="form-control select2" style="width: 100%">
                                <option value="">--</option>
                                <?php foreach($products as $product) {
                                    $selected = in_array($product->id,$prod_ids) ? 'selected':'';
                                    ?>
                                    <option <?php echo $selected ?> value="<?php echo $product->id ?>"><?php echo $product->title ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 input_field">
                        <label>&nbsp;</label>
                        <div>
                            <select name="user_prod_visibility[permission][<?php echo $user_id ?>]" class="form-control select2" style="width: 100%">
                                <option value="allow" <?php echo $perm == 'allow' ? 'selected':'' ?>>Show only selected</option>
                                <option value="disallow" <?php echo $perm == 'disallow' ? 'selected':'' ?>>Hide selected</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <?php if($user_id) { ?>
                            <a type="button" class="btn" style="background-color: transparent" data-confirm="Remove user product visibility?" data-href="<?php echo admin_url() ?>show-hide-products?rm_user_prod=<?php echo $user_id ?>"><i class="lni lni-close color-base"></i></a>
                        <?php } ?>
                    </div>
                </div>
                <?php
            }
            ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="table-box">
                        <label>Category visibility by user</label>
                        <div id="visibility-by-cat">
                            <div>
                                <?php
                                    if(!empty($user_category_permissions)) {
                                        foreach($user_category_permissions as $data) {
                                            $data_value = json_decode($data['value'],true);
                                            $data_value['user'] = $userModel->get_user($data_value['user_id'],'fname,lname,display_name');
                                            user_cat_fields($data_value, $categories);
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <button onclick="add_cat_by_user()" type="button" class="btn btn-secondary mt-20">Add Rule</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="visibility-by-prod" class="table-box">
                        <label>Product visibility by user</label>
                        <div id="visibility-by-product">
                            <div>
                                <?php
                                if(!empty($user_product_permissions)) {
                                    foreach($user_product_permissions as $data) {
                                        $data_value = json_decode($data['value'],true);
                                        $data_value['user'] = $userModel->get_user($data_value['user_id'],'fname,lname,display_name');
                                        user_prod_fields($data_value, $products);
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <button onclick="add_prod_by_user()" type="button" class="btn btn-secondary mt-20">Add Rule</button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-20">
                    <button class="btn btn-primary" name="save_changes" value="1" type="submit">Save Changes</button>
                </div>
            </div>
        </form>

        <template id="user-cat-fields">
            <?php user_cat_fields([],$categories) ?>
        </template>

        <template id="user-prod-fields">
            <?php user_prod_fields([],$products) ?>
        </template>

        <script>
            const add_cat_by_user = ()=>{
                const template = $('#user-cat-fields').contents().clone();
                $('#visibility-by-cat > div').append(template);
                setTimeout(()=>{
                    select2_init();
                    customers_autocomplete();
                },50);
            }

            const add_prod_by_user = ()=>{
                const template = $('#user-prod-fields').contents().clone();
                $('#visibility-by-product > div').append(template);
                setTimeout(()=>{
                    select2_init();
                    customers_autocomplete();
                },50);
            }
        </script>

    </div>
</div>