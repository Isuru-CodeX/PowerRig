<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["id"])) {
    $brand_id = $_GET["id"];

    // 1. Find all products associated with this brand
    $product_rs = Database::search("SELECT `id` FROM `product` WHERE `brand_brand_id` = '" . $brand_id . "'");
    $product_num = $product_rs->num_rows;

    if ($product_num > 0) {
        $product_ids = [];
        // Loop through each linked product to get all product IDs
        while ($product_data = $product_rs->fetch_assoc()) {
            $product_ids[] = "'" . $product_data["id"] . "'";
        }
        $id_list = implode(",", $product_ids);

        // A. Clear references from user shopping carts
        Database::iud("DELETE FROM `cart` WHERE `product_id` IN (" . $id_list . ")");

        // B. Clear references from user wishlists
        Database::iud("DELETE FROM `wishlist` WHERE `product_id` IN (" . $id_list . ")");

        // C. Clear references from purchase history invoice logs
        Database::iud("DELETE FROM `invoice_item` WHERE `product_id` IN (" . $id_list . ")");

        // D. Clear linked product image gallery paths
        Database::iud("DELETE FROM `product_img` WHERE `product_id` IN (" . $id_list . ")");

        // E. Finally, delete the products themselves
        Database::iud("DELETE FROM `product` WHERE `id` IN (" . $id_list . ")");
    }

    // 2. Clear out any brand-to-category mapping rules if you use a mapping/junction table 
    // (If your category table links directly inside product, step 1 already handled it via the product drop)

    // 3. Safe to delete the core brand since all external foreign key blocks have been resolved
    Database::iud("DELETE FROM `brand` WHERE `brand_id` = '" . $brand_id . "'");

    echo "success";

} else {
    echo "Administrative authentication or parameters missing.";
}
?>