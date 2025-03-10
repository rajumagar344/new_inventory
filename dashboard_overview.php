<?php
session_start();
include('./includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch statistics
$stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

$stmt = $pdo->query("SELECT COUNT(*) as total_categories FROM categories");
$total_categories = $stmt->fetch(PDO::FETCH_ASSOC)['total_categories'];

$stmt = $pdo->query("SELECT COUNT(*) as total_products FROM products");
$total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

$stmt = $pdo->query("SELECT SUM(total_price) as total_sales FROM sales");
$total_sales = $stmt->fetch(PDO::FETCH_ASSOC)['total_sales'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">

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

<div class="row">
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-users fa-2x"></i>
                <h5>Users</h5>
                <p><?= $total_users ?></p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-th-list fa-2x"></i>
                <h5>Categories</h5>
                <p><?= $total_categories ?></p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-boxes fa-2x"></i>
                <h5>Products</h5>
                <p><?= $total_products ?></p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-dollar-sign fa-2x"></i>
                <h5>Total Sales</h5>
                <p><?= $total_sales ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Sales Chart -->
<div class="mt-4">
    <h4>Sales Analytics</h4>
    <canvas id="salesChart"></canvas>
</div>

<script>
    $(document).ready(function() {
        function loadSalesChart() {
            $.ajax({
                url: 'fetch_sales_data.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        console.error(data.error);
                        alert('Failed to load sales data.');
                        return;
                    }

                    var labels = [];
                    var sales = [];
                    data.forEach(function(item) {
                        labels.push(item.date);
                        sales.push(item.total_sales);
                    });

                    var ctx = document.getElementById('salesChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Sales',
                                data: sales,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                fill: false
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error: ' + textStatus + ' : ' + errorThrown);
                    alert('Failed to load sales data.');
                }
            });
        }

        loadSalesChart();
    });
</script>

</body>
</html>