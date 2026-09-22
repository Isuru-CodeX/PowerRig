<?php

include "connection.php";

if(isset($_GET["id"])){

    $invoice_id = $_GET["id"];

    $invoice_rs = Database :: search("SELECT * FROM `order` WHERE `order_id`='".$invoice_id."'");
    $invoice_data = $invoice_rs->fetch_assoc();

    $status_id = $invoice_data["order_status_id"];

    if($status_id == 1){

        Database :: iud("UPDATE `order` SET `order_status_id`='2' WHERE `order_id`='".$invoice_id."'");

    }else if($status_id == 2){

        Database :: iud("UPDATE `order` SET `order_status_id`='3' WHERE `order_id`='".$invoice_id."'");

    }else if($status_id == 3){

        Database :: iud("UPDATE `order` SET `order_status_id`='4' WHERE `order_id`='".$invoice_id."'");

    }else if($status_id == 4){

        Database :: iud("UPDATE `order` SET `order_status_id`='5' WHERE `order_id`='".$invoice_id."'");
    }

    echo("success");
    
}

?>