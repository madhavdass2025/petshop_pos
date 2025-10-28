<?php
?>
<?php
include './core/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $owner_id = $_POST['owner_id'];
    $name = $_POST['name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $dob = $_POST['dob'];

    $sql = "INSERT INTO pets (owner_id, name, species, breed, dob) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $owner_id, $name, $species, $breed, $dob);

    if ($stmt->execute()) {
        header("location: pets.php");
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
<?php
if (!isset($_SESSION['user_id'])) {
    header("location: ./index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Pet - Pet Clinic Pharmacy POS</title>
</head>
<body>
    <?php include './includes/sidebar.php'; ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Pet</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form action="add_pet.php" method="post">
                            <div class="form-group">
                                <label for="owner_id">Owner</label>
                                <select name="owner_id" id="owner_id" class="form-control" required>
                                    <?php
                                    $sql = "SELECT id, name FROM customers ORDER BY name";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="species">Species</label>
                                <input type="text" name="species" id="species" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="breed">Breed</label>
                                <input type="text" name="breed" id="breed" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="dob">Date of Birth</label>
                                <input type="date" name="dob" id="dob" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Add Pet</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
<?php include './includes/footer.php'; ?>
