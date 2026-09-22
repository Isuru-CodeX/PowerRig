<?php

session_start();
include "connection.php";

$email = $_POST["email"];
$password = $_POST["password"];
$rememberme = $_POST["rememberme"];

if(empty($email)){
    echo("Please Enter Your Email Address");
}else if(empty($password)){
    echo("Please Enter Your Password.");
}else{

    $result = Database ::search("SELECT * FROM `user` WHERE `email`='".$email."' AND `password`='".$password."'");

    if($result->num_rows == 1){
        $result_data = $result->fetch_assoc();
        if($result_data["status_status_id"] == 1){
            echo("Success");
            $_SESSION["user"] = $result_data;
    
            if($rememberme == "true"){
                setcookie("email",$email,time()+(60*60*24*365));
                setcookie("password",$password,time()+(60*60*24*365));
            }else {
                setcookie("email","",-1);
                setcookie("password","",-1);
            }
        }else{
            echo "Sorry you have been blocked";
        }

    }else{
        echo("Invalid Username or Password");
    }
}

?>