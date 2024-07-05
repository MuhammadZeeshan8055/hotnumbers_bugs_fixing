<?php
if(!empty($order_id)) {
    $orderModel = model('OrderModel');
    $emails = $orderModel->order_emails($order_id);

    if(!empty($emails)) {
        ?>
        <div class="mail_log_wrapper">
            <div class="mails">
                <?php foreach($emails as $email) { ?>
                    <div>
                        <table class="table">
                            <tr>
                                <td><div><?php echo _date($email['send_date']) ?></div><small><?php echo _time($email['send_date']) ?></small></td>
                                <td width="75%"><?php echo $email['mail_subject'] ?></td>
                                <td>  <a id="mail_content_<?php echo $email['mail_id'] ?>" href="#" class="edit_row btn btn-secondary btn-sm red">View</a>
                                    <script>
                                        document.querySelector('#mail_content_<?php echo $email['mail_id'] ?>').addEventListener('click',(e)=>{
                                            e.preventDefault();
                                            Swal.fire({
                                                html: `<div style="text-align:left"><?php echo $email['mail_content'] ?></div>`,
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
                                    </script>
                                </td>
                            </tr>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>
        <style>
            .mail_log_wrapper > .mails {
                max-height: 350px;
                overflow: auto;
            }
            .log_mail div#swal2-content {
                color: #000;
                min-width: 640px;
                margin: 0;
                padding: 0;
            }
            .swal2-content {
                padding: 0;
            }
            .swal2-container .log_mail.swal2-popup {
                min-width: 900px;
            }
        </style>
        <?php
    }else {
        ?>
        <div class="notices-wrapper"><h5>No record found</h5></div>
    <?php
    }
}