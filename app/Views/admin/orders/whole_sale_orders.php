<div class="container">

    <div class="admin_title_row">
        <div class="d-inline-block">
            <?php admin_page_title('Orders'); ?>
        </div>
        &nbsp;
        &nbsp;
        &nbsp;
        <div class="d-inline-block">
            <!-- <a class="add_btn" href="<?php echo admin_url() ?>orders/add">Add Order</a> -->
        </div>
    </div>

    <div class="datatable">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <form method="get">
                        <div class="d-inline-block align-middle">
                            <div class="input_field">
                                <label>Bulk selection actions</label>
                                <div class="rel">
                                    <select class="select2"  data-search="false" onchange="select_bulk_action(this)">
                                        <option value="">--</option>
                                        <option value="<?php echo admin_url() ?>generate-order-csv/">Export as CSV</option>
                                        <option value="<?php echo admin_url() ?>generate-pdf-slip/">PDF packing slip</option>
                                        <?php foreach(order_statuses() as $k=>$s) { ?>
                                            <option value="<?php echo admin_url() ?>change-order-status/<?php echo $k ?>?orders=" data-refresh="1">Move to <?php echo $s ?></option>
                                        <?php } ?>
                                        <option value="<?php echo admin_url() ?>delete-orders/" data-prompt="Are you sure to delete selected orders?" data-refresh="1">Delete permanently</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        &nbsp;
                        <div class="d-inline-block align-middle">
                            <div class="input_field">
                                <label>Order status</label>
                                <div class="rel">

                                    <select  data-search="false" class="select2" name="status" value="<?php echo !empty($_GET['status']) ? $_GET['status']:'' ?>">
                                        <option value="">All orders (<?php echo !empty($order_count['all']) ? $order_count['all'] : 0 ?>)</option>
                                        <?php foreach(order_statuses() as $k=>$s) { ?>
                                            <option value="<?php echo $k ?>"><?php echo $s ?> (<?php echo !empty($order_count[$k]) ? $order_count[$k] : 0 ?>)</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        &nbsp;
                        <div class="d-inline-block align-middle">
                            <div class="input_field">
                                <label>Order type</label>
                                <div class="rel">

                                    <select data-search="false" class="select2" name="order_type" value="<?php echo !empty($_GET['order_type']) ? $_GET['order_type']:'' ?>">
                                        <option value="">All orders</option>
                                        <option value="shop_order">Shop order</option>
                                        <option value="shop_subscription">Subscription</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        &nbsp;
                        <?php
                        if(!empty($order_months)) {
                            ?>
                            <div class="d-inline-block align-middle">
                                <div class="input_field">
                                    <label>Order months</label>
                                    <div class="rel">
                                        <select class="select2" name="date" value="<?php echo !empty($_GET['date']) ? $_GET['date']:'' ?>">
                                            <option value="">All dates</option>
                                            <?php foreach($order_months as $date=>$month) { ?>
                                                <option value="<?php echo $date ?>"><?php echo $month ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            &nbsp;
                        <?php }
                        /*<div class="d-inline-block align-middle">
                            <div class="input_field">
                                <label>Customer types</label>
                                <div class="rel">
                                    <select class="select2" name="role" value="<?php echo !empty($_GET['role']) ? $_GET['role']:'' ?>">
                                        <option value="">All customers</option>
                                        <?php foreach(shop_roles() as $role) { ?>
                                            <option value="<?php echo $role->role ?>"><?php echo ucfirst($role->name) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>*/
                        ?>

                        &nbsp;

                        <?php
                        if(!empty($customers)) {
                            ?>

                            <div class="d-inline-block align-middle">
                                <div class="input_field">
                                    <label>Registered customers</label>
                                    <div class="rel">
                                        <select style="width: 310px" class="customers_autocomplete form-control" name="customer" value="<?php echo !empty($_GET['customer']) ? $_GET['customer']:'' ?>">
                                            <option value="">All customers</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        &nbsp;
                        &nbsp;
                        <div class="d-inline-block align-middle">
                            <br>
                            <input type="hidden" name="sorted" value="1">
                            <button type="submit" class="btn-primary btn bg-black btn-sm" style="margin-top: 7px;padding: 8px 15px 9px">Filter</button>
                            <?php
                            if(isset($_GET['sorted'])) {
                                ?>
                                &nbsp;
                                <a href="<?php echo admin_url() ?>orders" class="btn-secondary btn btn-sm" style="margin-top: 7px;padding: 8px 15px 9px">Reset</a>
                                <?php
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4 text-right" style="padding: 8px 0">

            </div>
            <div class="clearfix"></div>
        </div>


        <?php
        $remote_url = '?get_data=1';

        if(!empty($_SERVER['QUERY_STRING'])) {
            $remote_url .= "&".$_SERVER['QUERY_STRING'];
        }
        ?>

        <form id="order-list" method="post">
            <div class="books_listing">

                <table id="books_table" data-sortcol="1" data-sortorder="desc" data-remote="<?php echo $remote_url ?>" class="ui data_table celled table responsive nowrap unstackable table_order" style="width:100%">
                    <thead>
                        <tr>
                            <th data-orderable="false" data-searching="false">
                                <div class="input_field inline-checkbox"><label><input type="checkbox" class="checkall"> </label></div>
                            </th>
                            <th>Order</th>
                            <th width="120">Date</th>
                            <th>Status</th>
                            <th>Customer</th>
                            <th>Customer Type</th>
                            <th>Ship to</th>
                            <th width="140">Payment method</th>
                            <th width="140">Shipment method</th>
                            <th>Order type</th>
                            <th width="80">Subtotal</th>
                            <th data-orderable="false">Actions</th>
                        </tr>
                    </thead>

                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </form>

        <style>
            .order-preview .swal2-content, .swal2-popup.order-preview {
                padding: 0;
            }
            .order-preview div#swal2-content {
                color: #000;
                font-weight: 400;
                margin: 0;
                text-align: left;
                box-shadow: var(--shadow);
            }

            .order-preview .content {
                padding: 0 16px;
            }

            .order-preview {
                min-width: 900px;
                margin: auto;
            }
            .order-preview .head {
                margin-bottom: 1em;
            }
            .order-preview .head, .order-preview .foot {
                background-color: #f9f9f9;
                padding: 10px 15px 10px;
            }
            .order-preview .foot {
                margin-top: 2em;
            }
            .order-preview h1 {
                font-size: 18px;
                font-weight: 900;
                padding: 4px 0;
            }
            .order-preview h2 {
                font-size: 16px;
            }
            .order-preview label {
                font-weight: 600;
                font-size: 15px;
            }
            .order-preview label + p {
                margin-top: 6px;
            }
            .order-preview table {
                border-spacing: 0;
            }
            .order-preview table label,
            .order-preview table label + p {
                font-size: 14px;
            }

            .order-preview .order-status {
                padding: 4px 18px 7px;
                font-size: 14px;
                margin-right: 3em;
            }

            .order-preview thead {
                background-color: #eee;
            }

            .order-preview th {
                font-size: 14px;
            }

            .order-preview th, .order-preview td {
                font-size: 14px;
            }

            #books_table td:has(.preview-open) {
                padding-right: 35px;
            }
            #books_table td:has(.sub-order) {
                padding-right: 60px;
            }

            #books_table tr:has([title="Subscription"]) {
                background-color: #f2f7ff
            }
        </style>

        <script>
            $(document).on('click','.preview-open', function(e) {
                e.preventDefault();
                const oid = $(this).data('id');
                const config = {
                    html: '<div class="processing bg-transparent" style="height: 300px"></div>',
                    showCloseButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    showClass: {
                        popup: 'animated windowIn order-preview'
                    },
                    hideClass: {
                        popup: 'animated windowOut'
                    }
                };
                Swal.fire(config);
                fetch('<?php echo admin_url() ?>ajax/get-order-preview/'+oid).then(res=>res.text()).then(res=>{
                    config.html = res;
                    config.showCloseButton = true;
                    swal.close();
                    Swal.fire(config);
                })
            });

            $(document).on('click','.order-preview .mark-completed', function(e) {
                e.preventDefault();
                if(window.confirm('Are you sure to mark this order as Completed?')) {
                    const href = this.href;
                    const win = window.open(href,'_blank');
                    win.addEventListener('unload', function() {
                        location.reload();
                    });
                }
            });

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
                    notification('No order selected');
                }
            }
        </script>


    </div>
</div>
