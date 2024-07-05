
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
</style>


<div class="underbanner" style="background: url('<?php echo base_url('assets/images'); ?>/banner.jpg');"></div>

<!-- wrapper -->
<div class="wrapper">
    <!-- title -->
    <h1 class="pagetitle">My account</h1>
    <div class="container">
        <div class="woocommerce">

            <?php include "menu.php"?>

            <!-- order -->
            <div class="woocommerce-MyAccount-content">
                
                <?php
                if(!empty($subscription_order)) { ?>
                    <!--- ORDERED LIST TABLE--->
                    <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
                        <thead>
                        <tr>
                            <th><span class="nobr">ID#</span></th>
                            <th><span class="nobr">Name</span></th>
                            <th><span class="nobr">Start Date</span></th>
                            <th><span class="nobr">Next Payment</span></th>
                            <th><span class="nobr">End Date</span></th>
                            <th><span class="nobr">Status</span></th>
                            <th><span class="nobr">Total</span></th>
                            <th class="actions"><span class="nobr">Actions</span></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        foreach($subscription_order as $order) {
                            $order_meta = $order['order_meta'];
                            $order_id = $order['order_id'];
                            $order_items = $order['order_items'];
                            foreach($order_items as $item) {
                                $item_meta = $item['item_meta'];
                                ?>
                                <tr>
                                    <td><?php echo _order_number($order_id) ?></td>
                                    <td><?php echo $item['product_name'] ?></td>
                                    <td><?php echo _date($order_meta['schedule_start']) ?></td>
                                    <td><?php echo _date($order_meta['schedule_next_payment']) ?></td>
                                    <td><?php echo _date($order_meta['schedule_end']) ?></td>
                                    <td><?php echo ucfirst(_order_status($order['status'])) ?></td>
                                    <td><?php echo $item_meta['display_price_html'] ?></td>
                                    <td><a href="<?php echo base_url('account/orders/view_order/'.$order_id)?>" class="woocommerce-button button view">View</a></td>

                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>

                    </table>
                    <!--- ORDERED LIST table end --->
                    <?php
                }  else {?>
                    <!-- browse products--->
                    <div class="text-center">
                        <div><i class="fa icon-exclamation color-red"></i> </div>
                        <h4>No subscription is available</h4>
                    </div>
                <?php } ?>

                <style>
                    tr.woocommerce-orders-table__row.alert_lvl_3 {
                        background-color: #fde6b7;
                    }
                    tr.woocommerce-orders-table__row.alert_lvl_4 {
                        background-color: #ffe7e7;
                    }
                </style>

            </div>
        </div>
    </div>
</div>





<!------------footer ---------------------------------------->
<?php echo view('includes/footer');?>
<!--------------- footer end -------------------------------->


