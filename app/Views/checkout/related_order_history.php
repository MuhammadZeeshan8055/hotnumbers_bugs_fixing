<style>
    th:hover,
    th.desc:hover,
    th.asc:hover
    {
        cursor: pointer;
        background-color: #ededed;
    }
    th.desc::after {
        content: "\eb15";
        font: normal normal normal 1em / 1 "lineicons";
        margin: 6px;
    }
    th.asc::after {
        content: "\eb1d";
        font: normal normal normal 1em / 1 "lineicons";
        margin: 6px;
    }
</style>
<?php
if(!empty($order)) {
    $order_meta = $order['order_meta'];

    $orderModel = model('OrderModel');;

    $oid = $order['order_id'];

    $orders = $orderModel->get_order_by_customer($order['customer_user']," AND o.order_id!=$oid");

    if(!empty($orders)) {
        ?>
        <div style="max-height: 500px; overflow: auto">
            <table class="order_receipt table" id="orderTable">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)" class="desc">Order ID</th>
                        <th class="text-left">Order</th>
                        <th onclick="sortTable(2)">Date</th>
                        <th onclick="sortTable(3)">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td><div class="text-center"><?php echo $order['order_id'] ?></div></td>
                            <td class="text-left"><div class="text-center"><?php echo $order['order_title'] ?></div></td>
                            <td><div class="text-center"><?php echo _date_full($order['order_date']) ?></div></td>
                            <td><div class="text-center"><?php echo !empty($order['status']) ? ucfirst($order['status']) : '' ?></div></td>
                            <td>
                                <div class="text-center">
                                    <a href="<?php echo admin_url().'orders/view/'.$order['order_id'] ?>" class="btn btn-primary btn-sm">View order</a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}?>


<!-- Sort table using JavaScript -->
<script>
// function sortTable(n) {
//     var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
//     table = document.getElementById("orderTable");
//     switching = true;
//     dir = "asc"; // Set the sorting direction to ascending initially

//     while (switching) {
//         switching = false;
//         rows = table.rows;

//         for (i = 1; i < (rows.length - 1); i++) {
//             shouldSwitch = false;
//             x = rows[i].getElementsByTagName("TD")[n];
//             y = rows[i + 1].getElementsByTagName("TD")[n];

//             // Check if the rows should switch based on the column type
//             if (dir == "asc") {
//                 if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
//                     shouldSwitch = true;
//                     break;
//                 }
//             } else if (dir === "desc") {
//                 if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
//                     shouldSwitch = true;
//                     break;
//                 }
//             }
//         }

//         if (shouldSwitch) {
//             rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
//             switching = true;
//             switchcount++;
//         } else {
//             if (switchcount == 0 && dir == "asc") {
//                 dir = "desc";
//                 switching = true;
//             }
//         }
//     }
// }


// function sortTable(n) {
//     var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
//     table = document.getElementById("orderTable");
//     switching = true;
//     dir = "asc"; // Set the sorting direction to ascending initially

//     // Toggle the sort direction class on the clicked header
//     var header = table.rows[0].cells[n];
//     if (header.classList.contains("desc")) {
//         header.classList.remove("desc");
//         header.classList.add("asc");
//         dir = "asc";
//     } else if (header.classList.contains("asc")) {
//         header.classList.remove("asc");
//         header.classList.add("desc");
//         dir = "desc";
//     } else {
//         // If no class is set, default to ascending
//         header.classList.add("asc");
//         dir = "asc";
//     }

//     while (switching) {
//         switching = false;
//         rows = table.rows;

//         for (i = 1; i < (rows.length - 1); i++) {
//             shouldSwitch = false;
//             x = rows[i].getElementsByTagName("TD")[n];
//             y = rows[i + 1].getElementsByTagName("TD")[n];

//             // Check if the rows should switch based on the column type
//             if (dir == "asc") {
//                 if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
//                     shouldSwitch = true;
//                     break;
//                 }
//             } else if (dir === "desc") {
//                 if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
//                     shouldSwitch = true;
//                     break;
//                 }
//             }
//         }

//         if (shouldSwitch) {
//             rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
//             switching = true;
//             switchcount++;
//         } else {
//             if (switchcount == 0 && dir == "asc") {
//                 dir = "desc";
//                 switching = true;
//             }
//         }
//     }
// }

function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("orderTable");
    switching = true;
    dir = "asc"; // Set the sorting direction to ascending initially

    // Remove 'asc' and 'desc' classes from all other headers
    var headers = table.rows[0].cells;
    for (i = 0; i < headers.length; i++) {
        if (i !== n) {  // Only remove classes from headers that are not clicked
            headers[i].classList.remove("asc", "desc");
        }
    }

    // Toggle the sort direction class on the clicked header
    var header = table.rows[0].cells[n];
    if (header.classList.contains("desc")) {
        header.classList.remove("desc");
        header.classList.add("asc");
        dir = "asc";
    } else if (header.classList.contains("asc")) {
        header.classList.remove("asc");
        header.classList.add("desc");
        dir = "desc";
    } else {
        // If no class is set, default to ascending
        header.classList.add("asc");
        dir = "asc";
    }

    while (switching) {
        switching = false;
        rows = table.rows;

        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];

            // Check if the rows should switch based on the column type
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir === "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }

        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}

</script>