<div class="container">

    <div class="d-inline-block">
        <?php admin_page_title('Subscriptions'); ?>
    </div>

    <div class="datatable">


        <div class="pull-left">
            <a class="add_btn <?php echo empty($status) ? 'active':'' ?>" href="?status=">All (<?php echo $all_count ?>)</a> &nbsp;
            <a class="add_btn <?php echo $status === "active" ? 'active':'' ?>" href="?status=active">Active (<?php echo $active_count ?>)</a> &nbsp;
            <a class="add_btn <?php echo $status === "pending" ? 'expired':'' ?>" href="?status=expired">Expired (<?php echo $expired_count ?>)</a> &nbsp;
            <a class="add_btn <?php echo $status === "pending-cancel" ? 'active':'' ?>" href="?status=pending-cancel">Pending Cancel (<?php echo $pending_cancel_count ?>)</a> &nbsp;
            <a class="add_btn <?php echo $status === "pending" ? 'active':'' ?>" href="?status=pending">Pending (<?php echo $pending_count ?>)</a> &nbsp;
            <a class="add_btn <?php echo $status === "on-hold" ? 'active':'' ?>" href="?status=on-hold">On Hold (<?php echo $on_hold_count ?>)</a> &nbsp;
            <a class="add_btn <?php echo $status === "cancelled" ? 'active':'' ?>" href="?status=cancelled">Cancelled (<?php echo $cancelled_count ?>)</a> &nbsp;
        </div>

        <div class="pull-right">
            <div>
                <a class="add_btn" href="<?php echo base_url(ADMIN . '/subscription-settings') ?>"><i class="lni lni-cog" style="vertical-align: middle;margin-top: -4px;"></i> &nbsp; Subscription Settings</a>
            </div>
        </div>

        <div class="clear"></div>

        <br>

        <div class="books_listing">
            <?php

            $remote_url = '?get_data=1';
            if(!empty($_GET['status'])) {
                $remote_url .= "&status=".$_GET['status'];
            }
            ?>
            <table id="books_table" data-remote="<?php echo $remote_url ?>" class="ui data_table celled table responsive nowrap table_order"
                   style="width:100%">
                <thead>
                <tr>
                    <th width="100">No.</th>
                    <th>Orders</th>
                    <th>Total</th>
                    <th>Start date</th>
                    <th>Next payment</th>
                    <th>Last order</th>
                    <th>End date</th>
                    <th>Order count</th>
                    <th>Status</th>
                    <th data-sortable="false" style="width: 100px">Actions</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>
</div>
