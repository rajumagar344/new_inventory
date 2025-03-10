<?php
session_start();
include('./includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Update product in the database
    $stmt = $pdo->prepare("UPDATE products SET name = ?, category_id = ?, price = ?, quantity = ? WHERE id = ?");
    $stmt->execute([$name, $category_id, $price, $quantity, $product_id]);

    echo json_encode(['status' => 'success']);
}
?>
