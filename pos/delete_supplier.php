<?php
include './core/db_connect.php';

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