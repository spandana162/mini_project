<?php
session_start();
include 'config.php'; // Assuming this file contains your database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to retrieve user information
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify user and password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username']; // Store username in session
        header('Location: dashboard.php'); // Redirect to dashboard or any secure page
        exit();
    } else {
        $error = '<div class="alert alert-danger" role="alert">Invalid credentials!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lost'N'Found</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        h1 {
            color: white;
        }
        body {
            background-image: url('bg1.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: right center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 100%;
            margin: auto;
            padding: 0 15px;
        }
        header {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 10px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-form {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            color: white;
            max-width: 400px;
            margin: auto;
        }
        .login-form input {
            margin-bottom: 10px;
        }
        .login-form .btn-primary {
            background-color: #0056b3;
            border: none;
        }
        .login-form .btn-primary:hover {
            background-color: #003d80;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Lost'N'Found</h1>
    </header>
    <div class="container">
        <div class="login-form">
            <form method="POST" action="">
                <h2 class="text-center">Login</h2>
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
                <?php if (isset($error)) echo $error; ?>
            </form>
        </div>
    </div>
</body>
</html>
