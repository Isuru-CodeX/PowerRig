<?php
require __DIR__ . '/vendor/autoload.php';
include "connection.php";

session_start();
$user = $_SESSION["user"]["email"];

$stripe_secret_key = 'sk_test_51PKw262MxzGUP02tooGly2PBXB4GgZJsBOohCardLp3AQyWkukd2MUkDcV4YmdpcG9GwabDJmc2LB9v7bLHwiw3Y00bBA9cBrU';
function generateInvoiceId()
{
    $counterFile = 'invoice_counter.txt';

    if (file_exists($counterFile)) {
        $counter = file_get_contents($counterFile);
        $counter = (int) $counter;
    } else {
        $counter = 0;
    }

    $counter++;

    // Save the new counter value back to the file
    file_put_contents($counterFile, $counter);

    $invoiceId = 'INV' . str_pad($counter, 9, '0', STR_PAD_LEFT);

    return $invoiceId;
}

if (isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];

    $stripe = new \Stripe\StripeClient($stripe_secret_key);

    $session = $stripe->checkout->sessions->retrieve($session_id);

    if ($session && $session->payment_status == 'paid') {
        $order_id = uniqid();

        $date = new DateTime();
        $date->setTimezone(new DateTimeZone("Asia/Colombo"));
        $time = $date->format("Y-m-d H:i:s");

        $shipping_address = isset($session->metadata['address']) ? intval($session->metadata['address']) : null;
        $billing_address = isset($session->metadata['address']) ? intval($session->metadata['address']) : null;
        $qty = isset($session->metadata['qty']) ? intval($session->metadata['qty']) : 0;
        $totDelivery = isset($session->metadata['delivery']) ? intval($session->metadata['delivery']) : 0;
        $product_id = isset($session->metadata['product_id']) ? intval($session->metadata['product_id']) : 0;

        if (is_null($shipping_address) || is_null($billing_address)) {
            echo "Invalid address provided.";
            exit;
        }

        $invoiceId = generateInvoiceId();
        $total_amount = $session->amount_total / 100;

        // Insert the order into the database
        Database::iud("INSERT INTO `order`(`order_id`,`order_date`,`total_amount`,`order_status_id`,`shipping_address`,`billing_address`,`user_email`,`qty`) 
        VALUES ('" . $order_id . "','" . $time . "','" . $total_amount . "','1','" . $shipping_address . "','" . $billing_address . "','" . $user . "','" . $qty . "')");

        $orderId = Database::$connection->insert_id;

        // Insert the invoice into the database
        Database::iud("INSERT INTO `invoice`(`invoice_id`, `order_order_id`, `invoice_date`, `total_price`) 
            VALUES ('" . $invoiceId . "', '" . $order_id . "', '" . $time . "', '" . $total_amount . "')");

        $rs = Database::search("SELECT * FROM `cart` WHERE `user_email`='" . $user . "'");

        if ($rs->num_rows == 0) {

            Database::iud("INSERT INTO `history`(`product_id`,`order_order_id`) 
            VALUES ('" . $product_id . "','" . $order_id . "')");

            Database::iud("INSERT INTO `invoice_item`(`invoice_invoice_id`, `product_id`, `product_qty`) 
            VALUES ('$invoiceId', '" . $product_id . "', '" . $qty . "')");

            $rs2 = Database::search("SELECT * FROM `product` WHERE `id`='" . $product_id . "'");
            $d2 = $rs2->fetch_assoc();

            $newQty = $d2["qty"] - intval($qty);
            Database::iud("UPDATE `product` SET `qty` = '" . $newQty . "' WHERE `id` = '" . $product_id . "'");

        } else {
            for ($i = 0; $i < $rs->num_rows; $i++) {
                $d = $rs->fetch_assoc();

                Database::iud("INSERT INTO `history`(`product_id`,`order_order_id`) 
                VALUES ('" . $d["product_id"] . "','" . $order_id . "')");


                Database::iud("INSERT INTO `invoice_item`(`invoice_invoice_id`, `product_id`, `product_qty`) 
                VALUES ('$invoiceId', '" . $d["product_id"] . "', '" . $d["qty"] . "')");

                $rs2 = Database::search("SELECT * FROM `product` WHERE `id`='" . $d["product_id"] . "'");
                $d2 = $rs2->fetch_assoc();

                $newQty = $d2["qty"] - $d["qty"];
                Database::iud("UPDATE `product` SET `qty` = '" . $newQty . "' WHERE `id` = '" . $d["product_id"] . "'");
            }
        }

        Database::iud("DELETE FROM `cart` WHERE `user_email`='" . $user . "'");

        // Redirect to the invoice page
        header("Location: invoice.php?order_id=" . $order_id . "&invoice_id=" . $invoiceId . "&delivery=" . $totDelivery);
        exit;
    } else {
        echo "Payment was not successful.";
    }
} else {
    echo "An unknown error occurred";
}
