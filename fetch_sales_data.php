<?php
include ('./includes/db.php');

try {
    $query = "SELECT date, total_sales FROM sales_data";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $sales_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($sales_data);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to fetch data: ' . $e->getMessage()]);
    exit();

}
?>