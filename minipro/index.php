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
    <title>Lost and Found - Shri Vishnu Engineering College for Women</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        h1 {
            color: white;
        }
        body {
            background-image: url('bg1.jpg'); /* Replace with your image path */
            background-size: cover;           /* Ensure the image covers the entire container */
            background-position: right center; /* Move the image to the right */
            background-repeat: no-repeat;     /* Prevent the image from repeating */
            background-attachment: fixed;     /* Keep the image fixed in the viewport */
            height: 100vh;                    /* Make sure the body takes the full height of the viewport */
            margin: 0;                        /* Remove default margin */
            padding: 0;                       /* Remove default padding */
        }
        .container {
            max-width: 100%;
            margin: auto;
            padding: 0 15px;
        }
        header {
            background-color: rgba(0, 0, 0, 0.8); /* Slightly transparent black background */
            padding: 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: fadeIn 2s;
        }
        header img {
            height: 50px;
            margin-right: 10px;
            opacity: 0.8; /* Make the logo transparent */
            animation: fadeInLogo 2s;
        }
        header h1 {
            font-size: 1.5rem;
            margin: 0;
            text-align: center;
            flex: 1;
            color: white; /* White text for contrast */
            animation: fadeIn 2s;
        }
        header nav {
            margin-left: auto;
        }
        header nav a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-size: 1rem;
        }
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 80vh;
        }
        .login-box {
            flex: 1;
            max-width: 500px;
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.8); /* Slightly transparent black background */
            border-radius: 10px;
            color: white; /* White text for contrast */
            animation: slideUp 1s;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.1); /* Slightly transparent white background */
            border: 1px solid #ccc;
            color: white;
        }
        .form-control::placeholder {
            color: #ccc; /* Light grey placeholder text */
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
            transform: translateY(-2px);
        }
        footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @keyframes fadeInLogo {
            from {
                opacity: 0;
                transform: rotate(-360deg);
            }
            to {
                opacity: 0.8;
                transform: rotate(0);
            }
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="logo.gif" alt="Logo"> <!-- Replace with the path to your logo -->
                <h1>LostNFound</h1>
            </div>
            <nav>
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
            </nav>
        </div>
    </header>

    <div class="login-container container">
        <div class="login-box">
            <?php if (isset($error)) echo $error; ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <h1>Login</h1>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <input type="submit" value="Login" class="btn btn-primary">
            </form>
            <a href="register.php" class="text-white">Don't have an account? Register here.</a>
        </div>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>