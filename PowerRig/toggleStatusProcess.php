<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["id"])) {
    $pid = $_GET["id"];
    $product_rs = Database::search("SELECT `status_status_id` FROM `product` WHERE `product_id` = '".$pid."'");
    if ($product_rs->num_rows > 0) {
        $product = $product_rs->fetch_assoc();
        $new_status = ($product["status_status_id"] == 1) ? 2 : 1;
        Database::iud("UPDATE `product` SET `status_status_id` = '".$new_status."' WHERE `product_id` = '".$pid."'");
        echo ($new_status == 1) ? "activated" : "deactivated";
    } else { echo "Product tracking row records could not be found."; }
} else { echo "Unauthorized administrative privilege error."; }
?>