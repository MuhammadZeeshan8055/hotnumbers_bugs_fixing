
<?php $session = session(); ?>

<?php echo view( 'includes/header');?>

    <style>
        .row>* {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: unset;
            padding-left: unset;
            margin-top: unset;
        }
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

    </style>




<div class="underbanner" style="background: url('<?php echo base_url('assets/images'); ?>/banner.jpg');"></div>

<!-- wrapper -->
<div class="wrapper">
        <!-- title -->
	<h1 class="pagetitle">My account</h1>
			<div class="container">
			    <div class="woocommerce">

                    <?php echo view("account/menu")?>
                    
                    <!-- order -->
                    <div class="woocommerce-MyAccount-content">
                	    

                                <div class="toolbar">
                                    <?php
                                    $idx = 0;
                                    foreach($order_counts as $status=>$count) {
                                        if($count) {
                                           $active = $curr_status === $status;
                                           if(empty($curr_status) && $idx == 0) {
                                               $active = 1;
                                           }
                                           $status_text = str_replace('_',' ',$status);
                                        ?>
                                        <a href="?status=<?php echo $status ?>" class="btn btn-sm btn-primary <?php echo !$active ? 'bg-light color-red':'bg-red color-white' ?>" style="float: none;"><?php echo ucfirst($status_text) ?> (<?php echo $count ?>)</a>
                                        <?php
                                        }
                                        $idx++;
                                    }?>
                                </div>
                            <!--- ORDERED LIST TABLE--->
                            <div class="table-wrapper">
                                <table id="books_table" class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
                                    <thead>
                                        <tr>
                                            <th class="woocommerce-orders-table__header"><span class="nobr">Order</span></th>
                                            <th class="woocommerce-orders-table__header" width="120"><span class="nobr">Date</span></th>
                                            <th class="woocommerce-orders-table__header"><span class="nobr">Status</span></th>
                                            <th class="woocommerce-orders-table__header"><span class="nobr">Products</span></th>
                                            <th class="woocommerce-orders-table__header"><span class="nobr">Subtotal</span></th>
                                            <th class="woocommerce-orders-table__header"><span class="nobr">Actions</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    //pr($list_orders,false);

                                    if(!empty($list_orders)) {
                                        foreach($list_orders as $order){
                                            $order_id = $order['order_id'];
                                            $date = date(env('date_format'),strtotime($order['order_date']));
                                            $status = $order['status'];
                                            $products = $orderModel->order_items($order_id);

                                            $status_text = str_replace('_',' ',$status);

                                            if(!empty($order['order_meta']))
                                            {
                                                $order_meta = $order['order_meta'];

                                                ?>
                                                <tr class="woocommerce-orders-table__row order">
                                                    <td class="woocommerce-orders-table__cell">
                                                        <a href="<?php echo base_url('account/orders/view_order/'.$order_id)?>">#<?php echo _order_number($order_id); ?></a>
                                                    </td>
                                                    <td class="woocommerce-orders-table__cell">
                                                        <time datetime="2020-06-05T11:59:57+00:00"><?php echo $date; ?></time>
                                                    </td>
                                                    <td class="woocommerce-orders-table__cell">
                                                        <?php echo ucfirst($status_text); ?>
                                                    </td>
                                                    <td class="woocommerce-orders-table__cell">
                                                        <?php
                                                        $product_total=0 ;
                                                        $tax_total = 0;
                                                        $subtotal = 0;
                                                        $calc_subtotal = $order['order_items'];

                                                        foreach($products as $product) {
                                                            if(!empty($product['item_meta'])) {
                                                                $meta = $product['item_meta'];
                                                                $product_total += !empty($meta['line_total']) ? $meta['line_total'] : 0;
                                                                $tax_total += !empty($meta['line_tax']) ? $meta['line_tax'] : 0;
                                                                $qty = !empty($meta['quantity']) ? $meta['quantity'] : 0;
                                                                $price = $meta['item_price_html'];
                                                                ?>
                                                                <p><?php echo $product['product_name'] ?> &nbsp; <?php echo $price ?> x <?php echo $qty ?></p>
                                                                <?php
                                                            }
                                                        }

                                                        $subtotal = $order_meta['order_total'];
                                                        $product_total = number_format($product_total,2);
                                                        $tax_total = number_format($tax_total,2);
                                                        ?>
                                                    </td>

                                                    <td class="woocommerce-orders-table__cell">
                                                        <?php echo _price($subtotal); ?>
                                                    </td>
                                                    <td class="woocommerce-orders-table__cell">
                                                        <a href="<?php echo base_url('account/orders/view_order/'.$order_id)?>" class="woocommerce-button button view">View</a>
                                                    </td>
                                                </tr>
                                            <?php }
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                           <!--- ORDERED LIST table end --->




                        </div>
                    </div>
                		</div>
                        </div>
             
                
         


<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


