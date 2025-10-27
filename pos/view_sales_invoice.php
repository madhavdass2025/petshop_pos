<?php
include './core/db_connect.php';

if (!isset($_GET['id'])) {
    header("location: sales_report.php");
    exit();
}

$invoice_id = $_GET['id'];
$sql = "SELECT * FROM sales_invoices WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Invoice - Medical Shop POS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                <h2>Invoice Details</h2>
                <p><strong>Invoice ID:</strong> <?php echo $invoice['id']; ?></p>
                <p><strong>Customer Name:</strong> <?php echo $invoice['customer_name']; ?></p>
                <p><strong>Invoice Date:</strong> <?php echo $invoice['invoice_date']; ?></p>
                <p><strong>Payment Method:</strong> <?php echo $invoice['payment_method']; ?></p>
                <p><strong>Physician Name:</strong> <?php echo $invoice['physician_name']; ?></p>
                <p><strong>Prescription ID:</strong> <?php echo $invoice['prescription_id']; ?></p>

                <h4>Items</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Batch</th>
                            <th>Expiry</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>GST</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT p.name, s.batch_number, s.expiry_date, sii.quantity, sii.unit_price, sii.gst_amount
                                FROM sales_invoice_items sii
                                JOIN stock s ON sii.stock_id = s.id
                                JOIN products p ON s.product_id = p.id
                                WHERE sii.invoice_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $invoice_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            $total = ($row['quantity'] * $row['unit_price']) + $row['gst_amount'];
                            echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['batch_number'] . "</td>";
                            echo "<td>" . $row['expiry_date'] . "</td>";
                            echo "<td>" . $row['quantity'] . "</td>";
                            echo "<td>" . $row['unit_price'] . "</td>";
                            echo "<td>" . $row['gst_amount'] . "</td>";
                            echo "<td>" . $total . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <p><strong>Total Amount:</strong> <?php echo $invoice['total_amount']; ?></p>
                <p><strong>Total GST:</strong> <?php echo $invoice['total_gst']; ?></p>
                <p><strong>Grand Total:</strong> <?php echo $invoice['total_amount'] + $invoice['total_gst']; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
