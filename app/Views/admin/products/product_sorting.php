<div class="container">
    <?php admin_page_title('Product Sorting'); ?>


        <div class="datatable">

        <div class="d-inline-block">
            <div class="input_field text-left">
                <label>Filter by category</label>
                <div>
                <form id="filterForm" method="GET">
                    <select id="filter_by_category" name="filter_by_category" class="select2" onchange="this.form.submit()">
                        <option value="">Select categories</option>
                        <?php
                            if(!empty($all_categories)) {
                                foreach($all_categories as $cat) {
                        ?>
                                
                                <option value="<?php echo $cat->id ?>" <?php echo !empty($_GET['filter_by_category']) && $_GET['filter_by_category'] == $cat->id ? 'selected' : ''; ?>><?php echo $cat->name ?></option>

                        <?php
                                }
                            }
                        ?>

                        
                    </select>
                </form>
                </div>
            </div>
        </div>
               
        <div class="mt-40"></div>

        <div class="books_listing">
            <table id="cats_table" class="ui data_table table_draggable celled table responsive nowrap unstackable" data-draggable="true" data-orderable="false" data-search="false" data-onreorder="onReOrder()" style="width:100%">
                <thead>
                <tr>
                    <th data-sortable="false" data-orderable="false" width="50"></th>
                    <th data-sortable="false" data-orderable="false" width="100" class="text-center">Order</th>
                    <th data-sortable="false" data-orderable="false" width="100">Image</th>
                    <th data-sortable="false">Product Name</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($categories as $cate) {

                    $status=get_product_status_by_id($cate->product_id);

                    preg_match('/\d+/', $status['img'], $matches);
                    $link = $matches[0] ?? null; // Get the first match or null if no match
                  
                    $prod_images=get_product_images_by_id($link);
                            
                    if($status['status']=='publish'){
                    ?>
                    <tr class="table_row_<?php echo $cate->product_id ?>" data-id="<?php echo $cate->product_id ?>" data-cid="<?php echo $cate->category_id?>">
                        <td><i class="lni lni-line-double"></i></td>
                        <td class="text_center">
                            <?php echo $cate->sort_order ?>
                        </td>

                        <td width="100"><?php echo $cat_img ?><img class="thumb" src="<?php echo base_url( '/assets/images/site-images/'.$prod_images['path'].'') ?>"></td>
                        <td> <?php echo $status['title'] ?></td>
                        
                       
                    </tr>
                <?php } } ?>
                </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>

        <script>
            let onReOrder = ()=> {
                const table = document.querySelector('#cats_table');
                const table_rows = table.querySelectorAll('tbody > tr');
                let order_data = [];

                let form = new FormData();

                table_rows.forEach((tr, idx)=>{
                    let order = idx+1;
                    let row_id = tr.getAttribute('data-id');
                    let c_id = tr.getAttribute('data-cid');
                    order_data.push({order: order, id: row_id, cid: c_id})
                });
                form.set('data',JSON.stringify(order_data));

                fetch('<?php echo admin_url() ?>product-sortorder',{
                    method: "POST",
                    body: form,
                }).then(res=>res.json()).then((res)=>{
                    location.reload();
                })
            }
        </script>
        
    </div>
</div>
