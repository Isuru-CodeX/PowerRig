<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["id"]) && isset($_GET["fee"])) {
    $delivery_id = $_GET["id"];
    $fee = trim($_GET["fee"]);

    if ($fee === "" || !is_numeric($fee) || $fee < 0) {
        echo "Invalid fee valuation parameter rules supplied.";
        exit();
    }

    // Execute field adjustment over matching primary configuration id
    Database::iud("UPDATE `shippingcost_by_district` SET `delivery_fee` = '" . $fee . "' WHERE `delivery_id` = '" . $delivery_id . "'");
    echo "success";
} else {
    echo "Access Denied.";
}
?>