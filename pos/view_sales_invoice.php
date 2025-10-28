<?php
include './core/db_connect.php';

if (!isset($_GET['id'])) {
    header("location: sales_report.php");
    exit();
}

$invoice_id = $_GET['id'];
$sql = "SELECT si.*, p.name as pet_name, c.name as owner_name
        FROM sales_invoices si
        JOIN pets p ON si.pet_id = p.id
        JOIN customers c ON p.owner_id = c.id
        WHERE si.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Invoice - Pet Clinic Pharmacy POS</title>
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
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Invoice ID:</strong> <?php echo $invoice['id']; ?></p>
                        <p><strong>Pet Owner Name:</strong> <?php echo $invoice['owner_name']; ?></p>
                        <p><strong>Pet Name:</strong> <?php echo $invoice['pet_name']; ?></p>
                        <p><strong>Invoice Date:</strong> <?php echo $invoice['invoice_date']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Veterinarian Name:</strong> <?php echo $invoice['veterinarian_name']; ?></p>
                        <p><strong>Prescription ID:</strong> <?php echo $invoice['prescription_id']; ?></p>
                        <p><strong>Status:</strong> <?php echo $invoice['status']; ?></p>
                    </div>
                </div>

                <h4>Items Sold</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Batch</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>GST</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_items = "SELECT p.name, s.batch_number, sii.quantity, sii.unit_price, sii.gst_amount
                                FROM sales_invoice_items sii
                                JOIN stock s ON sii.stock_id = s.id
                                JOIN products p ON s.product_id = p.id
                                WHERE sii.invoice_id = ?";
                        $stmt_items = $conn->prepare($sql_items);
                        $stmt_items->bind_param("i", $invoice_id);
                        $stmt_items->execute();
                        $result_items = $stmt_items->get_result();
                        while ($row = $result_items->fetch_assoc()) {
                            $total = ($row['quantity'] * $row['unit_price']) + $row['gst_amount'];
                            echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['batch_number'] . "</td>";
                            echo "<td>" . $row['quantity'] . "</td>";
                            echo "<td>" . $row['unit_price'] . "</td>";
                            echo "<td>" . $row['gst_amount'] . "</td>";
                            echo "<td>" . number_format($total, 2) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <h4>Returned Items</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Batch</th>
                            <th>Qty Returned</th>
                            <th>Refund Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_returns = "SELECT p.name, s.batch_number, sri.quantity, sri.refund_amount
                                        FROM sales_return_items sri
                                        JOIN sales_returns sr ON sri.return_id = sr.id
                                        JOIN stock s ON sri.stock_id = s.id
                                        JOIN products p ON s.product_id = p.id
                                        WHERE sr.invoice_id = ?";
                        $stmt_returns = $conn->prepare($sql_returns);
                        $stmt_returns->bind_param("i", $invoice_id);
                        $stmt_returns->execute();
                        $result_returns = $stmt_returns->get_result();
                        if ($result_returns->num_rows > 0) {
                            while ($row = $result_returns->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['batch_number'] . "</td>";
                                echo "<td>" . $row['quantity'] . "</td>";
                                echo "<td>" . number_format($row['refund_amount'], 2) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No items returned for this invoice.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-md-6">
                        <h4>Payment Details</h4>
                        <table class="table">
                            <?php
                            $sql_payments = "SELECT payment_method, amount FROM sales_payments WHERE invoice_id = ?";
                            $stmt_payments = $conn->prepare($sql_payments);
                            $stmt_payments->bind_param("i", $invoice_id);
                            $stmt_payments->execute();
                            $result_payments = $stmt_payments->get_result();
                            while($payment = $result_payments->fetch_assoc()) {
                                echo "<tr><td>" . $payment['payment_method'] . "</td><td>" . number_format($payment['amount'], 2) . "</td></tr>";
                            }
                            ?>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4>Summary</h4>
                        <p><strong>Sub Total:</strong> <?php echo number_format($invoice['total_amount'], 2); ?></p>
                        <p><strong>Total GST:</strong> <?php echo number_format($invoice['total_gst'], 2); ?></p>
                        <p><strong>Discount:</strong> - <?php echo number_format($invoice['discount_amount'], 2); ?></p>
                        <p><strong>Grand Total:</strong> <?php echo number_format($invoice['total_amount'] + $invoice['total_gst'] - $invoice['discount_amount'], 2); ?></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
