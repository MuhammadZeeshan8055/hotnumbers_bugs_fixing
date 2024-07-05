<div class="container">
    <div class="datatable featured featured_page ">
        <div class="flex_space">
            <h3 class="label">
                <?php
                if(!empty($user_row->user_id)) { ?>
                    User Profile ID#<?=$user_row->user_id?>
                <?php }else {
                    ?>
                    Add User
                    <?php
                } ?>
            </h3>
            <a class="btn back" href="#" onclick="history.back()" class="add_banner"><i
                        class="icon-left-small"></i> Back</a>
        </div>


        <form class="mt-30"
              action="<?php echo base_url(ADMIN . '/users/add') ?><?php echo !empty($user_row->user_id) ? '/' . $user_row->user_id : '' ?>"
              method="post" enctype="multipart/form-data" autocomplete="off">

            <?php if(!empty($user_row->user_id)) { ?>
                <input type="hidden" name="user_id" value="<?php echo $user_row->user_id ?>">
            <?php } ?>
            <?php /* if (session('msg')) : ?>
                <div class="alert mb-50">
                    <span class=" closebtn" onclick="this.parentElement.style.display='none';"><i
                                class="icon-angle-right"></i></span>
                    <strong>Success!</strong> <?= session('msg') ?>
                </div>
            <?php endif*/ ?>

            <br>
            <br>

            <div class="table-box">
                <label><?php
                    if(!empty($user_row->user_id)) { ?>
                        Edit Profile
                    <?php }else {
                        ?>
                        Add Profile
                        <?php
                    } ?></label>
                <div>
                    <?php
                    $user_data = [
                        'fname'=>'',
                        'lname'=>'',
                        'username'=>'',
                        'email'=>'',
                        'role'=>'',
                        'img'=>'',
                        'status' => 0
                    ];

                    if(empty($user_row->user_id)) {
                        $user_data['status'] = 1;
                    }

                    if(!empty($user_row->fname)) {
                        $user_data['fname'] = $user_row->fname;
                    }
                    if(!empty($user_row->username)) {
                        $user_data['username'] = $user_row->username;
                    }
                    if(!empty($user_row->lname)) {
                        $user_data['lname'] = $user_row->lname;
                    }
                    if(!empty($user_row->email)) {
                        $user_data['email'] = $user_row->email;
                    }
                    if(!empty($user_row->img)) {
                        $user_data['img'] = $user_row->img;
                    }
                    if(!empty($user_row->status)) {
                        $user_data['status'] = $user_row->status;
                    }
                    if(!empty($user_row->role)) {
                        $user_data['role'] = $user_row->role;
                    }
                    ?>

                    <div class="row row-fluid">

                        <div class="col-lg-6">

                            <div class="row">
                                <?php
                                /*<div class="col-lg-12">

                                    <div class="input_field">
                                        <label>Profile picture</label>
                                        <?php if(!empty($user_data['img'])) { ?>
                                            <div class="preview">
                                                <?php if(!empty($user_data['img'])) { ?>
                                                    <img class="thumb" style="width: 120px;height: 120px;margin-bottom: 1em;margin-top: 10px;" width="120" src="<?php echo asset('images/site-images/users/'.$user_data['img']); ?>">
                                                <?php } ?>
                                                <input type="hidden" name="old_image" value="<?php echo $user_data['img']; ?>">
                                            </div>
                                        <?php } ?>
                                        <input type="file" name="image" accept="image/*">
                                    </div>
                                    <br>
                                    <br>
                                </div>*/
                                ?>

                                <div class="col-lg-4">
                                    <div class="input_field">
                                        <label>Username</label>
                                        <input type="text" name="username"
                                               value="<?php echo $user_data['username'] ?>">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="input_field">
                                        <label>First Name</label>
                                        <input type="text" name="fname"
                                               value="<?php echo $user_data['fname'] ?>">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="input_field">
                                        <label>Last Name</label>
                                        <input type="text" name="lname"
                                               value="<?php echo $user_data['lname'] ?>">
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-19">
                                <div class="col-lg-4">
                                    <div class="input_field">
                                        <label>Email</label>
                                        <input type="text" name="email"
                                               value="<?php echo $user_data['email'] ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input_field">
                                        <label for="id_label_multiple">Select Role</label>
                                        <div class="rel">
                                            <select class="form-control select2" name="roles[]" multiple>
                                                <option disabled>Please Select</option>
                                                <?php
                                                if(!empty($roles)) {
                                                    foreach($roles as $role) {
                                                        $selected = in_array($role->role,array_keys($user_roles)) ? 'selected':'';
                                                        ?>
                                                        <option <?php echo $selected ?> value="<?php echo $role->id ?>"><?php echo $role->name ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <?php if(!in_array('administrator',array_keys($user_roles))) { ?>
                                    <div class="col-lg-4">
                                        <div class="input_field">
                                            <label>Status</label>
                                            <div>
                                                <div class="input_field checkbox">
                                                    <input type="radio" name="status" value="1" <?php echo $user_data['status'] == 1 ? 'checked':'' ?>>
                                                    <label>Active</label>
                                                </div>
                                                <div class="input_field checkbox">
                                                    <input type="radio" name="status" value="0" <?php echo empty($user_data['status']) ? 'checked':'' ?>>
                                                    <label>Inactive</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>

                            <?php if(!empty($user_row->user_id)) { ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <br>
                                        <br>
                                        <a href="#" onclick="$('#new-password').show();$('#new-password input').val('');$(this).hide();" class="btn btn-sm btn-primary">Reset Password</a>
                                    </div>
                                    <div id="new-password" style="display:none;" class="col-lg-12">
                                        <div class="input_field">
                                            <label>Enter new password</label>
                                            <input type="password" name="new_password" autocomplete="off">
                                        </div>
                                    </div>

                                </div>

                            <?php } ?>

                        </div>
                        <div class="col-lg-6">

                        </div>

                    </div>



                    <div class="row">

                        <?php
                        $billing_info = [
                            'first_name'=>'',
                            'last_name'=>'',
                            'billing_company'=>'',
                            'billing_address_1'=>'',
                            'billing_address_2'=>'',
                            'billing_city'=>'',
                            'billing_state'=>'',
                            'billing_postcode'=>'',
                            'billing_country'=>'',
                            'billing_phone'=>'',
                            'billing_email'=>'',
                            'billing_county'=>'',
                        ];

                        $shipping_info = [
                            'shipping_first_name'=>'',
                            'shipping_last_name'=>'',
                            'shipping_company'=>'',
                            'shipping_address_1'=>'',
                            'shipping_address_2'=>'',
                            'shipping_city'=>'',
                            'shipping_postcode'=>'',
                            'shipping_country'=>'',
                            'shipping_county'=>'',
                            'shipping_state'=>'',
                            'shipping_phone'=>'',
                            'shipping_email'=>''
                        ];



                        if(!empty($user_meta['billing_first_name'])) {
                            $billing_info['first_name'] = $user_meta['billing_first_name'];
                        }
                        if(!empty($user_meta['billing_last_name'])) {
                            $billing_info['last_name'] = $user_meta['billing_last_name'];
                        }

                        if(!empty($user_meta['billing_address_1'])) {
                            $billing_info['billing_address_1'] = $user_meta['billing_address_1'];
                        }
                        if(!empty($user_meta['billing_address_2'])) {
                            $billing_info['billing_address_2'] = $user_meta['billing_address_2'];
                        }
                        if(!empty($user_meta['billing_city'])) {
                            $billing_info['billing_city'] = $user_meta['billing_city'];
                        }
                        if(!empty($user_meta['billing_state'])) {
                            $billing_info['billing_state'] = $user_meta['billing_state'];
                        }
                        if(!empty($user_meta['billing_postcode'])) {
                            $billing_info['billing_postcode'] = $user_meta['billing_postcode'];
                        }
                        if(!empty($user_meta['billing_country'])) {
                            $billing_info['billing_country'] = $user_meta['billing_country'];
                        }
                        if(!empty($user_meta['billing_email'])) {
                            $billing_info['billing_email'] = $user_meta['billing_email'];
                        }
                        if(!empty($user_meta['billing_phone'])) {
                            $billing_info['billing_phone'] = $user_meta['billing_phone'];
                        }
                        if(!empty($user_meta['billing_company'])) {
                            $billing_info['billing_company'] = $user_meta['billing_company'];
                        }

                        //////////////////////////////////////////

                        if(!empty($user_meta['shipping_first_name'])) {
                            $shipping_info['shipping_first_name'] = $user_meta['shipping_first_name'];
                        }
                        if(!empty($user_meta['shipping_last_name'])) {
                            $shipping_info['shipping_last_name'] = $user_meta['shipping_last_name'];
                        }
                        if(!empty($user_meta['shipping_address_1'])) {
                            $shipping_info['shipping_address_1'] = $user_meta['shipping_address_1'];
                        }
                        if(!empty($user_meta['shipping_address_2'])) {
                            $shipping_info['shipping_address_2'] = $user_meta['shipping_address_2'];
                        }
                        if(!empty($user_meta['shipping_company'])) {
                            $shipping_info['shipping_company'] = $user_meta['shipping_company'];
                        }
                        if(!empty($user_meta['shipping_city'])) {
                            $shipping_info['shipping_city'] = $user_meta['shipping_city'];
                        }
                        if(!empty($user_meta['shipping_state'])) {
                            $shipping_info['shipping_state'] = $user_meta['shipping_state'];
                        }
                        if(!empty($user_meta['shipping_postcode'])) {
                            $shipping_info['shipping_postcode'] = $user_meta['shipping_postcode'];
                        }
                        if(!empty($user_meta['shipping_country'])) {
                            $shipping_info['shipping_country'] = $user_meta['shipping_country'];
                        }
                        if(!empty($user_meta['shipping_county'])) {
                            $shipping_info['shipping_county'] = $user_meta['shipping_county'];
                        }
                        if(!empty($user_meta['shipping_email'])) {
                            $shipping_info['shipping_email'] = $user_meta['shipping_email'];
                        }
                        if(!empty($user_meta['shipping_phone'])) {
                            $shipping_info['shipping_phone'] = $user_meta['shipping_phone'];
                        }
                        if(!empty($user_meta['shipping_company'])) {
                            $billing_info['shipping_company'] = $user_meta['shipping_company'];
                        }

                        ?>
                    </div>



                    <br>
                    <br>
                    <br>

                    <div class="row">
                        <div class="col-lg-6 billing_info">
                            <div class="table-box">
                                <label>Customer billing address</label>
                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="input_field mb-15">
                                            <label>First Name</label>
                                            <input type="text" name="meta[billing_first_name]"
                                                   value="<?php echo $billing_info['first_name'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input_field mb-15">
                                            <label>Last Name</label>
                                            <input type="text" name="meta[billing_last_name]"
                                                   value="<?php echo $billing_info['last_name'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input_field mb-15">
                                            <label>Billing company</label>
                                            <input type="text" name="meta[billing_company]"
                                                   value="<?php echo $billing_info['billing_company'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">

                                    </div>

                                    <div class="col-lg-6 mb-15">
                                        <div class="input_field ">
                                            <label>Postcode</label>

                                            <input role="textbox" autocomplete="address" type="text" name="meta[billing_postcode]" class="postcode_lookup" value="<?php echo $billing_info['billing_postcode'] ?>">

                                            <div class="postcode_lookup_list"></div>

                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="input_field mb-15">
                                            <label>Address line 1</label>
                                            <input type="text" id="billing_address_1" name="meta[billing_address_1]" data-address-1 class="address_lookup address_1"
                                                   value="<?php echo $billing_info['billing_address_1'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input_field mb-15">
                                            <label>Address line 2</label>
                                            <input type="text" id="billing_address_2" name="meta[billing_address_2]" class="address_2"
                                                   value="<?php echo $billing_info['billing_address_2'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input_field mb-15">
                                            <label>Country</label>
                                            <div class="rel">
                                                <select name="meta[billing_country]" id="billing_country_1" data-country class="select2 country" value="<?php echo $billing_info['billing_country'] ?>">
                                                    <option>Select Country</option>
                                                    <?php foreach (get_countries() as $code=>$country) {
                                                        ?>
                                                        <option value="<?php echo $code ?>"><?php echo $country ?></option>
                                                        <?php
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input_field mb-15">
                                            <label>Town/City</label>
                                            <input type="text"  class="town_city" name="meta[billing_city]" data-city id="billing_town_1"
                                                   value="<?php echo $billing_info['billing_city'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input_field mb-15">
                                            <label>County</label>
                                            <input type="text" name="meta[billing_state]" data-state class="state" id="billing_county_1"
                                                   value="<?php echo $billing_info['billing_state'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input_field mb-15">
                                            <label>Phone</label>
                                            <input type="text" name="meta[billing_phone]" id="billing_phone" data-phone
                                                   value="<?php echo $billing_info['billing_phone'] ?>">
                                        </div>
                                    </div>

                                    <?php /*<div class="col-lg-6">
                                        <div class="input_field mb-15">
                                            <label>Postcode</label>
                                            <input type="text" id="billing_postcode" data-postcode name="meta[billing_postcode]"
                                                   value="<?php echo $billing_info['billing_postcode'] ?>">
                                        </div>
                                    </div>*/ ?>

                                    <div class="col-lg-6">
                                        <div class="input_field mb-15">
                                            <label>Email</label>
                                            <input type="text" name="meta[billing_email]" id="billing_email"
                                                   value="<?php echo $billing_info['billing_email'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6 shipping_info">
                            <div class="table-box">
                                <label>
                                    <span>Customer shipping address</span>
                                    <div class="pull-right"><a style="margin: -2px" class="btn btn-sm bg-red color-white" onclick="copy_billing_address();return false" href="">Copy from billing address</a> </div>
                                </label>
                                <div class="row">
                                    <div class="col-lg-6 mb-15">
                                        <div class="input_field ">
                                            <label>First Name</label>
                                            <input type="text" name="meta[shipping_first_name]"
                                                   value="<?php echo $shipping_info['shipping_first_name'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-15">
                                        <div class="input_field ">
                                            <label>Last Name</label>
                                            <input type="text" name="meta[shipping_last_name]"
                                                   value="<?php echo $shipping_info['shipping_last_name'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6 mb-15">
                                        <div class="input_field ">
                                            <label>Shipping company</label>
                                            <input type="text" name="meta[shipping_company]"
                                                   value="<?php echo $shipping_info['shipping_company'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">

                                    </div>

                                    <div class="col-lg-6 mb-15">
                                        <div class="input_field ">
                                            <label>Postcode</label>

                                            <input role="textbox" autocomplete="address" type="text" name="meta[billing_postcode]" class="postcode_lookup" value="<?php echo $billing_info['billing_postcode'] ?>">

                                            <div class="postcode_lookup_list"></div>

                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-15">
                                        <div class="input_field ">
                                            <label>Address line 1</label>
                                            <input type="text" name="meta[shipping_address_1]" class="address_lookup address_1" autocomplete="off"
                                                   value="<?php echo $shipping_info['shipping_address_1'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mb-15">
                                        <div class="input_field ">
                                            <label>Address line 2</label>
                                            <input type="text" name="meta[shipping_address_2]" class="address_2"
                                                   value="<?php echo $shipping_info['shipping_address_2'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input_field mb-15">
                                            <label>Country</label>
                                            <div class="rel">
                                                <select name="meta[shipping_country]" class="select2 country" value="<?php echo $shipping_info['shipping_country'] ?>">
                                                    <option>Select Country</option>
                                                    <?php foreach (get_countries() as $code=>$country) {
                                                        ?>
                                                        <option value="<?php echo $code ?>"><?php echo $country ?></option>
                                                        <?php
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 mb-15">
                                        <div class="input_field ">
                                            <label>Town/City</label>
                                            <input type="text" name="meta[shipping_city]" class="town_city"
                                                   value="<?php echo $shipping_info['shipping_city'] ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="input_field mb-15">
                                            <label>County</label>
                                            <input type="text" name="meta[shipping_state]" class="state"
                                                   value="<?php echo $shipping_info['shipping_state'] ?>">
                                        </div>
                                    </div>





                                    <div class="col-lg-6 mb-15">
                                        <div class="input_field ">
                                            <label>Phone</label>
                                            <input type="text" name="meta[shipping_phone]"
                                                   value="<?php echo $shipping_info['shipping_phone'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-15">
                                        <div class="input_field ">
                                            <label>Email</label>
                                            <input type="text" name="meta[shipping_email]"
                                                   value="<?php echo $shipping_info['shipping_email'] ?>">
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>


                    <div>
                        <div class="pull-left">
                            <button type="submit" class=" btn save">Save changes</button>
                        </div>
                        <div class="pull-right">
                            <?php if(!in_array('administrator',array_keys($user_roles))) { ?>
                                <a href="?delete=1" class="btn save btn-secondary" onclick="return confirm('Are you sure to delete this user?')">Delete User</a>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <?php

            if(!empty($user_row->user_id) && !empty($order_history)) { ?>
                <br>

                <div class="table-box">
                    <label>Order history</label>
                    <table width="100%" class="table">
                        <thead>
                        <tr>
                            <th width="150">Order</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Shipping address</th>
                            <th>Payment method</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($order_history as $order) {

                            ?>
                            <tr>
                                <td><a href="<?php echo admin_url().'orders/edit/'.$order['order_id'] ?>">#<?php echo $order['order_id'] ?></a> </td>
                                <td><?php echo _date($order['order_date']) ?></td>
                                <td><?php echo $order['status'] ?></td>
                                <td><?php echo !empty($order['order_meta']['shipping_address_index']) ? $order['order_meta']['shipping_address_index'] : '' ?></td>
                                <td><?php echo payment_method_map(@$order['payment_method']) ?></td>
                                <td><?php echo _price(@$order['order_meta']['order_total']) ?></td>
                                <td>
                                    <a href="<?php echo admin_url().'orders/edit/'.$order['order_id'] ?>" target="_blank" class="btn btn-primary btn-sm">View order</a>
                                </td>
                            </tr>
                            <?php
                        }?>
                        </tbody>
                    </table>
                </div>

            <?php } ?>


            <script src="https://cdn.getaddress.io/scripts/jquery.getAddress-4.0.0.min.js"></script>

            <style>
                .postcode_lookup_list:not(.open) {
                    display: none;
                }
                .postcode_lookup_list {
                    border: 1px solid #eee;
                    line-height: 2.5;
                    margin-top: -4px;
                    background-color: #fff;
                    font-size: 14px;
                    position: absolute;
                    z-index: 10;
                    width: 100%;
                    box-shadow: var(--drop-shadow);
                    max-height: 320px;
                    overflow: auto;
                }
                .postcode_lookup_list > div {
                    padding: 8px 12px 8px;
                    cursor: pointer;
                    line-height: normal;
                    border-bottom: 1px solid #eee;
                    transition: all 0.2s ease;
                }
                .postcode_lookup_list > div:hover {
                    background-color: var(--red);
                    color: #fff;
                }
            </style>

            <script>
                let copy_billing_address = ()=> {
                    $('[name*=billing_]').each(function() {
                        const ship_name = this.name.replaceAll('billing_','shipping_');
                        $("[name='"+ship_name+"']").val(this.value);
                    }).promise().done(function() {
                        select2_init()
                    });
                }

                let timeout;

                document.querySelectorAll('.postcode_lookup').forEach((input)=>{
                    // const rand = '_'+Math.random().toString().substring(5);
                    //   input.id = rand;
                    const parent = $(input).closest('.table-box');

                    input.addEventListener('keyup', (ele)=>{
                        const value = ele.target.value;
                        clearTimeout(timeout);
                        timeout = setTimeout(()=>{
                            parent.find('.postcode_lookup_list').html('');
                            parent.find('.postcode_lookup_list').removeClass('open');
                            fetch(`<?php echo site_url() ?>addressautocomplete/${value}`).then(res=>res.json()).then((res)=>{
                                if(res.suggestions) {
                                    res.suggestions.forEach((option)=>{
                                        parent.find('.postcode_lookup_list').append(`<div id="${option.id}">${option.address}</div>`);
                                    });
                                    parent.find('.postcode_lookup_list').addClass('open');
                                }
                            });
                        },500);
                    });
                });

                $(document).on('click','.postcode_lookup_list > div', function() {
                    const parent = $(this).closest('.table-box');
                    let addr_id = this.id;
                    fetch(`<?php echo site_url() ?>addressautocomplete/data/${addr_id}`).then(res=>res.json()).then((res)=>{
                        parent.find('.postcode_lookup').val(res.postcode);
                        parent.find('.address_1').val(res.line_1+' '+res.line_2);
                        parent.find('.address_2').val(res.line_3+' '+res.line_4);
                        parent.find('.country').val("GB").trigger("change");
                        parent.find('.town_city').val(res.town_or_city);
                        parent.find('.state').val(res.county);

                        $('.postcode_lookup_list').removeClass('open');
                    });
                });
            </script>

        </form>


    </div>
</div>