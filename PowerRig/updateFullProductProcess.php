<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"])) {
    $pid = $_POST["id"];
    $category = $_POST["category"];
    $brand = $_POST["brand"];
    $model = $_POST["model"];
    
    // FIXED: Use addslashes() to safely escape apostrophes (') and quotes (") in the title
    $title = addslashes(trim($_POST["title"]));
    
    $color = $_POST["color"];
    $qty = trim($_POST["qty"]);
    $price = trim($_POST["price"]);
    
    // FIXED: Use addslashes() to safely escape apostrophes in the description block
    $desc = addslashes(trim($_POST["desc"]));

    // Updates core product attributes using `id` column matching your precise SQL schema
    // The query now safely inserts the escaped title and description strings
    Database::iud("UPDATE `product` SET `price` = '".$price."', `qty` = '".$qty."', `description` = '".$desc."', `title` = '".$title."', `category_category_id` = '".$category."', `brand_brand_id` = '".$brand."', `model_model_id` = '".$model."' WHERE `id` = '".$pid."'");
    
    // Updates mapped item accent colors using the relational `product_id` matching your product_has_color mapping key
    Database::iud("UPDATE `product_has_color` SET `color_color_id` = '".$color."' WHERE `product_id` = '".$pid."'");

    // Check if new visual file assets have been loaded into the request
    if (isset($_FILES["imgFile0"])) {
        
        // Clean out previous physical files from storage to prevent memory fragmentation
        $old_imgs = Database::search("SELECT `img_path` FROM `product_img` WHERE `product_id` = '".$pid."'");
        while ($old = $old_imgs->fetch_assoc()) { 
            if (file_exists($old["img_path"])) { 
                unlink($old["img_path"]); 
            } 
        }
        
        // Wipe structural image listings inside database matching target context
        Database::iud("DELETE FROM `product_img` WHERE `product_id` = '".$pid."'");

        $allowed_extensions = array("image/jpeg", "image/png", "image/jpg", "image/webp");
        $target_dir = "./resources/product_images/";
        
        // Regular expressions to filter title formatting cleanly
        // Note: stripslashes is used here to safely build physical file names without backslashes
        $clean_title = preg_replace('/[^a-z0-9\s_]/', '', strtolower(stripslashes($title)));
        $clean_title = preg_replace('/[\s_]+/', '_', $clean_title);

        for ($i = 0; $i < 6; $i++) {
            if (isset($_FILES["imgFile" . $i])) {
                $image_file = $_FILES["imgFile" . $i];
                $file_type = $image_file["type"];

                if (in_array($file_type, $allowed_extensions)) {
                    $new_img_extension = ($file_type == "image/png") ? ".png" : (($file_type == "image/webp") ? ".webp" : ".jpg");
                    $file_name = $clean_title . "_prod" . $pid . "_img" . $i . $new_img_extension;
                    $full_storage_path = $target_dir . $file_name;

                    if (move_uploaded_file($image_file["tmp_name"], $full_storage_path)) {
                        Database::iud("INSERT INTO `product_img` (`img_path`, `product_id`) VALUES ('".$full_storage_path."', '".$pid."')");
                    }
                }
            }
        }
    }

    echo "success";
} else {
    echo "Authentication required. Session expired.";
}
?>