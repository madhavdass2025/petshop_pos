<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link">
        <span class="brand-text font-weight-light">Medical POS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block"><?php echo $_SESSION['username']; ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="modules/purchase/suppliers.php" class="nav-link">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Suppliers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="modules/stock/products.php" class="nav-link">
                        <i class="nav-icon fas fa-pills"></i>
                        <p>Products</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="modules/purchase/purchase_orders.php" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Purchase Orders</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="modules/sales/pos.php" class="nav-link">
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>POS</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            Reports
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="modules/reports/sales_report.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sales Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="modules/reports/reorder_report.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reorder Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="modules/reports/expiry_report.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Expiry Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="modules/reports/dead_stock_report.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dead Stock Report</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
