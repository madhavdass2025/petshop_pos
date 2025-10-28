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
<!DOCTYPE html>
<html>
<head>
    <title>Sales Report - Pet Clinic Pharmacy POS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
</head>
<body>
    <?php include './includes/sidebar.php'; ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Sales Report</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="sales_table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Invoice ID</th>
                                            <th>Pet Owner</th>
                                            <th>Pet Name</th>
                                            <th>Date</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT si.id, si.invoice_date, si.total_amount, si.status, p.name as pet_name, c.name as owner_name
                                                FROM sales_invoices si
                                                JOIN pets p ON si.pet_id = p.id
                                                JOIN customers c ON p.owner_id = c.id
                                                ORDER BY si.invoice_date DESC";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['owner_name'] . "</td>";
                                            echo "<td>" . $row['pet_name'] . "</td>";
                                            echo "<td>" . $row['invoice_date'] . "</td>";
                                            echo "<td>" . $row['total_amount'] . "</td>";
                                            echo "<td>" . $row['status'] . "</td>";
                                            echo "<td><a href='view_sales_invoice.php?id=" . $row['id'] . "' class='btn btn-info btn-sm'>View</a></td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#sales_table').DataTable();
        });
    </script>
</body>
</html>
<?php include './includes/footer.php'; ?>
