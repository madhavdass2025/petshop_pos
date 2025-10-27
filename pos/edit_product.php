<?php
include './core/db_connect.php';

$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $generic_name = $_POST['generic_name'];
    $storage_condition = $_POST['storage_condition'];
    $unit_of_measure = $_POST['unit_of_measure'];
    $hsn_sac_code = $_POST['hsn_sac_code'];
    $gst_rate = $_POST['gst_rate'];
    $reorder_level = $_POST['reorder_level'];

    $sql = "UPDATE products SET name = ?, generic_name = ?, storage_condition = ?, unit_of_measure = ?, hsn_sac_code = ?, gst_rate = ?, reorder_level = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssdii", $name, $generic_name, $storage_condition, $unit_of_measure, $hsn_sac_code, $gst_rate, $reorder_level, $id);

    if ($stmt->execute()) {
        header("location: products.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<?php include './includes/header.php'; ?>
<?php
if (!isset($_SESSION['user_id'])) {
    header("location: ./index.php");
    exit();
}
?>
<?php include './includes/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Product</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="edit_product.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $product['name']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="generic_name">Generic Name</label>
                                    <input type="text" name="generic_name" id="generic_name" class="form-control" value="<?php echo $product['generic_name']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="storage_condition">Storage Condition</label>
                                    <input type="text" name="storage_condition" id="storage_condition" class="form-control" value="<?php echo $product['storage_condition']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="unit_of_measure">Unit of Measure</label>
                                    <input type="text" name="unit_of_measure" id="unit_of_measure" class="form-control" value="<?php echo $product['unit_of_measure']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="hsn_sac_code">HSN/SAC Code</label>
                                    <input type="text" name="hsn_sac_code" id="hsn_sac_code" class="form-control" value="<?php echo $product['hsn_sac_code']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="gst_rate">GST Rate</label>
                                    <input type="number" step="0.01" name="gst_rate" id="gst_rate" class="form-control" value="<?php echo $product['gst_rate']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="reorder_level">Reorder Level</label>
                                    <input type="number" name="reorder_level" id="reorder_level" class="form-control" value="<?php echo $product['reorder_level']; ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include './includes/footer.php'; ?>
