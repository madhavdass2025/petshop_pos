<?php
session_start();
include './core/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST['pet_id'];
    $veterinarian_name = $_POST['veterinarian_name'];
    $prescription_id = $_POST['prescription_id'];
    $discount = $_POST['discount'] ?? 0;
    $action = $_POST['action'];
    $items = $_POST['items'];

    $total_amount = 0;
    $total_gst = 0;

    // First, calculate the total amount and GST from the items in the cart
    foreach ($items as $item) {
        $quantity = $item['quantity'];
        $unit_price = $item['unit_price'];
        $sub_total = $quantity * $unit_price;
        $total_amount += $sub_total;

        // Calculate GST for each item
        $stock_id = $item['stock_id'];
        $sql = "SELECT p.gst_rate FROM stock s JOIN products p ON s.product_id = p.id WHERE s.id = ?";
        $stmt_gst = $conn->prepare($sql);
        $stmt_gst->bind_param("i", $stock_id);
        $stmt_gst->execute();
        $result_gst = $stmt_gst->get_result();
        $product_gst = $result_gst->fetch_assoc();
        $gst_rate = $product_gst['gst_rate'];
        $total_gst += ($sub_total * $gst_rate) / 100;
    }

    $grand_total = $total_amount + $total_gst - $discount;
    $total_paid = 0;
    $status = 'Credit'; // Default to Credit

    if ($action == 'paid' && isset($_POST['payments'])) {
        $payments = $_POST['payments'];
        foreach($payments as $payment) {
            $total_paid += $payment['amount'];
        }

        if (abs($total_paid - $grand_total) < 0.01) { // Use a small tolerance for float comparison
            $status = 'Paid';
        } else {
            $status = 'Partial';
        }
    }

    $conn->begin_transaction();
    try {
        // Insert into sales_invoices
        $sql_invoice = "INSERT INTO sales_invoices (pet_id, veterinarian_name, prescription_id, total_amount, total_gst, discount_amount, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_invoice = $conn->prepare($sql_invoice);
        $stmt_invoice->bind_param("issddds", $pet_id, $veterinarian_name, $prescription_id, $total_amount, $total_gst, $discount, $status);
        $stmt_invoice->execute();
        $invoice_id = $stmt_invoice->insert_id;

        // Insert into sales_invoice_items and update stock
        foreach ($items as $item) {
            $stock_id = $item['stock_id'];
            $quantity = $item['quantity'];
            $unit_price = $item['unit_price'];

            $sql = "SELECT p.gst_rate FROM stock s JOIN products p ON s.product_id = p.id WHERE s.id = ?";
            $stmt_gst = $conn->prepare($sql);
            $stmt_gst->bind_param("i", $stock_id);
            $stmt_gst->execute();
            $result_gst = $stmt_gst->get_result();
            $product_gst = $result_gst->fetch_assoc();
            $gst_rate = $product_gst['gst_rate'];
            $gst_amount = ($quantity * $unit_price * $gst_rate) / 100;

            $sql_item = "INSERT INTO sales_invoice_items (invoice_id, stock_id, quantity, unit_price, gst_amount) VALUES (?, ?, ?, ?, ?)";
            $stmt_item = $conn->prepare($sql_item);
            $stmt_item->bind_param("iiidd", $invoice_id, $stock_id, $quantity, $unit_price, $gst_amount);
            $stmt_item->execute();

            $sql_stock = "UPDATE stock SET quantity = quantity - ? WHERE id = ?";
            $stmt_stock = $conn->prepare($sql_stock);
            $stmt_stock->bind_param("ii", $quantity, $stock_id);
            $stmt_stock->execute();
        }

        // Insert into sales_payments
        if ($action == 'paid' && isset($_POST['payments'])) {
            foreach($payments as $payment) {
                $sql_payment = "INSERT INTO sales_payments (invoice_id, payment_method, amount) VALUES (?, ?, ?)";
                $stmt_payment = $conn->prepare($sql_payment);
                $stmt_payment->bind_param("isd", $invoice_id, $payment['method'], $payment['amount']);
                $stmt_payment->execute();
            }
        }

        $conn->commit();
        header("location: pos.php?success=1");
    } catch (Exception $e) {
        $conn->rollback();
        header("location: pos.php?error=1");
    }
}
?>
