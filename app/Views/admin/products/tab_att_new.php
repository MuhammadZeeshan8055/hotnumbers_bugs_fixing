<?php
$k = 0;
$attribute_arr = [];
$attribs_json = [];
$variations_arr = [];
$pid = 0;
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
    $pid = $product_row['id'];
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

<div id="product-attributes" class="input_field">
    <div>
        <div class="table-box">
            <label>Variable product options</label>
            <div class="row">
                <div class="col-md-4">
                    <div id="attr-list">

                    </div>
                    <button type="button" class="btn btn-secondary" onclick="addnewAttribute(this)">Add Attribute</button>
                </div>

                <div class="col-md-8">

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const addnewAttribute = (button)=> {
        const attr_url = '<?php echo admin_url() ?>ajax/add_product_attribute?pid=<?php echo $pid ?>';
        const parent = $(button).closest('#product-attributes');
        parent.addClass('processing');
        $.get(attr_url, function(result) {
            $('#attr-list').html(result);
            parent.removeClass('processing');
        });
    }
</script>