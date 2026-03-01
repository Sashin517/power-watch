<?php
session_start();
require "../../includes/connection.php";

// --- EMAIL NOTIFICATION FUNCTION ---
function sendLoginAlertEmail($user_email, $user_fname, $login_method) {
    date_default_timezone_set("Asia/Colombo");
    $login_time = date('Y-m-d h:i A');
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $method_display = ($login_method === 'google') ? 'Google Sign-In' : 'Standard Password';

    $subject = "New Login Alert - Power Watch";
    
    // HTML Email Template matching your theme
    $message = "
    <html>
    <head>
    <style>
        body { font-family: 'Montserrat', Arial, sans-serif; background-color: #0A111F; color: #f8f9fa; padding: 20px; margin: 0; }
        .container { max-width: 500px; margin: 0 auto; background-color: #151f32; padding: 30px; border-radius: 8px; border: 1px solid #2d3748; }
        .header { text-align: center; border-bottom: 1px solid #2d3748; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { color: #D4AF37; font-size: 24px; font-weight: 700; text-transform: uppercase; font-family: 'Oswald', sans-serif; text-decoration: none; letter-spacing: 1px;}
        .content { line-height: 1.6; color: #e2e8f0; }
        .content h2 { color: #ffffff; }
        .details-box { background-color: #0f1623; padding: 15px 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #D4AF37; }
        .details-box p { margin: 8px 0; font-size: 14px; }
        .footer { margin-top: 30px; border-top: 1px solid #2d3748; padding-top: 20px; text-align: center; font-size: 12px; color: #adb5bd; }
    </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <span class='logo'>POWER WATCH</span>
            </div>
            <div class='content'>
                <h2>Hello ".$user_fname.",</h2>
                <p>We noticed a new login to your Power Watch account. If this was you, no further action is required.</p>
                
                <div class='details-box'>
                    <p><strong>Time:</strong> ".$login_time." (LKT)</p>
                    <p><strong>IP Address:</strong> ".$ip_address."</p>
                    <p><strong>Method:</strong> ".$method_display."</p>
                </div>

                <p style='font-size: 13px; color: #adb5bd;'>If you did not authorize this login, please change your password or contact our support team immediately to secure your account.</p>
            </div>
            <div class='footer'>
                &copy; ".date('Y')." Power Watch. All rights reserved.<br>
                Secure Account Notification
            </div>
        </div>
    </body>
    </html>
    ";

    // Set content-type header for sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // Additional headers
    $headers .= "From: Power Watch Security <admin@sldevs.web.lk>" . "\r\n";

    // Send email (Suppressed with @ to prevent UI errors if mail server isn't configured locally)
    @mail($user_email, $subject, $message, $headers);
}
// --- END EMAIL FUNCTION ---


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
            
            // Trigger Email Notification
            sendLoginAlertEmail($email, $d['fname'], 'google');
            
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

            // Trigger Email Notification
            sendLoginAlertEmail($email, $d['fname'], 'standard');

            echo ("success");
        } else {
            echo ("Your account has been deactivated.");
        }
    } else {
        echo ("Invalid Email or Password");
    }
}
?>