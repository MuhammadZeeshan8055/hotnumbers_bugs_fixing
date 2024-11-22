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

        <div class="books_listing">
            <div class="table-wrapper">
                <table id="message-table" class="ui celled table data_table table_draggable" data-orderable="false" data-search="false" data-paging="false" data-lengthmenu="false" data-draggable="true" data-onreorder="onReOrder()" style="width:100%">
                    <thead>
                    <tr>
                        <th width="300" data-orderable="false">Name</th>
                        <th width="300" data-orderable="false">From</th>
                        <th width="50%" data-orderable="false">Subject</th>
                        <th width="100" data-orderable="false">Status</th>
                        <th width="100" data-orderable="false">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($emails as $mail) {
                        $smtp_id = $mail['smtp_id'];
                        $smtp_config = !empty($smtp_configs[$smtp_id]) ? $smtp_configs[$smtp_id] : [];
                        if(!empty($smtp_config)) {
                        ?>
                        <tr data-id="<?php echo $mail['id'] ?>">
                            <td style="cursor:row-resize;"><?php echo $mail['name'] ?></td>
                            <td><?php echo $smtp_config->username ?></td>
                            <td><?php echo $mail['subject'] ?></td>
                            <td><div class="text-center status-<?php echo $mail['status'] ?>"><?php echo $mail['status'] ? 'Active':'Inactive' ?></div></td>
                            <td class="text-center">
                                <div class="text-center" style="width: 150px">
                                    <a href="<?php echo base_url('admin/email-templates/view/'.$mail['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <?php if(!$mail['is_default']) {
                                        ?>
                                        <a href="<?php echo base_url('admin/email-templates/view/'.$mail['id']) ?>?delete=1" class="btn btn-sm btn-primary">Delete</a>
                                        <?php
                                    }?>
                                    &nbsp;
                                    <a href="#" onclick="email_preview('<?php echo base_url('admin/ajax/get-email-preview/'.$mail['id']) ?>'); return false" class="btn btn-sm btn-primary">Preview</a>
                                </div>
                            </td>
                        </tr>
                    <?php }
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <style>
                .status-0 {
                    background-color: var(--red);
                    color: #fff;
                }
            </style>
        </div>

        <?php if(!empty($custom_emails)) { ?>
                <h4>Custom Emails</h4>
        <div class="books_listing">
            <div class="table-wrapper">
                <table id="message-table" class="ui celled table data_table table_draggable" data-orderable="false" data-search="false" data-paging="false" data-lengthmenu="false" data-draggable="true" data-onreorder="onReOrder()" style="width:100%">
                    <thead>
                    <tr>
                        <th width="300" data-orderable="false">Name</th>
                        <th width="50%" data-orderable="false">Subject</th>
                        <th width="100" data-orderable="false">Status</th>
                        <th width="100" data-orderable="false">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($custom_emails as $mail) {
                        ?>
                        <tr data-id="<?php echo $mail['id'] ?>">
                            <td style="cursor:row-resize;"><?php echo $mail['name'] ?></td>
                            <td><?php echo $mail['subject'] ?></td>
                            <td><div class="text-center"><?php echo $mail['status'] ? 'Active':'Inactive' ?></div></td>
                            <td class="text-center">
                                <div class="text-center btn-group" style="width: 200px">
                                    <a href="<?php echo base_url('admin/email-templates/view/'.$mail['id']) ?>" class="btn btn-sm btn-primary">Edit</a>

                                    <a href="#" onclick="email_preview('<?php echo base_url('admin/ajax/get-email-preview/'.$mail['id']) ?>'); return false" class="btn btn-sm btn-primary">Preview</a>
                                    &nbsp;
                                    <?php if(!$mail['is_default']) {
                                        ?>
                                        <a href="<?php echo base_url('admin/email-templates/?delete='.$mail['id']) ?>" onclick="return confirm('Are you sure to delete this template?')" class="btn btn-sm btn-primary bg-black">Delete</a>
                                        <?php
                                    }?>
                                </div>
                            </td>
                        </tr>
                    <?php }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php } ?>

    </div>
</div>

<script>
    let onReOrder = ()=> {
        const table = document.querySelector('#message-table');
        const table_rows = table.querySelectorAll('tbody > tr');
        let order_data = [];

        let form = new FormData();

        table_rows.forEach((tr, idx)=>{
            let order = idx+1;
            let row_id = tr.getAttribute('data-id');
            order_data.push({order: order, id: row_id})
        });
        form.set('data',JSON.stringify(order_data));

        fetch('<?php echo admin_url() ?>ajax/emailtemplate-sortorder',{
            method: "POST",
            body: form,
        }).then(res=>res.json()).then((res)=>{
            location.reload();
        })
    }

    const email_preview = (url)=> {
        Swal.showLoading();
        fetch(url).then(res=>res.text()).then(preview=> {
            Swal.fire({
                title: "Email Preview",
                html: preview,
                width: 850,
                showConfirmButton: false,
                showCancelButton: false,
                showCloseButton: true,
                showClass: {
                    popup: 'animated windowIn log_mail'
                },
                hideClass: {
                    popup: 'animated windowOut'
                }
            });
        })
    }
</script>
