<?php
include './core/db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sales Return - Pet Clinic Pharmacy POS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
                <h2>Sales Return</h2>
                <div class="form-group">
                    <label for="invoice_id_search">Enter Invoice ID</label>
                    <input type="text" id="invoice_id_search" class="form-control" style="width: 200px;">
                    <button id="search_invoice" class="btn btn-primary mt-2">Search</button>
                </div>
                <hr>
                <div id="invoice_details">
                    <!-- Invoice details will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#search_invoice').click(function(){
                var invoice_id = $('#invoice_id_search').val();
                if(invoice_id != ''){
                    $.ajax({
                        url: 'get_invoice_details.php',
                        method: 'POST',
                        data: {invoice_id: invoice_id},
                        success: function(data){
                            $('#invoice_details').html(data);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
