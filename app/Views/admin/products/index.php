<div class="container">


    <div class="admin_title_row">
        <div class="d-inline-block ">
            <?php admin_page_title('All Products'); ?>
        </div>

        &nbsp;
        &nbsp;
        &nbsp;

        <div class="d-inline-block">
            <a class="add_btn" href="<?php echo admin_url() ?>products/add">Add Product</a>
        </div>

        <div class="d-inline-block">
            <a class="add_btn"
               href="<?php echo base_url(ADMIN.'/product-categories'); ?>"> Product categories</a>
        </div>
    </div>


    <div class="datatable">


        <script>
            $(function() {
                //Default Publish Posts
                setTimeout(()=>{
                    if(!$('#filter_by_status').val()) {
                     //   $('#filter_by_status').val('publish').trigger('change').val('');
                    }
                    <?php if(!empty($_GET['category'])) { ?>
                    if($('#filter_by_category').val()) {
                        //$('#filter_by_category').trigger('change');
                    }
                    <?php } ?>
                },200)
            })
        </script>

        <div class="books_listing">

            <div class="toolbar">
                <div class="d-inline-block">
                    <div class="input_field text-left">
                        <label>Bulk selection actions</label>
                        <div class="rel">
                            <select class="select2" data-search="false" onchange="select_bulk_action(this)">
                                <option value="">--</option>
                                <option value="<?php echo admin_url() ?>trash-products/" data-prompt="Are you sure to move selected products to Trash?" data-refresh="1">Trash Products</option>
                                <option value="<?php echo admin_url() ?>publish-products/" data-prompt="Are you sure to move selected products to Publish?" data-refresh="1">Publish Products</option>
                                <option value="<?php echo admin_url() ?>draft-products/" data-prompt="Are you sure to move selected products to Draft?" data-refresh="1">Draft Products</option>
                                <option value="<?php echo admin_url() ?>private-products/" data-prompt="Are you sure to move selected products to Private?" data-refresh="1">Private Products</option>
                            </select>
                        </div>
                    </div>
                </div>
                &nbsp; &nbsp;
                <div class="d-inline-block">
                    <div class="input_field text-left">
                        <label>Filter by status</label>
                        <div>
                            <select id="filter_by_status"  data-search="false" class="select2" style="width: 180px" value="<?php echo !empty($_GET['filter_by_status']) ? $_GET['filter_by_status'] : '' ?>">
                                <option value="">All statuses</option>
                                <?php
                                foreach(post_statuses() as $status) {
                                    $getstatus = !empty($_GET['status']) ? $_GET['status']:'';
                                    $selected = $getstatus == $status ? 'selected':'';
                                    ?>
                                    <option <?php echo $selected ?> value="<?php echo $status ?>"><?php echo ucfirst($status) ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                &nbsp; &nbsp;
                <div class="d-inline-block">
                    <div class="input_field text-left">
                        <label>Filter by category</label>
                        <div>
                            <select id="filter_by_category" class="select2" <?php echo !empty($_GET['filter_by_category']) ? 'value="'.$_GET['filter_by_category'].'"':'' ?>>
                                <option value="">All categories</option>
                                <?php
                                if(!empty($categories)) {
                                    foreach($categories as $cat) {
                                        ?>
                                        <option value="<?php echo $cat->id ?>"><?php echo $cat->name ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                &nbsp; &nbsp;
            </div>
            <div class="table-wrapper">
                <table id="books_table" data-sortcol="1" data-sortorder="desc" data-remote="?get_table=1<?php echo !empty($_SERVER['QUERY_STRING']) ? '&'.$_SERVER['QUERY_STRING'] : '' ?>" data-filter='filter_by_status,filter_by_category' class="ui data_table table_order celled table responsive nowrap" data-draggable="false" style="width:100%" data-onreorder="onReOrder()">
                    <thead>
                    <tr>
                        <th width="30" data-orderable="false" data-sortable="false" data-searching="false">
                            <div class="input_field inline-checkbox"><label><input type="checkbox" class="checkall"> </label></div>
                        </th>
                        <th width="40" class="text-center">ID</th>
                        <th width="350">Title</th>
                        <th width="300">Description</th>
                        <th width="150">Price</th>
                        <th width="260">Categories</th>
                        <th width="100">Product type</th>
                        <th width="40">Stock</th>
                        <th width="40">Stock Status</th>
                        <th width="60">Sales</th>
                        <th width="60">Status</th>
                        <th data-sortable="false" width="180">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /*foreach ($product_rows as $product_row) {
                        $product_cats = explode(',',$product_row->category);
                        $cat_names = [];
                        foreach($product_cats as $cat) {
                            $getcat = $productModel->get_category_by_id($cat);
                            $cat_names[] = '<a href="'.site_url('admin/products/?category='.$cat).'">'.$getcat->name.'</a>';
                        }
                        $img = explode(',',$product_row->img);
                        $img_src = $media->get_media_src($img[0],'','thumbnail');
                        $desc = [limit(strip_tags($product_row->description), 35),limit(strip_tags($product_row->additional_desc), 35)];
                        $desc = array_filter($desc);
                        ?>
                        <tr class="table_row_<?php echo $product_row->id ?>">
                            <td><img width="50" height="50"
                                    src="<?php echo $img_src ?>"></td>
                            <td> <?php echo $product_row->title ?></td>
                            <td><?php echo implode('<br><br>',$desc) ?></td>
                            <td><?php echo implode(', ',$cat_names) ?></td>
                            <td><a class="edit_row" href="<?php echo base_url(ADMIN . '/products/add') ?>/<?php echo $product_row->id ?>"><i class="icon-edit-alt"></i> </a>
                                <a class="del_row edit_row" onclick="del_item('<?php echo base_url(ADMIN . '/products/delete/') ?>/<?php echo $product_row->id ?>')"
                                href="javascript:void(0)"></i><i class="icon-trash"></i></a>
                            </td>

                        </tr>
                    <?php }*/
                    ?>


                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>

            <style>
                [class^=status-] {
                    opacity: 0.5;
                }
                .status-publish {
                    color: green;
                    opacity: 1;
                }
                .stock {
                    text-align: center;
                }
                .stock_available {
                    color: green;
                }
                .stock_low {
                    color: orange;
                }
                .stock_outofstock {
                    color: var(--base-color);
                    font-weight: 700;
                }
            </style>

            <script>
                let onReOrder = ()=> {
                    const table = document.querySelector('#books_table');
                    const table_rows = table.querySelectorAll('tbody > tr');
                    let order_data = [];

                    let form = new FormData();

                    table_rows.forEach((tr, idx)=>{
                        let order = idx+1;
                        let row_id = tr.getAttribute('data-id');
                        order_data.push({order: order, id: row_id})
                    });
                    form.set('data',JSON.stringify(order_data));

                    fetch('<?php echo admin_url() ?>product-sortorder',{
                        method: "POST",
                        body: form,
                    }).then(res=>res.json()).then((res)=>{
                        location.reload();
                    })
                }

                window.onload = ()=> {
                    dtable.on('row-reorder', function (e, diff, edit) {
                        const newPosition = diff[diff.length-1].newPosition;
                        const eleID = $(edit.triggerRow.data()[0]).data('id');

                        const postData = new FormData();
                        postData.set('product_id',eleID);
                        postData.set('order',newPosition);

                        fetch('<?php echo admin_url() ?>product-sortorder',{
                            method: "POST",
                            body: postData
                        }).then(res=>res.json()).then((res)=>{
                            if(res.success) {
                                location.reload();
                            }
                        })
                    });
                }
                let select_bulk_action = (ele)=> {
                    let selectedOrders = $('[name="product-row[]"]:checked');

                    if(selectedOrders.length) {
                        let selectedOption = $(ele).find('option[value="'+ele.value+'"]');
                        if(selectedOption.attr('data-prompt')) {
                            if(!confirm(selectedOption.attr('data-prompt'))) {
                                selectedOrders.val('');
                                selectedOrders.trigger('change');
                                return;
                            }
                        }
                        let selectedOrderIds = selectedOrders.map((idx,element)=>{
                            return element.value;
                        });
                        const Ids = selectedOrderIds.toArray().join();
                        const action = ele.value+''+Ids;
                        const win = window.open(action,'_blank');
                        ele.value = '';
                        select2_init();
                        if(selectedOption.data('refresh')) {
                            win.addEventListener('unload', function() {
                                location.reload();
                            });
                        }
                    }else {
                        ele.value = '';
                        select2_init();
                        notification('No product selected');
                    }
                }
            </script>
        </div>
    </div>
</div>
