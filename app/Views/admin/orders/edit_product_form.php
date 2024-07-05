<?php
$datas = unserialize($_GET['data']);
$productModel = model('ProductsModel');
pr($datas,false);
?>
<table width="100%" class="table layout-fixed product_edit_table">
    <thead>
    <tr class="text-left">
        <th width="35%">Product(s)</th>
        <th width="35%">Variation</th>
        <th>Cost</th>
        <th>Qty</th>
        <th>Subtotal</th>
        <th width="50"></th>
    </tr>
    </thead>
    <tbody>
        <?php foreach($datas as $data) {
            $pid = $data['product_id'];
            $product = $productModel->product_by_id($pid, 'title');
            $product_name = $product->title;
            $variations = !empty($data['variations']) ? $data['variations'] : [];
            ?>
            <tr>
                <td>
                    <div class="input_field">
                        <label>Select product</label>
                        <div class="rel">
                            <select name="products" style="width: 100%" onchange="get_product_info(this)" value="<?php echo $pid ?>" required class="option_products product_autocomplete">
                                <option value="0">..</option>
                                <?php if($pid) {
                                    ?>
                                    <option value="<?php echo $pid ?>" selected><?php echo $product_name ?></option>
                                    <?php
                                }?>
                            </select>
                        </div>
                    </div>
                </td>
                <td>
                    <?php foreach($variations as $variation) {
                        ?>

                        <?php
                    } ?>
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php
        } ?>
    </tbody>
</table>