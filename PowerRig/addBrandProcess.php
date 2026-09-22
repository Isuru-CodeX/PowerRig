<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["name"])) {
    $name = trim($_GET["name"]);

    if (empty($name)) {
        echo "Brand designation parameter value cannot be empty.";
        exit();
    }

    // Guarding against duplicate model insertions
    $check_rs = Database::search("SELECT * FROM `brand` WHERE `brand_name` LIKE '" . $name . "'");
    if ($check_rs->num_rows > 0) {
        echo "This product brand marker is already stored inside system indexes.";
    } else {
        Database::iud("INSERT INTO `brand` (`brand_name`) VALUES ('" . $name . "')");
        echo "success";
    }
} else {
    echo "Unauthorized system execution request context path error.";
}
?>