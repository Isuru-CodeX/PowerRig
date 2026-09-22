<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["type"]) && isset($_GET["cat_id"])) {
    $type = $_GET["type"];
    $cat_id = $_GET["cat_id"];

    header('Content-Type: application/json');

    // Cascade 1: Triggered immediately when Category changes
    if ($type == "category") {
        $brands = array();
        $models = array();

        // Pull corresponding vendor brand names based on category tracking constraints
        $brand_rs = Database::search("SELECT b.brand_id, b.brand_name FROM `category_has_brand` chb 
                                      INNER JOIN `brand` b ON chb.brand_brand_id = b.brand_id 
                                      WHERE chb.category_category_id = '".$cat_id."' ORDER BY b.brand_name ASC");
        while($b = $brand_rs->fetch_assoc()) { $brands[] = $b; }

        // Pull baseline model sets filtered strictly by primary category index fields
        $model_rs = Database::search("SELECT m.model_id, m.model_name FROM `category_has_model` chm 
                                      INNER JOIN `model` m ON chm.model_model_id = m.model_id 
                                      WHERE chm.category_category_id = '".$cat_id."' ORDER BY m.model_name ASC");
        while($m = $model_rs->fetch_assoc()) { $models[] = $m; }

        echo json_encode(array("brands" => $brands, "models" => $models));
        exit();
    }

    // Cascade 2: Refines the Models option list when an admin selects a specific Brand option
    if ($type == "brand" && isset($_GET["brand_id"])) {
        $brand_id = $_GET["brand_id"];
        $models = array();

        $model_rs = Database::search("SELECT m.model_id, m.model_name 
                                      FROM `category_has_model` chm 
                                      INNER JOIN `model` m ON chm.model_model_id = m.model_id 
                                      INNER JOIN `category_has_brand` chb ON chm.category_category_id = chb.category_category_id
                                      WHERE chm.category_category_id = '".$cat_id."' 
                                      AND chb.brand_brand_id = '".$brand_id."'
                                      AND m.model_name LIKE CONCAT((SELECT brand_name FROM `brand` WHERE brand_id = '".$brand_id."'), '%')
                                      ORDER BY m.model_name ASC");
                                      
        while($m = $model_rs->fetch_assoc()) { $models[] = $m; }
        echo json_encode($models);
        exit();
    }
}
?>