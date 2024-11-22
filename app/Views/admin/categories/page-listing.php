<div class="container">
    <?php admin_page_title(current_page() === "product-categories" ? 'Product Categories':'Page Categories'); ?>


    <div class="datatable">
        <div class="row header no-gutters" style="text-align:center;color:green">
            <a class="add_btn" href="<?php echo base_url(ADMIN . '/product-categories/add') ?>">+ Add Category</a>
            &nbsp;
            &nbsp;

            <?php
            if(current_page() === "product-categories") {
                ?>
                <a class="add_btn" href="<?php echo base_url(ADMIN . '/products') ?>">All Products</a>
                <?php
            }else {
                ?>
                <a class="add_btn" href="<?php echo base_url(ADMIN . '/pages') ?>">All Pages</a>
                <?php
            } ?>
        </div>

        <div class="mt-40"></div>

        <div class="books_listing">
            <div class="table-wrapper">
                <table id="cats_table" class="ui data_table table_draggable celled table responsive nowrap unstackable" data-draggable="true" data-orderable="false" data-search="false" data-onreorder="onReOrder()" style="width:100%">
                    <thead>
                    <tr>
                        <th data-sortable="false" data-orderable="false" width="50"></th>
                        <th data-sortable="false" data-orderable="false" width="100" class="text-center">Order</th>
                        <th data-sortable="false">Image</th>
                        <th data-sortable="false">Category Name</th>
                        <?php if($group === "shop") { ?>
                            <th data-sortable="false">Product Count</th>
                        <?php } ?>
                        <th data-sortable="false" width="120" class="text-center">Show In Menu</th>
                        <th data-sortable="false" width="120" class="text-center">Status</th>

                        <th data-sortable="false" width="220" class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($categories as $cate) {
                        $cat_img = $media->get_media_src($cate->img,'','thumbnail');
                        $prod_cats = $productModel->category_products($cate->id,'any');
                        $prod_count = count($prod_cats);
                        ?>
                        <tr class="table_row_<?php echo $cate->id ?>" data-id="<?php echo $cate->id ?>">
                            <td><i class="lni lni-line-double"></i></td>
                            <td class="text_center">
                                <?php echo $cate->sort_order ?>
                            </td>

                            <td width="100"><img class="thumb" src="<?php echo $cat_img ?>"></td>
                            <td> <?php echo $cate->name ?></td>
                            <?php if($group === "shop") { ?>
                                <td width="100"> <?php echo $prod_count ?></td>
                            <?php } ?>
                            <td> <?php echo $cate->show_in_menu ? 'Yes':'No' ?></td>
                            <td> <?php echo $cate->status ? 'Active':'Inactive' ?></td>

                            <td>
                                <a class="del_row edit_row btn save text-center d-block btn-sm"
                                href="<?php echo base_url(ADMIN . '/product-categories/add/') ?>/<?php echo $cate->id ?>">Edit</a>
                                <a class="del_row edit_row btn save text-center d-block bg-black btn-sm"
                                onclick="del_item('<?php echo base_url(ADMIN . '/categories/delete/') ?>/<?php echo $cate->id ?>')"
                                href="javascript:void(0)">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
        </div>

        <script>
            let onReOrder = ()=> {
                const table = document.querySelector('#cats_table');
                const table_rows = table.querySelectorAll('tbody > tr');
                let order_data = [];

                let form = new FormData();

                table_rows.forEach((tr, idx)=>{
                    let order = idx+1;
                    let row_id = tr.getAttribute('data-id');
                    order_data.push({order: order, id: row_id})
                });
                form.set('data',JSON.stringify(order_data));

                fetch('<?php echo admin_url() ?>product-category-sortorder',{
                    method: "POST",
                    body: form,
                }).then(res=>res.json()).then((res)=>{
                    location.reload();
                })
            }
        </script>
    </div>
</div>
