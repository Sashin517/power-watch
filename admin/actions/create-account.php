<?php

require "connection.php";

$fname = $_POST["f"];
$lname = $_POST["l"];
$email = $_POST["e"];
$password = $_POST["p"];

if(empty($fname)){
    echo ("Please enter your First Name");
}else if(strlen($fname) > 50){
    echo ("First Name must have less than 50 characters");
}else if(empty($lname)){
    echo ("Please enter your Last Name");
}else if(strlen($lname) > 50){
    echo ("Last Name must have less than 50 characters");
}else if(empty($email)){
    echo ("Please enter your Email");
}else if(strlen($email) >= 100){
    echo ("Email must have less than 100 characters");
}else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    echo ("Invalid Email");
}else if(empty($password)){
    echo ("Please enter your Password");
}else if(strlen($password) < 5 || strlen($password) > 20){
    echo ("Password must be between 5 - 20 characters");
}else{

    $rs = Database::search("SELECT * FROM `users` WHERE `email`='".$email."'");
    $n = $rs->num_rows;

    if($n > 0){
        echo ("User with the same Email or Mobile already exists.");
    }else{

        // Check if the user is in the admin table
        $admin_rs = Database::search("SELECT * FROM `admin` WHERE `email`='".$email."'");
        $admin_n = $admin_rs->num_rows;

        if($admin_n > 0){
            // User exists in admin table, proceed with registration
            $d = new DateTime();
            $tz = new DateTimeZone("Asia/Colombo");
            $d->setTimezone($tz);
            $date = $d->format("Y-m-d H:i:s");

            Database::iud("INSERT INTO `users` 
            (`fname`,`lname`,`email`,`password`,`joined_date`,`status`) VALUES 
            ('".$fname."','".$lname."','".$email."','".$password."','".$date."','1')");

            echo ("success");
        }else{
            // User not found in admin table
            echo ("You are not authorized to register.");
        }

    }
    
}

?>