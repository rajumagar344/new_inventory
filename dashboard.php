<?php

session_start();
include ('./includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./assets/js/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script>
        $(document).ready(function() {
            function loadProducts() {
                $.ajax({
                    url: 'fetch_products.php',
                    method: 'GET',
                    success: function(data) {
                        $('#product-list').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading products: ', error);
                    }
                });
            }

            function loadStatistics() {
                $.ajax({
                    url: 'fetch_statistics.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#total_users').text(data.total_users);
                        $('#total_categories').text(data.total_categories);
                        $('#total_products').text(data.total_products);
                        $('#total_sales').text(data.total_sales);
                    }, 
                    error: function(xhr, status, error) {
                        console.error('Error loading statistics: ', error);
                    }
                });
            }

            function loadContent(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        $('#dashboard-content').html(response);
                    },
                    error: function(xhr, status, error) {
                        alert('Failed to load content.' + error);
                    }
                });
            }

            // Load default content (e.g., dashboard overview)
            loadContent('dashboard_overview.php');

            // Handle sidebar link clicks
            $('.sidebar a.nav-link').click(function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                loadContent(url);
            });

            loadProducts();
            loadStatistics();

            // Refresh product list and statistics every 10 seconds
            setInterval(function() {
                loadProducts();
                loadStatistics();
            }, 10000);

            $('#load-categories').click(function() {
                $.ajax({
                    url: 'categories.php',
                    method: 'GET',
                    success: function(response) {
                        $('#dashboard-content').html(response);
                    },
                    error: function() {
                        alert('Failed to load categories.');
                    }
                });
            });

            function loadSalesData() {
                $.ajax({
                    url: 'fetch_sales_data.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            console.error('Server error:', data.error);
                        } else {
                            $('#sales-data').html(JSON.stringify(data));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error: ', status, error);
                    }
                });
            }
            loadSalesData();
            setInterval(loadSalesData, 10000);

            
        });
    </script>
</head>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background: #343a40;
            color: white;
            height: 100vh;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
        }
        .sidebar a:hover {
            background: #495057;
            border-radius: 5px;
        }
        .card {
            border-radius: 10px;
            transition: 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="d-flex flex-column flex-md-row">
    <div class="sidebar flex-shrink-0 p-3 bg-dark text-white">
        <h4>INVENTORY SYSTEM</h4>
        <a href="dashboard_overview.php" class="nav-link text-white"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="users.php" class="nav-link text-white"><i class="fas fa-users"></i> User Management</a>
        <a href="categories.php" class="nav-link text-white"><i class="fas fa-th-list"></i> Categories</a>
        <a href="products.php" class="nav-link text-white"><i class="fas fa-boxes"></i> Products</a>
        <a href="sales.php" class="nav-link text-white"><i class="fas fa-shopping-cart"></i> Sales</a>
        <a href="reports.php" class="nav-link text-white"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="logout.php" class="nav-link text-white"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="container-fluid p-4">
        <h3 class="mb-4">Admin Dashboard</h3>
        <div class="row">
            <div class="col-md-3">total Users: <span id="total_users">0</span><p></p></div>
            <div class="col-md-3"><p>Total Categories: <span id="total_categories">0</span></p></div>
            <div class="col-md-3"><p>total Products: <span id="total_products"></span>0</span></p></div>
            <div class="col-md-3"><p>total Sales: <span id="total_sales"></span>0</span></p></div>
        </div>
        <div id="product-list" class="mt-4"></div>
        <div id="dashboard-content" class="mt-4"></div>
        <div id="sales-data" class="mt-4"></div>
    </div>
</div>

<script src="./assets/js/script.js"></script>

</body>
</html>