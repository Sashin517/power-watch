<?php
session_start();
require "../../includes/connection.php";

$email = $_POST["e"];
$password = $_POST["p"];
$rememberMe = isset($_POST["rm"]) ? $_POST["rm"] : "0";

if(empty($email)){
    echo ("Please enter your Email");
}else if(strlen($email) >= 100){
    echo ("Email must have less than 100 characters");
}else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    echo ("Invalid Email Address");
}else if(empty($password)){
    echo ("Please enter your Password");
}else{

    // Search for the user in the database
    // Note: This assumes plain text passwords matching your create-account.php logic
    $rs = Database::search("SELECT * FROM `users` WHERE `email`='".$email."' AND `password`='".$password."'");
    $n = $rs->num_rows;

    if($n == 1){
        
        // Fetch user data
        $d = $rs->fetch_assoc();
        
        // Check if user status is active (1)
        if($d['status'] == '1'){
            
            // Login Successful - Start Session
            echo ("success");
            
            $_SESSION["u"] = $d;

            // Handle Remember Me Cookies
            if($rememberMe == "1"){
                setcookie("email", $email, time() + (60*60*24*365));
                setcookie("password", $password, time() + (60*60*24*365));
            } else {
                setcookie("email", "", -1);
                setcookie("password", "", -1);
            }

        } else {
            echo ("Your account has been deactivated. Please contact admin.");
        }

    }else{
        echo ("Invalid Email or Password");
    }
}
?>