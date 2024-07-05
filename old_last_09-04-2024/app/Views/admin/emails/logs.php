<div class="container">
    <?php admin_page_title('Email Logs'); ?>

    <div class="datatable">
        <style>
            div#swal2-content {
                color: #000;
                min-width: 640px;
                margin: 0;
                padding: 0;
            }
            .swal2-content {
                padding: 0;
            }
            .swal2-container .swal2-popup {
                min-width: 710px;
            }
        </style>

        <?php get_message(); ?>

        <form id="order-list" method="post">
            <div class="books_listing mt-0 pt-0" style="padding-top: 0em;">
                <table class="ui celled table data_table responsive nowrap table_order" data-sortcol="0" data-sortorder="desc" data-remote="?data=1" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>To</th>
                        <th>From</th>
                        <th>Subject</th>
                        <th>CC</th>
                        <th>BCC</th>
                        <th></th>
                        <th>Status</th>
                        <th>Date</th>
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
        </form>
    </div>

</div>
