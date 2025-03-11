<?php
include('./includes/db.php');

try {
    // Query execution with error handling 
    $stmt = $pdo->query("SELECT p.id, p.name, c.name as category, p.price, p.quantity FROM products p JOIN categories c ON P.category_id = c.id");

    // Fetch all products from database
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$products) {
        throw new Exception("NO Products found in the database");
    }

    // Generate the table rows with fetched data
    $output = '';
    foreach ($products as $product) {
        $output .= "<tr>
            <td>{$product['id']}</td>
            <td>" . htmlspecialchars($product['name']) . "</td>
            <td>" . htmlspecialchars($product['category']) . "</td>
            <td>" . htmlspecialchars($product['price']) . "</td>
            <td>" . htmlspecialchars($product['quantity']) . "</td>
        </tr>";
    }
        echo $output;
} catch (Exception $e) {
    // If an error occurs, display a meaningful error message
    echo "<tr><td colspan='5' style='color:red; text-align: center;'>Error: " .htmlspecialchars($e->getMessage()) . "</td></tr>";
}
?>