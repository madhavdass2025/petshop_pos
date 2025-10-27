<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: ./index.php");
    exit();
}
include './core/db_connect.php';

$days = isset($_GET['days']) ? $_GET['days'] : 90;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dead Stock Report - Medical Shop POS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include './includes/header.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <?php include './includes/sidebar.php'; ?>
            </div>
            <div class="col-md-10">
                <h2>Dead Stock Report</h2>
                <form class="form-inline mb-3">
                    <label for="days" class="mr-2">No sales in the last:</label>
                    <select name="days" id="days" class="form-control mr-2" onchange="this.form.submit()">
                        <option value="90" <?php if($days == 90) echo 'selected'; ?>>90 Days</option>
                        <option value="180" <?php if($days == 180) echo 'selected'; ?>>180 Days</option>
                    </select>
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Total Quantity in Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT p.name, SUM(s.quantity) as total_stock
                                FROM products p
                                JOIN stock s ON p.id = s.product_id
                                WHERE p.id NOT IN (
                                    SELECT s.product_id
                                    FROM sales_invoice_items sii
                                    JOIN stock s ON sii.stock_id = s.id
                                    JOIN sales_invoices si ON sii.invoice_id = si.id
                                    WHERE si.invoice_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                                )
                                GROUP BY p.id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $days);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['total_stock'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2'>No dead stock found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>