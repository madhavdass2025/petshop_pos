<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: ../../index.php");
    exit();
}
include '../../core/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $generic_name = $_POST['generic_name'];
    $storage_condition = $_POST['storage_condition'];
    $unit_of_measure = $_POST['unit_of_measure'];
    $hsn_sac_code = $_POST['hsn_sac_code'];
    $gst_rate = $_POST['gst_rate'];
    $reorder_level = $_POST['reorder_level'];

    $sql = "INSERT INTO products (name, generic_name, storage_condition, unit_of_measure, hsn_sac_code, gst_rate, reorder_level) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssdi", $name, $generic_name, $storage_condition, $unit_of_measure, $hsn_sac_code, $gst_rate, $reorder_level);

    if ($stmt->execute()) {
        header("location: products.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product - Medical Shop POS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <?php include '../../includes/sidebar.php'; ?>
            </div>
            <div class="col-md-10">
                <h2>Add Product</h2>
                <form action="add_product.php" method="post">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="generic_name">Generic Name</label>
                        <input type="text" name="generic_name" id="generic_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="storage_condition">Storage Condition</label>
                        <input type="text" name="storage_condition" id="storage_condition" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="unit_of_measure">Unit of Measure</label>
                        <input type="text" name="unit_of_measure" id="unit_of_measure" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="hsn_sac_code">HSN/SAC Code</label>
                        <input type="text" name="hsn_sac_code" id="hsn_sac_code" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="gst_rate">GST Rate</label>
                        <input type="number" step="0.01" name="gst_rate" id="gst_rate" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="reorder_level">Reorder Level</label>
                        <input type="number" name="reorder_level" id="reorder_level" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>