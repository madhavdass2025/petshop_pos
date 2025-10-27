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
    <title>Products - Medical Shop POS</title>
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
                <h2>Products</h2>
                <a href="add_product.php" class="btn btn-primary mb-3">Add Product</a>
                <table class="table table-bordered" id="products_table">
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
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="/assets/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#products_table').DataTable();
        } );
    </script>
</body>
</html>