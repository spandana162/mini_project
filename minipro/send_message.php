<?php
session_start(); // Start the session
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect or handle the error if user is not logged in
    header('Location: login.php');
    exit();
}

$item_id = $_POST['item_id'];
$user_id = $_SESSION['user_id'];
$message = $_POST['message'];

// Check if item_id and message are provided
if ($item_id && $message) {
    $stmt = $pdo->prepare("INSERT INTO chats (item_id, user_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$item_id, $user_id, $message]);

    header('Location: search_items.php');
    exit();
} else {
    // Handle error for missing data
    echo "Item ID and message cannot be empty.";
}
?>
