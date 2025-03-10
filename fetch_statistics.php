<?php
include('./includes/db.php');

// Fetch total users
$stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

// Fetch total categories
$stmt = $pdo->query("SELECT COUNT(*) as total_categories FROM categories");
$total_categories = $stmt->fetch(PDO::FETCH_ASSOC)['total_categories'];

// Fetch total products
$stmt = $pdo->query("SELECT COUNT(*) as total_products FROM products");
$total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

// Fetch total sales
$stmt = $pdo->query("SELECT SUM(amount) as total_sales FROM sales");
$total_sales = $stmt->fetch(PDO::FETCH_ASSOC)['total_sales'];

echo json_encode([
    'total_users' => $total_users,
    'total_categories' => $total_categories,
    'total_products' => $total_products,
    'total_sales' => $total_sales
]);
?>
