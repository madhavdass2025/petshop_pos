<?php
include './core/db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>POS - Medical Shop POS</title>
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
            <div class="col-md-5">
                <h2>Point of Sale</h2>
                <form id="add_to_cart_form">
                    <div class="form-group">
                        <label for="product_search">Search Product</label>
                        <input type="text" id="product_search" class="form-control" placeholder="Start typing product name...">
                        <div id="product_suggestions"></div>
                    </div>
                    <div id="selected_product_details"></div>
                </form>
            </div>
            <div class="col-md-5">
                <h4>Cart</h4>
                <form id="sale-form" action="submit_sale.php" method="post">
                    <table class="table table-bordered" id="cart_table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Batch</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Cart items will be added here -->
                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Total: <span id="grand_total">0.00</span></h4>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount">Discount</label>
                                <input type="number" name="discount" id="discount" class="form-control" value="0">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="customer_name">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="physician_name">Physician Name</label>
                        <input type="text" name="physician_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="prescription_id">Prescription ID</label>
                        <input type="text" name="prescription_id" class="form-control">
                    </div>
                    <hr>
                    <h4>Payments</h4>
                    <div id="payment_fields">
                        <!-- Payment fields will be added here -->
                    </div>
                    <button type="button" id="add_payment" class="btn btn-primary btn-sm">Add Payment</button>
                    <hr>
                    <button type="submit" name="action" value="paid" class="btn btn-success">Complete Sale</button>
                    <button type="submit" name="action" value="credit" class="btn btn-warning">Place on Credit</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#product_search").keyup(function(){
                var query = $(this).val();
                if(query != ''){
                    $.ajax({
                        url: "search_products.php",
                        method: "POST",
                        data: {query:query},
                        success: function(data){
                            $('#product_suggestions').fadeIn();
                            $('#product_suggestions').html(data);
                        }
                    });
                }
            });
            $(document).on('click', 'li', function(){
                $('#product_search').val($(this).text());
                $('#product_suggestions').fadeOut();
                var product_id = $(this).data('product-id');
                $.ajax({
                    url: "get_product_details.php",
                    method: "POST",
                    data: {product_id:product_id},
                    success: function(data){
                        $('#selected_product_details').html(data);
                    }
                });
            });

            var i = 0;
            $('#add_to_cart_form').on('submit', function(event){
                event.preventDefault();
                var product_name = $('#product_search').val();
                var batch_id = $('#batch_select').val();
                var batch_number = $('#batch_select option:selected').text();
                var quantity = $('#quantity').val();
                var price = $('#selling_price').val();
                var total = quantity * price;

                var new_row = '<tr id="row'+i+'">';
                new_row += '<td>'+product_name+'<input type="hidden" name="items['+i+'][stock_id]" value="'+batch_id+'"></td>';
                new_row += '<td>'+batch_number+'</td>';
                new_row += '<td>'+quantity+'<input type="hidden" name="items['+i+'][quantity]" value="'+quantity+'"></td>';
                new_row += '<td>'+price+'<input type="hidden" name="items['+i+'][unit_price]" value="'+price+'"></td>';
                new_row += '<td>'+total+'</td>';
                new_row += '<td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn-sm btn_remove">X</button></td>';
                new_row += '</tr>';
                $('#cart_table tbody').append(new_row);
                i++;
                $('#add_to_cart_form')[0].reset();
                $('#selected_product_details').html('');

            });

            $(document).on('click', '.btn_remove', function(){
                var button_id = $(this).attr("id");
                $('#row'+button_id+'').remove();
                update_total();
            });

            $('#discount').on('input', function() {
                update_total();
            });

            var payment_count = 0;
            $('#add_payment').click(function() {
                payment_count++;
                var new_payment = '<div class="row" id="payment_row_'+payment_count+'">';
                new_payment += '<div class="col-md-5"><div class="form-group"><select name="payments['+payment_count+'][method]" class="form-control"><option value="Cash">Cash</option><option value="Card">Card</option><option value="UPI">UPI</option></select></div></div>';
                new_payment += '<div class="col-md-5"><div class="form-group"><input type="number" name="payments['+payment_count+'][amount]" class="form-control payment-amount" placeholder="Amount"></div></div>';
                new_payment += '<div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-payment" data-row="'+payment_count+'">X</button></div>';
                new_payment += '</div>';
                $('#payment_fields').append(new_payment);
            });

            $(document).on('click', '.remove-payment', function(){
                var row_id = $(this).data('row');
                $('#payment_row_'+row_id).remove();
            });

            function update_total() {
                var total = 0;
                $('#cart_table tbody tr').each(function() {
                    total += parseFloat($(this).find('td:eq(4)').text());
                });
                var discount = parseFloat($('#discount').val());
                if(isNaN(discount)) {
                    discount = 0;
                }
                var grand_total = total - discount;
                $('#grand_total').text(grand_total.toFixed(2));
            }

            // Update total when cart is modified
            var original_append = $.fn.append;
            $.fn.append = function() {
                original_append.apply(this, arguments);
                if (this.selector === '#cart_table tbody') {
                    update_total();
                }
            };

        });
    </script>
</body>
</html>