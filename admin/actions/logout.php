<?php
session_start();
require "../../includes/connection.php";

// Disable error display so warnings don't break the "success" text response
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    // If the user is logged in, attempt to remove their database lock
    if(isset($_SESSION["u"])){
        $user_id = $_SESSION["u"]["id"];
        
        // 1. Establish Database Connection First!
        Database::setUpConnection();
        
        // 2. Release the session lock using a secure prepared statement
        $stmt = Database::$connection->prepare("UPDATE `users` SET `active_session_id`=NULL, `last_active_time`=0 WHERE `id`=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    // 3. Always clear local session variables
    session_unset();
    
    // 4. Always destroy the local session
    session_destroy();

    // 5. Send the exact string the JavaScript is looking for
    echo "success";

} catch (Exception $e) {
    // If the database crashes, log the error silently in the background
    error_log("Logout Error: " . $e->getMessage());
    
    // STILL destroy the local session so the user isn't trapped
    session_unset();
    session_destroy();
    
    // Tell the frontend it worked anyway so they get redirected to login.php
    echo "success";
}
?>