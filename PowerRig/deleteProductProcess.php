<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["id"])) {
    $pid = $_GET["id"];

    // 1. Query and physically delete image files from your project's storage folder
    $img_rs = Database::search("SELECT `img_path` FROM `product_img` WHERE `product_id` = '".$pid."'");
    while ($img = $img_rs->fetch_assoc()) {
        $file_path = $img["img_path"];
        
        // Check if the file exists on your local drive, then delete it
        if (!empty($file_path) && file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // 2. Delete the image rows from the `product_img` table first to clear the foreign key constraint
    Database::iud("DELETE FROM `product_img` WHERE `product_id` = '".$pid."'");

    // 3. Delete the color relationship rows from the `product_has_color` table
    Database::iud("DELETE FROM `product_has_color` WHERE `product_id` = '".$pid."'");

    // 4. Finally, delete the parent row from the main `product` table using its primary key column `id`
    Database::iud("DELETE FROM `product` WHERE `id` = '".$pid."'");

    echo "success";
} else {
    echo "Authentication required or missing parameters.";
}
?>