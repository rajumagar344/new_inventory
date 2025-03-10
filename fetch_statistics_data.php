<?php
include ('./includes/db.php');

$statistics = array();

$query = "SELECT COUNT(*) as total_users FROM users";
$result = mysqli_query($conn, $query);
$statistics['total_users'] = mysqli_fetch_assoc($result)['total_users'];

$query = "SELECT COUNT(*) as total_categories FROM categories";
$result = mysqli_query($conn, $query);
$statistics['total_categories'] = mysqli_fetch_assoc($result)['total_categories'];

$query = "SELECT COUNT(*) as total_products FROM products";
$result = mysqli_query($conn, $query);
$statistics['total_products'] = mysqli_fetch_assoc($result)['total_products'];

$query = "SELECT SUM(total_price) as total_sales FROM sales";
$result = mysqli_query($conn, $query);
$statistics['total_sales'] = mysqli_fetch_assoc($result)['total_sales'];

echo json_encode($statistics);
?>
