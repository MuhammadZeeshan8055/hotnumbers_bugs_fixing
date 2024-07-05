<div id="products" class="table-box">
    <label>Product Information</label>

    <div id="product_information_form"></div>

</div>

<?php
if(!empty($order_data)) {
    ?>
    <input type="hidden" name="order_id" value="<?php echo $order_data['order_id'] ?>">
    <?php
}
?>

<input type="hidden" name="current_url" value="<?php echo current_url() ?>">

<button type="submit" name="submit" value="1" class="btn-primary btn"><?php echo !empty($order_data) ? 'Save changes':'Add Order' ?></button>

<?php
$products = [];
foreach($order_data['order_items'] as $item) {
    if($item['item_type'] === "line_item") {
        $meta = $item['item_meta'];
        $products[$meta['product_id']] = $meta;
    }
}
?>

<script>

    fetch('<?php echo admin_url() ?>ajax/edit-product-form-data?data=<?php echo serialize($products) ?>', {
        method: "GET"
    }).then(res=>res.json()).then(()=>{

    })
</script>

<style>
    table.table tbody tr td, table.table tbody tr th {
        vertical-align: top;
    }
</style>