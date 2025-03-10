<?php
include ('./includes/db.php');

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Login logic
        $username = trim($_POST['loginUsername']);
        $password = trim($_POST['loginPassword']);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit();
        } else {
            $errors[] = "Invalid username or password.";
        }
    } elseif (isset($_POST['register'])) {
        // Registration logic
        $username = trim($_POST['username']);
        $mobile_number = trim($_POST['mobile_number']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        $address = trim($_POST['address']);
        $gender = $_POST['gender'];

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
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR mobile_number = ?");
            $stmt->execute([$username, $mobile_number]);

            if ($stmt->rowCount() > 0) {
                $errors[] = 'Username or mobile number already exists';
            } else {
                // Hash the password for security
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert the user data into the database
                $stmt = $pdo->prepare("INSERT INTO users (username, mobile_number, password, address, gender) VALUES (?, ?, ?, ?, ?)");
                if ($stmt->execute([$username, $mobile_number, $hashed_password, $address, $gender])) {
                    $_SESSION['user_id'] = $pdo->lastInsertId();
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $errors[] = 'Failed to register. Please try again.';
                }
            }
        }
    }
}

// Display errors if any
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo '<p>' . htmlspecialchars($error) . '</p>';
    }
}
?>