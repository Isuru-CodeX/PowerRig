<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["model_id"]) && isset($_GET["cat_id"])) {
    $model_id = $_GET["model_id"];
    $cat_id = $_GET["cat_id"];

    if ($model_id == "0" || $cat_id == "0") {
        echo "Invalid relational database configuration arguments provided.";
        exit();
    }

    $map_rs = Database::search("SELECT * FROM `category_has_model` WHERE `model_model_id` = '" . $model_id . "' AND `category_category_id` = '" . $cat_id . "'");
    if ($map_rs->num_rows > 0) {
        echo "This explicit structural category-to-model configuration mapping profile is already active.";
    } else {
        Database::iud("INSERT INTO `category_has_model` (`category_category_id`, `model_model_id`) VALUES ('" . $cat_id . "', '" . $model_id . "')");
        echo "success";
    }
} else {
    echo "Access denied.";
}
?>