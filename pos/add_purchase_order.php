<?php
include './core/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_id = $_POST['supplier_id'];
    $po_date = $_POST['po_date'];
    $status = "Draft";

    $sql = "INSERT INTO purchase_orders (supplier_id, po_date, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $supplier_id, $po_date, $status);
    $stmt->execute();
    $po_id = $stmt->insert_id;

    $products = $_POST['products'];
    foreach ($products as $product) {
        $product_id = $product['id'];
        $quantity = $product['quantity'];
        $unit_price = $product['unit_price'];

        $sql = "INSERT INTO purchase_order_items (po_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $po_id, $product_id, $quantity, $unit_price);
        $stmt->execute();
    }
    header("location: purchase_orders.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Purchase Order - Medical Shop POS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
                <h2>Add Purchase Order</h2>
                <form action="add_purchase_order.php" method="post">
                    <div class="form-group">
                        <label for="supplier_id">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-control" required>
                            <?php
                            $sql = "SELECT * FROM suppliers";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="po_date">PO Date</label>
                        <input type="date" name="po_date" id="po_date" class="form-control" required>
                    </div>
                    <hr>
                    <h4>Products</h4>
                    <table class="table table-bordered" id="products_table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Product rows will be added here -->
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary" id="add_product_row">Add Product</button>
                    <hr>
                    <button type="submit" class="btn btn-success">Create Purchase Order</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var i = 0;
            $("#add_product_row").click(function(){
                var new_row = '<tr id="row'+i+'">';
                new_row += '<td><select name="products['+i+'][id]" class="form-control" required><?php $sql = "SELECT * FROM products"; $result = $conn->query($sql); while($row = $result->fetch_assoc()){ echo "<option value=\'" . $row['id'] . "\'>" . $row['name'] . "</option>"; } ?></select></td>';
                new_row += '<td><input type="number" name="products['+i+'][quantity]" class="form-control" required></td>';
                new_row += '<td><input type="number" step="0.01" name="products['+i+'][unit_price]" class="form-control" required></td>';
                new_row += '<td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td>';
                new_row += '</tr>';
                $('#products_table tbody').append(new_row);
                i++;
            });
            $(document).on('click', '.btn_remove', function(){
                var button_id = $(this).attr("id");
                $('#row'+button_id+'').remove();
            });
        });
    </script>
</body>
</html>