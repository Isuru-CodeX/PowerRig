<?php
include "connection.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    Database::iud("UPDATE `product` SET `status_status_id`='3' WHERE `id`='".$id."'");
} else {
    echo "something went wrong";
}
