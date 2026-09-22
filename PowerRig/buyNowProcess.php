
<?php

session_start();
include "connection.php";


if(isset($_SESSION["user"])){

    $id = $_GET["id"];
    $qty = $_GET["qty"];
    $user = $_SESSION["user"]["email"];

    $PHPobj = new stdClass();

    $order_id = uniqid();

    $product_rs = Database::search("SELECT * FROM `product` WHERE `id`='".$id."'");
    $product_data = $product_rs->fetch_assoc();

    $city_rs = Database::search("SELECT * FROM `user_has_address` INNER JOIN `address` ON 
    `user_has_address`.`address_address_id` = `address`.`address_id` WHERE `user_email`='".$user."'");
    $city_num = $city_rs->num_rows;

    if($city_num == 1){

        $city_data = $city_rs->fetch_assoc();

        $city_id = $city_data["city_city_id"];
        $address = $city_data["address_line"];

        $district_rs = Database::search("SELECT * FROM `city` WHERE `city_id`='".$city_id."'");
        $district_data = $district_rs->fetch_assoc();

        $district_id = $district_data["district_id"];

        $delivery_district_rs = Database::search("SELECT * FROM `shippingcost_by_district` WHERE 
        `district_district_id`='".$district_id."' AND `product_id`='".$id."'");

        $delivery = "0";

        if($delivery_district_rs->num_rows == 0){
            $delivery = $product_data["default_delivery_fee"];
        }else{
            $delivery_district_data = $delivery_district_rs->fetch_assoc(); 
            $delivery = $delivery_district_data["delivery_fee"];
        }

        $item = $product_data["title"];
        $amount = ((int)$product_data["price"] * (int)$qty) + (int)$delivery;

        $fname = $_SESSION["user"]["fname"];
        $lname = $_SESSION["user"]["lname"];
        $uaddress = $address;
        $city = $district_data["city_name"];

        $merchant_id = "1226894";
        $merchant_secret = "NzQwNTk2NzQxMzcwMzYxMzYzMTI0MzQ4NTM3MzIxMjIzNzk4MDAw";
        $currency = "LKR";

        $hash = strtoupper(
            md5(
                $merchant_id . 
                $order_id . 
                number_format($amount, 2, '.', '') . 
                $currency .  
                strtoupper(md5($merchant_secret)) 
            ) 
        );

        $PHPobj->id = $order_id;
        $PHPobj->item = $item;
        $PHPobj->amount = $amount;
        $PHPobj->fname = $fname;
        $PHPobj->lname = $lname;
        $PHPobj->address = $uaddress;
        $PHPobj->city = $city;
        $PHPobj->umail = $user;
        $PHPobj->mid = $merchant_id;
        $PHPobj->msecret = $merchant_secret;
        $PHPobj->currency = $currency;
        $PHPobj->hash = $hash;

        echo json_encode($PHPobj);

    }else{
        echo ("2");
    }

}else{
    echo ("1");
}

?>