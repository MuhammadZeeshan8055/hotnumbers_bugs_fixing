<div class="container">

    <div class="books_page featured featured_page ">
        <div class="admin_title_row">
            <?php admin_page_title('Profile'); ?>

        </div>
        <form class="mt-20" action="<?php echo base_url(ADMIN . '/profile/') ?>"
              method="post"
              enctype="multipart/form-data">

            <?php
            get_message('profile_message');
            ?>

            <div class="table-box d-inline-block">
                <div class="no-gutters">
                    <div class="pb-20">
                        <div class="input_field">
                            <label>Username</label>
                            <input type="text" name="username"
                                   value="<?php echo $profile_row->username ?>" required>
                        </div>
                    </div>

                    <div class="pb-20">
                        <div class="input_field">
                            <label>First Name</label>
                            <input type="text" name="fname"
                                   value="<?php echo $profile_row->fname ?>">
                        </div>

                    </div>
                    <div class="pb-20">
                        <div class="input_field">
                            <label>Last name</label>
                            <input type="text" name="lname"
                                   value="<?php echo $profile_row->lname ?>">
                        </div>

                    </div>
                    <div class="pb-20">
                        <div class="input_field">
                            <label>Email address</label>
                            <input type="email" name="email"
                                   value="<?php echo $profile_row->email ?>" required>
                        </div>
                    </div>

                    <div class="pb-10" id="change-pass">
                        <div class="input_field">
                            <label>Change Password</label>
                            <input type="text" name="password" disabled value="" required placeholder="Type new password here">
                            <button type="button" onclick="$(this).hide();$(this).prev().prop('disabled',false);" class=" btn save" style="position: absolute;left: 1px;bottom: 2px;">Click here to change password</button>
                        </div>
                    </div>

                </div>

                <div class="row mt-15">
                    <div class="col-lg-12 btn_bar">
                        <button type="submit" class=" btn save">Save changes</button>

                    </div>
                </div>
            </div>
        </form>

    </div>
</div>