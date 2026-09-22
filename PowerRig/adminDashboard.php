<?php

session_start();
include "connection.php";

if (isset($_SESSION["admin"])) {

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>PowerRig - Admin Management Platform</title>
        <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <link rel="stylesheet" href="bootstrap.css" />
        <link rel="stylesheet" href="home.css" />
        <link rel="stylesheet" href="dashboard.css" />
    </head>

    <body onload="loadChart();">
        
        <?php include "adminNavbar.php" ?>

        <section class="content">
            <?php include "adnav.php" ?>

            <main>
                <?php
                $today = date("Y-m-d");
                $thismonth = date("m");
                $thisyear = date("Y");

                $a = "0";
                $b = "0";
                $c = "0";
                $e = "0";
                $f = "0";

                $invoice_rs = Database::search("SELECT * FROM `invoice` INNER JOIN `order` ON 
                `invoice`.`order_order_id`=`order`.`order_id`");
                $invoice_num = $invoice_rs->num_rows;

                for ($x = 0; $x < $invoice_num; $x++) {
                    $invoice_data = $invoice_rs->fetch_assoc();

                    $f = $f + $invoice_data["qty"]; // total overall volume qty
            
                    $d = $invoice_data["order_date"];
                    $splitDate = explode(" ", $d); // separate timestamps
                    $pdate = $splitDate["0"]; // extracted sold calendar date
            
                    if ($pdate == $today) {
                        $a = $a + $invoice_data["total_price"];
                        $c = $c + $invoice_data["qty"];
                    }

                    $splitMonth = explode("-", $pdate);
                    $pyear = $splitMonth["0"];
                    $pmonth = $splitMonth["1"];

                    if ($pyear == $thisyear) {
                        if ($pmonth == $thismonth) {
                            $b = $b + $invoice_data["total_price"];
                            $e = $e + $invoice_data["qty"];
                        }
                    }
                }
                ?>

                <div class="head-title">
                    <a href="#" class="download-btn">
                        <i class="fas fa-cloud-download-alt"></i>
                        <span class="text">Download Systems Report</span>
                    </a>
                </div>

                <ul class="box-info">
                    <li>
                        <i class="fas fa-calendar-day"></i>
                        <span class="text">
                            <h3>Rs. <?php echo $a; ?> .00</h3>
                            <p>Daily Earnings Today</p>
                        </span>
                    </li>
                    <li>
                        <i class="fas fa-cart-shopping"></i>
                        <span class="text">
                            <h3><?php echo $c; ?></h3>
                            <p>Daily Invoice Volume</p>
                        </span>
                    </li>
                    <li>
                        <i class="fas fa-dollar-sign"></i>
                        <span class="text">
                            <h3>Rs. <?php echo $b; ?> .00</h3>
                            <p>Monthly Gross Income</p>
                        </span>
                    </li>
                    <li>
                        <i class="fas fa-boxes-stacked"></i>
                        <span class="text">
                            <h3><?php echo $e; ?></h3>
                            <p>Monthly Units Shipped</p>
                        </span>
                    </li>
                    <li>
                        <i class="fas fa-chart-line"></i>
                        <span class="text">
                            <h3><?php echo $f; ?></h3>
                            <p>All-Time Volume Sold</p>
                        </span>
                    </li>
                    <li>
                        <i class="fas fa-users"></i>
                        <span class="text">
                            <?php
                            $user_rs = Database::search("SELECT * FROM `user`");
                            $user_num = $user_rs->num_rows;
                            ?>
                            <h3><?php echo $user_num; ?></h3>
                            <p>Total Engaged Clients</p>
                        </span>
                    </li>
                    <li style="grid-column: span 2;">
                        <i class="fas fa-hourglass-high"></i>
                        <span class="text">
                            <?php
                            $start_date = new DateTime($_SESSION["admin"]["joined_date"]);
                            $tdate = new DateTime();
                            $tz = new DateTimeZone("Asia/Colombo");
                            $tdate->setTimezone($tz);
                            $end_date = new DateTime($tdate->format("Y-m-d H:i:s"));

                            $difference = $end_date->diff($start_date);
                            ?>
                            <h3>
                                <?php
                                echo $difference->format('%Y') . "y : " . $difference->format('%m') . "m : " .
                                    $difference->format('%d') . "d " . $difference->format('%H') . "h : " .
                                    $difference->format('%i') . "m : " . $difference->format('%s') . "s";
                                ?>
                            </h3>
                            <p>Active System Session Lifetime</p>
                        </span>
                    </li>
                </ul>

                <div class="insights-grid">
                    <div class="chart-panel">
                        <h2>Monthly Sales Performance Data</h2>
                        <div style="position: relative; height:320px; width:100%;">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-panel">
                        <h2>Top Performing Dynamic Products</h2>
                        <div style="position: relative; height:320px; width:100%;">
                            <canvas id="myChart2"></canvas>
                        </div>
                    </div>
                </div>

                <div class="leaderboard-grid">
                    
                    <div class="leaderboard-card">
                        <h3>Top Sold Item</h3>
                        <?php
                        $freq_rs = Database::search("SELECT `product_id`, COUNT(`product_id`) AS `value_occurence`
                        FROM `invoice_item`
                        INNER JOIN `invoice` ON `invoice_item`.`invoice_invoice_id` = `invoice`.`invoice_id` 
                        INNER JOIN `order` ON `invoice`.`order_order_id` = `order`.`order_id` 
                        WHERE `invoice_date` LIKE '%" . $today . "%' 
                        GROUP BY `product_id` 
                        ORDER BY COUNT(`product_id`) DESC 
                        LIMIT 1");

                        $freq_num = $freq_rs->num_rows;

                        if ($freq_num > 0) {
                            $freq_data = $freq_rs->fetch_assoc();

                            $product_rs = Database::search("SELECT * FROM `product` INNER JOIN `product_img` ON 
                            product.id=product_img.product_id INNER JOIN `user` ON product.user_email=user.email WHERE `id`='" . $freq_data["product_id"] . "'");

                            $qty_rs = Database::search("SELECT SUM(`product_qty`) AS `product_qty_total` FROM `invoice_item` 
                            INNER JOIN `invoice` ON `invoice_item`.`invoice_invoice_id` = `invoice`.`invoice_id`
                            INNER JOIN `order` ON `invoice`.`order_order_id` = `order`.`order_id`
                            WHERE `product_id`='" . $freq_data["product_id"] . "' AND `invoice_date` LIKE '%" . $today . "%'");
                            $qty_data = $qty_rs->fetch_assoc();
                            $product_data = $product_rs->fetch_assoc();
                            ?>
                            <div class="showcase-img-container">
                                <img src="<?php echo $product_data["img_path"]; ?>" class="img-fluid" />
                            </div>
                            <div class="leaderboard-details">
                                <div class="details-row-item">
                                    <p><strong>Item Label:</strong> <?php echo $product_data["title"]; ?></p>
                                </div>
                                <div class="details-row-item">
                                    <p><strong>Throughput Sold:</strong> <?php echo $qty_data["product_qty_total"]; ?> Units</p>
                                </div>
                                <div class="details-row-item">
                                    <p><strong>Total Earnings Yield:</strong> Rs. <?php echo $qty_data["product_qty_total"] * $product_data["price"]; ?> .00</p>
                                </div>
                            </div>
                        <?php
                        } else {
                            ?>
                            <div class="leaderboard-details">
                                <div class="details-row-item empty-state">
                                    <p><i class="fas fa-triangle-exclamation me-2"></i> No transaction telemetry registered for today.</p>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>

                    <div class="leaderboard-card">
                        <h3>High-Performance Merchant Account</h3>
                        <?php
                        if ($freq_num > 0) {
                            $img_rs = Database::search("SELECT * FROM `profile_img` WHERE `user_email`='" . $product_data["email"] . "'");

                            if ($img_rs->num_rows == 0) {
                                ?>
                                <div class="showcase-img-container">
                                    <img src="./resources/system/fox7.jpg" class="img-fluid" />
                                </div>
                                <?php
                            } else {
                                $img_data = $img_rs->fetch_assoc();
                                    ?>
                                <div class="showcase-img-container">
                                    <img src="<?php echo $img_data["img_path"]; ?>" class="img-fluid" />
                                </div>
                                <?php
                            }
                            ?>
                            <div class="leaderboard-details">
                                <div class="details-row-item">
                                    <p><strong>Profile Identifier:</strong> <?php echo $product_data["fname"] . " " . $product_data["lname"]; ?></p>
                                </div>
                                <div class="details-row-item">
                                    <p><strong>Communication Routing Email:</strong> <?php echo $product_data["email"]; ?></p>
                                </div>
                                <div class="details-row-item">
                                    <p><strong>Registration Epoch:</strong> <?php echo $product_data["joined_date"] ?></p>
                                </div>
                            </div>
                        <?php
                        } else {
                            ?>
                            <div class="leaderboard-details">
                                <div class="details-row-item empty-state">
                                    <p><i class="fas fa-triangle-exclamation me-2"></i> Operational merchant metadata unavailable.</p>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>

                </div>
            </main>
        </section>

        <script src="app.js"></script>
        <script src="script.js"></script>
        <script src="bootstrap.bundle.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </body>
    </html>
    <?php
} else {
    header("Location:adminLogin.php");
}
?>