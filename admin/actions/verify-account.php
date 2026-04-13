<?php
session_start();
require "../../includes/connection.php";

// 1. FORCE ERRORS TO DISPLAY (No more blank boxes!)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- EMAIL NOTIFICATION FUNCTION ---
function sendLoginAlertEmail($user_email, $user_fname, $login_method) {
    date_default_timezone_set("Asia/Colombo");
    $login_time = date('Y-m-d h:i A');
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $method_display = ($login_method === 'google') ? 'Google Sign-In' : 'Standard Password';
    $subject = "New Login Alert - Power Watch";
    
    $message = "
    <html><head><style>body { font-family: sans-serif; background: #0A111F; color: white; padding: 20px; }</style></head>
    <body><h2>Hello ".$user_fname.",</h2><p>New login detected from IP: ".$ip_address." via ".$method_display.".</p></body></html>";

    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: Power Watch Security <admin@sldevs.web.lk>\r\n";
    @mail($user_email, $subject, $message, $headers);
}

try {
    // 2. CRITICAL: ENSURE CONNECTION IS OPEN BEFORE USING prepare()
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
            if ($d['status'] != '1') { echo "Your account is deactivated."; exit(); }
            
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
    // 3. THROWABLE CATCHES FATAL ERRORS SO THE BOX IS NEVER BLANK
    echo "SERVER CRASH: " . $e->getMessage() . " on line " . $e->getLine();
}
?>