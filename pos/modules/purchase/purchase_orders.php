<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: ../../index.php");
    exit();
}
include '../../core/db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Orders - Medical Shop POS</title>
    <link rel="stylesheet" href="/assets/css/jquery.dataTables.min.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <?php include '../../includes/sidebar.php'; ?>
            </div>
            <div class="col-md-10">
                <h2>Purchase Orders</h2>
                <a href="add_purchase_order.php" class="btn btn-primary mb-3">Add Purchase Order</a>
                <table class="table table-bordered" id="purchase_orders_table">
                    <thead>
                        <tr>
                            <th>PO ID</th>
                            <th>Supplier</th>
                            <th>PO Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT po.id, s.name as supplier_name, po.po_date, po.status
                                FROM purchase_orders po
                                JOIN suppliers s ON po.supplier_id = s.id";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['supplier_name'] . "</td>";
                                echo "<td>" . $row['po_date'] . "</td>";
                                echo "<td>" . $row['status'] . "</td>";
                                echo "<td><a href='view_purchase_order.php?id=" . $row['id'] . "' class='btn btn-sm btn-info'>View</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No purchase orders found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="/assets/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#purchase_orders_table').DataTable();
        } );
    </script>
</body>
</html>