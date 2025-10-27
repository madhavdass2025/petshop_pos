<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: ./index.php");
    exit();
}
include './core/db_connect.php';

$po_id = $_GET['po_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $grn_date = $_POST['grn_date'];
    $supplier_invoice_number = $_POST['supplier_invoice_number'];

    $sql = "INSERT INTO goods_receipt_notes (po_id, grn_date, supplier_invoice_number) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $po_id, $grn_date, $supplier_invoice_number);
    $stmt->execute();
    $grn_id = $stmt->insert_id;

    $items = $_POST['items'];
    foreach ($items as $item) {
        $product_id = $item['product_id'];
        $batch_number = $item['batch_number'];
        $expiry_date = $item['expiry_date'];
        $quantity = $item['quantity'];
        $purchase_price = $item['purchase_price'];
        $gst_amount = 0; // This will be calculated based on the product's GST rate

        // Add to grn_items
        $sql = "INSERT INTO grn_items (grn_id, product_id, batch_number, expiry_date, quantity, purchase_price, gst_amount) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissddd", $grn_id, $product_id, $batch_number, $expiry_date, $quantity, $purchase_price, $gst_amount);
        $stmt->execute();

        // Update stock
        $sql = "INSERT INTO stock (product_id, batch_number, expiry_date, quantity, purchase_price, selling_price, location) VALUES (?, ?, ?, ?, ?, 0, '')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issid", $product_id, $batch_number, $expiry_date, $quantity, $purchase_price);
        $stmt->execute();
    }

    // Update PO status
    $sql = "UPDATE purchase_orders SET status = 'Received' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $po_id);
    $stmt->execute();

    header("location: purchase_orders.php");
}

$sql = "SELECT p.id as product_id, p.name as product_name, poi.quantity
        FROM purchase_order_items poi
        JOIN products p ON poi.product_id = p.id
        WHERE poi.po_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $po_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Receive GRN - Medical Shop POS</title>
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
                <h2>Receive GRN for PO #<?php echo $po_id; ?></h2>
                <form action="receive_grn.php?po_id=<?php echo $po_id; ?>" method="post">
                    <div class="form-group">
                        <label for="grn_date">GRN Date</label>
                        <input type="date" name="grn_date" id="grn_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="supplier_invoice_number">Supplier Invoice Number</label>
                        <input type="text" name="supplier_invoice_number" id="supplier_invoice_number" class="form-control" required>
                    </div>
                    <hr>
                    <h4>Items</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Batch Number</th>
                                <th>Expiry Date</th>
                                <th>Purchase Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><input type="text" name="items[<?php echo $row['product_id']; ?>][batch_number]" class="form-control" required></td>
                                <td><input type="date" name="items[<?php echo $row['product_id']; ?>][expiry_date]" class="form-control" required></td>
                                <td><input type="number" step="0.01" name="items[<?php echo $row['product_id']; ?>][purchase_price]" class="form-control" required></td>
                                <input type="hidden" name="items[<?php echo $row['product_id']; ?>][product_id]" value="<?php echo $row['product_id']; ?>">
                                <input type="hidden" name="items[<?php echo $row['product_id']; ?>][quantity]" value="<?php echo $row['quantity']; ?>">
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-success">Receive Stock</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>