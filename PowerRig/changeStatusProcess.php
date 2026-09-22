<?php
include "connection.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $product_rs = Database::search("SELECT * FROM `product` WHERE `id`='" . $id . "'");
    $product_num = $product_rs->num_rows;

    if ($product_num == 1) {

        $product_data = $product_rs->fetch_assoc();
        $status = $product_data["status_status_id"];

        if ($status == 1) {

            Database::iud("UPDATE `product` SET `status_status_id`='3' WHERE `id`='" . $id . "'");
            echo ("Deactivated");
        } else if ($status == 3) {
            Database::iud("UPDATE `product` SET `status_status_id`='1' WHERE `id`='" . $id . "'");
            echo ("Activated");
        }
    } else {
        echo ("something went wrong.Please Try again later.");
    }
} else {
    echo "something went wrong";
}
