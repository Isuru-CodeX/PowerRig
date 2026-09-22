<?php
session_start();
include "connection.php";

if(isset($_SESSION["user"])){
    if(isset($_GET["id"])){

        $email = $_SESSION["user"]["email"];
        $pid = $_GET["id"];

        $wishlist_rs = Database::search("SELECT * FROM `wishlist` WHERE `user_email`='".$email."' AND 
        `product_id`='".$pid."'");

        if($wishlist_rs->num_rows == 1){

            $wishlist_data = $wishlist_rs->fetch_assoc();
            $list_id = $wishlist_data["wishlist_id"];

            Database::iud("DELETE FROM `wishlist` WHERE `wishlist_id`='".$list_id."'");
            echo ("wishlist removed");

        }else{

            Database::iud("INSERT INTO `wishlist`(`user_email`,`product_id`) VALUES ('".$email."','".$pid."')");
            echo ("wishlist added");
            
        }

    }else{
        echo ("Something went wrong. Please try again later.");
    }
}else{
    echo ("Please Login First.");
}

?>