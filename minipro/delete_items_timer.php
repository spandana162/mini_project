<?php
session_start();
require_once 'db.php';

// Get the current timestamp
$current_time = time();

// Define one week in seconds
$one_week_in_seconds = 7 * 24 * 60 * 60;

// delete_old_items.php
<?php
require_once 'db.php';

// Delete lost items older than 7 days
$stmt_lost = $pdo->prepare("DELETE FROM lost_item WHERE created_at < NOW() - INTERVAL 7 DAY");
$stmt_lost->execute();

// Delete found items older than 7 days
$stmt_found = $pdo->prepare("DELETE FROM found_item WHERE created_at < NOW() - INTERVAL 7 DAY");
$stmt_found->execute();
?>

// Redirect to the search items page
header("Location: search_items.php");
exit();
?>
