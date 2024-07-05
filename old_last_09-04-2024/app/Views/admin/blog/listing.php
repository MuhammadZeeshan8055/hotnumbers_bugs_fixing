<div class="container">
    <?php admin_page_title('Blog Posts'); ?>
    <div class="datatable ">
        <div class="row header no-gutters" style="text-align:center;color:green">
            <a class="add_btn" href="<?php echo base_url(ADMIN . '/categories/add?group=') ?>">+ Add Post</a>
        </div>
        <div class="books_listing">
            <table id="books_table" data-remote="?table_data=1" class="ui data_table celled table responsive nowrap unstackable" style="width:100%">
                <thead>
                <tr>
                    <th width="50" data-orderable="false">Preview image</th>
                    <th>Post title</th>
                    <th width="150">Date posted</th>
                    <th data-orderable="false" width="150">Edit</th>
                </tr>
                </thead>
                <tbody>
                <?php
                /*foreach ($post_rows as $post_row) { ?>
                    <tr class="table_row_<?php echo $post_row->post_id ?>">
                        <td><img width="50" height="50"
                                 src="<?php echo base_url('assets/images/site-images/blogs/' . $post_row->img) ?>">
                        </td>
                        <td> <?php echo limit($post_row->title, 30) ?></td>

                        <td><?php echo datetime($post_row->post_date); ?></td>
                        <td><input type="checkbox" name="featured_post" data-postid="<?php echo $post_row->post_id ?>"
                                   class="featured_post" <?php echo($post_row->featured_post == 'yes' ? 'checked' : '') ?>>
                        </td>
                        <td><a class="edit_row"
                               href="<?php echo base_url(ADMIN . '/blog/add/') ?>/<?php echo $post_row->post_id ?>"><i
                                        class="icon-edit-alt"></i> </a>
                            <a class="del_row edit_row"
                               onclick="del_item('<?php echo base_url(ADMIN . '/blog/delete/') ?>/<?php echo $post_row->post_id ?>')"
                               href="javascript:void(0)"></i><i class="icon-trash"></i></a>
                        </td>
                    </tr>
                <?php }*/
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
