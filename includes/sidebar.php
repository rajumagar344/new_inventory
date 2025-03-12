<?php
session_start();
include ('./includes/db.php');

if (!isset($_SESSION['user_id'])) { 
    header('Location: index.php'); 
    exit();
}


$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


$username = $user ? $user['username'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./assets/js/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background: #343a40;
            color: white;
            height: 100vh;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
        }
        .sidebar a:hover {
            background: #495057;
            border-radius: 5px;
        }
        .card {
            border-radius: 10px;
            transition: 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    
<!-- Sidebar -->
<div class="d-flex flex-column flex-md-row">
    <div class="sidebar flex-shrink-0 p-3 bg-dark text-white">
        <h4>INVENTORY SYSTEM</h4>

         <p>Welcome, <strong><?= htmlspecialchars($username); ?></strong>!</p>

        <a href="dashboard_overview.php" class="nav-link text-white"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="users.php" class="nav-link text-white"><i class="fas fa-users"></i> User Management</a>
        <a href="categories.php" class="nav-link text-white"><i class="fas fa-th-list"></i> Categories</a>
        <a href="products.php" class="nav-link text-white"><i class="fas fa-boxes"></i> Products</a>
        <a href="sales.php" class="nav-link text-white"><i class="fas fa-shopping-cart"></i> Sales</a>
        <a href="reports.php" class="nav-link text-white"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="logout.php" class="nav-link text-white"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<script src="./assets/js/script.js"></script>

</body>
</html>
