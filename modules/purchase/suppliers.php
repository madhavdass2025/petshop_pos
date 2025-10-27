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
    <title>Suppliers - Medical Shop POS</title>
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
                <h2>Suppliers</h2>
                <a href="add_supplier.php" class="btn btn-primary mb-3">Add Supplier</a>
                <table class="table table-bordered" id="suppliers_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>GSTIN</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM suppliers";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['contact'] . "</td>";
                                echo "<td>" . $row['gstin'] . "</td>";
                                echo "<td><a href='edit_supplier.php?id=" . $row['id'] . "' class='btn btn-sm btn-info'>Edit</a> <a href='delete_supplier.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger'>Delete</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No suppliers found</td></tr>";
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
            $('#suppliers_table').DataTable();
        } );
    </script>
</body>
</html>