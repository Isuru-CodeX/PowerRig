<?php

include "connection.php";

$email = $_POST["email"];
$newpw = $_POST["newPassword"];
$retypepw= $_POST["retypePassword"];
$vcode = $_POST["verification"];

if(empty($email)){
    echo("Pleace Enter Your Email");
}else if(strlen($email) > 100){
    echo("Email Must Contain LOWER THAN 100 characters.");
}else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    echo("Invalid Email Address.");
}else if(empty($newpw)){
    echo("Pleace Enter Your New Password");
}else if(strlen($newpw) < 5 || strlen($newpw) > 20){
    echo("Password Must Contain 5 to 20 Characters.");
}else if(empty($retypepw)){
    echo("Pleace Enter Your New Password");
}else if($newpw != $retypepw){
    echo("Password does not match");
}else {

    $rs = Database :: search("SELECT * FROM `user` WHERE `email`='".$email."' AND `verification_code`='".$vcode."'");
    $num = $rs->num_rows;

    if($num == 1){

        Database :: iud("UPDATE `user` SET `password`='".$retypepw."' WHERE `email`='".$email."'");
        echo("Success");

    }else{
        echo("Invalid Email Addess or Verification Code");
    };
}

?>