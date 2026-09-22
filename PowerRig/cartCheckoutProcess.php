<?php
session_start();
require 'vendor/autoload.php';
include "connection.php";

// Set your Stripe Secret Key (Test Mode)
\Stripe\Stripe::setApiKey('sk_test_51TktYCHoQmFn3lOsaVoDT0L00RAXZHjrGAKnK0fxpTjQRwFs5tdyi8BLjbJrZBqwQXcyP6uIxokJcmMJ5fCfAslR00AMaPcuPv');

header('Content-Type: application/json');

if (!isset($_SESSION["user"])) {
    echo json_encode(["error" => "Authentication required. Please log in."]);
    exit();
}

$user_email = $_SESSION["user"]["email"];

try {
    // 1. Fetch all items in this user's cart by joining with the product table
    $cart_rs = Database::search("SELECT `cart`.*, `product`.`title`, `product`.`price` FROM `cart` 
                                  INNER JOIN `product` ON `cart`.`product_id` = `product`.`id` 
                                  WHERE `cart`.`user_email` = '" . addslashes($user_email) . "'");
    
    if (!$cart_rs || $cart_rs->num_rows == 0) {
        echo json_encode(["error" => "Your cart is completely empty."]);
        exit();
    }

    $line_items = [];

    // 2. Loop over every product found in the cart
    while ($cart_data = $cart_rs->fetch_assoc()) {
        $product_title = $cart_data['title'];
        $unit_price = floatval($cart_data['price']);
        $quantity = intval($cart_data['qty']);

        $line_items[] = [
            'price_data' => [
                'currency' => 'lkr',
                'product_data' => [
                    'name' => $product_title,
                ],
                'unit_amount' => $unit_price * 100, // Converts amount to cents-equivalent
            ],
            'quantity' => $quantity,
        ];
    }

    // 3. Match Cart delivery logic by identifying user's exact district metrics from DB
    $shipping_fee = 0;
    
    $address_rs = Database::search("SELECT `district`.* FROM `user_has_address` 
                                    INNER JOIN `district` ON `user_has_address`.`district_district_id` = `district`.`district_id`
                                    WHERE `user_has_address`.`user_email` = '" . addslashes($user_email) . "'");
    
    if ($address_rs && $address_rs->num_rows > 0) {
        $address_data = $address_rs->fetch_assoc();
        
        // Match Colombo ID configuration (Colombo is ID 5 in your system)
        if (intval($address_data['district_id']) == 5) {
            $shipping_fee = isset($address_data['delivery_fee_colombo']) ? floatval($address_data['delivery_fee_colombo']) : 200;
        } else {
            $shipping_fee = isset($address_data['delivery_fee_other']) ? floatval($address_data['delivery_fee_other']) : 500;
        }
    } else {
        // Default safe fallback if user address record mapping isn't fully linked yet
        $shipping_fee = 200; 
    }

    // Append Shipping Fee line item to Stripe Checkout session array bundle
    if ($shipping_fee > 0) {
        $line_items[] = [
            'price_data' => [
                'currency' => 'lkr',
                'product_data' => [
                    'name' => 'Shipping & Delivery Fee',
                    'description' => 'Region-based logistics handling fee',
                ],
                'unit_amount' => $shipping_fee * 100,
            ],
            'quantity' => 1,
        ];
    }

    // 4. Construct unified Stripe Checkout session
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => 'http://localhost/PowerRig/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost/PowerRig/cart.php',
    ]);

    echo json_encode(["url" => $session->url]);

} catch (Exception $e) {
    echo json_encode(["error" => "Stripe Session Error: " . $e->getMessage()]);
}
?>