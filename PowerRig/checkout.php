<?php
session_start();
require 'vendor/autoload.php';
include "connection.php";

// Set your Stripe Secret Key (Test Mode)
\Stripe\Stripe::setApiKey('sk_test_51TktYCHoQmFn3lOsaVoDT0L00RAXZHjrGAKnK0fxpTjQRwFs5tdyi8BLjbJrZBqwQXcyP6uIxokJcmMJ5fCfAslR00AMaPcuPv');

if (!isset($_SESSION["user"])) {
    echo json_encode(['Error' => 'Please log in before initiating your purchase.']);
    exit();
}

$pro_id = intval($_POST["pro_id"]);
$qty = intval($_POST["pro_qty"]);
$user_email = $_SESSION["user"]["email"];

// Fetch Product Details
$product_rs = Database::search("SELECT * FROM `product` WHERE `id`='".$pro_id."'");
if ($product_rs->num_rows == 0) {
    echo json_encode(['Error' => 'Product records could not be found.']);
    exit();
}
$product_data = $product_rs->fetch_assoc();

if (intval($product_data['qty']) < $qty) {
    echo json_encode(['Error' => 'Requested quantity exceeds current stock limits.']);
    exit();
}

// Calculate Shipping Fees dynamically
$delivery_fee = floatval($product_data["default_delivery_fee"]);
$address_rs = Database::search("SELECT `user_has_address`.`district_district_id` AS did FROM `user_has_address`
    INNER JOIN `address` ON `user_has_address`.`address_address_id`=`address`.`address_id`
    WHERE `user_has_address`.`user_email`='" . addslashes($user_email) . "'");

if ($address_rs->num_rows > 0) {
    $address_data = $address_rs->fetch_assoc();
    $shipping_rs = Database::search("SELECT `delivery_fee` FROM `shippingcost_by_district` WHERE `district_district_id`='" . $address_data["did"] . "'");
    if ($shipping_rs->num_rows > 0) {
        $delivery_fee = floatval($shipping_rs->fetch_assoc()["delivery_fee"]);
    }
}

$unit_price = floatval($product_data["price"]);
$total_items_price = $unit_price * $qty;
$final_total = $total_items_price + $delivery_fee;

try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'lkr',
                'unit_amount' => $final_total * 100, // Amount in cents
                'product_data' => [
                    'name' => $product_data['title'],
                    'description' => "Qty: " . $qty . " | Combined with Delivery Fee (Rs. " . number_format($delivery_fee, 2) . ")",
                ],
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost/PowerRig/success.php?session_id={CHECKOUT_SESSION_ID}&pid='.$pro_id.'&qty='.$qty,
        'cancel_url' => 'http://localhost/PowerRig/productDetails.php?id='.$pro_id,
    ]);

    echo json_encode(['Error' => 'none', 'url' => $checkout_session->url]);

} catch (Exception $e) {
    echo json_encode(['Error' => $e->getMessage()]);
}
?>