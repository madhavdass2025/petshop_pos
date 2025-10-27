<?php
include './core/db_connect.php';
?>
<?php include './includes/header.php'; ?>
<?php
if (!isset($_SESSION['user_id'])) {
    header("location: ./index.php");
    exit();
}
?>
<?php include './includes/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Purchase Orders</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="add_purchase_order.php" class="btn btn-primary">Add Purchase Order</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="purchase_orders_table" class="table table-bordered table-hover">
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
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include './includes/footer.php'; ?>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
        $('#purchase_orders_table').DataTable();
    } );
</script>
