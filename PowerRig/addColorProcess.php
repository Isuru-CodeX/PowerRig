<?php 
include "connection.php";

if(isset($_GET['color'])) {
    $color = $_GET['color'];

    if(empty($color)){
        echo("please enter color");
    }else{
        Database::iud("INSERT INTO `color`(`color_name`) VALUES('".$color."')");
        echo("color added");
    }
}else{
    echo "something went wrong";
}
?>