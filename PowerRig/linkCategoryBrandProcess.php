<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["cat_id"]) && isset($_GET["brand_id"])) {
    $cat_id = $_GET["cat_id"];
    $brand_id = $_GET["brand_id"];

    if ($cat_id == "0" || $brand_id == "0") {
        echo "Invalid relational database configuration arguments provided.";
        exit();
    }

    $map_rs = Database::search("SELECT * FROM `category_has_brand` WHERE `category_category_id` = '" . $cat_id . "' AND `brand_brand_id` = '" . $brand_id . "'");
    if ($map_rs->num_rows > 0) {
        echo "This explicit structural category mapping relation configuration is already active.";
    } else {
        Database::iud("INSERT INTO `category_has_brand` (`category_category_id`, `brand_brand_id`) VALUES ('" . $cat_id . "', '" . $brand_id . "')");
        echo "success";
    }
} else {
    echo "Access denied.";
}
?>