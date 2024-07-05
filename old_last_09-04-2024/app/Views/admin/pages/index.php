<div class="container">
    <div class="admin_title_row">
        <?php admin_page_title('All Pages'); ?>
    </div>
    <div class="datatable">
        <div class="row header no-gutters" style="text-align:center;color:green">
            <a class="add_btn" href="<?php echo base_url(ADMIN . '/pages/add') ?>">+ Add New Page</a>

            <a class="add_btn" href="<?php echo base_url(ADMIN); ?>/page-categories"> Page categories</a>

        </div>
        <?php get_message(); ?>
        <div class="books_listing">
            <table id="books_table" class="ui celled data_table table responsive nowrap unstackable" style="width:100%">
                <thead>
                <tr>
                    <th width="80">Page ID</th>
                    <th>Name</th>
                    <th width="200">Slug</th>
                    <th width="150">Date Created</th>
                    <th width="150">Status</th>
                    <th width="250">Edit</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(!empty($pages)) {
                    foreach ($pages as $page) {
                        ?>
                        <tr class="table_row_<?php echo $page['id'] ?>">
                            <td class="text-center"> <?php echo $page['id'] ?></td>
                            <td width="100"><?php echo $page['title'] ?></td>
                            <td class="text-center">
                                <div style="padding: 0 1em">
                                    <a title="View <?php echo $page['title'] ?>" href="<?php echo site_url($page['slug']) ?>" target="_blank"><?php echo $page['slug'] ?></a>
                                </div>
                            </td>
                            <td class="text-center"> <?php echo date('d-m-Y',strtotime($page['create_date'])) ?></td>
                            <td class="text-center"> <?php echo $page['status']?'Active':'Inactive'; ?></td>
                            <td class="text-center">
                                <a class="edit_row btn btn-sm save text-center d-block"
                                   href="<?php echo base_url(ADMIN . '/pages/add?load='.$page['id']) ?>">Edit</a>
                                &nbsp;
                                &nbsp;
                                <a class="del_row edit_row btn-sm btn save text-center d-block bg-black"
                                   onclick="del_item('<?php echo base_url(ADMIN . '/pages/delete/') ?>/<?php echo $page['id'] ?>')"
                                   href="javascript:void(0)">Delete</a>
                            </td>
                        </tr>
                    <?php }
                }

                ?>


                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
    </div>
</div>
