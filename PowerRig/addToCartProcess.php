<?php
session_start();
include "connection.php";

if(isset($_SESSION["user"])){

    if(isset($_GET["id"])){

        $product_id = $_GET["id"];
        $user_email = $_SESSION["user"]["email"];

        $cart_rs = Database :: search ("SELECT * FROM `cart` WHERE `product_id`='".$product_id."' AND `user_email`='".$user_email."'");

        $product_rs = Database :: search ("SELECT * FROM `product` WHERE `id`='".$product_id."'");
        $product_data = $product_rs->fetch_assoc();

        $product_qty =$product_data["qty"];

        if($cart_rs->num_rows == 1){

            $cart_data = $cart_rs->fetch_assoc();
            $current_qty = $cart_data["qty"];
            $new_qty = (int)$current_qty + 1;

            if($product_qty >= $new_qty){

                Database :: iud("UPDATE `cart` SET `qty`='".$new_qty."' WHERE `cart_id`='".$cart_data["cart_id"]."'");
                echo ("Cart Updated");
            }else {
                echo ("Invalid Quantity");
            }

        }else {

            Database :: iud("INSERT INTO  `cart` (`qty`,`product_id`,`user_email`) VALUES('1','".$product_id."','".$user_email."')");
            echo ("New product added to the cart");

        }

    }else {

        echo("Somthing Went Wrong");

    }

}else {
    echo("Please Loging or Signup first");
}

?>