<?php
session_start();
include('./includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch sales records
$stmt = $pdo->query("SELECT sales.id, products.name AS product_name, sales.quantity, sales.total_price, sales.sale_date 
                     FROM sales 
                     JOIN products ON sales.product_id = products.id 
                     ORDER BY sales.sale_date DESC");
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">

</head>
<body>

<!-- Sidebar (same as in dashboard.php) -->

<div class="container-fluid p-4">
    <h3 class="mb-4">Sales Management</h3>
    <a href="add_sale.php" class="btn btn-success mb-3">Add Sale</a>

    <!-- Sales Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Sale Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?= $sale['id'] ?></td>
                    <td><?= $sale['product_name'] ?></td>
                    <td><?= $sale['quantity'] ?></td>
                    <td>$<?= number_format($sale['total_price'], 2) ?></td>
                    <td><?= $sale['sale_date'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
