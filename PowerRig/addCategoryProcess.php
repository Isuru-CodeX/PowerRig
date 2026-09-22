<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["name"]) && isset($_GET["brand"])) {
    $name = trim($_GET["name"]);
    $brand_id = $_GET["brand"];

    if (empty($name) || $brand_id == "0") {
        echo "Missing required relational parameter values.";
        exit();
    }

    // Checking using the verified column: category_name
    $cat_rs = Database::search("SELECT * FROM `category` WHERE `category_name` LIKE '" . $name . "'");
    if ($cat_rs->num_rows > 0) {
        $cat_data = $cat_rs->fetch_assoc();
        $category_id = $cat_data["category_id"];
    } else {
        Database::iud("INSERT INTO `category` (`category_name`) VALUES ('" . $name . "')");
        
        $new_cat_rs = Database::search("SELECT * FROM `category` WHERE `category_name` LIKE '" . $name . "'");
        $new_cat_data = $new_cat_rs->fetch_assoc();
        $category_id = $new_cat_data["category_id"];
    }

    // Mapping using structural table keys: category_category_id, brand_brand_id
    $map_rs = Database::search("SELECT * FROM `category_has_brand` WHERE `category_category_id` = '" . $category_id . "' AND `brand_brand_id` = '" . $brand_id . "'");
    if ($map_rs->num_rows > 0) {
        echo "This exact Category-to-Brand configuration link mapping is already active.";
    } else {
        Database::iud("INSERT INTO `category_has_brand` (`category_category_id`, `brand_brand_id`) VALUES ('" . $category_id . "', '" . $brand_id . "')");
        echo "success";
    }
} else {
    echo "Unauthorized path invocation protocol block.";
}
?>