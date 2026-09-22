<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["id"])) {
    $brand_id = $_GET["id"];

    // 1. Find all products associated with this brand
    $product_rs = Database::search("SELECT `id` FROM `product` WHERE `brand_brand_id` = '" . $brand_id . "'");
    $product_num = $product_rs->num_rows;

    if ($product_num > 0) {
        // Loop through each linked product to safely purge its relational dependencies
        while ($product_data = $product_rs->fetch_assoc()) {
            $product_id = $product_data["id"];

            // A. Clear references from user shopping carts
            Database::iud("DELETE FROM `cart` WHERE `product_id` = '" . $product_id . "'");

            // B. Clear references from user wishlists
            Database::iud("DELETE FROM `wishlist` WHERE `product_id` = '" . $product_id . "'");

            // C. Clear references from purchase history invoice logs
            Database::iud("DELETE FROM `invoice_item` WHERE `product_id` = '" . $product_id . "'");

            // D. Clear linked product image gallery paths
            Database::iud("DELETE FROM `product_img` WHERE `product_id` = '" . $product_id . "'");

            // E. Finally, delete the product itself
            Database::iud("DELETE FROM `product` WHERE `id` = '" . $product_id . "'");
        }
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