<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["id"]) && isset($_GET["name"]) && isset($_GET["brand_id"]) && isset($_GET["mapping_id"])) {
    $category_id = $_GET["id"];
    $name = trim($_GET["name"]);
    $brand_id = $_GET["brand_id"];
    $mapping_id = $_GET["mapping_id"]; // 0 indicates the item had no prior brand association links

    if (empty($name)) {
        echo "Category designation parameter entry text values cannot be blank.";
        exit();
    }

    // 1. Perform primary text identity update inside category table
    Database::iud("UPDATE `category` SET `category_name` = '" . $name . "' WHERE `category_id` = '" . $category_id . "'");

    // 2. Perform relational matrix modifications dynamically
    if ($brand_id == "0") {
        // If assigned brand choice is set to 'None', completely dissolve the connection relationship if one existed
        if ($mapping_id != "0") {
            Database::iud("DELETE FROM `category_has_brand` WHERE `category_has_brand_id` = '" . $mapping_id . "'");
        }
    } else {
        // Validate against duplicating structural combinations to keep schema keys unique
        $dup_rs = Database::search("SELECT * FROM `category_has_brand` WHERE `category_category_id` = '" . $category_id . "' AND `brand_brand_id` = '" . $brand_id . "' AND `category_has_brand_id` != '" . $mapping_id . "'");
        
        if ($dup_rs->num_rows > 0) {
            echo "Conflict: This explicit combination mapping configuration is already operational.";
            exit();
        }

        if ($mapping_id == "0") {
            // It was previously standalone (None); write a brand map row entry now
            Database::iud("INSERT INTO `category_has_brand` (`category_category_id`, `brand_brand_id`) VALUES ('" . $category_id . "', '" . $brand_id . "')");
        } else {
            // It has an existing relation profile; perform foreign key swap updates safely
            Database::iud("UPDATE `category_has_brand` SET `brand_brand_id` = '" . $brand_id . "' WHERE `category_has_brand_id` = '" . $mapping_id . "'");
        }
    }

    echo "success";
} else {
    echo "Access Denied.";
}
?>