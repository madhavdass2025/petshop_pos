<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: ./index.php");
    exit();
}
include './core/db_connect.php';

$po_id = $_GET['id'];

$sql = "SELECT po.id, s.name as supplier_name, po.po_date, po.status
        FROM purchase_orders po
        JOIN suppliers s ON po.supplier_id = s.id
        WHERE po.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $po_id);
$stmt->execute();
$result = $stmt->get_result();
$po = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Purchase Order - Medical Shop POS</title>
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
                <h2>Purchase Order #<?php echo $po['id']; ?></h2>
                <p><strong>Supplier:</strong> <?php echo $po['supplier_name']; ?></p>
                <p><strong>PO Date:</strong> <?php echo $po['po_date']; ?></p>
                <p><strong>Status:</strong> <?php echo $po['status']; ?></p>
                <hr>
                <h4>Items</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT p.name, poi.quantity, poi.unit_price
                                FROM purchase_order_items poi
                                JOIN products p ON poi.product_id = p.id
                                WHERE poi.po_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $po_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['quantity'] . "</td>";
                            echo "<td>" . $row['unit_price'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <hr>
                <?php if ($po['status'] == 'Sent'): ?>
                <a href="receive_grn.php?po_id=<?php echo $po['id']; ?>" class="btn btn-success">Receive GRN</a>
                <?php endif; ?>

                <hr>
                <h4>Change Status</h4>
                <form action="update_po_status.php" method="post">
                    <input type="hidden" name="po_id" value="<?php echo $po_id; ?>">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Draft" <?php if($po['status'] == 'Draft') echo 'selected'; ?>>Draft</option>
                            <option value="Sent" <?php if($po['status'] == 'Sent') echo 'selected'; ?>>Sent</option>
                            <option value="Cancelled" <?php if($po['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>