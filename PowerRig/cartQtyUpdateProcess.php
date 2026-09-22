<?php

include "connection.php";
session_start();

if (isset($_GET["qty"]) && isset($_GET["id"])  && isset($_GET["cid"])) {

    $qty = $_GET["qty"];
    $product_id = $_GET["id"];
    $cart_id = $_GET["cid"];

    if ($qty == 0) {
        echo ("Please insert the quantity");
    } elseif ($qty < 0) {
        echo ("please enter a non-negative number");
    } elseif (!filter_var($qty, FILTER_VALIDATE_INT)) {
        echo "Quantity must be an integer";
    } else {

        $product_rs = Database::search("SELECT * FROM `product` WHERE `id`='" . $product_id . "'");
        $product_data = $product_rs->fetch_assoc();

        $product_qty = $product_data["qty"];

        if ($product_qty >= $qty) {

            Database::iud("UPDATE `cart` SET `qty`='" . $qty . "' WHERE `cart_id`='" . $cart_id . "'");
            echo ("Updated");
        } else {
            echo ("Invalid Quantity");
        }
    }
} else {
    echo ("Something went wrong.");
}
