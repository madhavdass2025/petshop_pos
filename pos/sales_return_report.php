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
    <title>Sales Return Report - Medical Shop POS</title>
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
                        <h1>Sales Return Report</h1>
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
                                <table id="returns_table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Return ID</th>
                                            <th>Original Invoice ID</th>
                                            <th>Return Date</th>
                                            <th>Reason</th>
                                            <th>Refund Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM sales_returns ORDER BY return_date DESC";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td><a href='view_sales_invoice.php?id=" . $row['invoice_id'] . "'>" . $row['invoice_id'] . "</a></td>";
                                            echo "<td>" . $row['return_date'] . "</td>";
                                            echo "<td>" . $row['reason'] . "</td>";
                                            echo "<td>" . $row['total_refund_amount'] . "</td>";
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
            $('#returns_table').DataTable();
        });
    </script>
</body>
</html>
<?php include './includes/footer.php'; ?>
