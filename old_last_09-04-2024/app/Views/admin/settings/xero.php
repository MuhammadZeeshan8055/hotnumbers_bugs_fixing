<section id="tab-xero">
    <form action="<?php echo base_url(ADMIN . '/settings') ?>"
          method="post"
          enctype="multipart/form-data">


        <div class="mt-22"></div>

        <div class="row footer">
            <div class="col-lg-12 btn_bar flex_space">
                <input data-tab-current-url type="hidden" name="current_url" value="<?php echo current_url() ?>">
                <button type="submit" class=" btn save btn-sm">Save changes</button>
            </div>
        </div>
</section>