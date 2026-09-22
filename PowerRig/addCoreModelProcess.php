<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["name"])) {
    $name = trim($_GET["name"]);

    if (empty($name)) {
        echo "Model description parameter text fields cannot be dropped blank.";
        exit();
    }

    $model_rs = Database::search("SELECT * FROM `model` WHERE `model_name` LIKE '" . $name . "'");
    if ($model_rs->num_rows > 0) {
        echo "This model identifier string name descriptor is already logged inside data registries.";
    } else {
        Database::iud("INSERT INTO `model` (`model_name`) VALUES ('" . $name . "')");
        echo "success";
    }
} else {
    echo "Access denied.";
}
?>