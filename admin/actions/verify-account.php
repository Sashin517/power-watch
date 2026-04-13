<?php
session_start();
require "../../includes/connection.php";

// Force all errors to display to Javascript so we never get a blank box again
error_reporting(E_ALL);
ini_set('display_errors', 1);

// IMPORT PHPMAILER CLASSES AT THE VERY TOP OF YOUR FILE
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// REQUIRE THE FILES
require '../../includes/PHPMailer/Exception.php';
require '../../includes/PHPMailer/PHPMailer.php';
require '../../includes/PHPMailer/SMTP.php';

// --- UPDATED EMAIL NOTIFICATION FUNCTION ---
function sendLoginAlertEmail($user_email, $user_fname, $login_method) {
    date_default_timezone_set("Asia/Colombo");
    $login_time = date('Y-m-d h:i A');
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $method_display = ($login_method === 'google') ? 'Google Sign-In' : 'Standard Password';

    // The beautiful HTML template
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
                <p>We noticed a new login to your Power Watch account.</p>
                <div class='details-box'>
                    <p><strong>Time:</strong> ".$login_time." (LKT)</p>
                    <p><strong>IP Address:</strong> ".$ip_address."</p>
                    <p><strong>Method:</strong> ".$method_display."</p>
                </div>
                <p style='font-size: 13px; color: #adb5bd;'>If you did not authorize this login, please change your password immediately.</p>
            </div>
        </div>
    </body>
    </html>";

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                            
        $mail->Host       = 'mail.sldevs.web.lk'; // Your cPanel mail server
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'admin@sldevs.web.lk'; // The email you created in cPanel
        $mail->Password   = 'YOUR_EMAIL_PASSWORD'; // The password for that email
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable SSL
        $mail->Port       = 465; // Standard SSL port

        // Recipients
        $mail->setFrom('admin@sldevs.web.lk', 'Power Watch Security');
        $mail->addAddress($user_email);     

        // Content
        $mail->isHTML(true);                                  
        $mail->Subject = 'New Login Alert - Power Watch';
        $mail->Body    = $message;

        $mail->send();
    } catch (Exception $e) {
        // Silently log the error so the user isn't stopped from logging in
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
// --- END EMAIL FUNCTION ---

try {
    // CRITICAL FIX: Explicitly open the database connection first
    if (empty(Database::$connection)) {
        Database::setUpConnection();
    }

    $login_method = isset($_POST["login_method"]) ? trim($_POST["login_method"]) : "standard";
    if (!isset($_POST["e"]) || empty(trim($_POST["e"]))) { echo "Please enter your Email"; exit(); }
    $email = trim($_POST["e"]);

    // ===== GOOGLE LOGIN =====
    if ($login_method === "google") {
        $stmt = Database::$connection->prepare("SELECT * FROM `users` WHERE `email` = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $rs = $stmt->get_result();
        
        if ($rs->num_rows == 1) {
            $d = $rs->fetch_assoc();
            if ($d['status'] != '1') { echo "Your account has been deactivated."; exit(); }
            
            $session_token = bin2hex(random_bytes(32));
            $_SESSION["session_token"] = $session_token;
            $_SESSION['last_activity'] = time();
            $_SESSION["u"] = $d;
            
            $update_stmt = Database::$connection->prepare("UPDATE `users` SET `active_session_id` = ?, `last_active_time` = ? WHERE `id` = ?");
            $current_time = time();
            $update_stmt->bind_param("sii", $session_token, $current_time, $d['id']);
            $update_stmt->execute();
            
            sendLoginAlertEmail($email, $d['fname'], 'google');
            echo "success";
        } else {
            echo "Account not found. Please Sign Up first.";
        }
    } 
    // ===== STANDARD LOGIN =====
    else {
        if (!isset($_POST["p"]) || empty($_POST["p"])) { echo "Please enter your Password"; exit(); }
        $password = $_POST["p"];
        $rememberMe = isset($_POST["rm"]) ? $_POST["rm"] : "0";
        
        $stmt = Database::$connection->prepare("SELECT * FROM `users` WHERE `email` = ? AND `password` = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $rs = $stmt->get_result();
        
        if ($rs->num_rows == 1) {
            $d = $rs->fetch_assoc();
            if ($d['status'] != '1') { echo "Your account has been deactivated."; exit(); }
            
            $session_token = bin2hex(random_bytes(32));
            $_SESSION["session_token"] = $session_token;
            $_SESSION['last_activity'] = time();
            $_SESSION["u"] = $d;
            
            $update_stmt = Database::$connection->prepare("UPDATE `users` SET `active_session_id` = ?, `last_active_time` = ? WHERE `id` = ?");
            $current_time = time();
            $update_stmt->bind_param("sii", $session_token, $current_time, $d['id']);
            $update_stmt->execute();
            
            if ($rememberMe == "1") {
                setcookie("email", $email, time() + (60*60*24*365), "/"); 
                setcookie("password", $password, time() + (60*60*24*365), "/");
            } else {
                setcookie("email", "", time() - 3600, "/");
                setcookie("password", "", time() - 3600, "/");
            }
            
            sendLoginAlertEmail($email, $d['fname'], 'standard');
            echo "success";
        } else {
            echo "Invalid Email or Password";
        }
    }
} catch (Throwable $e) { 
    // CATCH FATAL ERRORS AND SEND THEM TO THE RED BOX
    echo "SERVER CRASH: " . $e->getMessage() . " on line " . $e->getLine();
}
?>