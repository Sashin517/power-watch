<?php
session_start();
require "../includes/connection.php";

// Check if user is logged in
if (isset($_SESSION["u"]) && isset($_SESSION["u"]["id"])) {
    $user_id = $_SESSION["u"]["id"];
    
    // Clear session token from database
    try {
        $stmt = Database::$connection->prepare("UPDATE `users` SET `active_session_id` = NULL, `last_active_time` = 0 WHERE `id` = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    } catch (Exception $e) {
        error_log("Logout Error: " . $e->getMessage());
    }
}

// Destroy session
session_unset();
session_destroy();

// Clear session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to login
header("Location: login.php?msg=logged_out");
exit();
?>
