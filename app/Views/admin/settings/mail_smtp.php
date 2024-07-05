<section id="mail-smtp">
    <br>
    <h4>Email SMTP</h4>
    <form method="post" action="<?php echo base_url(ADMIN . '/settings') ?>" autocomplete="off">
        <?php
            function smtp_mail_form_body($data=[]) {

                ?>
                <div class="col-md-4 col-sm-12">
                    <div class="table-box">
                        <?php
                        if(!empty($data['id'])) {
                            ?>
                            <input type="hidden" name="mail_smtp[id][]" value="<?php echo $data['id'] ?>">
                            <a data-href="?del_smtp=<?php echo $data['id'] ?>" data-confirm="Are you sure to delete this SMTP setting?" class="btn btn-sm close_btn"><i class="lni lni-trash-can color-red"></i></a>
                            <?php
                        }
                        ?>
                        <div>
                            <div class="input_field">
                                <label>Host</label>
                                <input type="text" autocomplete="off" name="mail_smtp[host][]" value="<?php echo !empty($data['host']) ? $data['host'] : '' ?>">
                            </div>
                            <div class="input_field">
                                <label>Port</label>
                                <input type="number" autocomplete="off" name="mail_smtp[port][]" value="<?php echo !empty($data['port']) ? $data['port'] : '' ?>">
                            </div>
                            <div class="input_field">
                                <label>From</label>
                                <input type="text" autocomplete="off" name="mail_smtp[mail_from][]" value="<?php echo !empty($data['mail_from']) ? $data['mail_from'] : '' ?>">
                            </div>
                            <div class="input_field">
                                <label>Username</label>
                                <input type="text" autocomplete="off" name="mail_smtp[username][]" value="<?php echo !empty($data['username']) ? $data['username'] : '' ?>">
                            </div>
                            <div class="input_field">
                                <label>Password</label>
                                <input type="password" autocomplete="off" name="mail_smtp[password][]" value="<?php echo !empty($data['password']) ? $data['password'] : '' ?>">
                            </div>

                            <?php if(!empty($data['id'])) {
                                ?>
                                <hr>
                                <div class="test_config">
                                    <h5 class="mb-10">Test config</h5>
                                    <div class="input_field">
                                        <label>To email</label>
                                        <input type="email" class="test_email">
                                    </div>
                                    <div class="input_field">
                                        <label>Message</label>
                                        <textarea style="min-height: initial" cols="2" class="form-control test_message"></textarea>
                                        <input class="smtp_test" value="<?php echo $data['id'] ?>" type="hidden">
                                    </div>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="send_test_email(this)">Send</button>
                                </div>
                            <?php
                            }?>

                        </div>
                    </div>
                </div>
                <?php
            }
        ?>

        <div id="mail_smtp_body" class="row">
            <?php if(!empty($smtp_settings)) {
                foreach($smtp_settings as $smtp) {
                    smtp_mail_form_body([
                            'id'=>$smtp->id,
                            'host' =>$smtp->host,
                            'port'=>$smtp->port,
                            'mail_from'=>$smtp->mail_from,
                            'username'=>$smtp->username,
                            'password'=>$smtp->password
                    ]);
                }
            }else {
                smtp_mail_form_body();
            } ?>
        </div>


        <div class="mt-10"></div>
        <button type="button" class="btn btn-secondary btn-sm" onclick="addSmtpBody()">Add SMTP</button>

        <div class="mt-22"></div>

        <div class="row footer">
            <div class="col-lg-12 btn_bar flex_space">
                <input data-tab-current-url type="hidden" name="current_url" value="<?php echo current_url() ?>">
                <button type="submit" class=" btn save btn-sm">Save changes</button>
            </div>
        </div>

        <style>
            .close_btn {
                font-size: 12px;
                text-align: center;
                position: relative;
                float: right;
                right: 0;
                margin-bottom: 12px;
                margin-top: -8px;
                margin-right: -13px;
                z-index: 100;
                color: var(--red);
            }
        </style>

        <script>
            function addSmtpBody() {
                const bodyHtml = `<?php smtp_mail_form_body() ?>`;
                document.querySelector('#mail_smtp_body').insertAdjacentHTML('beforeend',bodyHtml);
            }

            function send_test_email(input) {
                const parent = $(input).closest('.test_config');
                const test_email = parent.find('.test_email').val();
                const test_message = parent.find('.test_message').val();
                const action =  $(input).closest('form').attr('action');
                parent.addClass('processing');

                if(!test_email || !test_message) {
                    message("Please enter email address and message");
                    parent.removeClass('processing');
                    return;
                }

                let form_data = {
                    "test_email": test_email,
                    "test_message": test_message,
                    "current_url": "<?php echo current_url() ?>",
                    "smtp_test": parent.find('.smtp_test').val()
                };
                form_data = new URLSearchParams(form_data).toString();

                $.post(action,form_data, function(data) {
                    const data_ = JSON.parse(data);
                    message(data_.message);
                    parent.removeClass('processing');
                });

                parent.find('input,textarea').val("");
            }
        </script>
    </form>
</section>