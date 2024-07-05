<section id="tab-squareup">
    <?php
    $payment = [
        'squareup' => [
            'live'=>[
                'app_id'=>'',
                'access_token'=>''
            ],
            'sandbox' => [
                'app_id'=>'',
                'access_token'=>''
            ],
            'mode' => ''
        ]
    ];
    if(!empty($payment_method)) {
        $payment = json_decode($payment_method,true);
    }
    ?>
    <form action="<?php echo base_url(ADMIN . '/settings') ?>"
          method="post"
          enctype="multipart/form-data">

    <br>
    <h4>Squareup Settings</h4>
    <br>

     <div class="table-box">
         <label>Public keys</label>
         <div>
             <div class="input_field">
                 <label>Merchant ID</label>
                 <input type="text" name="payment_method[squareup][live][app_id]" value="<?php echo @$payment['squareup']['live']['app_id'] ?>">
             </div>

             <div class="input_field">
                 <label>Public Key</label>
                 <input type="text" name="payment_method[squareup][live][access_token]" value="<?php echo @$payment['squareup']['live']['access_token'] ?>">
             </div>
         </div>
     </div>

        <div class="table-box">
            <label>Sandbox keys</label>
            <div>
                <div class="input_field">
                    <label>Merchant ID</label>
                    <input type="text" name="payment_method[squareup][sandbox][app_id]" value="<?php echo @$payment['squareup']['sandbox']['app_id'] ?>">
                </div>

                <div class="input_field">
                    <label>Public Key</label>
                    <input type="text" name="payment_method[squareup][sandbox][access_token]" value="<?php echo @$payment['squareup']['sandbox']['access_token'] ?>">
                </div>
            </div>
        </div>

        <div class="input_field">
            <label>Environment</label>
            <div>
                <div class="checkbox input_field">
                    <input type="radio" name="payment_method[squareup][mode]" value="live" <?php echo @$payment['squareup']['mode'] === 'live' ?'checked':'' ?>>
                    <label>Live</label>
                </div>
                &nbsp;
                <div class="checkbox input_field">
                    <input type="radio" name="payment_method[squareup][mode]" value="sandbox" <?php echo @$payment['squareup']['mode'] === 'sandbox' ?'checked':'' ?>>
                    <label>Sandbox</label>
                </div>
            </div>
        </div>

        <div class="mt-22"></div>

        <div class="row footer">
            <div class="col-lg-12 btn_bar flex_space">
                <input data-tab-current-url type="hidden" name="current_url" value="<?php echo current_url() ?>">
                <button type="submit" class=" btn save btn-sm">Save changes</button>
            </div>
        </div>
</section>