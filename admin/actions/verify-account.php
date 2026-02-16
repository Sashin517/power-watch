<?php
session_start();
require "../../includes/connection.php";

// 1. Get the Login Method (default to standard if not set)
$login_method = isset($_POST["login_method"]) ? $_POST["login_method"] : "standard";
$email = $_POST["e"];

// 2. Common Validation (Email is always required)
if(empty($email)){
    echo ("Please enter your Email");
    exit();
} else if(strlen($email) >= 100){
    echo ("Email must have less than 100 characters");
    exit();
} else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo ("Invalid Email Address");
    exit();
}

// 3. LOGIC BRANCHING
if ($login_method == "google") {
    // --- GOOGLE LOGIN FLOW ---
    // In this mode, we DO NOT check the password. 
    // We trust that Google has already verified the user.
    
    $rs = Database::search("SELECT * FROM `users` WHERE `email`='".$email."'");
    $n = $rs->num_rows;

    if($n == 1){
        $d = $rs->fetch_assoc();
        if($d['status'] == '1'){
            echo ("success");
            $_SESSION["u"] = $d;
            // No cookies needed for Google login usually, or handled differently
        } else {
            echo ("Your account has been deactivated.");
        }
    } else {
        // Optional: Auto-register user if they don't exist yet?
        echo ("Account not found. Please Sign Up first.");
    }

} else {
    // --- STANDARD LOGIN FLOW ---
    // This requires a password check.
    
    $password = $_POST["p"];
    $rememberMe = isset($_POST["rm"]) ? $_POST["rm"] : "0";

    if(empty($password)){
        echo ("Please enter your Password");
        exit();
    }

    $rs = Database::search("SELECT * FROM `users` WHERE `email`='".$email."' AND `password`='".$password."'");
    $n = $rs->num_rows;

    if($n == 1){
        $d = $rs->fetch_assoc();
        if($d['status'] == '1'){
            echo ("success");
            $_SESSION["u"] = $d;

            if($rememberMe == "1"){
                setcookie("email", $email, time() + (60*60*24*365));
                setcookie("password", $password, time() + (60*60*24*365));
            } else {
                setcookie("email", "", -1);
                setcookie("password", "", -1);
            }

        } else {
            echo ("Your account has been deactivated.");
        }
    } else {
        echo ("Invalid Email or Password");
    }
}
?>