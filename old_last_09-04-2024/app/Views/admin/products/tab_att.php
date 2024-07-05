<?php
$k = 0;
$attribute_arr = [];
$attribs_json = [];
$variations_arr = [];

if(!empty($product_row['id'])) {
    $attribes = $productModel->get_attributes($product_row['id']);
    if (!empty($attribes)) {
        foreach ($attribes as $attribute) {
            $attribs_json[] = json_encode($attribute);
            if(!empty($attribute['attribute_variation'])) {
                $label_id = strtolower($attribute['label']);
                $label_id = str_replace(' ','-',$label_id);
                $label_id = 'attribute_'.$label_id;
                $variations_arr[$label_id] = $attribute['value'];
            }
        }
    }
}

?>
<template class="attribute-row">
    <div class="col-md-12 mb-30 attribute-row">
        <fieldset id="">
            <div class="field-title"></div>
            <div class="input_field">
                <input type="text" placeholder="Name" name="attributes[0][label]" value="" onblur="if(this.closest('fieldset').querySelector(':scope .used_for_variation').checked) {usedForVariation(this)}" required>
                <textarea name="attributes[0][value]" placeholder="Value" class="mt-30" onblur="if(this.closest('fieldset').querySelector(':scope .used_for_variation').checked) {usedForVariation(this)}"></textarea>
                <p class="caption">(separate attributes by "|")</p>

                <div style="display: inline-block">
                    <div class="flex-start">
                        <div class="input_field checkbox">
                            <input type="checkbox" class="checkbox" name="attributes[0][attribute_visibility]" onblur="if(this.closest('fieldset').querySelector(':scope .used_for_variation').checked) {usedForVariation(this)}" value="1">
                            <label>Visible on the product page</label>
                        </div>
                    </div>
                </div>
                &nbsp; &nbsp;
                <div style="display: inline-block">
                    <div class="flex-start mb-16" >
                        <div class="input_field checkbox">
                            <input type="checkbox" class="used_for_variation" name="attributes[0][attribute_variation]" onchange="usedForVariation(this)" value="1">
                            <label>Used for variations</label>
                        </div>
                    </div>
                </div>

                <div class="input_field">
                    <button onclick="removenewAttribute(this); return false;" class="btn save bg-black button">Remove</button>
                </div>
            </div>
        </fieldset>
    </div>
</template>



<div class="tabs">
    <ul id="tab-links">
        <li><a href="#tab-1" class="active" title="Attributes">Attributes</a></li>
        <li><a href="#tab-2" title="Variations">Variations</a></li>
    </ul>

    <section id="tab-1" class="active">
        <div id="json_data" style="display:none;"><?php echo !empty($attribes) ? json_encode($attribes) : '' ?></div>
        <div id="attribute_list" class="row">

        </div>
        <br>
        <div class="input_field">
            <button type="button" onclick="addnewAttribute()" class="btn btn-sm save"> Add Attribute</button>
        </div>
    </section>


    <section id="tab-2">
        <div id="variation_list" class="row">
            <?php
            function variation_rows($product_variations=[],$variations_arr=[]) {
                $k = 0;
                foreach($product_variations as $idx=>$variation) {
                    $keys = $variation['keys'];
                    $vdata = $variation['values'];
                    ?>
                    <div class="col-md-12 attribute-row mt-8">
                        <fieldset>
                            <div class="row">
                                <div class="col-md-12 input_field input_variations">
                                    <?php
                                    foreach($keys as $id=>$key) {
                                        if(!empty($variations_arr[$id])) {
                                            $label = str_replace('attribute_','',$id);
                                            $label = str_replace('_',' ',$label);
                                            $label = str_replace('-',' ',$label);
                                            $label = ucfirst($label);
                                            ?>
                                            <div class="attr_variation_row variation_<?php echo $idx ?> variation_<?php echo $id ?>">
                                                <label><?php echo $label ?></label>
                                                <select class="select2" name="variations[<?php echo $idx ?>][keys][<?php echo $id; ?>]" value="<?php echo trim($key) ?>">
                                                    <option value="">Any <?php echo $label ?></option>
                                                    <?php
                                                    foreach ($variations_arr[$id] as $variation) {
                                                        $selected = $key === $variation ? 'selected':'';
                                                        ?>
                                                        <option <?php echo $selected ?> value="<?php echo $variation ?>"><?php echo $variation ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>

                                <div class="clearfix"></div>

                                <div class="col-12">
                                    <br>
                                    <button type="button" class="btn save btn-sm" onclick="toggle_variation_expand(this)">Options</button>
                                    &nbsp; &nbsp;
                                    <button class="mt-20 btn save remove_attr btn-sm" onclick="removenewAttribute(this); return false;" style="background-color: #000">Remove</button>
                                </div>

                                <div class="clearfix"></div>

                                <div class="variation-details col-md-12">
                                    <div class="animated fadeInDown">
                                        <?php /*<div class="variation-close-btn" onclick="variation_close(this)">
                                                       <i class="lni lni-cross-circle text-white" style="color: #fff;padding: 5px;line-height: 2;"></i>
                                                   </div>*/ ?>
                                        <div class="row variation_data_details">
                                            <div class=" col-md-4 input_field mb-15">
                                                <label>SKU</label>
                                                <input type="text" placeholder="sku" name="variations[<?php echo $idx ?>][values][sku]" value="<?php echo !empty($vdata['sku']) ? $vdata['sku'] : '' ?>">
                                            </div>
                                            <div class="col-md-4 input_field mb-15">
                                                <label>Regular price (<?php echo currency_symbol ?>)</label>
                                                <input type="number" step="0.01" name="variations[<?php echo $idx ?>][values][regular_price]" value="<?php echo !empty($vdata['regular_price']) ? $vdata['regular_price'] : '' ?>">
                                            </div>
                                            <div class="col-md-4 input_field mb-15">
                                                <label>Sale Price (<?php echo currency_symbol ?>)</label>
                                                <input type="number" step="0.01" name="variations[<?php echo $idx ?>][values][sale_price]" value="<?php echo !empty($vdata['sale_price']) ? $vdata['sale_price'] : '' ?>">
                                            </div>
                                        </div>

                                        <div class="stock-row">
                                            <div class="variation_data_details">
                                                <div class="input_field mb-15 checkbox">
                                                    <input type="checkbox" class="checkbox" name="variations[<?php echo $idx ?>][values][manage_stock]" value="yes" onchange="this.checked ? $(this).closest('.stock-row').find('.stock-managed').slideDown() : $(this).closest('.stock-row').find('.stock-managed').slideUp()" <?php echo !empty($vdata['manage_stock']) && $vdata['manage_stock']=='yes' ? 'checked':'' ?>>
                                                    <label class="inline">Stock Management</label>
                                                </div>
                                                &nbsp;
                                                <div class="input_field checkbox">
                                                    <input type="checkbox" class="checkbox" name="variations[<?php echo $idx ?>][values][product_level_subscription]" value="yes" onchange="this.checked ? $(this).closest('.row').find('.product-level-sub-box').slideDown() : $(this).closest('.row').find('.product-level-sub-box').slideUp()" <?php echo !empty($vdata['product_level_subscription']) && $vdata['product_level_subscription']=='yes' ? 'checked':'' ?>>
                                                    <label class="inline">Allow subscription plan</label>
                                                </div>
                                            </div>
                                            <div class="stock-managed" style="display: <?php echo (!empty($vdata['manage_stock']) && $vdata['manage_stock'] !== 'yes') ? 'none':'block' ?>">
                                                <div class="row" >
                                                    <div class=" col-md-4 input_field mb-15">
                                                        <label>Stock quantity</label>
                                                        <input type="number" name="variations[<?php echo $idx ?>][values][stock]" value="<?php echo !empty($vdata['stock']) ? $vdata['stock'] : 0 ?>">
                                                    </div>

                                                    <div class=" col-md-4 input_field mb-15">
                                                        <label>Low stock threshold</label>
                                                        <input type="number" placeholder="Low stock threshold" min="1" name="variations[<?php echo $idx ?>][values][low_stock_amount]" value="<?php echo !empty($vdata['low_stock_amount']) ? $vdata['low_stock_amount'] : 1 ?>">
                                                    </div>

                                                    <div class=" col-md-4 input_field mb-15">
                                                        <label>Stock status</label>
                                                        <div>
                                                            <select class="select2 form-control" data-search="false" name="variations[<?php echo $idx ?>][values][stock_status]" value="<?php echo !empty($vdata['stock_status']) ? $vdata['stock_status'] : '' ?>">
                                                                <option value="instock">In Stock</option>
                                                                <option value="outofstock">Out of Stock</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row variation_data_details">

                                            <div class=" col-md-4 input_field mb-15">
                                                <label>Weight (kg)</label>
                                                <input type="number" step="0.01" placeholder="Weight (kg)" name="variations[<?php echo $idx ?>][values][weight]" value="<?php echo !empty($vdata['weight']) ? $vdata['weight'] : 0 ?>">
                                            </div>

                                            <?php if(get_setting('enable_tax_rates')) { ?>
                                                <div class=" col-md-4 input_field mb-15">
                                                    <label>Tax Status</label>
                                                    <div>
                                                        <select class="select2" data-search="false" name="variations[<?php echo $idx ?>][values][tax_status]" value="<?php echo !empty($vdata['tax_status']) ? $vdata['tax_status'] : '' ?>">
                                                            <?php foreach(product_tax_statuses() as $k=>$status) {
                                                                ?>
                                                                <option value="<?php echo $k ?>"><?php echo $status ?></option>
                                                                <?php
                                                            }?>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>

                                        <div class="row">
                                            <div class=" col-md-12 input_field mb-15">
                                                <label>Description</label>
                                                <textarea name="variations[<?php echo $idx ?>][values][description]"><?php echo !empty($vdata['description']) ? stripslashes($vdata['description']) : "" ?></textarea>
                                            </div>
                                        </div>

                                        <div style="padding: 20px 0 40px;">
                                            <button type="button" class="btn save btn-sm bg-black" onclick="variation_close(this)">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <br>
                    </div>
                    <?php
                    $k++;
                }
            }
            ?>


            <?php
            if (!empty($product_row['attributes']) && !empty($product_variations)) {
                $attributes = $productModel->get_attributes($product_row['id']);
                variation_rows($product_variations,$variations_arr);
            }
            else {
                $product_variations=[[
                    'keys'=>$variations_arr,
                    'values'=>''
                ]];
                variation_rows($product_variations,$variations_arr);
            }
            ?>

        </div>

        <br>
        <div class="input_field d-inline-block" style="display: inline-block">
            <button type="button" onclick="addnewVariation()" class="btn btn-sm save"> Add Variation</button>
        </div>




    </section>


</div>

<style>
    section#tab-2 {
        background-color: #fff;
    }
    #variation_list > div > .row {
        background-color: #f9f9f9;
        padding: 20px 0px;
        border-radius: 5px;
    }
    div#variation_list .attribute-row:first-child .remove_attr {
        display: none;
    }
    #tab-2 > * {
        display: none;
    }
    #tab-2:has(.attr_variation_row) > * {
        display: block;
    }
    fieldset {
        margin-bottom: 0;
    }
    .tabs {
        overflow: visible;
    }
</style>

<script>
    function toggle_variation_expand(_this) {
        $(_this).closest('#variation_list').find('.variation-details').removeClass('variation-open');
        $('#varation-open-overlay').remove();

        // $(_this).closest('.attribute-row').find('.variation-details').slideDown();
        $(_this).closest('.attribute-row').find('.variation-details').addClass('variation-open');
        $(_this).closest('.attribute-row').find('.variation-details').after('<div id="varation-open-overlay"></div>');
    }

    function variation_close(_this) {
        $('.variation-details.variation-open').removeClass('variation-open');
        $('#varation-open-overlay').remove();
    }

    function usedForVariation(ele={}) {

        const data = $('#attribute_list').find('input,select,textarea').serialize();
        let input_field = $(ele).closest('fieldset');

        let label = $(input_field).find('input[name*=label]').val();
        const val = $(input_field).find('textarea[name*=value]').val();

        const field_idx = input_field.parent().index();

        if(!label) {
            label = '';
        }

        let label_id = label.toLowerCase();
        label_id = label_id.replaceAll(' ','-');
        label_id = 'attribute_'+label_id;

        const idx =  field_idx;

        if(!$(input_field).find('.used_for_variation').is(':checked')) {
            $('#variation_list .variation_'+label_id).remove();
        }else {
            if( $('#variation_list .variation_'+label_id).length) {
                $('#variation_list .variation_'+label_id).remove();
            }
            let variation_html = `<div class="attr_variation_row variation_vidx variation_${label_id}">`;
            if(label) {
                variation_html += '<label>'+label+'</label>';
                variation_html += '<select class="select2" name="variations-vidx[keys]['+label_id+']">';
                variation_html += '<option value="">Any '+label+'</option>';
                const val_options = val.split('|');
                for(let i in val_options) {
                    const v = val_options[i];
                    variation_html += '<option value="'+v+'">'+v+'</option>';
                }
                variation_html += '</select>';
                variation_html += '</div>';

                $('#variation_list .input_variations').append(variation_html);
                $('select.select2:not(.select2-hidden-accessible)').select2();
            }
            resetVariationIndex();
        }

        <?php /*if(!empty($product_row['id'])) {
            ?>
        const url = '<?php echo base_url('admin/products/update_variations/'.$product_row['id']) ?>';
        // $('.tabs').addClass('loading');
        $.post(url,data, function(res) {
            if(res) {
                $('.tabs').load(location.href + " .tabs > *", function(tabdata) {
                    $('#attribute_list').html('');
                    let json_text = $(tabdata).find('#json_data').text();
                    if(json_text) {
                        let attr_json = JSON.parse(json_text);
                        attr_json.forEach((json)=>{
                            template_row_init(json);
                        });
                        resetAttributeIndex();
                    }
                    // $('.tabs').removeClass('loading');
                    tabLinksInit();
                    $('select[value]').each(function() {
                        this.value = this.getAttribute('value');
                    });
                    $('.select2').select2();
                });
            }
        });
            <?php
    }else {
         ?>

        <?php
    }*/ ?>
    }

    function resetAttributeIndex() {
        $('#attribute_list').children().each(function(idx) {
            const label = 'Attribute '+ (idx+1);
            const html = this;
            $(html).find('.field-title').text(label);
            $(html).find('fieldset').attr('id','attribute-'+idx);

            $(html).find('[name*=attributes]').each(function() {
                let _name = $(this).attr('name');
                _name = _name.replace('attributes[0]','attributes['+idx+']');
                $(this).attr('name',_name);
            });
        });
    }

    function resetVariationIndex() {
        $('#variation_list').find('.variation_vidx').each(function() {
            const idx = $(this).closest('.attribute-row').index();
            $(this).addClass('variation_'+idx);
            $(this).removeClass('variation_vidx')
        });
        $('#variation_list').find('[name*=variations-vidx]').each(function() {
            const idx = $(this).closest('.attribute-row').index();
            const name = this.name.replace('variations-vidx','variations['+idx+']');
            this.name = name;
        });
    }

    function template_row_init(data={}) {
        const tmp = document.getElementsByClassName('attribute-row')[0];
        const template = tmp.content.cloneNode(true);
        template.querySelector('[name*=label]').value = data.label;
        template.querySelector('[name*=value]').value = data.value.join(' | ');
        template.querySelector('[name*=attribute_variation]').checked = data.attribute_variation;
        template.querySelector('[name*=attribute_visibility]').checked = data.attribute_visibility;
        document.getElementById('attribute_list').appendChild(template);
    }

    <?php

    if(!empty($attribs_json)) {
    foreach($attribs_json as $value) { ?>
    template_row_init(<?php echo $value ?>);
    <?php }
    }
    ?>

    resetAttributeIndex();

    function addnewAttribute(idx=0) {
        if($('#attribute_list').children().length) {
            let html = $('#attribute_list').children(':first-child').clone();
            $(html).addClass('animated fadeInDown');
            $(html).find('input,select,textarea').val('');
            $(html).find('input:checked').prop('checked', false);
            $(html).find('input:checked').removeAttr('checked');
            $('#attribute_list').append(html);
            resetAttributeIndex();
            $('html,body').animate({scrollTop: $('#attribute_list').children(':last-child').offset().top});
        }else {
            template_row_init({
                label: '',
                value:[],
                attribute_variation:0,
                attribute_visibility: 0
            });
            resetAttributeIndex();
        }
    }


    function removenewAttribute(_this) {
        let msgtext = 'Remove this attribute?';
        const row = $(_this).closest('.attribute-row');
        if($(row).find('.used_for_variation').is(':checked')) {
            msgtext = 'Remove this attribute and variation options?';
        }
        Swal.fire({
            icon: 'question',
            title: msgtext,
            showCancelButton: true
        }).then((result) => {
            if(result.isConfirmed) {
                let input_field = $(_this).closest('.attribute-row');
                const row_id = input_field.index();
                const label = $(input_field).find('input[name*=label]').val();
                if(label) {
                    let label_id = label.toLowerCase();
                    label_id = label_id.replaceAll(' ', '-');
                    $('#variation_list').find('.variation_attribute_' + label_id).remove();
                }
                $(_this).closest('.attribute-row').remove();
                usedForVariation();
                resetAttributeIndex();
            }
        });
    }

    function addnewVariation() {
        let template = $('#variation_list').children(':first-child');
        const idx = $('#variation_list').children().length;
        template = template.clone();
        $(template).find('input,select,textarea').val('');
        $(template).addClass('animated fadeInDown');
        $(template).find('input[checked]').prop('checked',false);
        $(template).find('input[checked]').removeAttr('checked');
        $(template).find('.select2-container').remove();
        $(template).find('select.select2-hidden-accessible').removeClass('select2-hidden-accessible');
        $(template).find('[name*=variation]').each(function() {
            const name = $(this).attr('name');
            const newName = name.replaceAll('variations[0]','variations['+idx+']');
            $(this).attr('name',newName);
        }).promise().done(function() {
            $('#variation_list').append(template);
            $('#variation_list').children(':last-child').find('select.select2').select2();
            $('html,body').animate({scrollTop: $('#variation_list').children(':last-child').offset().top});
        });

    }
</script>