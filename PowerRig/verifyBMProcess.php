<?php 
include "connection.php";

$sub_category_id=$_GET['subCategory'];

if($sub_category_id != 0){
    $category_id_rs = Database::search("SELECT `category_id` FROM `sub_category` WHERE `sub_category_id`='".$sub_category_id."'");
    if($category_id_rs->num_rows==1){
        $category_id = $category_id_rs->fetch_assoc();

        if($category_id["category_id"]==1){
            echo "Done";
        }
    }
}

?>