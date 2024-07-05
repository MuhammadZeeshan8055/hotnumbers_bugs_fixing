<section id="tab-general" class="active">

    <form action="<?php echo base_url(ADMIN . '/settings') ?>"
          method="post"
          enctype="multipart/form-data">
        <?php if (session('msg')) :
            message_notice(session('msg'));
        endif ?>

        <div class=" row" style="width:80%">

            <div class="col-lg-6">
                <div class="input_field">
                    <label>Site title</label>
                    <input type="text" name="website[title]"
                           value="<?php echo @$setting_row['title'] ?>" required>
                </div>
            </div>

            <div class=" col-lg-6">
                <div class="input_field">
                    <label>Admin email</label>
                    <input type="email" name="website[admin_email]"
                           value="<?php echo @$setting_row['admin_email'] ?>" required>
                </div>

            </div>

            <div class=" col-lg-6">
                <div class="input_field mt-20">
                    <label>Recipient From Email</label>
                    <input type="email" name="website[online_admin_email]" value="<?php echo @$setting_row['online_admin_email'] ?>" required>
                </div>

            </div>

        </div>

        <hr>

        <div class="row" style="width:80%">


            <div class="col-md-6 input_field">
                <label>Address 1</label>
                <input type="text" name="website[site_address_1]" value="<?php echo @$setting_row['site_address_1'] ?>">
            </div>

            <div class="col-md-6 input_field mt-10">
                <label>Address 2</label>
                <input type="text" name="website[site_address_2]" value="<?php echo @$setting_row['site_address_2'] ?>">
            </div>

            <div class="col-md-6 input_field mt-10">
                <label>Postcode</label>
                <input type="text" name="website[post_code]" value="<?php echo @$setting_row['post_code'] ?>">
            </div>

            <div class="col-md-6 input_field mt-10">
                <label>Contact Number</label>
                <input type="text" name="website[contact_number]" value="<?php echo @$setting_row['contact_number'] ?>">
            </div>

            <div class="col-md-6 input_field mt-10">
                <label>Contact Email</label>
                <input type="text" name="website[contact_email]" value="<?php echo @$setting_row['contact_email'] ?>">
            </div>

        </div>

        <div class="mt-22"></div>

        <div class="row footer">
            <div class="col-lg-12 btn_bar flex_space">
                <input data-tab-current-url type="hidden" name="current_url" value="<?php echo current_url() ?>">
                <button type="submit" class=" btn save btn-sm">Save changes</button>
            </div>
        </div>

    </form>
    <br>
    <br>




</section>