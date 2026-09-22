<?php

require __DIR__ . '/vendor/autoload.php';
include "connection.php";

$stripe_secret_key = 'sk_test_51PKw262MxzGUP02tooGly2PBXB4GgZJsBOohCardLp3AQyWkukd2MUkDcV4YmdpcG9GwabDJmc2LB9v7bLHwiw3Y00bBA9cBrU';
\Stripe\Stripe::setApiKey($stripe_secret_key);


session_start();
$user = $_SESSION["user"];

$stockList = [];
$qtyList = [];
$phpObj = new stdClass();

if (isset($_POST["cart"]) && $_POST["cart"] == "true") {
    // From cart
    $rs = Database::search("SELECT * FROM `cart` WHERE `user_email`='" . $user["email"] . "'");
    while ($d = $rs->fetch_assoc()) {
        $productList[] = $d["product_id"];
        $qtyList[] = $d["qty"];
    }
} else {
    // From buy Now
    $productList[] = $_POST["pro_id"];
    $qtyList[] = $_POST["pro_qty"];
}

$items = [];
$netTotal = 0;
$totQty = 0;
$totDelivery = 0;
$orderId = uniqid();

$city_rs = Database::search("SELECT * FROM `user_has_address` INNER JOIN `address` ON `user_has_address`.`address_address_id` = `address`.`address_id` WHERE `user_email`='" . $user["email"] . "'");
$city_num = $city_rs->num_rows;

if ($city_num == 1) {
    $city_data = $city_rs->fetch_assoc();
    $city_id = $city_data["city_city_id"];
    $address = $city_data["address_address_id"];

    $district_rs = Database::search("SELECT * FROM `city` WHERE `city_id`='" . $city_id . "'");
    $district_data = $district_rs->fetch_assoc();
    $district_id = $district_data["district_id"];

    for ($i=0; $i < sizeof($productList); $i++) { 
        $rs2 = Database::search("SELECT * FROM `product` WHERE `id`='" . $productList[$i] . "'");
        $d2 = $rs2->fetch_assoc();
        $productQty = $d2["qty"];

        if ($productQty >= $qtyList[$i]) {
            $netTotal += (intval($d2["price"]) * intval($qtyList[$i]));
            $totQty += intval($qtyList[$i]);

            // Delivery Fee
            $delivery_district_rs = Database::search("SELECT * FROM `shippingcost_by_district` 
            WHERE `district_district_id`='" . $district_id . "' AND `product_id`='" . $productList[$i]. "'");
            if ($delivery_district_rs->num_rows == 0) {
                $delivery = $d2["default_delivery_fee"];
            } else {
                $delivery_district_data = $delivery_district_rs->fetch_assoc();
                $delivery = $delivery_district_data["delivery_fee"];
            }
            $totDelivery += intval($delivery); 
            $netTotal += intval($delivery);
        } else {
            $phpObj->Error = 'Product has no available stock';
            echo json_encode($phpObj);
            exit;
        }
    }

    try {
        $checkout_session = \Stripe\Checkout\Session::create([
            "mode" => 'payment',
            "success_url" => "http://localhost/PowerRig/checkoutProcess.php?session_id={CHECKOUT_SESSION_ID}",
            "cancel_url" => "http://localhost/PowerRig/cart.php",
            "payment_method_types" => ['card'],
            "line_items" => [
                [
                    "price_data" => [
                        "currency" => 'LKR',
                        "unit_amount" => $netTotal * 100,
                        "product_data" => [
                            "name" => "BookHaven Payment",
                        ]
                    ],
                    "quantity" => 1,
                ]
            ],
            "metadata" => [
                "address" => $address,
                "qty" => $totQty,
                "delivery" => $totDelivery,
                "product_id" => $productList[0]
            ]
        ]);
        $phpObj->Error = 'none';
        $phpObj->url = $checkout_session->url;

    } catch (Exception $e) {
        http_response_code(500);
        $phpObj->Error = 'Error: '. $e->getMessage();
    }
    

} else {
    $phpObj->Error = 'Please enter address';
}

echo json_encode($phpObj);
?>
