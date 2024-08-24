<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection settings
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "123456"; // Replace with your database password
$dbname = "lost_and_found";

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch notifications
    function fetchNotifications($pdo, $userId) {
        $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = :user_id AND is_read = 0 ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch notifications for the logged-in user
    $notifications = fetchNotifications($pdo, $_SESSION['user_id']);

    // Mark notifications as read
    function markNotificationsAsRead($pdo, $userId) {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id AND is_read = 0");
        $stmt->execute(['user_id' => $userId]);
    }

    // Mark notifications as read once fetched
    markNotificationsAsRead($pdo, $_SESSION['user_id']);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Lost'N'Found</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #343a40;
            color: #ffffff;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }
        header .logo {
            height: 50px;
            background: none;
        }
        header h1 {
            flex: 1;
            text-align: center;
            margin: 0;
        }
        .profile-menu {
            display: flex;
            align-items: center;
        }
        .logout-button {
            background-color: #ffc107;
            border: none;
            color: #343a40;
            font-size: 1em;
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .logout-button:hover {
            background-color: #e0a800;
        }
        .container {
            display: flex;
            flex-direction: row;
            flex: 1;
            padding: 20px;
        }
        .sidebar {
            background-color: #495057;
            color: #ffffff;
            width: 250px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
            position: fixed;
            top: 80px;
            left: 0;
            height: calc(100% - 80px);
        }
        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
        .sidebar button {
            display: block;
            color: #ffffff;
            text-decoration: none;
            padding: 12px;
            border: none;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: background-color 0.3s, transform 0.2s;
            width: 100%;
            text-align: left;
            background-color: #6c757d;
            border: 1px solid #495057;
        }
        .sidebar button:hover {
            background-color: #5a6268;
            transform: scale(1.02);
        }
        .sidebar button.menu-item {
            background-color: #007bff;
        }
        .sidebar button.menu-item:hover {
            background-color: #0056b3;
        }
        .sidebar button.item-status {
            background-color: #28a745;
        }
        .sidebar button.item-status:hover {
            background-color: #1e7e34;
        }
        .sidebar button.history {
            background-color: #ffc107;
        }
        .sidebar button.history:hover {
            background-color: #e0a800;
        }
        .sections-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            padding-left: 270px;
        }
        .sections {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 20px;
            max-width: 100%;
            width: 100%;
        }
        .section {
            text-align: center;
            width: 300px;
            height: 400px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s, transform 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .section:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            transform: scale(1.02);
        }
        .section img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .section h3 {
            margin-top: 10px;
            font-size: 1.5em;
            color: #343a40;
        }
        .section p {
            font-size: 0.9em;
            color: #6c757d;
            margin-top: 10px;
        }
        .section button {
            display: block;
            color: #007bff;
            text-decoration: none;
            padding: 12px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s, transform 0.2s;
            background-color: #f4f4f4;
            width: 100%;
        }
        .section button:hover {
            background-color: #e0e0e0;
            transform: scale(1.02);
        }
        .notification-popup {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 300px;
            background-color: #ffffff;
            border: 1px solid #007bff;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
        }
        .notification-popup h2 {
            font-size: 1.2em;
            margin: 10px;
            color: #007bff;
            text-align: center;
        }
        .notification-popup ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .notification-popup li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .notification-popup li:last-child {
            border-bottom: none;
        }
        .notification-popup li.unread {
            background-color: #f8f9fa;
            border-left: 5px solid #007bff;
        }
        .notification-popup .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;
        }
        .footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php">
            <img src="logo.gif" alt="Logo" class="logo"> <!-- Ensure your logo path is correct -->
        </a>
        <h1>Lost'N'Found</h1>
        <div class="profile-menu">
            <form action="logout.php" method="POST">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>
    </header>

    <div class="container">
        <aside class="sidebar">
            <h2>Menu</h2>
            <button class="menu-item" onclick="window.location.href='index.php'">Home</button>
            <button class="item-status" onclick="window.location.href='item_status.php'">Item Status</button>
            <button class="history" onclick="window.location.href='history.php'">History</button>
        </aside>
        <div class="sections-container">
            <div class="sections">
                <div class="section">
                    <img src="lstim.jpg" alt="Image 1">
                    <h3>Section 1</h3>
                    <p>Description for section 1.</p>
                    <button onclick="window.location.href='post_lost_item.php'">Go to Section 1</button>
                </div>
                <div class="section">
                    <img src="fndim.jpeg" alt="Image 2">
                    <h3>Section 2</h3>
                    <p>Description for section 2.</p>
                    <button onclick="window.location.href='post_found_item.php'">Go to Section 2</button>
                </div>
                <div class="section">
                    <img src="lf1.jpg" alt="Image 3">
                    <h3>Section 3</h3>
                    <p>Description for section 3.</p>
                    <button onclick="window.location.href='search_items.php'">Go to Section 3</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Popup -->
    <div id="notificationPopup" class="notification-popup">
        <button class="close-btn">&times;</button>
        <h2>Notifications</h2>
        <?php if (is_array($notifications) && count($notifications) > 0): ?>
            <ul>
                <?php foreach ($notifications as $notification): ?>
                    <li class="<?php echo $notification['is_read'] ? '' : 'unread'; ?>">
                        <?php echo htmlspecialchars($notification['message']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No new notifications.</p>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Lost'N'Found. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zyn23gA5Zt8DJTUMn9j7bD3ez9z1Z3+3Ebg9p6fL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.6/dist/umd/popper.min.js" integrity="sha384-R7g5vK6D5znh2vDsgG9u9IibVuPv2F6ENpeW8U58PjQQU8z+YAp0MR2aOfjt5TZn" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-5dLw1tP1LgPY8A8I5W9RRZlf0mOOH+HG04xQ0nBGT/fpHjpH7vCzklqMeW6Az2Ic" crossorigin="anonymous"></script>

    <script>
        // Function to show the notification popup
        function showNotificationPopup() {
            document.getElementById('notificationPopup').style.display = 'block';
        }

        // Function to hide the notification popup
        function hideNotificationPopup() {
            document.getElementById('notificationPopup').style.display = 'none';
        }

        // Automatically show the notification popup when the page loads if there are notifications
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (count($notifications) > 0): ?>
                showNotificationPopup();
            <?php endif; ?>
        });

        // Add event listener to close button
        document.querySelector('.notification-popup .close-btn').addEventListener('click', hideNotificationPopup);
    </script>
</body>
</html>
