<?php
session_start();
require "../../includes/connection.php";

if(isset($_SESSION["u"])){
    $user_id = $_SESSION["u"]["id"];
    // Release the session lock in the database
    Database::iud("UPDATE `users` SET `active_session_id`=NULL, `last_active_time`=0 WHERE `id`='".$user_id."'");
}

// Clear the session variables and destroy
session_unset();
session_destroy();

echo "success";
?>