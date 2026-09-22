<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"])) {
    $brands_array = array();
    // Retrieve registered equipment vendor rows sequentially
    $rs = Database::search("SELECT `brand_id`, `brand_name` FROM `brand` ORDER BY `brand_name` ASC");
    
    while ($row = $rs->fetch_assoc()) {
        $brands_array[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($brands_array);
} else {
    echo json_encode([]);
}
?>