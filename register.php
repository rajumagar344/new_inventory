<?php 
include('./includes/db.php'); // Include your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input
    $username = trim($_POST['username']);
    $mobile_number = trim($_POST['mobile_number']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $address = trim($_POST['address']);
    $gender = $_POST['gender'];

    $errors = [];

    // Check if fields are empty
    if (empty($username)) {
        $errors[] = 'Username is required';
    }

    if (empty($mobile_number)) {
        $errors[] = 'Mobile number is required';
    }

    if (empty($password)) {
        $errors[] = 'Password is required';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }

    if (empty($address)) {
        $errors[] = 'Address is required';
    }

    // If no errors, check if the username or mobile number already exists in the database
    if (empty($errors)) {
        // Prepare SQL query to check for duplicate username or mobile number
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR mobile_number = ?");
        $stmt->execute([$username, $mobile_number]);

        if ($stmt->rowCount() > 0) {
            $errors[] = 'Username or mobile number already exists';
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert the user data into the database
            $stmt = $pdo->prepare("INSERT INTO users (username, mobile_number, password, address, gender) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $mobile_number, $hashed_password, $address, $gender]);
            
            echo 'Registration Successful!';
        }
    }

    // Display errors if any
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<p>' . htmlspecialchars($error) . '</p>';
        }
    }
}
?>
