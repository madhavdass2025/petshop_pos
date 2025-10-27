<?php
include './core/db_connect.php';
if(isset($_POST["product_id"]))
{
    $output = '';
    $sql = "SELECT id, batch_number, expiry_date, quantity, selling_price FROM stock WHERE product_id = '".$_POST["product_id"]."' AND quantity > 0 ORDER BY expiry_date ASC";
    $result = $conn->query($sql);
    $output .= '<div class="form-group">';
    $output .= '<label for="batch_select">Select Batch</label>';
    $output .= '<select id="batch_select" class="form-control">';
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $output .= '<option value="'.$row["id"].'">'.$row["batch_number"].' (Expires: '.$row["expiry_date"].') - Avl Qty: '.$row["quantity"].'</option>';
        }
    }
    else
    {
        $output .= '<option>No stock available</option>';
    }
    $output .= '</select>';
    $output .= '</div>';
    $output .= '<div class="form-group">';
    $output .= '<label for="selling_price">Selling Price</label>';
    $output .= '<input type="number" step="0.01" id="selling_price" class="form-control" value="'.$row["selling_price"].'">'; // Assuming selling price is the same for all batches
    $output .= '</div>';
    $output .= '<div class="form-group">';
    $output .= '<label for="quantity">Quantity</label>';
    $output .= '<input type="number" id="quantity" class="form-control" min="1">';
    $output .= '</div>';
    $output .= '<button type="submit" class="btn btn-primary">Add to Cart</button>';
    echo $output;
}
?>