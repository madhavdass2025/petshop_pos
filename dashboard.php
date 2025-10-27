<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit();
}
?>
<?php
include 'core/db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Medical Shop POS</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <?php include 'includes/sidebar.php'; ?>
            </div>
            <div class="col-md-10">
                <h2>Dashboard</h2>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Total Sales</div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php
                                    $sql = "SELECT SUM(total_amount) as total_sales FROM sales_invoices";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo "₹" . number_format($row['total_sales'], 2);
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header">Total Products</div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php
                                    $sql = "SELECT COUNT(*) as total_products FROM products";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo $row['total_products'];
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-header">Total Suppliers</div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php
                                    $sql = "SELECT COUNT(*) as total_suppliers FROM suppliers";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo $row['total_suppliers'];
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-header">Out of Stock</div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php
                                    $sql = "SELECT COUNT(*) as out_of_stock FROM products WHERE id NOT IN (SELECT product_id FROM stock WHERE quantity > 0)";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    echo $row['out_of_stock'];
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>