<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["id"]) && isset($_GET["type"])) {
    $target_id = $_GET["id"];
    $type = $_GET["type"];

    if ($type == "mapping") {
        // 1. Look up the Category and Brand IDs bound to this mapping record
        $map_info_rs = Database::search("SELECT `category_category_id`, `brand_brand_id` FROM `category_has_brand` WHERE `category_has_brand_id` = '" . $target_id . "'");
        
        if ($map_info_rs->num_rows > 0) {
            $map_info = $map_info_rs->fetch_assoc();
            $cat_id = $map_info["category_category_id"];
            $brand_id = $map_info["brand_brand_id"];

            // 2. Query products matching BOTH this category and brand directly
            $product_rs = Database::search("SELECT `id` FROM `product` WHERE `category_category_id` = '" . $cat_id . "' AND `brand_brand_id` = '" . $brand_id . "'");
            
            while ($product_data = $product_rs->fetch_assoc()) {
                $product_id = $product_data["id"];
                
                // Clean up dependent child logs across the system before dropping the product
                Database::iud("DELETE FROM `cart` WHERE `product_id` = '" . $product_id . "'");
                Database::iud("DELETE FROM `wishlist` WHERE `product_id` = '" . $product_id . "'");
                Database::iud("DELETE FROM `invoice_item` WHERE `product_id` = '" . $product_id . "'");
                Database::iud("DELETE FROM `product_img` WHERE `product_id` = '" . $product_id . "'");
                Database::iud("DELETE FROM `product` WHERE `id` = '" . $product_id . "'");
            }
        }
        
        // 3. Safe to drop the category_has_brand relationship mapping row
        Database::iud("DELETE FROM `category_has_brand` WHERE `category_has_brand_id` = '" . $target_id . "'");
        echo "success";
        
    } else if ($type == "core") {
        // Prevent deletion if this core category is currently mapped to any hardware brands
        $check_link = Database::search("SELECT * FROM `category_has_brand` WHERE `category_category_id` = '" . $target_id . "'");
        if ($check_link->num_rows > 0) {
            echo "Operation Blocked: Active brand relationships exist. Drop mapping links first.";
        } else {
            // Safe to drop the category record entirely
            Database::iud("DELETE FROM `category` WHERE `category_id` = '" . $target_id . "'");
            echo "success";
        }
    }
} else {
    echo "Access denied.";
}
?>