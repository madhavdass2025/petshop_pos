<?php
session_start();
include '../../core/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $physician_name = $_POST['physician_name'];
    $prescription_id = $_POST['prescription_id'];
    $total_amount = 0;
    $total_gst = 0;

    // First, calculate the total amount and GST
    $items = $_POST['items'];
    foreach ($items as $item) {
        $quantity = $item['quantity'];
        $unit_price = $item['unit_price'];
        $total_amount += $quantity * $unit_price;

        // Calculate GST
        $stock_id = $item['stock_id'];
        $sql = "SELECT p.gst_rate FROM stock s JOIN products p ON s.product_id = p.id WHERE s.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $stock_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $gst_rate = $product['gst_rate'];
        $total_gst += ($quantity * $unit_price * $gst_rate) / 100;
    }

    $sql = "INSERT INTO sales_invoices (customer_name, physician_name, prescription_id, total_amount, total_gst) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddd", $customer_name, $physician_name, $prescription_id, $total_amount, $total_gst);
    $stmt->execute();
    $invoice_id = $stmt->insert_id;

    foreach ($items as $item) {
        $stock_id = $item['stock_id'];
        $quantity = $item['quantity'];
        $unit_price = $item['unit_price'];

        // Get GST rate for the product
        $sql = "SELECT p.gst_rate FROM stock s JOIN products p ON s.product_id = p.id WHERE s.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $stock_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $gst_rate = $product['gst_rate'];
        $gst_amount = ($quantity * $unit_price * $gst_rate) / 100;

        $sql = "INSERT INTO sales_invoice_items (invoice_id, stock_id, quantity, unit_price, gst_amount) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiidd", $invoice_id, $stock_id, $quantity, $unit_price, $gst_amount);
        $stmt->execute();

        // Update stock
        $sql = "UPDATE stock SET quantity = quantity - ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $quantity, $stock_id);
        $stmt->execute();
    }

    header("location: pos.php");
}
?>