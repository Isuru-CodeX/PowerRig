<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["id"]) && isset($_GET["name"]) && isset($_GET["cat_id"]) && isset($_GET["mapping_id"])) {
    $model_id = $_GET["id"];
    $name = trim($_GET["name"]);
    $cat_id = $_GET["cat_id"];
    $mapping_id = $_GET["mapping_id"]; // 0 means it had no prior mapping rule

    if (empty($name)) {
        echo "Model parameter designation label text values cannot be left blank.";
        exit();
    }

    // 1. Perform name adjustment to model table
    Database::iud("UPDATE `model` SET `model_name` = '" . $name . "' WHERE `model_id` = '" . $model_id . "'");

    // 2. Perform relational mapping matrix sync checks
    if ($cat_id == "0") {
        if ($mapping_id != "0") {
            Database::iud("DELETE FROM `category_has_model` WHERE `category_has_model_id` = '" . $mapping_id . "'");
        }
    } else {
        // Prevent unique constraint clash conflicts
        $dup_rs = Database::search("SELECT * FROM `category_has_model` WHERE `category_category_id` = '" . $cat_id . "' AND `model_model_id` = '" . $model_id . "' AND `category_has_model_id` != '" . $mapping_id . "'");
        if ($dup_rs->num_rows > 0) {
            echo "Conflict: This combination configuration map sequence is already operating.";
            exit();
        }

        if ($mapping_id == "0") {
            Database::iud("INSERT INTO `category_has_model` (`category_category_id`, `model_model_id`) VALUES ('" . $cat_id . "', '" . $model_id . "')");
        } else {
            Database::iud("UPDATE `category_has_model` SET `category_category_id` = '" . $cat_id . "' WHERE `category_has_model_id` = '" . $mapping_id . "'");
        }
    }

    echo "success";
} else {
    echo "Access Denied.";
}
?>