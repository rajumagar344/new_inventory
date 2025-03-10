<?php

include('./includes/db.php');

// Fetch total users
$stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
$users = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch total categories
$stmt = $pdo->query("SELECT COUNT(*) as total FROM categories");
$categories = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch total products
$stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
$products = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch total sales
$stmt = $pdo->query("SELECT SUM(total_price) as total FROM sales");
$sales = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch sales data for the chart
$stmt = $pdo->query("SELECT DATE(created_at) as date, SUM(total_price) as total_sales FROM sales GROUP BY DATE(created_at)");
$sales_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'total_users' => $users['total'],
    'total_categories' => $categories['total'],
    'total_products' => $products['total'],
    'total_sales' => $sales['total'],
    'sales_data' => $sales_data
]);
?>
