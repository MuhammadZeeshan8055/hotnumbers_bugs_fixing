<div class="container">
    <div class="  datatable ">
        <div class="row header no-gutters flex_space" style="text-align:center;color:green">
            <a class="add_btn" href="<?php echo base_url(ADMIN . '/shop/add') ?>">+ Add Shop</a>
            <a class="btn back" href="#" onclick="history.back()" class="add_banner"><i class="icon-left-small"></i> Back</a>


        </div>
        <div class="books_listing">
            <table id="books_table" class="ui data_table celled table responsive nowrap unstackable" style="width:100%">
                <thead>
                <tr>
                    <th>Preview image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Slug</th>
                    <th>Edit</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($product_rows as $product_row) { ?>
                    <tr class="table_row_<?php echo $product_row->id ?>">
                        <td><img width="50" height="50"
                                 src="<?php echo base_url('assets/images/site-images/products/' . $product_row->img) ?>">
                        </td>
                        <td> <?php echo $product_row->title ?></td>
                        <td> <?php echo limit(strip_tags($product_row->description),50) ?></td>
                        <td><?php echo $product_row->slug ?></td>
                        <td><a class="edit_row"
                               href="<?php echo base_url(ADMIN . '/shop/add') ?>/<?php echo $product_row->id ?>"><i
                                        class="icon-edit-alt"></i> </a>

                            <a class="del_row edit_row"
                               onclick="del_item('<?php echo base_url(ADMIN . '/shop/delete/') ?>/<?php echo $product_row->id ?>')"
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
