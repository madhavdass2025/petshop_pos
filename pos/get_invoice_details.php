<?php
include './core/db_connect.php';

if (isset($_POST['invoice_id'])) {
    $invoice_id = $_POST['invoice_id'];

    $sql = "SELECT si.*, sii.id as item_id, sii.quantity, sii.unit_price, p.name, s.batch_number, s.id as stock_id
            FROM sales_invoices si
            JOIN sales_invoice_items sii ON si.id = sii.invoice_id
            JOIN stock s ON sii.stock_id = s.id
            JOIN products p ON s.product_id = p.id
            WHERE si.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $invoice_details = $result->fetch_all(MYSQLI_ASSOC);
        $invoice = $invoice_details[0];

        echo "<h4>Invoice #" . $invoice['id'] . "</h4>";
        echo "<p><strong>Customer:</strong> " . $invoice['customer_name'] . "</p>";
        echo "<p><strong>Date:</strong> " . $invoice['invoice_date'] . "</p>";
        echo "<hr>";

        echo "<form action='submit_sale_return.php' method='post'>";
        echo "<input type='hidden' name='invoice_id' value='" . $invoice['id'] . "'>";
        echo "<table class='table table-bordered'>";
        echo "<thead><tr><th>Product</th><th>Batch</th><th>Sold Qty</th><th>Return Qty</th></tr></thead>";
        echo "<tbody>";

        foreach ($invoice_details as $item) {
            echo "<tr>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>" . $item['batch_number'] . "</td>";
            echo "<td>" . $item['quantity'] . "</td>";
            echo "<td>";
            echo "<input type='number' name='items[" . $item['item_id'] . "][quantity]' class='form-control' value='0' min='0' max='" . $item['quantity'] . "'>";
            echo "<input type='hidden' name='items[" . $item['item_id'] . "][stock_id]' value='" . $item['stock_id'] . "'>";
            echo "<input type='hidden' name='items[" . $item['item_id'] . "][unit_price]' value='" . $item['unit_price'] . "'>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
        echo "<div class='form-group'><label for='reason'>Reason for Return</label><textarea name='reason' class='form-control'></textarea></div>";
        echo "<button type='submit' class='btn btn-danger'>Process Return</button>";
        echo "</form>";

    } else {
        echo "<div class='alert alert-danger'>Invoice not found.</div>";
    }
}
?>
