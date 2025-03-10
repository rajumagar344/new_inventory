<?php
session_start();
include('./includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Check if category ID is provided
if (!isset($_GET['id'])) {
    header('Location: categories.php');
    exit();
}

$category_id = $_GET['id'];

// Delete the category
$stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
$stmt->execute([$category_id]);

header('Location: categories.php');
exit();
?>
