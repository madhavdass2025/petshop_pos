<?php
include './core/db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Stock Report - Medical Shop POS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
</head>
<body>
    <?php include './includes/header.php'; ?>
<?php
if (!isset($_SESSION['user_id'])) {
    header("location: ./index.php");
    exit();
}
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <?php include './includes/sidebar.php'; ?>
            </div>
            <div class="col-md-10">
                <h2>Stock Report</h2>
                <table id="stock_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Batch Number</th>
                            <th>Expiry Date</th>
                            <th>Quantity</th>
                            <th>Purchase Price</th>
                            <th>Selling Price</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT p.name, s.batch_number, s.expiry_date, s.quantity, s.purchase_price, s.selling_price, s.location
                                FROM stock s
                                JOIN products p ON s.product_id = p.id
                                WHERE s.quantity > 0
                                ORDER BY p.name, s.expiry_date";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['batch_number'] . "</td>";
                            echo "<td>" . $row['expiry_date'] . "</td>";
                            echo "<td>" . $row['quantity'] . "</td>";
                            echo "<td>" . $row['purchase_price'] . "</td>";
                            echo "<td>" . $row['selling_price'] . "</td>";
                            echo "<td>" . $row['location'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#stock_table').DataTable();
        });
    </script>
</body>
</html>
