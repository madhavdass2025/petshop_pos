<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit();
}
include 'core/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $po_id = $_POST['po_id'];
    $status = $_POST['status'];

    $sql = "UPDATE purchase_orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $po_id);

    if ($stmt->execute()) {
        header("location: view_purchase_order.php?id=" . $po_id);
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>