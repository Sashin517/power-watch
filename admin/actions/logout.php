<?php
session_start();

// Clear the session variables
session_unset();

// Destroy the session
session_destroy();

// Send a success response back to the Javascript fetch request
echo "success";
?>