<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: ../../index.php");
    exit();
}
include '../../core/db_connect.php';

$id = $_GET['id'];

$sql = "DELETE FROM suppliers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("location: suppliers.php");
} else {
    echo "Error deleting record: " . $conn->error;
}
?>