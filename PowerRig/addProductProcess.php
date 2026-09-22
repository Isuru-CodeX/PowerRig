<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"])) {
    $category = $_POST["category"];
    $brand = $_POST["brand"];
    $model = $_POST["model"];
    
    // FIXED: Use addslashes() to safely escape apostrophes (') and quotes (") in the title
    $title = addslashes(trim($_POST["title"]));
    
    $color = $_POST["color"];
    $qty = trim($_POST["qty"]);
    $price = trim($_POST["price"]);
    
    // FIXED: Use addslashes() to safely escape paragraphs containing apostrophes
    $desc = addslashes(trim($_POST["desc"]));

    // Simple Server-side Validations
    if ($category == "0" || $brand == "0" || $model == "0" || empty($title) || $color == "0" || empty($qty) || empty($price)) {
        echo "Please fulfill all core catalog fields before broadcasting.";
        exit();
    }

    // Capture the current timestamp in standard MySQL format
    $current_datetime = date("Y-m-d H:i:s");
    
    // Define a default delivery fee (Change 0.00 to any value if required)
    $default_fee = "0.00";

    // FIXED QUERY: Safely inserting the escaped title and description into the database
    Database::iud("INSERT INTO `product` (`price`, `qty`, `description`, `title`, `category_category_id`, `brand_brand_id`, `model_model_id`, `status_status_id`, `datetime_added`, `default_delivery_fee`) 
    VALUES ('".$price."', '".$qty."', '".$desc."', '".$title."', '".$category."', '".$brand."', '".$model."', '1', '".$current_datetime."', '".$default_fee."')");

    // Grab the auto-incremented ID generated for this product
    $insert_rs = Database::search("SELECT LAST_INSERT_ID() AS `new_id`");
    $insert_data = $insert_rs->fetch_assoc();
    $new_product_id = $insert_data["new_id"];

    // Register Accent Color mapping link
    Database::iud("INSERT INTO `product_has_color` (`product_id`, `color_color_id`) VALUES ('".$new_product_id."', '".$color."')");

    // Sequential File Upload Handler loop logic (0 to 5 matching ordered array streams)
    $allowed_extensions = array("image/jpeg", "image/png", "image/jpg", "image/webp");
    $target_dir = "./resources/product_images/";

    // Ensure the folder path exists on your hard drive
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Clean title alphanumeric string regex conversion for clean filename outputs
    // Note: stripslashes is used here to remove the safety slashes just for naming the physical file
    $clean_title = preg_replace('/[^a-z0-9\s_]/', '', strtolower(stripslashes($title)));
    $clean_title = preg_replace('/[\s_]+/', '_', $clean_title);

    for ($i = 0; $i < 6; $i++) {
        if (isset($_FILES["imgFile" . $i])) {
            $image_file = $_FILES["imgFile" . $i];
            $file_type = $image_file["type"];

            if (in_array($file_type, $allowed_extensions)) {
                $ext = ($file_type == "image/png") ? ".png" : (($file_type == "image/webp") ? ".webp" : ".jpg");
                
                // Naming convention logs files sequentially via loop index
                $file_name = $clean_title . "_prod" . $new_product_id . "_img" . $i . $ext;
                $full_storage_path = $target_dir . $file_name;

                if (move_uploaded_file($image_file["tmp_name"], $full_storage_path)) {
                    // Stores paths in order. Index 0 gets written first
                    Database::iud("INSERT INTO `product_img` (`img_path`, `product_id`) VALUES ('".$full_storage_path."', '".$new_product_id."')");
                }
            }
        }
    }

    echo "success";
} else {
    echo "Unauthorized access runtime block context.";
}
?>