<div class="container">
    <?php admin_page_title('Email Templates'); ?>


    <div class="row datatable header no-gutters" style="text-align:center;color:green">
        <a class="add_btn" href="<?php echo base_url(ADMIN . '/add-email-template') ?>">+ Add Email Template</a>
        &nbsp;
        <a class="add_btn" href="<?php echo base_url(ADMIN . '/email-templates/view/header') ?>">Header</a>
        &nbsp;
        <a class="add_btn" href="<?php echo base_url(ADMIN . '/email-templates/view/footer') ?>">Footer</a>
    </div>

    <div class="mt-50">

        <?php get_message() ?>
        <div class="books_listing">
            <table class="ui celled table" style="width:100%">
                <thead>
                <tr>
                    <th width="300">Name</th>
                    <th width="50%">Subject</th>
                    <th width="100">Status</th>
                    <th width="100">View / Edit</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($emails as $mail) {
                    ?>
                    <tr>
                        <td><?php echo $mail['name'] ?></td>
                        <td><?php echo $mail['subject'] ?></td>
                        <td><?php echo $mail['status'] ? 'Active':'Inactive' ?></td>
                        <td class="text-center">
                            <a href="<?php echo base_url('admin/email-templates/view/'.$mail['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                            <?php if(!$mail['is_default']) {
                                ?>
                                <a href="<?php echo base_url('admin/email-templates/view/'.$mail['id']) ?>?delete=1" class="btn btn-sm btn-primary">Delete</a>
                                <?php
                            }?>
                        </td>
                    </tr>
                <?php }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
