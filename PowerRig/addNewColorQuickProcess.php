<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["color"])) {
    $color_name = trim($_GET["color"]);

    if (empty($color_name)) {
        echo "Color description argument parameters cannot be dropped blank.";
        exit();
    }

    // Check for duplicate color values to maintain strict clean records
    $check_rs = Database::search("SELECT * FROM `color` WHERE `color_name` LIKE '".$color_name."'");
    if ($check_rs->num_rows > 0) {
        $existing = $check_rs->fetch_assoc();
        echo "success|".$existing["color_id"];
    } else {
        // Register the new color record
        Database::iud("INSERT INTO `color` (`color_name`) VALUES ('".$color_name."')");
        // Capture the auto-generated primary key ID string reference
        $new_id = Database::$connection->insert_id;
        echo "success|".$new_id;
    }
} else {
    echo "Access denied.";
}
?>