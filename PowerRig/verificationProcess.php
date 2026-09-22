<?php

session_start();
include "connection.php";

if(isset($_POST["vcode"])){

    $vcode = $_POST["vcode"];

    $admin_rs = Database :: search("SELECT * FROM `admin` WHERE `vcode`='".$vcode."'");
    $admin_num = $admin_rs ->num_rows;

    if($admin_num == 1){

        $admin_data = $admin_rs->fetch_assoc();
        $_SESSION["admin"] = $admin_data;
        echo("Success");

    }else {
        echo("Invalid Verification Code");
    }

}else {
    echo("Please enter the verification code");
}
?>