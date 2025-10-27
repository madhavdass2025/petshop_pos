<?php
include './core/db_connect.php';

$days = isset($_GET['days']) ? $_GET['days'] : 30;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Expiry Report - Medical Shop POS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include './includes/header.php'; ?>
<?php
if (!isset($_SESSION['user_id'])) {
    header("location: ./index.php");
    exit();
}
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <?php include './includes/sidebar.php'; ?>
            </div>
            <div class="col-md-10">
                <h2>Expiry Report</h2>
                <form class="form-inline mb-3">
                    <label for="days" class="mr-2">Expiring in:</label>
                    <select name="days" id="days" class="form-control mr-2" onchange="this.form.submit()">
                        <option value="30" <?php if($days == 30) echo 'selected'; ?>>30 Days</option>
                        <option value="60" <?php if($days == 60) echo 'selected'; ?>>60 Days</option>
                        <option value="90" <?php if($days == 90) echo 'selected'; ?>>90 Days</option>
                    </select>
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Batch Number</th>
                            <th>Expiry Date</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT p.name, s.batch_number, s.expiry_date, s.quantity
                                FROM stock s
                                JOIN products p ON s.product_id = p.id
                                WHERE s.expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $days);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['batch_number'] . "</td>";
                                echo "<td>" . $row['expiry_date'] . "</td>";
                                echo "<td>" . $row['quantity'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No products expiring within the selected period</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>