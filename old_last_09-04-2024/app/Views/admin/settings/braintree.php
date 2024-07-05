<section id="tab-braintree">
    <?php
    $payment = [
        'braintree' => [
            'live'=>[
                'merchant_id'=>'',
                'public_key'=>'',
                'private_key'=>''
            ],
            'sandbox' => [
                'merchant_id'=>'',
                'public_key'=>'',
                'private_key'=>''
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
        <h4>Braintree Settings</h4>
        <br>

        <div class="table-box">
            <label>Public keys</label>
            <div>
                <div class="input_field">
                    <label>Merchant ID</label>
                    <input type="text" name="payment_method[braintree][live][merchant_id]" value="<?php echo $payment['braintree']['live']['merchant_id'] ?>">
                </div>

                <div class="input_field">
                    <label>Public Key</label>
                    <input type="text" name="payment_method[braintree][live][public_key]" value="<?php echo $payment['braintree']['live']['public_key'] ?>">
                </div>

                <div class="input_field">
                    <label>Private Key</label>
                    <input type="text" name="payment_method[braintree][live][private_key]" value="<?php echo $payment['braintree']['live']['private_key'] ?>">
                </div>
            </div>
        </div>

        <div class="table-box">
            <label>Sandbox keys</label>
            <div>
                <div class="input_field">
                    <label>Merchant ID</label>
                    <input type="text" name="payment_method[braintree][sandbox][merchant_id]" value="<?php echo $payment['braintree']['sandbox']['merchant_id'] ?>">
                </div>

                <div class="input_field">
                    <label>Public Key</label>
                    <input type="text" name="payment_method[braintree][sandbox][public_key]" value="<?php echo $payment['braintree']['sandbox']['public_key'] ?>">
                </div>

                <div class="input_field">
                    <label>Private Key</label>
                    <input type="text" name="payment_method[braintree][sandbox][private_key]" value="<?php echo $payment['braintree']['sandbox']['private_key'] ?>">
                </div>
            </div>
        </div>

        <div class="input_field">
            <label>Environment</label>
            <div>
                <div class="checkbox input_field">
                    <input type="radio" name="payment_method[braintree][mode]" value="live" <?php echo $payment['braintree']['mode'] === 'live' ?'checked':'' ?>>
                    <label>Live</label>
                </div>
                &nbsp;
                <div class="checkbox input_field">
                    <input type="radio" name="payment_method[braintree][mode]" value="sandbox" <?php echo $payment['braintree']['mode'] === 'sandbox' ?'checked':'' ?>>
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