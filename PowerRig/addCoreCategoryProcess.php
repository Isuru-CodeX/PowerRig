<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["name"])) {
    $name = trim($_GET["name"]);

    if (empty($name)) {
        echo "Category designation parameter text entry cannot be dropped blank.";
        exit();
    }

    $cat_rs = Database::search("SELECT * FROM `category` WHERE `category_name` LIKE '" . $name . "'");
    if ($cat_rs->num_rows > 0) {
        echo "This product category label descriptor is already logged inside data registries.";
    } else {
        Database::iud("INSERT INTO `category` (`category_name`) VALUES ('" . $name . "')");
        echo "success";
    }
} else {
    echo "Unauthorized path invocation protocol block.";
}
?>