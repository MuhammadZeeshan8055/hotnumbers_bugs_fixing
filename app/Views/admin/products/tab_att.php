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
    <div class="col-md-12 mb-10 attribute-row">
        <div class="table-box">
            <label class="field-title"></label>
            <a href="#" onclick="removenewAttribute(this); return false;" class="remove-row btn btn-sm bg-black color-white">Delete</a>

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
                <div style="display: inline-block">
                    <div class="flex-start mb-16" >
                        <div class="input_field checkbox">
                            <input type="checkbox" class="used_for_variation" name="attributes[0][first_value_default]" onchange="first_value_default(this)" value="1">
                            <label>Set first value as default</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<div id="product-attributes" class="input_field">
    <div>
        <div class="table-box">
            <label>Product Attributes
            </label>
            <div class="row">
                <div class="col-md-12">
                    <div id="json_data" style="display:none;"><?php echo !empty($attribes) ? json_encode($attribes) : '' ?></div>
                    <div id="attribute_list" class="row">

                    </div>
                    <br>
                    <div class="input_field">
                        <button type="button" onclick="addnewAttribute()" class="btn btn-sm save"> Add Attribute</button>
                    </div>
                </div>


            </div>
        </div>

        <div class="table-box">
            <label>Product Variations</label>
            <div class="col-md-12">
                <div id="variation_list">
                    <?php
                    function variation_rows($product_variations=[],$variations_arr=[]) {
                        $k = 0;
                        foreach($product_variations as $idx=>$variation) {
                            $keys = $variation['keys'];
                            $vdata = $variation['values'];
                            ?>
                            <div class="attribute-row">
                                <div class="table-box">
                                    <label>Variation</label>
                                    <a href="#" onclick="removenewAttribute(this); return false;" class="remove-row btn btn-sm bg-black color-white">Delete</a>

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
                                                    <div class="attr_variation_row input_field d-inline-block variation_<?php echo $idx ?> variation_<?php echo $id ?>">
                                                        <label><?php echo $label ?></label>
                                                        <div>
                                                            <select class="select2" name="variations[<?php echo $idx ?>][keys][<?php echo $id; ?>]">
                                                                <option value="">Any <?php echo $label ?></option>
                                                                <?php
                                                                foreach ($variations_arr[$id] as $variation) {
                                                                    // Trim both $key and $variation to remove any trailing/leading spaces
                                                                    $selected = (trim($key) === trim($variation)) ? 'selected' : '';
                                                                    ?>
                                                                    <option <?php echo $selected ?> value="<?php echo trim($variation) ?>"><?php echo trim($variation) ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>

                                        <div class="clearfix"></div>

                                        <div class="variation-details col-md-12">
                                            <div>
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
                                                        <input type="number" placeholder="Required" step="0.01" min="0" name="variations[<?php echo $idx ?>][values][regular_price]" value="<?php echo !empty($vdata['regular_price']) ? $vdata['regular_price'] : '' ?>">
                                                    </div>
                                                    <div class="col-md-4 input_field mb-15">
                                                        <label>Sale Price (<?php echo currency_symbol ?>)</label>
                                                        <input type="number" step="0.01" min="0" name="variations[<?php echo $idx ?>][values][sale_price]" value="<?php echo !empty($vdata['sale_price']) ? $vdata['sale_price'] : '' ?>">
                                                    </div>
                                                </div>

                                                <div class="stock-row">
                                                    <div class="variation_data_details">
                                                        <div class="input_field mb-15 checkbox">
                                                            <input type="checkbox" class="checkbox" name="variations[<?php echo $idx ?>][values][manage_stock]" value="yes" onchange="this.checked ? $(this).closest('.stock-row').find('.stock-managed').slideDown() : $(this).closest('.stock-row').find('.stock-managed').slideUp()" <?php echo !empty($vdata['manage_stock']) && @$vdata['manage_stock']=='yes' ? 'checked':'' ?> <?php echo (!empty($vdata['manage_stock']) && $vdata['manage_stock'] == 'yes') ? 'checked':'' ?>>
                                                            <label class="inline">Stock Management</label>
                                                        </div>
                                                        &nbsp;
                                                        <div class="input_field checkbox">
                                                            <input type="checkbox" class="checkbox" name="variations[<?php echo $idx ?>][values][product_level_subscription]" value="yes" onchange="this.checked ? $(this).closest('.row').find('.product-level-sub-box').slideDown() : $(this).closest('.row').find('.product-level-sub-box').slideUp()" <?php echo !empty($vdata['product_level_subscription']) && $vdata['product_level_subscription']=='yes' ? 'checked':'' ?>>
                                                            <label class="inline">Allow subscription plan</label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="stock-managed" style="display: <?php echo (empty($vdata['manage_stock']) || @$vdata['manage_stock'] !== 'yes') ? 'none':'block' ?>">
                                                        <div class="row" >
                                                            <div class=" col-md-4 input_field mb-15">
                                                                <label>Stock quantity</label>
                                                                <input type="number" placeholder="Same as parent" min="0" name="variations[<?php echo $idx ?>][values][stock]" value="<?php echo !empty($vdata['stock']) ? $vdata['stock'] : "" ?>">
                                                            </div>

                                                            <div class=" col-md-4 input_field mb-15">
                                                                <label>Low stock threshold</label>
                                                                <input type="number" placeholder="Low stock threshold" min="1" name="variations[<?php echo $idx ?>][values][low_stock_amount]" value="<?php echo !empty($vdata['low_stock_amount']) ? $vdata['low_stock_amount'] : "" ?>">
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
                                                                    <option value="" selected>Same as parent</option>
                                                                    <?php foreach(product_tax_statuses() as $k=>$status) {
                                                                        ?>
                                                                        <option value="<?php echo $k ?>"><?php echo $status ?></option>
                                                                        <?php
                                                                    }?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $tax_classes = get_setting('tax_classes');
                                                        if(!empty($tax_classes)) {
                                                            ?>
                                                            <div class=" col-md-4 input_field mb-15">
                                                                <label>Tax Class</label>
                                                                <div>
                                                                    <select class="select2" data-search="false" name="variations[<?php echo $idx ?>][values][tax_class]" value="<?php echo !empty($vdata['tax_class']) ? $vdata['tax_class'] : '' ?>">
                                                                        <option value="" selected>Same as parent</option>
                                                                        <option value="standard">Standard</option>
                                                                        <?php
                                                                        if(!empty($tax_classes)) {
                                                                            foreach(explode("\n",$tax_classes) as $tax_class) {
                                                                                $key = strtolower($tax_class);
                                                                                $key = str_replace(' ','_',$key);
                                                                                $key = trim($key);
                                                                                ?>
                                                                                <option value="<?php echo $key ?>"><?php echo $tax_class ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>

                                                </div>

                                                <div class="row">
                                                    <div class=" col-md-12 input_field mb-15">
                                                        <label>Description</label>
                                                        <textarea name="variations[<?php echo $idx ?>][values][description]" style="min-height: initial"><?php echo !empty($vdata['description']) ? stripslashes($vdata['description']) : "" ?></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            </div>
        </div>
    </div>
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

    .remove-row {
        position: absolute;
        right:10px;
        top: 5px;
    }
    .table-box {
        position: relative;
    }

    #product-attributes > div > .table-box {
        position: relative;
    }
    div#variation_list > div > .table-box {
        border: 1px solid #c7c7c7;
    }
    .attr_variation_row {
        margin-right: 1em;
    }
    .select2.select2-container.select2-container--default {
        min-width: 150px;
    }
</style>

<script>

    let attributeIndex = 0;
    
    function addAttribute() {
        const template = document.getElementById('attribute-template').innerHTML;
        const newAttribute = template.replace(/__index__/g, attributeIndex++);
        document.getElementById('attribute_list').insertAdjacentHTML('beforeend', newAttribute);
    }


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
        let input_field = $(ele).closest('.table-box');

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
            let variation_html = `<div class="attr_variation_row input_field d-inline-block variation_vidx variation_${label_id}">`;
            if(label) {
                variation_html += '<label>'+label+'</label>';
                variation_html += '<div><select class="select2" name="variations-vidx[keys]['+label_id+']">';
                variation_html += '<option value="">Any '+label+'</option>';
                const val_options = val.split('|');
                for(let i in val_options) {
                    const v = val_options[i];
                    variation_html += '<option value="'+v+'">'+v+'</option>';
                }
                variation_html += '</select></div>';
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
        template.querySelector('[name*=first_value_default]').checked = data.first_value_default;
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
            showCancelButton: true,
            showClass: {
                popup: 'animated windowIn log_mail'
            },
            hideClass: {
                popup: 'animated windowOut'
            }
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
            select2_init();
        });

    }


// working code for all as tested
// function first_value_default(checkbox) {
//     let globalIndex = 0; // Global index to track the position of options across all select boxes

//     // Function to update select boxes based on the checkbox state
//     const updateSelectBoxes = function(newSelect = null) {
//         const sections = ['#variation_list', '#append_list']; // Section IDs to look for select boxes

//         sections.forEach(function(sectionId) {
//             // Select boxes to update
//             const selects = newSelect 
//                 ? [newSelect] 
//                 : document.querySelectorAll(`${sectionId} .select2.select2-hidden-accessible[name^="variations"][name*="[keys]"]`);

//             selects.forEach(function(select) {
//                 // Check for the "Any" option
//                 const firstOption = select.querySelector('option[value=""]');

//                 if (checkbox.checked) {
//                     // If checkbox is checked, remove "Any" option
//                     if (firstOption) {
//                         firstOption.remove();
//                     }

//                     // Get all options in the select box
//                     const options = Array.from(select.options);

//                     // Ensure globalIndex stays within bounds
//                     if (globalIndex < options.length) {
//                         select.selectedIndex = globalIndex; // Set to the current globalIndex
//                         globalIndex++; // Increment index for next select box
//                     } else {
//                         select.selectedIndex = options.length - 1; // Select the last option if out of bounds
//                     }
//                 } else {
//                     // If checkbox is unchecked, add "Any" option if it doesn't exist
//                     if (!firstOption) {
//                         const label = select.previousElementSibling ? select.previousElementSibling.innerText : "Option";
//                         const newOption = document.createElement('option');
//                         newOption.value = "";
//                         newOption.text = "Any " + label;
//                         select.insertBefore(newOption, select.firstChild);

//                         // Select the "Any" option
//                         select.selectedIndex = 0; // Set to the newly added "Any" option
//                     }
//                 }
//             });
//         });
//     };

//     // Initial update of select boxes
//     updateSelectBoxes();

//     // Observe the DOM for dynamically added content (e.g., appended sections)
//     const observer = new MutationObserver(function(mutationsList) {
//         mutationsList.forEach(function(mutation) {
//             mutation.addedNodes.forEach(function(node) {
//                 if (node.nodeType === 1) { // Ensure the added node is an element
//                     // Find newly added select boxes with the relevant naming pattern
//                     const newSelects = node.querySelectorAll(`.select2.select2-hidden-accessible[name^="variations"][name*="[keys]"]`);
//                     newSelects.forEach(function(newSelect) {
//                         // Update newly added select boxes
//                         updateSelectBoxes(newSelect);
//                     });
//                 }
//             });
//         });
//     });

//     // Start observing the document body for changes
//     observer.observe(document.body, {
//         childList: true, // Listen for direct children added or removed
//         subtree: true    // Listen for changes deeper in the DOM
//     });

//     // Add change event listeners to each select box for maintaining unique selections
//     sections.forEach(function(sectionId) {
//         const selects = document.querySelectorAll(`${sectionId} .select2.select2-hidden-accessible[name^="variations"][name*="[keys]"]`);
//         selects.forEach(function(select) {
//             select.addEventListener('change', function() {
//                 const selectedValue = this.value;
//                 console.log(`Selected value for ${this.name}: ${selectedValue}`); // Debugging log
//             });
//         });
//     });
// }


// Set first value as default for all attributes
// function first_value_default(checkbox) {
//     const updateSelectBoxes = function(newSelect = null) {
//         const sections = ['#variation_list', '#append_list']; // Add more section IDs if needed

//         sections.forEach(function(sectionId) {
//             // Find all select boxes for attributes only, based on a specific naming convention
//             const selects = newSelect 
//                 ? [newSelect] 
//                 : document.querySelectorAll(`${sectionId} .select2.select2-hidden-accessible[name^="variations"][name*="[keys]["]`); // Updated selector

//             // Loop through each select box
//             selects.forEach(function(select) {
//                 // Find the option with value="" (the "Any" option)
//                 const firstOption = select.querySelector('option[value=""]');

//                 if (checkbox.checked) {
//                     // If checked, remove the option with value=""
//                     if (firstOption) {
//                         firstOption.remove();
//                     }

//                     // If there are still options available, select the first remaining option
//                     if (select.options.length > 0) {
//                         select.selectedIndex = 0; // Select the first available option
//                     }
//                 } else {
//                     // If unchecked, re-add the option with value="" if it's missing
//                     if (!firstOption) {
//                         const label = select.previousElementSibling ? select.previousElementSibling.innerText : "Option";
//                         const newOption = document.createElement('option');
//                         newOption.value = "";
//                         newOption.text = "Any " + label;
//                         select.insertBefore(newOption, select.firstChild);

//                         // Select the newly added "Any" option
//                         select.selectedIndex = 0; // Select the "Any" option
//                     }
//                 }
//             });
//         });
//     };

//     // Update the select boxes based on the checkbox state
//     updateSelectBoxes();

//     // Observe the DOM for dynamically added content (e.g., appended sections)
//     const observer = new MutationObserver(function(mutationsList) {
//         mutationsList.forEach(function(mutation) {
//             mutation.addedNodes.forEach(function(node) {
//                 if (node.nodeType === 1) { // Ensure the added node is an element
//                     // Check for newly added select boxes for attributes
//                     const newSelects = node.querySelectorAll(`.select2.select2-hidden-accessible[name^="variations"][name*="[keys]["]`); // Updated selector
//                     newSelects.forEach(function(newSelect) {
//                         // Call updateSelectBoxes for the newly added select boxes only
//                         updateSelectBoxes(newSelect);
//                     });
//                 }
//             });
//         });
//     });

//     // Start observing the entire document or specific container for changes
//     observer.observe(document.body, {
//         childList: true, // Listen for direct children added or removed
//         subtree: true    // Also listen for changes deeper in the DOM
//     });

//     // Add change event listeners to each select box to maintain unique selections
//     const sections = ['#variation_list', '#append_list']; // Add more section IDs if needed
//     sections.forEach(function(sectionId) {
//         const selects = document.querySelectorAll(`${sectionId} .select2.select2-hidden-accessible[name^="variations"][name*="[keys]["]`); // Updated selector
//         selects.forEach(function(select) {
//             select.addEventListener('change', function() {
//                 const selectedValue = this.value;
//                 console.log(`Selected value for ${this.name}: ${selectedValue}`); // Optional: for debugging
//             });
//         });
//     });
// }




</script>
