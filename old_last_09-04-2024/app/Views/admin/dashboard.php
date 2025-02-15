<div class="container ">
    <div class="row mt-50">
        <div class="tile wide resource">
            <div class="header" onclick="window.location.href='<?php echo base_url(ADMIN . '/products') ?>'">
                <div class="right">

                    <div class="count"><?php echo !empty($totalProducts->count) ? $totalProducts->count : 0 ?></div>
                    <div class="title">Total Active Products</div>
                </div>
            </div>
        </div>
        <div class="tile wide quote">
            <div class="header">
                <div class="right">
                    <div class="count"><?php echo !empty($totalOrders->count) ? $totalOrders->count : 0 ?></div>
                    <div class="title">Total Orders</div>
                </div>
            </div>
        </div>
        <div class="tile wide invoice">
            <div class="header" onclick="window.location.href='<?php echo base_url(ADMIN . '/users') ?>'">

                <div class="right">
                    <div class="count"><?php echo !empty($totalCustomers->count) ? $totalCustomers->count : 0 ?></div>
                    <div class="title">Total Users</div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-50">

        <?php
        if(!empty($top_sales)) { ?>
        <div class="col-md-4">
            <div class="table-box">
            <label class="text-left">Top Sales</label>
            <table width="100%" class="table">
                <thead>
                    <tr>
                        <th class="text-left" style="text-align: left">Product</th>
                        <th class="text-left" style="text-align: left">Sales</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($top_sales as $sale) {

                        ?>
                        <tr>
                            <td class="text-left"><?php echo $sale->title ?></td>
                            <td class="text-left"><?php echo $sale->total_sales ?></td>
                            <td><a class="edit_row btn btn-primary btn-sm red" href="<?php echo site_url() ?>admin/products/add/<?php echo $sale->id ?>">View</a> </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
        </div>
        <?php } ?>

        <?php
        if(!empty($year_sales)) {
            $month_list = [];
            $sale_x_group = [];
            $month_sales = [];
            foreach($year_sales as $yr=>$yr_sales) {
                foreach($yr_sales as $month=>$data) {
                    $total_sales = count($data);
                    $month_name = date("M", mktime(0, 0, 0, $month, 10));

                    $month_list[] = $month_name.' - '.$yr;
                    $month_sales[$month_name] = count($data);
                    $sale_x_group[] = $total_sales;
                }
            }


            $month_list = array_reverse($month_list);
            $sale_x_group = array_reverse($sale_x_group);

            $month_list = json_encode($month_list);

            $sale_x_group = json_encode($sale_x_group);


            ?>
            <div class="col-md-8">
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

                <div class="table-box">
                <label>Sales stats <small style="font-size: 12px">(<?php echo date('Y',strtotime('-1 year')) ?> - <?php echo date('Y') ?>)</small></label>

                <canvas id="annual_sales" style="width: 100%; height: 400px"></canvas>

                <script>
                    const xValues = <?php echo $month_list ?>;
                    const yValues = <?php echo $sale_x_group ?>;

                    new Chart("annual_sales", {
                        type: "line",
                        data: {
                            labels: xValues,
                            datasets: [{
                                fill: false,
                                lineTension: 0,
                                backgroundColor: "#181818",
                                borderColor: "#d8262f",
                                data: yValues,
                                label: 'Sales',
                                pointRadius: 6
                            }]
                        },
                        options: {
                            legend: {
                                display: false,
                                title: 'Month'
                            },
                        }
                    });

                </script>

                </div>
            </div>
        <?php } ?>

    </div>




</div>