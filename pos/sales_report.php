<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: ./index.php");
    exit();
}
include './core/db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sales Report - Medical Shop POS</title>
</head>
<body>
    <?php include './includes/header.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <?php include './includes/sidebar.php'; ?>
            </div>
            <div class="col-md-10">
                <h2>Sales Report</h2>
                <p>This is a placeholder for the sales report. More functionality can be added here.</p>
            </div>
        </div>
    </div>
</body>
</html>