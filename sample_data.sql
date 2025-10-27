-- Sample Users
INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', 'admin', 'admin'),
('cashier', 'cashier', 'cashier');

-- Sample Suppliers
INSERT INTO `suppliers` (`name`, `contact`, `gstin`) VALUES
('MedPlus', '9876543210', '29AAAAA0000A1Z5'),
('Apollo Pharmacy', '9876543211', '29BBBBB0000B1Z5');

-- Sample Products
INSERT INTO `products` (`name`, `generic_name`, `storage_condition`, `unit_of_measure`, `hsn_sac_code`, `gst_rate`, `reorder_level`) VALUES
('Dolo 650', 'Paracetamol', 'Room Temperature', 'Strip', '3004', 12.00, 10),
('Crocin', 'Paracetamol', 'Room Temperature', 'Strip', '3004', 12.00, 10),
('Betadine', 'Povidone-Iodine', 'Room Temperature', 'Bottle', '3004', 12.00, 5);

-- Sample Stock
INSERT INTO `stock` (`product_id`, `batch_number`, `expiry_date`, `quantity`, `purchase_price`, `selling_price`, `location`) VALUES
(1, 'DOLO123', '2025-12-31', 100, 25.00, 30.00, 'Rack A'),
(2, 'CRO123', '2025-11-30', 50, 20.00, 25.00, 'Rack B'),
(3, 'BETA123', '2026-01-31', 20, 50.00, 60.00, 'Rack C');

-- Sample Purchase Order
INSERT INTO `purchase_orders` (`supplier_id`, `po_date`, `status`) VALUES
(1, '2024-01-15', 'Received');

-- Sample Purchase Order Items
INSERT INTO `purchase_order_items` (`po_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 1, 100, 25.00),
(1, 2, 50, 20.00);

-- Sample Goods Receipt Note
INSERT INTO `goods_receipt_notes` (`po_id`, `grn_date`, `supplier_invoice_number`) VALUES
(1, '2024-01-16', 'INV-12345');

-- Sample GRN Items
INSERT INTO `grn_items` (`grn_id`, `product_id`, `batch_number`, `expiry_date`, `quantity`, `purchase_price`, `gst_amount`) VALUES
(1, 1, 'DOLO123', '2025-12-31', 100, 25.00, 300.00),
(1, 2, 'CRO123', '2025-11-30', 50, 20.00, 120.00);

-- Sample Sales Invoice
INSERT INTO `sales_invoices` (`customer_name`, `total_amount`, `total_gst`, `physician_name`, `prescription_id`) VALUES
('John Doe', 60.00, 7.20, 'Dr. Smith', 'PS-123');

-- Sample Sales Invoice Items
INSERT INTO `sales_invoice_items` (`invoice_id`, `stock_id`, `quantity`, `unit_price`, `gst_amount`) VALUES
(1, 1, 2, 30.00, 7.20);
