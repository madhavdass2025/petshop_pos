<?php
session_start();
include './core/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $invoice_id = $_POST['invoice_id'];
    $reason = $_POST['reason'];
    $items = $_POST['items'];
    $total_refund_amount = 0;

    $conn->begin_transaction();

    try {
        // Create a record in the sales_returns table
        $sql = "INSERT INTO sales_returns (invoice_id, reason, total_refund_amount) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        // We will update the total_refund_amount later
        $placeholder_amount = 0.00;
        $stmt->bind_param("isd", $invoice_id, $reason, $placeholder_amount);
        $stmt->execute();
        $return_id = $stmt->insert_id;

        foreach ($items as $item_id => $details) {
            $return_quantity = (int)$details['quantity'];
            if ($return_quantity > 0) {
                $stock_id = $details['stock_id'];
                $unit_price = $details['unit_price'];
                $refund_amount = $return_quantity * $unit_price;
                $total_refund_amount += $refund_amount;

                // Add the item to sales_return_items
                $sql = "INSERT INTO sales_return_items (return_id, stock_id, quantity, refund_amount) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iiid", $return_id, $stock_id, $return_quantity, $refund_amount);
                $stmt->execute();

                // Update the stock quantity
                $sql = "UPDATE stock SET quantity = quantity + ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $return_quantity, $stock_id);
                $stmt->execute();
            }
        }

        // Now update the total refund amount in the sales_returns table
        $sql = "UPDATE sales_returns SET total_refund_amount = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $total_refund_amount, $return_id);
        $stmt->execute();

        $conn->commit();
        header("location: add_sale_return.php?success=1");

    } catch (Exception $e) {
        $conn->rollback();
        header("location: add_sale_return.php?error=1");
    }
}
?>
