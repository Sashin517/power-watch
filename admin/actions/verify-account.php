<?php
session_start();
require "../../includes/connection.php";

// 1. Check if the login method is set (default to standard)
$login_method = isset($_POST["login_method"]) ? $_POST["login_method"] : "standard";
$email = $_POST["e"];

// 2. Validate Email
if(empty($email)){
    echo ("Please enter your Email");
    exit();
}

// 3. LOGIC BRANCHING
if ($login_method === "google") {
    // --- GOOGLE LOGIN (No Password Check) ---
    
    $rs = Database::search("SELECT * FROM `users` WHERE `email`='".$email."'");
    $n = $rs->num_rows;

    if($n == 1){
        $d = $rs->fetch_assoc();
        if($d['status'] == '1'){
            $_SESSION["u"] = $d;
            echo ("success");
        } else {
            echo ("Your account has been deactivated.");
        }
    } else {
        echo ("Account not found. Please Sign Up first.");
    }

} else {
    // --- STANDARD LOGIN (Password Check) ---
    
    if(!isset($_POST["p"]) || empty($_POST["p"])){
        echo ("Please enter your Password");
        exit();
    }
    
    $password = $_POST["p"];
    $rememberMe = isset($_POST["rm"]) ? $_POST["rm"] : "0";

    $rs = Database::search("SELECT * FROM `users` WHERE `email`='".$email."' AND `password`='".$password."'");
    $n = $rs->num_rows;

    if($n == 1){
        $d = $rs->fetch_assoc();
        if($d['status'] == '1'){
            $_SESSION["u"] = $d;

            if($rememberMe == "1"){
                setcookie("email", $email, time() + (60*60*24*365));
                setcookie("password", $password, time() + (60*60*24*365));
            } else {
                setcookie("email", "", -1);
                setcookie("password", "", -1);
            }

            echo ("success");
        } else {
            echo ("Your account has been deactivated.");
        }
    } else {
        echo ("Invalid Email or Password");
    }
}
?>