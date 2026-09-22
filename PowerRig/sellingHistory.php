<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"])) {
    $user = $_SESSION["admin"]["email"];
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Selling History | PowerRig</title>
        <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">

        <!--font awesome + ionicons-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <!--google font, matches home page theme-->
        <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap"
            rel="stylesheet">

        <!--css file-->
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="dashboardHistory.css" />
        <link rel="stylesheet" href="dashboard.css" />
        <link rel="stylesheet" href="home.css" />
    </head>

    <body>
        <?php include "adminNavbar.php" ?>

        <section class="content">
            <?php include "adnav.php" ?>

            <main>
                <div class="history-content">
                    <div class="history-container">

                        <div class="history-page-header">
                            <div class="icon-badge"><ion-icon name="receipt-outline"></ion-icon></div>
                            <div>
                                <h1>Selling History</h1>
                                <p>Track every order placed across the store and update fulfillment status.</p>
                            </div>
                        </div>

                        <?php

                        $query = "SELECT * FROM `invoice` 
                        INNER JOIN `invoice_item` ON `invoice`.`invoice_id` = `invoice_item`.`invoice_invoice_id` 
                        INNER JOIN `order` ON `invoice`.`order_order_id` = `order`.`order_id`";

                        if (isset($_GET["page"])) {
                            $pageno = intval($_GET["page"]);
                            if ($pageno < 1) { $pageno = 1; }
                        } else {
                            $pageno = 1;
                        }

                        $invoice_rs = Database::search($query);
                        $invoice_num = $invoice_rs->num_rows;

                        $result_per_page = 20;
                        $number_of_pages = max(1, ceil($invoice_num / $result_per_page));

                        $page_results = ($pageno - 1) * $result_per_page;
                        $selected_rs = Database::search($query . " ORDER BY `invoice`.`invoice_date` DESC LIMIT " . $result_per_page . " OFFSET " . $page_results . "");

                        $selected_num = $selected_rs->num_rows;

                        if ($selected_num == 0) {
                        ?>

                            <div class="history-empty-state">
                                <div class="empty-icon"><ion-icon name="file-tray-outline"></ion-icon></div>
                                <h2>No orders yet</h2>
                                <p>Once customers start purchasing, their orders will show up here.</p>
                                <a href="adminDashboard.php" class="btn-shop">Back to Dashboard</a>
                            </div>

                        <?php
                        } else {
                        ?>

                        <div class="order-data-card">
                            <div class="order-data-head">
                                <h3>Recent Orders</h3>
                                <span class="order-count-pill"><?php echo $invoice_num; ?> total invoice<?php echo $invoice_num == 1 ? '' : 's'; ?></span>
                            </div>

                            <div class="history-table-scroll">
                                <table class="history-table">
                                    <thead>
                                        <tr>
                                            <th>Invoice ID</th>
                                            <th>Product</th>
                                            <th>Buyer</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        $row_delay = 0;

                                        for ($x = 0; $x < $selected_num; $x++) {
                                            $selected_data = $selected_rs->fetch_assoc();
                                            $row_delay += 0.04;

                                            $product_rs = Database::search("SELECT * FROM `product` WHERE `id`='" . $selected_data["product_id"] . "'");
                                            $product_data = $product_rs->fetch_assoc();

                                            $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $selected_data["product_id"] . "' LIMIT 1");
                                            $img_data = $img_rs->fetch_assoc();

                                            $user_rs = Database::search("SELECT * FROM `user` WHERE `email`='" . $selected_data["user_email"] . "'");
                                            $user_data = $user_rs->fetch_assoc();

                                        ?>
                                            <tr style="animation-delay: <?php echo $row_delay; ?>s;">
                                                <td class="cell-id">#<?php echo htmlspecialchars($selected_data["invoice_id"]); ?></td>

                                                <td>
                                                    <div class="cell-product">
                                                        <div class="cell-product-thumb">
                                                            <img src="<?php echo $img_data["img_path"] ?? './resources/system/logo/PowerRig.png'; ?>"
                                                                loading="lazy" alt="Product thumbnail">
                                                        </div>
                                                        <span class="cell-product-title"><?php echo htmlspecialchars($product_data["title"] ?? 'Unknown product'); ?></span>
                                                    </div>
                                                </td>

                                                <td class="cell-buyer"><?php echo htmlspecialchars(($user_data["fname"] ?? '') . " " . ($user_data["lname"] ?? '')); ?></td>
                                                <td class="cell-price">Rs. <?php echo number_format($selected_data["total_price"], 2); ?></td>
                                                <td class="cell-qty">x<?php echo intval($selected_data["product_qty"]); ?></td>

                                                <?php
                                                $status_id = intval($selected_data["order_status_id"]);
                                                $order_id = $selected_data["order_id"];

                                                $status_map = [
                                                    1 => ["label" => "Confirm Order", "btn" => "btn-confirm", "icon" => "checkmark-circle-outline"],
                                                    2 => ["label" => "Packing",       "btn" => "btn-packing", "icon" => "cube-outline"],
                                                    3 => ["label" => "Shipping",      "btn" => "btn-shipping", "icon" => "boat-outline"],
                                                    4 => ["label" => "Dispatch",      "btn" => "btn-dispatch", "icon" => "rocket-outline"],
                                                    5 => ["label" => "Delivered",     "btn" => "btn-delivered", "icon" => "checkmark-done-outline"],
                                                ];

                                                $current = $status_map[$status_id] ?? $status_map[1];
                                                ?>

                                                <td>
                                                    <?php if ($status_id == 5) { ?>
                                                        <button class="btn-status <?php echo $current['btn']; ?>" id="btn<?php echo $order_id; ?>" disabled>
                                                            <ion-icon name="<?php echo $current['icon']; ?>"></ion-icon> Delivered
                                                        </button>
                                                    <?php } else { ?>
                                                        <button class="btn-status <?php echo $current['btn']; ?>" id="btn<?php echo $order_id; ?>"
                                                            onclick="changeInvoiceStatus('<?php echo $order_id; ?>', this);">
                                                            <ion-icon name="<?php echo $current['icon']; ?>"></ion-icon> <?php echo $current['label']; ?>
                                                        </button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <nav class="history-pagination" aria-label="Selling history pagination">
                            <a class="page-pill <?php echo ($pageno <= 1) ? 'disabled' : ''; ?>"
                                href="<?php echo ($pageno <= 1) ? '#' : '?page=' . ($pageno - 1); ?>" aria-label="Previous">
                                <ion-icon name="chevron-back-outline"></ion-icon>
                            </a>

                            <?php for ($x = 1; $x <= $number_of_pages; $x++) { ?>
                                <a class="page-pill <?php echo ($x == $pageno) ? 'active' : ''; ?>" href="?page=<?php echo $x; ?>"><?php echo $x; ?></a>
                            <?php } ?>

                            <a class="page-pill <?php echo ($pageno >= $number_of_pages) ? 'disabled' : ''; ?>"
                                href="<?php echo ($pageno >= $number_of_pages) ? '#' : '?page=' . ($pageno + 1); ?>" aria-label="Next">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                            </a>
                        </nav>

                        <?php } ?>

                    </div>
                </div>
            </main>

        </section>

        <div class="pr-toast-stack" id="toastStack"></div>

        <script src="app.js"></script>
        <script src="script.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <script>
            function showToast(message, type) {
                var stack = document.getElementById("toastStack");
                var toast = document.createElement("div");
                toast.className = "pr-toast" + (type === "error" ? " error" : "");
                toast.innerHTML = '<ion-icon name="' + (type === "error" ? "alert-circle-outline" : "checkmark-circle-outline") + '"></ion-icon><span>' + message + '</span>';
                stack.appendChild(toast);
                setTimeout(function () {
                    toast.classList.add("leaving");
                    setTimeout(function () { toast.remove(); }, 300);
                }, 2600);
            }

            function changeInvoiceStatus(id, btnEl) {
                if (btnEl) { btnEl.classList.add("is-loading"); }

                var request = new XMLHttpRequest();
                request.onreadystatechange = function () {
                    if (request.status == 200 && request.readyState == 4) {
                        var response = request.responseText.trim();
                        if (response == "success") {
                            showToast("Order status updated", "success");
                            setTimeout(function () { window.location.reload(); }, 600);
                        } else {
                            if (btnEl) { btnEl.classList.remove("is-loading"); }
                            showToast(response || "Something went wrong", "error");
                        }
                    }
                };
                request.open("GET", "changeInvoiceStatusProcess.php?id=" + encodeURIComponent(id), true);
                request.send();
            }
        </script>

    </body>

    </html>
<?php
} else {
    header("Location: adminLogin.php");
}
?>
