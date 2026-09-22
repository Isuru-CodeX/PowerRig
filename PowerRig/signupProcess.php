<?php

include "connection.php";

$fname = $_POST["fname"];
$lname = $_POST["lname"];
$email = $_POST["email"];
$password = $_POST["password"];


// echo($fname);
if(empty($fname)){
    echo("Pleace Enter Your First Name");
}else if(strlen($fname) > 20){
    echo("Fisrt Name Must Contain LOWER THAN 20 characters.");
}else if(empty($lname)){
    echo("Pleace Enter Your last Name");
}else if(strlen($lname) > 20){
    echo("Last Name Must Contain LOWER THAN 20 characters.");
}else if(empty($email)){
    echo("Pleace Enter Your Email");
}else if(strlen($email) > 50){
    echo("Email Must Contain LOWER THAN 50 characters.");
}else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    echo("Invalid Email Address.");
}else if(empty($password)){
    echo("Pleace Enter Your Password");
}else if(strlen($password) < 5 || strlen($password) > 10){
    echo("Password Must Contain 5 to 10 Characters.");
}else {

    $rs = Database :: search("SELECT * FROM `user` WHERE `email` = '".$email."'");
    $n = $rs->num_rows;

    if($n > 0){
        echo("User with the same Email Address already exists");
    }else {
        $date = new DateTime();
        $tz = new DateTimeZone("Asia/Colombo");
        $date ->setTimezone($tz);
        $formattedDate = $date->format("Y-m-d H:i:s");

        Database::iud("INSERT INTO `user` (`email`,`fname`,`lname`,`password`,`joined_date`,`status_status_id`)
        VALUES ('".$email."','".$fname."','".$lname."','".$password."','".$formattedDate."','1')");
        
        echo("Success");
        
    }
}

?>