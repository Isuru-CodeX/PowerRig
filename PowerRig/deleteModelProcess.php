<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["id"]) && isset($_GET["type"])) {
    $target_id = $_GET["id"];
    $type = $_GET["type"];

    if ($type == "mapping") {
        // Find Category and Model details from mapping bridge row
        $map_info_rs = Database::search("SELECT `category_category_id`, `model_model_id` FROM `category_has_model` WHERE `category_has_model_id` = '" . $target_id . "'");
        
        if ($map_info_rs->num_rows > 0) {
            $map_info = $map_info_rs->fetch_assoc();
            $cat_id = $map_info["category_category_id"];
            $model_id = $map_info["model_model_id"];

            // Query products combining BOTH matching criteria to prevent foreign key execution block crashes
            $product_rs = Database::search("SELECT `id` FROM `product` WHERE `category_category_id` = '" . $cat_id . "' AND `model_model_id` = '" . $model_id . "'");
            
            while ($product_data = $product_rs->fetch_assoc()) {
                $product_id = $product_data["id"];
                
                Database::iud("DELETE FROM `cart` WHERE `product_id` = '" . $product_id . "'");
                Database::iud("DELETE FROM `wishlist` WHERE `product_id` = '" . $product_id . "'");
                Database::iud("DELETE FROM `invoice_item` WHERE `product_id` = '" . $product_id . "'");
                Database::iud("DELETE FROM `product_img` WHERE `product_id` = '" . $product_id . "'");
                Database::iud("DELETE FROM `product` WHERE `id` = '" . $product_id . "'");
            }
        }
        
        // Remove the bridge map record entry itself safely
        Database::iud("DELETE FROM `category_has_model` WHERE `category_has_model_id` = '" . $target_id . "'");
        echo "success";
        
    } else if ($type == "core") {
        // Halt drops if the core model has ongoing brand relationship maps configured
        $check_link = Database::search("SELECT * FROM `category_has_model` WHERE `model_model_id` = '" . $target_id . "'");
        if ($check_link->num_rows > 0) {
            echo "Operation Blocked: Active category relationships exist. Drop mapping links first.";
        } else {
            Database::iud("DELETE FROM `model` WHERE `model_id` = '" . $target_id . "'");
            echo "success";
        }
    }
} else {
    echo "Access denied.";
}
?>