<div class="container">
    <div class="datatable featured featured_page ">

        <?php
        if($mode === "add") {
            $action = base_url(ADMIN . '/email-templates/add');
            $email = [
                'name' => '',
                'mail_from' => '',
                'subject' => '',
                'cc' => '',
                'bcc' => '',
                'content' => '',
                'id' => '',
                'tags'=>'',
                'status'=>1
            ];
        }else {
            $action = base_url(ADMIN . '/email-templates/update');
            $action .= !empty($categories_row['id']) ? '/' . $categories_row['id'] : '';
        }
        $setting = get_setting('website',true);

        ?>

        <div class="d-inline-block">
            <?php admin_page_title(ucfirst($mode).' Template'); ?>
        </div>

        <form
              action="<?php echo $action ?>"
              method="post"
              enctype="multipart/form-data">

            <fieldset>

                <?php if($email['keyname'] !== "header" && $email['keyname'] !== "footer") { ?>

                <div class="row">
                    <div class="col-md-4">
                        <div class="input_field">
                            <label>Name:</label>
                            <input name="name" value="<?php echo $email['name'] ?>">
                        </div>
                    </div>
                </div>

                <div class="row mt-10">
                    <div class="col-md-4">
                        <div class="input_field">
                            <label>From:</label>
                            <input name="from" value="<?php echo $email['mail_from'] ?>" placeholder="<?php echo $setting['online_admin_email'] ?>">
                        </div>
                    </div>
                </div>

                <div class="row mt-10">
                    <div class="col-md-4">
                        <div class="input_field">
                            <label>Subject:</label>
                            <input name="subject" value="<?php echo $email['subject'] ?>">
                        </div>
                    </div>
                </div>

                <div class="row mt-10">
                    <div class="col-md-4">
                        <div class="input_field">
                            <label>CC:</label>
                            <input name="cc" value="<?php echo $email['cc'] ?>">
                        </div>
                    </div>
                </div>

                <div class="row mt-10">
                    <div class="col-md-4">
                        <div class="input_field">
                            <label>BCC:</label>
                            <input name="bcc" value="<?php echo $email['bcc'] ?>">
                        </div>
                    </div>
                </div>

                <div class="row mt-10">
                    <div class="col-md-4">
                        <div class="input_field">
                            <label>Status:</label>
                            <div>
                                <div class="input_field checkbox">
                                    <input type="radio" name="status" value="1" <?php echo $email['status'] == 1 ? 'checked':'' ?>>
                                    <label>Active</label>
                                </div>
                                <div class="input_field checkbox">
                                    <input type="radio" name="status" value="0" <?php echo $email['status'] == 0 ? 'checked':'' ?>>
                                    <label>Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php } ?>

                <?php if(!empty($shortcodes)) {
                    ?>
                    <div class="row mt-20">
                        <div class="col-md-12">
                            <div class="input_field">
                                <label>Email body codes:</label>
                                <div class="mt-10">
                                    <?php
                                    foreach($shortcodes as $code) {
                                        ?>
                                        <a href="" onclick="insertToTextfield('<?php echo trim($code) ?>');return false;" style="color: var(--color-2);padding: 6px 8px 6px" class="insertToTextfield btn btn-sm d-inline-block mb-5" data-text="{{<?php echo trim($code) ?>}}"><?php echo trim($code) ?></a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <style>
                            .insertToTextfield {
                                margin-right: 10px;
                                border-bottom: 1px solid #eee;
                            }
                            .insertToTextfield:hover {
                                background-color: var(--color-2);
                                color: #fff !important;
                            }
                        </style>
                    </div>
                    <?php
                } ?>


                <div class="row mt-19">
                    <div class="col-md-12">
                        <div class="input_field">
                            <label>Content:</label>

                            <div id="add-media-btn" hidden>
                                <?php
                                $label =  !empty($media_button_label) ? $media_button_label : 'Add Media';
                                upload_media_box([
                                    'textarea'=>'#media-input',
                                    'buttonText' => '<i class="lni lni-image"></i>&nbsp;&nbsp;Add Media',
                                    'multiple'=>true,
                                    'editor'=>'email-editor'
                                ],false);
                                ?>
                            </div>

                            <textarea id="email-editor" name="mail_content" rows="10" class="text-editor"><?php echo !empty($email['content']) ? base64_decode($email['content']) : '' ?></textarea>


                        </div>
                    </div>
                </div>
            </fieldset>

            <script>
                const insertToTextfield = (text)=> {
                    const code = `{{${text}}}`;
                    CKEDITOR.instances['email-editor'].insertText(code);
                }
            </script>

            <input type="hidden" name="template_id" value="<?php echo $email['id'] ?>">

            <br>
            <button type="submit" name="update" value="1" class="btn btn-primary"><?php echo ucfirst($mode) ?> template</button>

        </form>

    </div>
</div>