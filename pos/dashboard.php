<?php
include 'core/db_connect.php';
?>
<?php include 'includes/header.php'; ?>
<?php
// This check must come AFTER the header, which starts the session.
if (!isset($_SESSION['user_id'])) {
    header("location: index.php");
    exit();
}
?>
<?php include 'includes/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>
                                <?php
                                $sql = "SELECT SUM(total_amount) as total_sales FROM sales_invoices";
                                $result = $conn->query($sql);
                                $row = $result->fetch_assoc();
                                echo "₹" . number_format($row['total_sales'], 2);
                                ?>
                            </h3>
                            <p>Total Sales</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>
                                <?php
                                $sql = "SELECT COUNT(*) as total_products FROM products";
                                $result = $conn->query($sql);
                                $row = $result->fetch_assoc();
                                echo $row['total_products'];
                                ?>
                            </h3>
                            <p>Total Products</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>
                                <?php
                                $sql = "SELECT COUNT(*) as total_suppliers FROM suppliers";
                                $result = $conn->query($sql);
                                $row = $result->fetch_assoc();
                                echo $row['total_suppliers'];
                                ?>
                            </h3>
                            <p>Total Suppliers</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>
                                <?php
                                $sql = "SELECT COUNT(*) as out_of_stock FROM products WHERE id NOT IN (SELECT product_id FROM stock WHERE quantity > 0)";
                                $result = $conn->query($sql);
                                $row = $result->fetch_assoc();
                                echo $row['out_of_stock'];
                                ?>
                            </h3>
                            <p>Out of Stock</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include 'includes/footer.php'; ?>
