<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: ./index.php");
    exit();
}
include './core/db_connect.php';

$id = $_GET['id'];
$sql = "SELECT * FROM suppliers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$supplier = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $gstin = $_POST['gstin'];
    $id = $_POST['id'];

    $sql = "UPDATE suppliers SET name = ?, contact = ?, gstin = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $contact, $gstin, $id);

    if ($stmt->execute()) {
        header("location: suppliers.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<?php include './includes/header.php'; ?>
<?php include './includes/sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Supplier</h1>
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
                            <form action="edit_supplier.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $supplier['id']; ?>">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="<?php echo $supplier['name']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="contact">Contact</label>
                                    <input type="text" name="contact" id="contact" class="form-control" value="<?php echo $supplier['contact']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="gstin">GSTIN</label>
                                    <input type="text" name="gstin" id="gstin" class="form-control" value="<?php echo $supplier['gstin']; ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Supplier</button>
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
