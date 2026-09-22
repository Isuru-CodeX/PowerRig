<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["district_id"]) && isset($_GET["fee"])) {
    $district_id = $_GET["district_id"];
    $fee = trim($_GET["fee"]);

    if ($district_id == "0") {
        echo "Please select a valid geographic target district.";
        exit();
    }

    if ($fee === "" || !is_numeric($fee) || $fee < 0) {
        echo "Please provide a valid, non-negative numerical fee structure.";
        exit();
    }

    // Ensure double configurations for the same district are caught before throwing DB constraints
    $check_rs = Database::search("SELECT * FROM `shippingcost_by_district` WHERE `district_district_id` = '" . $district_id . "'");
    if ($check_rs->num_rows > 0) {
        echo "A shipping tariff is already configured for this district. Consider using edit instead.";
    } else {
        Database::iud("INSERT INTO `shippingcost_by_district` (`district_district_id`, `delivery_fee`) VALUES ('" . $district_id . "', '" . $fee . "')");
        echo "success";
    }
} else {
    echo "Access denied.";
}
?>