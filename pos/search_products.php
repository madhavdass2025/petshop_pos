<?php
include './core/db_connect.php';
if(isset($_POST["query"]))
{
    $output = '';
    $query = "SELECT * FROM products WHERE name LIKE ?";
    $stmt = $conn->prepare($query);
    $search_term = "%".$_POST["query"]."%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    $output = '<ul class="list-unstyled">';
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $output .= '<li data-product-id="'.$row["id"].'">'.$row["name"].'</li>';
        }
    }
    else
    {
        $output .= '<li>Product not found</li>';
    }
    $output .= '</ul>';
    echo $output;
}
?>