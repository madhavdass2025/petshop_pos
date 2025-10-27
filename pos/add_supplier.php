<?php
include './core/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $gstin = $_POST['gstin'];

    $sql = "INSERT INTO suppliers (name, contact, gstin) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $contact, $gstin);

    if ($stmt->execute()) {
        header("location: suppliers.php");
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
                    <h1>Add Supplier</h1>
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
                            <form action="add_supplier.php" method="post">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="contact">Contact</label>
                                    <input type="text" name="contact" id="contact" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="gstin">GSTIN</label>
                                    <input type="text" name="gstin" id="gstin" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Supplier</button>
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
