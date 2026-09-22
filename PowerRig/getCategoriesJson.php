<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"])) {
    $categories_array = array();
    $rs = Database::search("SELECT `category_id`, `category_name` FROM `category` ORDER BY `category_name` ASC");
    
    while ($row = $rs->fetch_assoc()) {
        $categories_array[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($categories_array);
} else {
    echo json_encode([]);
}
?>