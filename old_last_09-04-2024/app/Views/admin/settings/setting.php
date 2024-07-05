<div class="container">

    <style>
        .remove_method {
            position: absolute;
            top: 50%;
            right: -10px;
            transform: translateY(-50%);
            color: red;
            font-size: 15px;
            cursor: pointer;
        }

        #shippiongmethods > div:first-child .remove_method {
            display: none;
        }
        #shippiongmethods .remove_method i {
            font-size: 14px;
            margin-top: 35px;
            display: block;
        }
    </style>

    <div class="settings ">
        <div class="admin_title_row">
            <?php admin_page_title('Settings'); ?>
        </div>
        <div class="tabs">
            <ul id="tab-links">
                <li><a href="#tab-general" class="active">General</a></li>
                <li><a href="#tab-shop">Shop</a></li>
                <li><a href="#tab-tax" class="">Tax</a></li>
                <li><a href="#tab-ship-methods" class="">Shipping</a></li>
                <li><a href="#tab-subscriptions" class="">Subscriptions</a></li>
                <?php if(env('braintree.enable')) { ?>
                    <li><a href="#tab-braintree" class="">Braintree</a></li>
                <?php } ?>

                <?php if(env('squareup.enable')) { ?>
                    <li><a href="#tab-squareup" class="">Squareup</a></li>
                <?php } ?>
                <li><a href="#tab-xero" class="">Xero</a></li>
            </ul>
            <?php
            $setting_row = [
                'title'=>'',
                'url'=>'',
                'admin_email'=>'',
                'customer_registration'=>'',
                'catalog_per_page'=>16,
                'items_per_page'=>16,
                'order_notification'=>'',
                'online_admin_email'=>'',
                'paypal_email'=>'',
                'currency_symbol'=>''
            ];
            if(!empty($setting_website)) {
                $setting_row = json_decode($setting_website,true);
            }

            $this->data['setting_row'] = $setting_row;

            if(!empty($tax_rates)) {
                $tax_rates = json_decode($tax_rates,true);
            }else {
                $tax_rates = [
                    'country'=>[0=>''],
                    'state'=>[0=>''],
                    'postcode'=>[0=>''],
                    'city'=>[0=>''],
                    'amount'=>[0=>''],
                    'type'=>[0=>''],
                    'tax_name'=>[0=>''],
                ];
            }

            $this->data['tax_rates'] = $tax_rates;

            echo view('admin/settings/general',$this->data) ?>

            <?php echo view('admin/settings/shop',$this->data) ?>

            <?php echo view('admin/settings/tax',$this->data) ?>

            <?php echo view('admin/settings/shipping-methods',$this->data) ?>

            <?php echo view('admin/settings/subscriptions',$this->data) ?>

            <?php echo view('admin/settings/braintree',$this->data) ?>

            <?php echo view('admin/settings/squareup',$this->data) ?>

            <?php echo view('admin/settings/xero',$this->data) ?>



        </div>
        <br>

    </div>
</div>