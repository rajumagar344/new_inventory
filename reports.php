<?php
session_start();
include('./includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch total sales
$stmt = $pdo->query("SELECT SUM(total_price) as total_sales FROM sales");
$total_sales = $stmt->fetch(PDO::FETCH_ASSOC)['total_sales'];

// Fetch total products sold
$stmt = $pdo->query("SELECT SUM(quantity) as total_quantity FROM sales");
$total_quantity = $stmt->fetch(PDO::FETCH_ASSOC)['total_quantity'];

// Fetch product-wise sales
$stmt = $pdo->query("SELECT products.name, SUM(sales.quantity) as sold_quantity, SUM(sales.total_price) as revenue 
                     FROM sales 
                     JOIN products ON sales.product_id = products.id 
                     GROUP BY sales.product_id 
                     ORDER BY revenue DESC");
$product_sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">

</head>
<body>

<!-- Sidebar (same as in dashboard.php) -->

<div class="container-fluid p-4">
    <h3 class="mb-4">Sales Reports</h3>

    <div class="row">
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Total Sales Revenue</h5>
                <p class="fs-4">$<?= number_format($total_sales, 2) ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Total Products Sold</h5>
                <p class="fs-4"><?= $total_quantity ?> items</p>
            </div>
        </div>
    </div>

    <h4 class="mt-4">Product Sales Breakdown</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity Sold</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($product_sales as $sale): ?>
                <tr>
                    <td><?= $sale['name'] ?></td>
                    <td><?= $sale['sold_quantity'] ?></td>
                    <td>$<?= number_format($sale['revenue'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
