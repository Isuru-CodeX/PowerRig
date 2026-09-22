<?php

session_start();
include "connection.php";

if(isset($_SESSION["user"]) && isset($_POST["from"]) && isset($_POST["mt"])){

    $sender = $_POST["from"];
    $msg = $_POST["mt"];
    $to = $_POST["to"];
    if(!empty($msg)){
        $d = new DateTime();
        $tz = new DateTimeZone("Asia/Colombo");
        $d->setTimezone($tz);
        $date = $d->format("Y-m-d H:i:s");
        
        Database::iud("INSERT INTO `chat`(`content`,`date_time`,`chat_status_id`,`from`,`to`) VALUES 
        ('".$msg."','".$date."','1','".$sender."','".$to."')");
        
        echo ("success");
    }else{
        echo "Please insert a message";
    }
}else{
    header("Location: index.php");
}


?>