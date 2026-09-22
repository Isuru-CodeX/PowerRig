<?php
session_start();
include "connection.php";

if (isset($_SESSION["user"])) {

    if (isset($_POST["id"])) {

        $qty = $_POST["qty"];

        if ($qty == 0) {
            echo ("Please insert the quantity");
        } elseif ($qty < 0) {
            echo ("please enter a non-negative number");
        }elseif (!filter_var($qty, FILTER_VALIDATE_INT)) {
            echo "Quantity must be an integer";
        } else {
            $product_id = $_POST["id"];
            $user_email = $_SESSION["user"]["email"];

            $cart_rs = Database::search("SELECT * FROM `cart` WHERE `product_id`='" . $product_id . "' AND `user_email`='" . $user_email . "'");

            $product_rs = Database::search("SELECT * FROM `product` WHERE `id`='" . $product_id . "'");
            $product_data = $product_rs->fetch_assoc();

            $product_qty = $product_data["qty"];

            if ($cart_rs->num_rows == 1) {

                $cart_data = $cart_rs->fetch_assoc();
                $current_qty = $cart_data["qty"];
                $new_qty = (int)$current_qty + (int)$qty;

                if ($product_qty >= $new_qty) {

                    Database::iud("UPDATE `cart` SET `qty`='" . $new_qty . "' WHERE `cart_id`='" . $cart_data["cart_id"] . "'");
                    echo ("Cart Updated");
                } else {
                    echo ("Invalid Quantity");
                }
            } else {
                if ($product_qty >= $qty) {

                    Database::iud("INSERT INTO  `cart` (`qty`,`product_id`,`user_email`) VALUES('".$qty."','" . $product_id . "','" . $user_email . "')");
                    echo ("New product added to the cart");
                } else {
                    echo ("Invalid Quantity");
                }
            }
        }
    } else {

        echo ("Somthing Went Wrong");
    }
} else {
    echo ("Please Loging or Signup first");
}
