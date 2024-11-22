<div class="container">

    <div class="admin_title_row">
        <div class="d-inline-block">
            <?php admin_page_title('Subscriptions'); ?>
        </div>
        &nbsp;&nbsp;&nbsp;
        <div class="d-inline-block">
            <a class="add_btn" href="<?php echo base_url(ADMIN . '/subscription-settings') ?>"><i class="lni lni-cog" style="vertical-align: middle;margin-top: -4px;"></i> &nbsp; Subscription Settings</a>
        </div>
    </div>

    <div class="datatable">

        <div class="books_listing">
            <div class="toolbar">
                <form method="get">
                    <?php
                    if(!empty($_SERVER['QUERY_STRING'])) {
                        $k_arr = [];
                        foreach(explode('&',$_SERVER['QUERY_STRING']) as $string) {
                            $str = explode('=',$string);
                            $k = $str[0];
                            $v = $str[1];
                            if(!in_array($k, $k_arr) && $k !== 'role' && $k !== 'status') {
                                $k_arr[] = $k;
                                ?>
                                <input type="hidden" name="<?php echo $k ?>" value="<?php echo $v ?>">
                                <?php
                            }
                        }
                    }
                    ?>
                    <div class="input_field">
                        <label>Subscription status</label>
                        <div class="rel">
                            <select onchange="filterForm(this.form)" data-search="false" class="select2" name="status" value="<?php echo !empty($_GET['status']) ? $_GET['status']:'' ?>">
                                <option value="">All subscriptions</option>
                                <?php foreach(subscription_statuses() as $k=>$s) { ?>
                                    <option value="<?php echo $k ?>"><?php echo $s ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </form>

                <script>
                    const filterForm = (form)=> {
                        const formvals = $(form).serialize();
                        window.location = '<?php echo admin_url() ?>subscriptions?'+formvals;
                    }
                </script>

            </div>
            <?php

            $remote_url = '?get_data=1';
            if(!empty($_GET['status'])) {
                $remote_url .= "&status=".$_GET['status'];
            }
            ?>
            <div class="table-wrapper">
                <table id="books_table" data-remote="<?php echo $remote_url ?>" class="ui data_table celled table responsive nowrap table_order"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th width="100">No.</th>
                            <th>Customer</th>
                            <th>Orders</th>
                            <th>Total</th>
                            <th>Start date</th>
                            <th>Next payment</th>
                            <th>Last order</th>
                            <th>End date</th>
                            <th>Order count</th>
                            <th>Status</th>
                            <th data-sortable="false" width="14%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>

        </div>

        <style>
            /*.table_order tr:has(.sub-new) {*/
            /*    background-color:#f2fffc;*/
            /*}*/
            .table_order tr:has(.sub-repeat) {
                background-color: #fff;
            }
            .table_order tr:has(.status-expired) {
                background-color: #bf33331a;
            }
            .table_order tr:has(.status-renew-failed) {
                background-color: rgba(255, 215, 54, 0.18);
            }
        </style>
    </div>
</div>
