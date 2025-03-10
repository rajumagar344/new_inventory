<?php
include('./includes/db.php');

$stmt = $pdo->query("SELECT p.id, p.name, c.name as category, p.price, p.quantity FROM products p JOIN categories c ON p.category_id = c.id");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$output = '';
foreach ($products as $product) {
    $output .= "<tr>
                    <td>{$product['id']}</td>
                    <td>{$product['name']}</td>
                    <td>{$product['category']}</td>
                    <td>{$product['price']}</td>
                    <td>{$product['quantity']}</td>
                </tr>";
}

echo $output;
