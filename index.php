<?php
session_start();
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

        // Debugging: Log the retrieved user data
        error_log(print_r($user, true));

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit();
        } else {
            // Debugging: Log the password verification result
            error_log('Password verification failed for user: ' . $username);
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #ff7e5f, #feb47b);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            position: relative;
            width: 90%;
            max-width: 1000px;
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.5s ease-in-out;
        }

        /* Forms Styling */
        .forms-container {
            display: flex;
            justify-content: space-between;
            position: relative;
            width: 100%;
            height: 100%;
        }

        .form-control {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 15px;
            width: 45%;
            position: absolute;
            transition: all 0.6s ease-in-out;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-control:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        .form-control h2 {
            margin-bottom: 20px;
            color: #ff7e5f;
        }

        .form-control input,
        .form-control select,
        .form-control button {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        /* Button Styling with Glow */
        .form-control button {
            background: linear-gradient(45deg, #ff7e5f, #feb47b);
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 0 5px rgba(255, 126, 95, 0.6);
        }

        .form-control button:hover {
            background: linear-gradient(45deg, #ff6a4d, #fe9a7b);
            box-shadow: 0 0 15px rgba(255, 126, 95, 0.9);
        }

        /* Sliding Effect */
        .signin-form {
            left: 0;
        }

        .signup-form {
            left: 100%;
        }

        .container.sign-up-mode .signin-form {
            left: -100%;
        }

        .container.sign-up-mode .signup-form {
            left: 0;
        }

        /* Intro Section */
        .intros-container {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .intro-control {
            background: #343a40;
            color: #fff;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            width: 45%;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease-in-out;
        }

        .intro-control:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255, 126, 95, 0.8);
        }

        /* Forgot Password Link */
        .forgot-password-link {
            color: #ff7e5f;
            transition: all 0.3s ease-in-out;
        }

        .forgot-password-link:hover {
            text-decoration: underline;
            color: #fe9a7b;
        }
    </style>
</head>
<body>
<div class="container sign-up-mode"> <!-- Add sign-up-mode class here -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="forms-container">
        <div class="form-control signup-form">
            <form id="registerForm" action="" method="POST" >
                <h2>Register</h2>
                <input type="text" id="registerUsername" name="username" placeholder="Username" required />
                <input type="number" id="mobileNumber" name="mobile_number" placeholder="Mobile Number" required />
                <input type="password" id="registerPassword" name="password" placeholder="Password" required />
                <input type="password" id="registerConfirmPassword" name="confirm_password" placeholder="Confirm password" required />
                <input type="textarea" id="registerAddress" name="address" placeholder="Address" required />
                <select name="gender" id="registerGender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <button type="submit" name="register">Register</button>
            </form>
        </div>
        <div class="form-control signin-form">
            <form action="" method="POST" id="loginForm">
                <h2>Login</h2>
                <input type="text" placeholder="Username" name="loginUsername" id="loginUsername" required />
                <input type="password" placeholder="Password" name="loginPassword" id="loginPassword" required />
                <button type="submit" name="login">Login</button>
                <!-- Forgot Password Section -->
                <p><a href="forgot-password.php" class="forgot-password-link">Forgot your password?</a></p>
            </form>
        </div>
    </div>
    <div class="intros-container">
        <!-- Signin Section -->
        <div class="intro-control signin-intro">
            <div class="intro-control__inner">
                <h2>Welcome back!</h2>
                <button id="signup-btn">No account yet? Register.</button>
            </div>
        </div>

        <!-- Signup Section -->
        <div class="intro-control signup-intro">
            <div class="intro-control__inner">
                <h2>Login</h2>
                <p>
                    We are so excited to have you here. If you haven't already, create an account to get access to exclusive offers, rewards, and discounts.
                </p>
                <button id="signin-btn">Already have an account? Signin.</button>
            </div>
        </div>
    </div>
</div>
<script src="./assets/js/jquery-3.7.1.js"></script>
<script src="./assets/js/bootstrap.bundle.min.js"></script>
<script src="./assets/js/script.js"></script>
<script>
        // Toggle between signup and signin forms with smooth transition
        const signInBtn = document.querySelector("#signin-btn");
    const signUpBtn = document.querySelector("#signup-btn");
    const container = document.querySelector(".container");

    signUpBtn.addEventListener("click", () => {
        container.classList.add("sign-up-mode");
    });

    signInBtn.addEventListener("click", () => {
        container.classList.remove("sign-up-mode");
    });
</script>
</body>
</html>