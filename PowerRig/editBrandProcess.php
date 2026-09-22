<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["id"]) && isset($_GET["name"])) {
    $id = $_GET["id"];
    $name = trim($_GET["name"]);

    if (empty($name)) {
        echo "Updated text value cannot be dropped blank.";
        exit();
    }

    Database::iud("UPDATE `brand` SET `brand_name` = '" . $name . "' WHERE `brand_id` = '" . $id . "'");
    echo "success";
} else {
    echo "Access denied.";
}
?>