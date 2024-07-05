<section id="tab-ship-methods">

    <form action="<?php echo base_url(ADMIN . '/settings') ?>"
          method="post"
          enctype="multipart/form-data">

        <div class="table-box">
            <label>Shipping settings</label>
            <table class="field_table">
                <tr>
                    <th>Enable shipping calculator on basket page</th>
                    <td>
                        <div class="input_field p-2 pt-10 inline-checkbox">
                            <label> <input type="checkbox" class="w-auto" value="1" name="enable_shipping_calculator" <?php echo get_setting('enable_shipping_calculator') ? 'checked':'' ?>></label>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="table-box">
            <label>Shipping Methods</label>
            <div class="row mt-30" style="width:80%">
                <div id="shippiongmethods" class="col-lg-12">
                    <?php

                    if (empty($shipping_methods)) {
                        ?>
                        <div class="row method-row">
                            <div class="col-md-4">
                                <div class="input_field">
                                    <label>Name</label>
                                    <input type="text" name="shippingmethods[name][]" value="">
                                </div>
                            </div>
                            <div class="col-md-4" style="position: relative">
                                <div class="input_field">
                                    <label>Amount</label>
                                    <input type="text" name="shippingmethods[amount][]" value="">
                                </div>
                                <span class="remove_method" onclick="$(this).closest('.method-row').remove()"><i class="lni lni-close"></i></span>
                            </div>

                        </div>
                        <?php
                    }
                    else {
                        foreach ($shipping_methods as $i => $method) {
                            $method_name = $method['name'];
                            $method_amount = $method['value'];
                            ?>
                            <div class="row method-row">
                                <div class="col-md-3">
                                    <div class="input_field">
                                        <label>Name</label>
                                        <input type="text" name="shippingmethods[name][]"
                                               value="<?php echo $method_name ?>">
                                    </div>
                                </div>
                                <div class="col-md-3" style="position: relative">
                                    <div class="input_field">
                                        <label>Amount</label>
                                        <input type="text" name="shippingmethods[value][]"
                                               value="<?php echo $method_amount ?>">
                                    </div>
                                    <span class="remove_method" onclick="console.log($(this).closest('.method-row'));$(this).closest('.method-row').remove()"><i class="lni lni-close"></i></span>
                                </div>

                            </div>
                        <?php }
                    }
                    ?>
                    <script>
                        function append_methods() {
                            jQuery(function () {
                                const first_el = $('#shippiongmethods').children(':first-child').clone();
                                first_el.find('*').val('');
                                $('#shippiongmethods').append(first_el);
                            })
                        }
                    </script>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 btn_bar flex_space">
                    <button type="button" class="btn save btn-sm" onclick="append_methods()">Add Method</button>
                </div>
            </div>
        </div>

        <div class="table-box">
            <label>Shipping Rules</label>

            <?php
            function get_shipping_rules() {
                return [
                    'User'=>[
                        'user'=>[
                            'label'=>'User',
                            'cond'=>[
                                'equal_to' => 'Equal to',
                                'not_equal_to' =>'Not equal to'
                            ]
                        ],
                        'user_role'=>[
                            'label'=>'User role',
                            'cond'=>[
                                'equal_to' => 'Equal to',
                                'not_equal_to' =>'Not equal to'
                            ]
                        ]
                    ],
                    'Product'=>[
                        'product_id'=>[
                            'label'=>'Product',
                            'cond'=>[
                                'equal_to' => 'Includes',
                                'not_equal_to' =>'Doesn\'t include'
                            ]
                        ],
                        'product_category'=>[
                            'label'=>'Product category',
                            'cond'=>[
                                'equal_to' => 'Includes',
                                'not_equal_to' =>'Doesn\'t include'
                            ]
                        ]
                    ],
                    'Cart'=>[
                        'subtotal'=>[
                            'label'=>'Subtotal',
                            'cond'=>[
                                'equal_to' => 'Equal to',
                                'greater_than' =>'Greater than',
                                'less_than' =>'Less than',
                            ]
                        ],
                        'quantity'=>[
                            'label'=>'Total quantity',
                            'cond'=>[
                                'equal_to' => 'Equal to',
                                'not_equal_to' =>'Not equal to'
                            ]
                        ],
                        'tax'=>[
                            'label'=>'Total tax',
                            'cond'=>[
                                'equal_to' => 'Equal to',
                                'not_equal_to' =>'Not equal to'
                            ]
                        ]
                    ]
                ];
            }

            function rule_templates($data=[]) {
                if(empty($data['option_data'])) {
                ?>
                <div>
                    <select class="select2 rule_subject" name="shipment_rule[x1][subject][x2]" onchange="getRuleConditions(this)">
                        <option value="">Select option</option>
                        <?php
                        foreach(get_shipping_rules() as $label=>$rules) {
                            ?>
                            <optgroup label="<?php echo $label ?>">
                                <?php foreach($rules as $id=>$rule) {
                                    ?>
                                    <option value="<?php echo $id ?>"><?php echo !empty($rule['label']) ? $rule['label'] : '' ?></option>
                                    <?php
                                }?>
                            </optgroup>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <select class="select2 rule_conditions" name="shipment_rule[x1][condition][x2]">

                    </select>
                </div>

                <div>
                    <div class="rule_values">

                    </div>
                </div>
                <div>
                    <div>
                        <button type="button" onclick="$(this).closest('.rule-row').remove()" class="btn-danger color-base bg-transparent btn btn-sm"><i class="lni lni-close"></i></button>
                        <button type="button" class="btn-primary btn btn-sm add_condition_btn" onclick="addRuleCondtion(this)">Add Condition</button> </div>
                </div>
                <?php
                }
                else {
                    $get_users = [];
                    $get_user_rules = [];

                    $rowIdx = $data['idx'];

                    foreach($data['option_data'] as $optidx=>$option) {
                        $subject = $option['subject'];
                        $condition = $option['condition'];
                        $value = $option['value'];

                        $shipping_rules = get_shipping_rules();

                        $get_condition = [];

                        foreach($shipping_rules as $rule) {
                            if(!empty($rule[$subject])) {
                                $get_condition = $rule[$subject]['cond'];
                                break;
                            }
                        }

                        $rule_input = '';

                        if(!empty($db_users) && $subject === "user") {
                            $rule_input = '<select class="form-control" name="shipment_rule['.$rowIdx.'][value]['.$optidx.']" value="'.$value.'">';
                                foreach($db_users as $user) {
                                    $name = !empty($user->display_name) ? $user->display_name : $user->fname.' '.$user->lname;
                                    $rule_input .= '<option value="'.$user->id.'">'.$name.'</option>';
                                }
                            $rule_input .= '</select>';
                        }

                        if(!empty($data['db_user_roles']) && $subject === "user_role") {
                            $rule_input = '<select class="form-control" name="shipment_rule['.$rowIdx.'][value]['.$optidx.']" value="'.$value.'">';
                                foreach($data['db_user_roles'] as $role) {
                                    $rule_input .= '<option value="'.$role->id.'">'.$role->name.'</option>';
                                }
                            $rule_input .= '</select>';
                        }

                        if(!empty($data['db_products']) && $subject === "product_id") {
                            $rule_input = '<select class="form-control" name="shipment_rule['.$rowIdx.'][value]['.$optidx.']" value="'.$value.'">';
                            foreach($data['db_products'] as $product) {
                                $rule_input .= '<option value="'.$product->id.'">'.$product->name.'</option>';
                            }
                            $rule_input .= '</select>';
                        }

                        if(!empty($data['db_product_categories']) && $subject === "product_category") {
                            $rule_input = '<select class="form-control" name="shipment_rule['.$rowIdx.'][value]['.$optidx.']" value="'.$value.'">';
                            foreach($data['db_product_categories'] as $category) {
                                $rule_input .= '<option value="'.$category->id.'">'.$category->name.'</option>';
                            }
                            $rule_input .= '</select>';
                        }

                        if($subject === "product_stock" || $subject === "subtotal" || $subject === "quantity" || $subject === "tax") {
                            $rule_input .= '<input type="number" name="shipment_rule['.$rowIdx.'][value]['.$optidx.']" value="'.$value.'" class="form-control">';
                        }

                        if($subject === "zipcode" || $subject === "city" || $subject === "state") {
                            $rule_input .= '<input type="text" name="shipment_rule['.$rowIdx.'][value]['.$optidx.']" value="'.$value.'" class="form-control">';
                        }

                        ?>
                        <div class="rule-row">
                            <div>
                            <select class="form-control rule_subject" name="shipment_rule[<?php echo $rowIdx ?>][subject][<?php echo $optidx ?>]" onchange="getRuleConditions(this)" value="<?php echo $subject ?>">
                            <option value="">Select option</option>
                            <?php
                            foreach(get_shipping_rules() as $label=>$rules) {
                                ?>
                                <optgroup label="<?php echo $label ?>">
                                    <?php foreach($rules as $id=>$rule) {
                                        ?>
                                        <option value="<?php echo $id ?>"><?php echo !empty($rule['label']) ? $rule['label'] : '' ?></option>
                                        <?php
                                    }?>
                                </optgroup>
                                <?php
                            }
                            ?>
                            </select>
                            </div>

                            <div>
                            <select class="form-control rule_conditions" name="shipment_rule[<?php echo $rowIdx ?>][condition][<?php echo $optidx ?>]" value="<?php echo $condition ?>">
                            <?php
                            foreach($get_condition as $k=>$condition) {
                                ?>
                                <option value="<?php echo $k ?>"><?php echo $condition ?></option>
                                <?php
                            }?>
                            </select>
                            </div>

                            <div>
                            <div class="rule_values">
                            <?php echo $rule_input ?>
                            </div>
                            </div>

                            <div>
                                <button type="button" class="btn-primary btn btn-sm add_condition_btn" onclick="addRuleCondtion(this)">Add Condition</button>
                                <button type="button" onclick="$(this).closest('.rule-row').remove()" class="btn-danger color-base bg-transparent btn btn-sm"><i class="lni lni-close"></i></button>
                            </div>
                        </div>
                        <?php
                    }

                }
            }
            ?>

            <template id="rule_template">
                <?php
                    rule_templates();
                ?>
            </template>

            <div id="rule_list">
                <?php
                $shipment_rules = get_setting('shipment_rule',true);
                if(!empty($shipment_rules)) {
                    foreach($shipment_rules as $i=>$rule) {
                        $rule['db_user_roles'] = !empty($db_user_roles) ? $db_user_roles : [];
                        $rule['db_products'] = !empty($db_products) ? $db_products : [];
                        $rule['db_product_categories'] = !empty($db_product_categories) ? $db_product_categories : [];
                        $rule['idx'] = $i;


                        $option_name = $rule['option_name'];
                        $option_value = $rule['option_value'];
                        $option_type = $rule['option_type'];
                        ?>
                        <fieldset data-index="<?php echo $i ?>">

                            <div class="close-btn">
                                <button type="button" onclick="$(this).closest('fieldset').remove()" class="btn-danger color-base bg-transparent btn btn-sm"><i class="lni lni-close"></i></button>
                            </div>

                            <div class="input_field rule-group">
                        <?php
                         rule_templates($rule);
                        ?>
                            </div>

                         <hr style="margin-top: 1em;padding-bottom: 1em;">

                        <div class="rule-options input_field">
                            <div>
                                <select class="form-control" name="shipment_rule[<?php echo $i ?>][option_name]" value="<?php echo $option_name ?>">
                                    <option value="add_cost">Shipping add cost</option>
                                    <option value="discount">Shipping discount</option>
                                </select>
                            </div>

                            <div>
                                <input type="number" name="shipment_rule[<?php echo $i ?>][option_value]" class="form-control" value="<?php echo $option_value ?>">
                            </div>
                            <div>
                                <div class="input_field checkbox">
                                    <input type="radio" value="percent" name="shipment_rule[<?php echo $i ?>][option_type]" <?php echo $option_type === "percent" ? 'checked':'' ?>>
                                    <label class="checkbox-hide">%</label>
                                </div>
                                <div class="input_field checkbox">
                                    <input type="radio" value="fixed" name="shipment_rule[<?php echo $i ?>][option_type]" value="<?php echo $option_type === "fixed" ? 'checked':'' ?>">
                                    <label class="checkbox-hide">=</label>
                                </div>
                            </div>
                        </div>


                        </fieldset>
                        <?php
                    }
                }
                ?>
            </div>

            <button type="button" id="add_rule_btn" onclick="add_rule()" class="btn btn-primary btn-sm">Add Group</button>


        </div>

        <style>
            .rule-row {
                display: flex;
            }
            .add_condition_btn {
                visibility: hidden;
            }
            .rule-row:last-child .add_condition_btn {
                visibility: visible;
            }
            .rule-options {
                display: flex;
            }
            fieldset {
                position: relative;
            }
            fieldset .btn-danger {
                margin-left: 1em;
            }
            fieldset .close-btn {
                position: absolute;
                right: 1em;
                z-index: 10;
            }
            .rule_subject {
                width: 190px;
            }
            .rule_conditions {
                width: 140px;
            }
            .rule_values {
                width: 210px;
            }
        </style>

        <script>
            const rules = <?php echo json_encode(get_shipping_rules()) ?>;
            const rules_array = Object.keys(rules).map((key) => [rules[key]][0]);


            const add_rule = ()=> {
                let template = document.querySelector('#rule_template').content.cloneNode(true);
                const rule_list = document.querySelector('#rule_list');
                const parentIdx = $('#rule_list').children().length;
                const idx = rule_list.childNodes.length - 1;
                template.querySelectorAll('[name]').forEach((ele)=>{
                    let name = ele.name;
                    name = name.replaceAll('[x2]','['+idx+']');
                    name = name.replaceAll('[x1]','['+parentIdx+']');
                    ele.name = name;
                });
                let div = document.createElement('div');
                div.append(template);

                const shippingOptions = `<div class="rule-options input_field">
                    <div>
                        <select class="form-control" name="shipment_rule[${parentIdx}][option_name]">
                            <option value="add_cost">Shipping add cost</option>
                            <option value="discount">Shipping discount</option>
                        </select>
                    </div>

                    <div>
                        <input type="number" name="shipment_rule[${parentIdx}][option_value]" class="form-control">
                    </div>
                    <div>
                        <div class="input_field checkbox">
                            <input type="radio" value="percent" name="shipment_rule[${parentIdx}][option_type]">
                            <label class="checkbox-hide">%</label>
                        </div>
                        <div class="input_field checkbox">
                            <input type="radio" value="fixed" name="shipment_rule[${parentIdx}][option_type]">
                            <label class="checkbox-hide">=</label>
                        </div>
                    </div>
                </div>`;

                $('#rule_list').append(`<fieldset data-index="${idx}">
                <div class="input_field rule-group">
                    <div class="rule-row">${div.innerHTML}</div>
                </div>

                <hr style="margin-top: 1em;padding-bottom: 1em;">

                ${shippingOptions}
                </fieldset>`);
            }

            const addRuleCondtion = (ele)=> {
                let template = document.querySelector('#rule_template').content.cloneNode(true);
                const parent = ele.closest('.rule-group');
                const idx = $(parent).children().length;
                const parentIdx = $(parent).closest('fieldset').data('index');

                template.querySelectorAll('[name]').forEach((ele)=>{
                    let name = ele.name;
                    name = name.replaceAll('[x2]','['+idx+']');
                    name = name.replaceAll('[x1]','['+parentIdx+']');
                    ele.name = name;
                });

                let div = document.createElement('div');
                div.append(template);

                const appendHTML = `<div class="rule-row">${div.innerHTML}</div>`;

                $(parent).append(appendHTML);
            }

            const getRuleConditions = (input)=> {
                const parent = input.closest('.rule-row');
                const idx = $(parent).index();
                const parentIdx = $(parent).closest('fieldset').data('index');
                const value = input.value;
                const conditionSelect = parent.querySelector('.rule_conditions');
                const ruleValue = parent.querySelector('.rule_values');

                conditionSelect.value = '';
                conditionSelect.innerHTML = '';
                ruleValue.innerHTML = '';

                rules_array.forEach((rules)=>{
                    for(let key in rules) {
                        if(key === value) {
                            for(let c in rules[key].cond) {
                                let optionElement = document.createElement('option');
                                optionElement.value = c;
                                optionElement.text = rules[key].cond[c];
                                parent.querySelector('.rule_conditions').appendChild(optionElement);
                            }
                            return false;
                        }
                    }
                });

                console.log(idx);

                if(value === 'user') {
                    let ruleValueSelect = document.createElement('select');
                    ruleValueSelect.classList.add("select2");
                    ruleValueSelect.name = 'shipment_rule['+parentIdx+'][value]['+idx+']';
                    getUserListOptions(ruleValueSelect);
                    ruleValue.appendChild(ruleValueSelect);
                }

                if(value === 'user_role') {
                    let ruleValueSelect = document.createElement('select');
                    ruleValueSelect.name = 'shipment_rule['+parentIdx+'][value]['+idx+']';
                    getUserRolesOptions(ruleValueSelect);
                    ruleValue.appendChild(ruleValueSelect);
                }

                if(value === 'product_id') {
                    let ruleValueSelect = document.createElement('select');
                    ruleValueSelect.name = 'shipment_rule['+parentIdx+'][value]['+idx+']';
                    getProductOptions(ruleValueSelect);
                    ruleValue.appendChild(ruleValueSelect);
                }

                if(value === 'product_category') {
                    let ruleValueSelect = document.createElement('select');
                    ruleValueSelect.name = 'shipment_rule['+parentIdx+'][value]['+idx+']';
                    getProductCatOptions(ruleValueSelect);
                    ruleValue.appendChild(ruleValueSelect);
                }

                if(value === 'product_stock' || value === 'subtotal'
                    || value === 'quantity' || value === 'tax') {
                    let ruleValueInput = document.createElement('input');
                    ruleValueInput.type = 'number';
                    ruleValueInput.classList.add('form-control');
                    ruleValueInput.name = 'shipment_rule['+parentIdx+'][value]['+idx+']';
                    ruleValue.appendChild(ruleValueInput);
                }

                if(value === 'zipcode' || value === 'city' || value === 'state') {
                    let ruleValueInput = document.createElement('input');
                    ruleValueInput.type = 'text';
                    ruleValueInput.classList.add('form-control');
                    ruleValueInput.name = 'shipment_rule['+parentIdx+'][value]['+idx+']';
                    ruleValue.appendChild(ruleValueInput);
                }
            }

            const getUserListOptions = (select)=> {
                select.innerHTML = '';
                select.value = '';
                fetch('<?php echo admin_url() ?>ajax/user_list_json').then(res=>res.json()).then(res=>{
                    if(res.length) {
                        res.forEach(result=>{
                            let option = document.createElement('option');
                            const display_name = result.display_name || `${result.fname} ${result.lname}`;
                            option.text = `${display_name} (${result.email})`;
                            option.value = result.user_id;
                            select.appendChild(option);
                        })
                    }
                });
            }

            const getUserRolesOptions = (select)=> {
                select.innerHTML = '';
                select.value = '';
                fetch('<?php echo admin_url() ?>ajax/user_roles_json').then(res=>res.json()).then(res=>{
                    if(res.length) {
                        res.forEach(result=>{
                            let option = document.createElement('option');
                            option.text = result.name;
                            option.value = result.id;
                            select.appendChild(option);
                        })
                    }
                });
            }

            const getProductOptions = (select)=> {
                select.innerHTML = '';
                select.value = '';
                fetch('<?php echo admin_url() ?>ajax/product_list_json').then(res=>res.json()).then(res=>{
                    if(res.length) {
                        res.forEach(result=>{
                            let option = document.createElement('option');
                            option.text = result.title;
                            option.value = result.id;
                            select.appendChild(option);
                        })
                    }
                });
            }

            const getProductCatOptions = (select)=> {
                select.innerHTML = '';
                select.value = '';

                fetch('<?php echo admin_url() ?>ajax/product_categories_json').then(res=>res.json()).then(res=>{
                    if(res.length) {
                        res.forEach(result=>{
                            let option = document.createElement('option');
                            option.text = result.name;
                            option.value = result.id;
                            select.appendChild(option);
                        })
                    }
                });
            }

        </script>

        <div class="mt-22"></div>

        <div class="row footer">
            <div class="col-lg-12 btn_bar flex_space">
                <input data-tab-current-url type="hidden" name="current_url" value="<?php echo current_url() ?>">
                <button type="submit" class=" btn save btn-sm">Save changes</button>
            </div>
        </div>

    </form>

</section>