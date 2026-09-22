<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["id"])) {
    $delivery_id = $_GET["id"];

    // Purge regional calculation rule from matching table layout row
    Database::iud("DELETE FROM `shippingcost_by_district` WHERE `delivery_id` = '" . $delivery_id . "'");
    echo "success";
} else {
    echo "Access denied.";
}
?>