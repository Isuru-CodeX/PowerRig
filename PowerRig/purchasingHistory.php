<?php
include "connection.php";
session_start();

if (isset($_SESSION["user"])) {
    $mail = $_SESSION["user"]["email"];
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Purchasing History | PowerRig</title>
        <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap"
            rel="stylesheet">

        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="dashboardHistory.css" />
        <link rel="stylesheet" href="dashboard.css" />
        <link rel="stylesheet" href="home.css" />
    </head>

    <body>
        <?php include "navbar.php" ?>

        <section class="content">
            <?php include "nav.php" ?>

            <main>
                <div class="history-content">
                    <div class="history-container">

                        <div class="history-page-header">
                            <div class="icon-badge"><ion-icon name="bag-check-outline"></ion-icon></div>
                            <div>
                                <h1>Purchasing History</h1>
                                <p>Every item you've ordered from PowerRig, all in one place.</p>
                            </div>
                        </div>

                        <?php
                        // FIX: product table has no user_email column (single-vendor store, no seller concept).
                        // The original query joined product -> user on a non-existent column, which would
                        // fatally error. invoice_item already carries product_id, so we pull product/img
                        // details separately per row below instead of cramming a broken join in here.
                        $order_rs = Database::search("SELECT * FROM `invoice` 
                        INNER JOIN `invoice_item` ON `invoice`.`invoice_id` = `invoice_item`.`invoice_invoice_id` 
                        INNER JOIN `order` ON `invoice`.`order_order_id` = `order`.`order_id` 
                        WHERE `order`.`user_email`='" . $mail . "'
                        ORDER BY `invoice`.`invoice_date` DESC");
                        $order_num = $order_rs->num_rows;

                        if ($order_num == 0) {
                        ?>
                            <div class="history-empty-state">
                                <div class="empty-icon"><ion-icon name="cart-outline"></ion-icon></div>
                                <h2>You haven't purchased any item yet</h2>
                                <p>Browse the catalog and your orders will show up here once you check out.</p>
                                <a href="home.php" class="btn-shop">Start Shopping</a>
                            </div>
                        <?php
                        } else {
                        ?>

                            <div class="order-data-card">
                                <div class="order-data-head">
                                    <h3>Recent Orders</h3>
                                    <span class="order-count-pill"><?php echo $order_num; ?> order<?php echo $order_num == 1 ? '' : 's'; ?></span>
                                </div>

                                <div class="history-table-scroll">
                                    <table class="history-table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Order Details</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Purchased Date & Time</th>
                                                <th>Order Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php

                                            $row_delay = 0;

                                            for ($x = 0; $x < $order_num; $x++) {
                                                $order_data = $order_rs->fetch_assoc();
                                                $row_delay += 0.04;

                                                // FIX: pull product + first image separately (no user_email on product table)
                                                $product_rs = Database::search("SELECT * FROM `product` WHERE `id`='" . intval($order_data["product_id"]) . "'");
                                                $product_data = $product_rs->fetch_assoc();

                                                $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . intval($order_data["product_id"]) . "' LIMIT 1");
                                                $img_data = $img_rs->fetch_assoc();

                                                $order_status_rs = Database::search("SELECT * FROM `order_status` WHERE `order_status_id`='" . intval($order_data["order_status_id"]) . "'");
                                                $order_status_data = $order_status_rs->fetch_assoc();

                                                $status_id = intval($order_data["order_status_id"]);
                                                $status_classes = [
                                                    1 => "status-confirm",
                                                    2 => "status-packing",
                                                    3 => "status-shipping",
                                                    4 => "status-dispatch",
                                                    5 => "status-delivered",
                                                ];
                                                $status_class = $status_classes[$status_id] ?? "status-confirm";

                                                // FIX: only allow leaving feedback once the order has actually been delivered
                                                $can_comment = ($status_id == 5);

                                                $pid = intval($order_data['product_id']);
                                            ?>
                                                <tr style="animation-delay: <?php echo $row_delay; ?>s;">
                                                    <td>
                                                        <div class="cell-product-thumb">
                                                            <img src="<?php echo $img_data["img_path"] ?? './resources/system/logo/PowerRig.png'; ?>" alt="Product thumbnail">
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <span class="cell-product-title"><?php echo htmlspecialchars($product_data["title"] ?? 'Unknown product'); ?></span>
                                                    </td>

                                                    <td class="cell-price">Rs. <?php echo number_format($order_data["total_price"], 2); ?></td>
                                                    <td class="cell-qty">x<?php echo intval($order_data["product_qty"]); ?></td>
                                                    <td class="cell-date"><?php echo date("d M Y, h:i A", strtotime($order_data["invoice_date"])); ?></td>

                                                    <td>
                                                        <span class="status-badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($order_status_data["order_status_name"] ?? 'Pending'); ?></span>
                                                    </td>

                                                    <td>
                                                        <?php if ($can_comment) { ?>
                                                            <button class="btn-comment" onclick="addFeedback(<?php echo $pid; ?>);">
                                                                <ion-icon name="chatbubble-ellipses-outline"></ion-icon> Comment
                                                            </button>
                                                        <?php } else { ?>
                                                            <button class="btn-comment" disabled style="opacity:.4; cursor:not-allowed;" title="Available once delivered">
                                                                <ion-icon name="chatbubble-ellipses-outline"></ion-icon> Comment
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

                        <?php
                        }
                        ?>

                    </div>
                </div>
            </main>

        </section>

        <!-- Feedback modal: single shared instance, populated by JS per product id -->
        <div class="pr-modal-backdrop" id="feedbackModal">
            <div class="pr-modal">
                <div class="pr-modal-head">
                    <h5>Add Comment</h5>
                    <button type="button" class="pr-modal-close" onclick="closeFeedback();">
                        <ion-icon name="close-outline"></ion-icon>
                    </button>
                </div>
                <div>
                    <label for="feedbackComment">Your comment</label>
                    <textarea id="feedbackComment" placeholder="Share your thoughts about this product..."></textarea>
                </div>
                <div class="pr-modal-actions">
                    <button type="button" class="pr-btn-cancel" onclick="closeFeedback();">Close</button>
                    <button type="button" class="pr-btn-save" id="feedbackSaveBtn" onclick="saveFeedback();">Save Comment</button>
                </div>
            </div>
        </div>

        <div class="pr-toast-stack" id="toastStack"></div>

        <script src="app.js"></script>
        <script src="script.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <script>
            var activeFeedbackProductId = null;

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

            function addFeedback(id) {
                activeFeedbackProductId = id;
                document.getElementById("feedbackComment").value = "";
                document.getElementById("feedbackModal").classList.add("is-open");
            }

            function closeFeedback() {
                document.getElementById("feedbackModal").classList.remove("is-open");
                activeFeedbackProductId = null;
            }

            // close on backdrop click
            document.getElementById("feedbackModal").addEventListener("click", function (e) {
                if (e.target === this) { closeFeedback(); }
            });

            function saveFeedback() {
                if (!activeFeedbackProductId) { return; }

                var comment = document.getElementById("feedbackComment").value.trim();
                if (comment === "") {
                    showToast("Please write a comment first", "error");
                    return;
                }

                var saveBtn = document.getElementById("feedbackSaveBtn");
                saveBtn.classList.add("is-loading");

                var form = new FormData();
                form.append("comment", comment);
                form.append("id", activeFeedbackProductId);

                var request = new XMLHttpRequest();
                request.onreadystatechange = function () {
                    if (request.status == 200 && request.readyState == 4) {
                        saveBtn.classList.remove("is-loading");
                        var response = request.responseText.trim();
                        if (response == "success") {
                            showToast("Feedback saved", "success");
                            closeFeedback();
                        } else {
                            showToast(response || "Something went wrong", "error");
                        }
                    }
                };
                request.open("POST", "saveCommentProcess.php", true);
                request.send(form);
            }
        </script>

    </body>

    </html>
<?php
} else {
    header("Location: userLogin.php");
}
?>
