<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: ../../index.php");
    exit();
}
include '../../core/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $gstin = $_POST['gstin'];

    $sql = "INSERT INTO suppliers (name, contact, gstin) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $contact, $gstin);

    if ($stmt->execute()) {
        header("location: suppliers.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Supplier - Medical Shop POS</title>
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
                <h2>Add Supplier</h2>
                <form action="add_supplier.php" method="post">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="contact">Contact</label>
                        <input type="text" name="contact" id="contact" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="gstin">GSTIN</label>
                        <input type="text" name="gstin" id="gstin" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Supplier</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>