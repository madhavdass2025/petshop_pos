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
                    <h1>Products</h1>
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
                            <a href="add_product.php" class="btn btn-primary">Add Product</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="products_table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Generic Name</th>
                                        <th>Storage Condition</th>
                                        <th>Unit of Measure</th>
                                        <th>HSN/SAC</th>
                                        <th>GST Rate</th>
                                        <th>Reorder Level</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM products";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['name'] . "</td>";
                                            echo "<td>" . $row['generic_name'] . "</td>";
                                            echo "<td>" . $row['storage_condition'] . "</td>";
                                            echo "<td>" . $row['unit_of_measure'] . "</td>";
                                            echo "<td>" . $row['hsn_sac_code'] . "</td>";
                                            echo "<td>" . $row['gst_rate'] . "</td>";
                                            echo "<td>" . $row['reorder_level'] . "</td>";
                                            echo "<td><a href='edit_product.php?id=" . $row['id'] . "' class='btn btn-sm btn-info'>Edit</a> <a href='delete_product.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger'>Delete</a></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='9'>No products found</td></tr>";
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
        $('#products_table').DataTable();
    } );
</script>
