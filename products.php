<?php
session_start();
include('./includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch products and categories for display
$stmt = $pdo->query("SELECT p.id, p.name, c.name as category, p.price, p.quantity FROM products p JOIN categories c ON p.category_id = c.id");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    // Return only the table content for AJAX requests
    ?>
    <div class="container-fluid p-4">
        <h3 class="mb-4">Products Management</h3>
        <a href="add_product.php" class="btn btn-success mb-3">Add Product</a>

        <!-- Products Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody id="product-list">
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category']) ?></td>
                            <td><?= htmlspecialchars($product['price']) ?></td>
                            <td><?= htmlspecialchars($product['quantity']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">

    <script>
        $(document).ready(function() {
            function loadProducts() {
                $.ajax({
                    url: 'fetch_products.php',
                    method: 'GET',
                    success: function(data) {
                        $('#product-list').html(data);
                    }
                });
            }

            loadProducts();

            // Refresh product list every 10 seconds
            setInterval(loadProducts, 10000);
        });
    </script>
</head>
<body></body>


<div class="container-fluid p-4"></div>
    <h3 class="mb-4">Products Management</h3>
    <a href="add_product.php" class="btn btn-success mb-3">Add Product</a>

    <!-- Products Table -->
    <div class="table-responsive"></div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody id="product-list">
                <?php foreach ($products as $product): ?>
                    <tr></tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['category']) ?></td>
                        <td><?= htmlspecialchars($product['price']) ?></td>
                        <td><?= htmlspecialchars($product['quantity']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
