<?php
include "connection.php";
session_start();

if ( isset($_SESSION["user"]) && isset($_GET["order_id"]) && isset($_GET["invoice_id"]) && isset($_GET["delivery"]) ) {

    $user = $_SESSION["user"];
    $order_id = $_GET["order_id"];
    $delivery = $_GET["delivery"];


    $invoice_rs = Database::search("SELECT * FROM `invoice` INNER JOIN `invoice_item` ON
    `invoice`.`invoice_id`=`invoice_item`.`invoice_invoice_id` 
    INNER JOIN `product` ON 
    `invoice_item`.`product_id`=`product`.`id`
    WHERE `order_order_id`='" . $order_id . "'");
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="invoice.css">
        <link rel="shortcut icon" href="./resources/system/logo/Swift-art.png" type="image/svg+xml">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <title>Invoice</title>
    </head>

    <body>
        <div class="page-content container" id="printSection">
            <div class="row">
                <div class="col-12">
                    <div class="text-left text-150 d-flex">
                        <a href="home.php" class="logo">
                            <img src="./resources/system/logo/Swift-art.png" width="50" alt="Book Haven">
                        </a>

                        <p class="text-default-d3"> &nbsp; Book Haven</p>
                    </div>
                </div>
            </div>
            <div class="page-header text-blue-d2">

                <h1 class="page-title text-secondary-d1">
                    Invoice
                    <small class="page-info">
                        INV-<?php echo $_GET["invoice_id"]; ?>
                    </small>
                </h1>

                <div class="page-tools">
                    <div class="action-buttons no-print">
                        <a class="btn bg-white btn-light mx-1px text-95" onclick="printWindow('printSection');" data-title="Print">
                            <i class="mr-1 bi bi-printer text-primary-m1 text-120 w-2"></i>
                            Print
                        </a>

                    </div>
                </div>
            </div>

            <div class="container px-0">
                <div class="row mt-4">
                    <div class="col-12 col-lg-12 d-flex flex-column-reverse">
                        <!-- .row -->

                        <hr class="row brc-default-l1 mx-n1 mb-4" />

                        <!-- this is the table and the lower part -->
                        <div class="mt-4">
                            <div class="table-responsive">
                                <table class="table table-striped table-borderless border-0 border-b-2 brc-default-l1">
                                    <thead class="bg-none bgc-default-tp1">
                                        <tr class="text-white">
                                            <th class="opacity-2">#</th>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Unit Price (LKR)</th>
                                            <th width="140">Amount  (LKR)</th>
                                        </tr>
                                    </thead>

                                    <tbody class="text-95 text-secondary-d3">
                                        <tr></tr>
                                        <?php
                                        $product_amount = 0;
                                        $total_amount = 0;
                                        $total_amount = $delivery;
                                        
                                        for ($i = 1; $i <= $invoice_rs->num_rows; $i++) {
                                            $invoice_data = $invoice_rs->fetch_assoc();

                                            $product_amount = $invoice_data["price"] * $invoice_data["product_qty"];
                                            $total_amount += $product_amount;
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $invoice_data["title"]; ?></td>
                                                <td><?php echo $invoice_data["product_qty"]; ?></td>
                                                <td class="text-95"><?php echo $invoice_data["price"]; ?></td>
                                                <td class="text-secondary-d2"><?php echo $product_amount; ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>


                            <div class="row mt-3">
                                <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                                    Extra note such as company or payment information...
                                </div>

                                <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">

                                    <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                    <div class="col-7 text-right">
                                            Delivery Fee
                                        </div>
                                        <div class="col-5">
                                            <span class="text-110 text-success-d3 opacity-2">LKR <?php echo $delivery;?></span>
                                        </div>
                                        <div class="col-7 text-right">
                                            Total Amount
                                        </div>
                                        <div class="col-5">
                                            <span class="text-150 text-success-d3 opacity-2">LKR <?php echo $total_amount;?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr />

                            <div class="d-flex align-items-center justify-content-lg-between mb-3">
                                <span class="text-secondary-d1 text-105">Thank you for purchasing from BookHaven.</span>
                                <a href="home.php" class="btn btn-primary btn-bold px-4 float-right mt-3 align-content-end no-print">Back to
                                    home</a>
                            </div>

                        </div>

                        <!-- this is the header part -->

                        <div class="row">
                            <div class="col-sm-6">
                                <div>
                                    <span class="text-sm text-grey-m2 align-middle">To:</span>
                                    <span class="text-600 text-110 text-blue align-middle"><?php echo $user["fname"]; ?>
                                        <?php echo $user["lname"]; ?></span>
                                </div>
                                <div class="text-grey-m2">
                                    <?php
                                    $address_rs = Database::search("SELECT `address_line`,
                                `city_name`,
                                `postal_code`,
                                `district_name`,
                                `province_name`
                         FROM `user_has_address`
                         INNER JOIN `address` ON `user_has_address`.`address_address_id` = `address`.`address_id`
                         INNER JOIN `city` ON `address`.`city_city_id` = `city`.`city_id`
                         INNER JOIN `district` ON `city`.`district_id` = `district`.`district_id`
                         INNER JOIN `province` ON `district`.`province_id` = `province`.`province_id`
                         WHERE `user_email`='" . $user["email"] . "'");

                                    if ($address_rs->num_rows > 0) {
                                        $address_data = $address_rs->fetch_assoc();
                                        ?>

                                        <div class="my-1">
                                            <?php echo $address_data["address_line"] ?>,
                                        </div>
                                        <div class="my-1">
                                            <?php echo $address_data["city_name"] ?>,
                                        </div>
                                        <div class="my-1">
                                            <?php echo $address_data["district_name"] ?>,
                                        </div>
                                        <div class="my-1">
                                            <?php echo $address_data["province_name"] ?>
                                        </div>
                                        <div class="my-1">
                                            Postal code: <?php echo $address_data["postal_code"] ?>
                                        </div>
                                        <?php
                                    } else {
                                        header("Location: userProfile.php");
                                    }
                                    ?>


                                    <div class="my-1"><i class="bi bi-envelope-at text-secondary"></i> <b
                                            class="text-600"><?php echo $user["email"] ?></b></div>
                                </div>
                            </div>
                            <!-- /.col -->

                            <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                                <hr class="d-sm-none" />
                                <div class="text-grey-m2">
                                    <div class="mt-1 mb-2 text-secondary-m1 text-600 text-125">
                                        Invoice
                                    </div>

                                    <div class="my-2"><i class="bi bi-circle-fill text-blue-m2 text-xs mr-1"></i> <span
                                            class="text-600 text-90">Invoice ID:</span>
                                        INV-<?php echo $invoice_data["invoice_id"]; ?>
                                    </div>
                                    <div class="my-2"><i class="bi bi-circle-fill text-blue-m2 text-xs mr-1"></i> <span
                                            class="text-600 text-90">Order ID:</span>
                                        #<?php echo $invoice_data["order_order_id"]; ?>
                                    </div>

                                    <div class="my-2"><i class="bi bi-circle-fill text-blue-m2 text-xs mr-1"></i> <span
                                            class="text-600 text-90">Date and time:</span>
                                        <?php echo $invoice_data["invoice_date"]; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>


                    </div>
                </div>
            </div>
        </div>

        <script src="script.js"></script>
    </body>

    </html>
    <?php

} else {
    header("Location: home.php");
}
?>