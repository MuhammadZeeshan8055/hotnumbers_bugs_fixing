<div class="container">
    <div class="  datatable ">
        <div class="row header no-gutters" style="text-align:center;color:green">
            <a class="add_btn" href="<?php echo base_url(ADMIN . '/shop/add') ?>">+ Add Shop</a>
        </div>
        <div class="books_listing">
            <table id="books_table" class="ui celled table data_table responsive nowrap unstackable" style="width:100%">
                <thead>
                <tr>
                    <th>Preview image</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($shop_rows as $shop_row) { ?>
                    <tr class="table_row_<?php echo $shop_row->id ?>">
                        <td><img width="50" height="50"
                                 src="<?php echo base_url('assets/images/site-images/categories/' . $shop_row->img) ?>">
                        </td>
                        <td> <?php echo $shop_row->name ?></td>
                        <td><?php echo $shop_row->slug ?></td>
                        <td>
                            <a class="edit_row"
                               href="<?php echo base_url(ADMIN . '/shop/add_cate') ?>/<?php echo $shop_row->id ?>"><i
                                        class="icon-edit-alt"></i> </a>
                            <a placeholder="List of category" class="edit_row"
                               href="<?php echo base_url(ADMIN . '/shop/list') ?>/<?php echo $shop_row->id ?>"><i
                                        class="icon-th"></i> </a>
                            <a class="del_row edit_row"
                               onclick="del_item('<?php echo base_url(ADMIN . '/shop/delete/') ?>/<?php echo $shop_row->id ?>')"
                               href="javascript:void(0)"></i><i class="icon-trash"></i></a>
                        </td>

                    </tr>
                <?php } ?>


                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
    </div>
</div>
