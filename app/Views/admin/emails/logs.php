<div class="container">
    <div class="admin_title_row">
        <div class="d-inline-block ">
            <?php admin_page_title('Email Logs'); ?>
        </div>
        &nbsp;
        &nbsp;
        &nbsp;
        &nbsp;
        <div class="d-inline-block ">
            <form method="get" class="form">
                <div class="inline-checkbox input_field">
                    <label>
                    <input onchange="this.form.submit()" type="checkbox" name="toggle_logs" value="1" <?php echo get_setting('record_email_logs') ? 'checked':'' ?>>
                    <span>Record logs</span>
                    </label>
                </div>
            </form>
        </div>
    </div>

    <div class="datatable">

        <?php get_message(); ?>

            <form id="order-list" method="post">
                <div class="books_listing mt-0 pt-0" style="padding-top: 0em;">
                    <div class="table-wrapper">
                        <table id="books_table" class="ui celled table data_table responsive nowrap table_order" data-sortcol="0" data-sortorder="desc" data-remote="?data=1" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>To</th>
                                <th>CC</th>
                                <th>BCC</th>
                                <th>From</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            /*
                            if(!empty($emails)) {
                                foreach ($emails as $mail) {
                                    ?>
                                    <tr>
                                        <td><?php echo $mail['mail_subject'] ?></td>
                                        <td><?php echo $mail['mail_to'] ?></td>
                                        <td><?php echo $mail['mail_from'] ?></td>
                                        <td><?php echo $mail['mail_cc'] ?></td>
                                        <td><?php echo $mail['mail_bcc'] ?></td>
                                        <td>
                                            <div class="text-center" style="padding: 5px">
                                                <a id="mail_content_<?php echo $mail['mail_id'] ?>" href="#" class="edit_row btn btn-primary btn-sm red">View Content</a>
                                            </div>
                                            <script>
                                                document.querySelector('#mail_content_<?php echo $mail['mail_id'] ?>').addEventListener('click',(e)=>{
                                                    e.preventDefault();
                                                    Swal.fire({
                                                        html: `<div style="text-align:left"><?php echo $mail['mail_content'] ?></div>`,
                                                        showConfirmButton: false,
                                                        showCancelButton: false,
                                                        showCloseButton: true
                                                    });
                                                })
                                            </script>
                                        </td>
                                        <td><?php echo $mail['status'] ? '✅ Sent':'⚠️ Failed' ?></td>
                                        <td class="text-center"><?php echo _date($mail['send_date']) ?></td>
                                    </tr>
                                <?php }
                            }*/

                            ?>
                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    </div>
                </div>
            </form>

    </div>

</div>
