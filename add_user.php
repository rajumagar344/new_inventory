<?php
session_start();
include('./includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the mobile_number key exists in the $_POST array
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $mobile_number = isset($_POST['mobile_number']) ? trim($_POST['mobile_number']) : ''; // Check for mobile_number

    $errors = [];

    // Validate the form data
    if (empty($username)) {
        $errors[] = 'Username is required';
    }

    if (empty($password)) {
        $errors[] = 'Password is required';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }

    // Password strength validation (optional)
    if (strlen($password) < 6) {
        $errors[] = 'Password should be at least 6 characters';
    }

    if (empty($mobile_number)) {
        $errors[] = 'Mobile number is required';
    } else {
        // Validate mobile number length (assuming 10 digits)
        if (strlen($mobile_number) != 10) {
            $errors[] = 'Mobile number should be exactly 10 digits';
        }
    }

    // If no errors, proceed with the database operations
    if (empty($errors)) {
        // Check if the username or mobile number already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR mobile = ?");
        $stmt->execute([$username, $mobile_number]);

        if ($stmt->rowCount() > 0) {
            $errors[] = 'Username or mobile number already exists';
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the user data into the database
            $stmt = $pdo->prepare("INSERT INTO users (username, password, mobile) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $mobile_number]);

            // Redirect to users.php
            header('Location: users.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>

<!-- Sidebar (same as in dashboard.php) -->

<div class="container-fluid p-4">
    <h3 class="mb-4">Add User</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="mobile_number" class="form-label">Mobile Number</label>
            <input type="text" name="mobile_number" id="mobile_number" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add User</button>
    </form>

    <!-- Display errors if any -->
    <?php if (!empty($errors)): ?>
        <div class="mt-3">
            <?php foreach ($errors as $error): ?>
                <p class="text-danger"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
