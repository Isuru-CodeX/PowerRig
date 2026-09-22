<?php
include "connection.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerRig | Shopping Cart</title>

    <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/svg+xml">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="bootstrap.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght=400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body id="top">

    <header class="site-header-wrapper" data-header>
        <?php include "header.php"; ?>
    </header>

    <?php
    // Safe validation execution block now that header.php has safely initialized
    if (isset($_SESSION["user"])) {
        $user = $_SESSION["user"]["email"];

        $total = 0;
        $shipping = 0;
    ?>

    <main class="cart-main-workspace">
        <div class="cart-table-container">

            <div class="row table-header-row align-items-center ps-3">
                <div class="col-md-2 th-label">Product</div>
                <div class="col-md-4 th-label">Details</div>
                <div class="col-md-2 th-label text-md-center">Price</div>
                <div class="col-md-2 th-label text-md-center">Quantity</div>
                <div class="col-md-2 th-label text-md-end pe-4">Action</div>
            </div>

            <?php
                $cart_rs = Database::search("SELECT * FROM `cart` WHERE `user_email`='" . $user . "'");
                $cart_num = $cart_rs->num_rows;

                if ($cart_num == 0) {
                ?>
            <div class="text-center py-5">
                <p class="text-muted mb-0">Your shopping cart matrix container is currently empty.</p>
            </div>
            <?php
                } else {
                    for ($x = 0; $x < $cart_num; $x++) {
                        $cart_data = $cart_rs->fetch_assoc();

                        $product_rs = Database::search("SELECT * FROM `product` INNER JOIN `product_img` ON 
                                `product`.`id`=`product_img`.`product_id` WHERE `id`='" . $cart_data["product_id"] . "'");
                        $product_data = $product_rs->fetch_assoc();

                        // Complete correct arithmetic processing logic based on quantity state counters
                        $total = $total + ($product_data["price"] * $cart_data["qty"]);

                        // CHANGED: District ID (did) retrieved directly from user_has_address table
                        $address_rs = Database::search("SELECT `user_has_address`.`district_district_id` AS did FROM `user_has_address`
                            INNER JOIN `address` ON `user_has_address`.`address_address_id`=`address`.`address_id`
                            INNER JOIN `city` ON `address`.`city_city_id`=`city`.`city_id`
                            WHERE `user_has_address`.`user_email`='" . $user . "'");

                        if ($address_rs->num_rows == 0) {
                            echo "<script>window.location.href='userProfile.php';</script>";
                            exit();
                        } else {
                            $address_data = $address_rs->fetch_assoc();
                            $ship = 0;

                        
                        $shippingCost_row = Database::search("SELECT `delivery_fee` FROM `shippingcost_by_district` 
                            WHERE `district_district_id`='" . $address_data["did"] . "'");

                        if ($shippingCost_row->num_rows == 0) {
                            $ship = $product_data["default_delivery_fee"];
                        } else {
                            $shippingCost_data = $shippingCost_row->fetch_assoc();
                            $ship = $shippingCost_data["delivery_fee"];
                        }

                        $shipping = $shipping + $ship;
                ?>

            <div class="row cart-item-row ps-3 g-0">
                <div class="col-md-2 d-flex justify-content-center justify-content-md-start">
                    <div class="product-media-frame">
                        <img src="<?php echo $product_data["img_path"]; ?>" alt="Hardware Asset Frame">
                    </div>
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-center text-center text-md-start">
                    <div class="item-title-text"><?php echo $product_data["title"]; ?></div>
                    <div class="item-desc-text text-truncate pe-md-3"><?php echo $product_data["description"]; ?></div>
                </div>

                <div class="col-md-2 text-md-center d-flex align-items-center justify-content-center">
                    <span class="item-price-display">Rs. <?php echo number_format($product_data["price"], 2); ?></span>
                </div>

                <div class="col-md-2 d-flex align-items-center justify-content-center">
                    <div class="qty-control-deck">
                        <button class="qty-deck-btn"
                            onclick="updateCartQty(<?php echo $product_data['id']; ?>, <?php echo $cart_data['cart_id']; ?>, -1)">
                            <ion-icon name="remove-outline"></ion-icon>
                        </button>

                        <div class="qty-deck-value" id="qty_val_<?php echo $product_data['id']; ?>">
                            <?php echo $cart_data["qty"]; ?>
                        </div>

                        <button class="qty-deck-btn"
                            onclick="updateCartQty(<?php echo $product_data['id']; ?>, <?php echo $cart_data['cart_id']; ?>, 1)">
                            <ion-icon name="add-outline"></ion-icon>
                        </button>
                    </div>

                    <input type="hidden" id="qty_num<?php echo $product_data['id']; ?>"
                        value="<?php echo $cart_data["qty"]; ?>">
                </div>

                <div
                    class="col-md-2 text-md-end d-flex align-items-center justify-content-center justify-content-md-end pe-md-4">
                    <button class="btn-action-delete" onclick="deleteFromCart(<?php echo $cart_data['cart_id']; ?>)"
                        title="Remove Item">
                        <ion-icon name="trash-outline" aria-hidden="true"></ion-icon>
                    </button>
                </div>
            </div>

            <?php
                        }
                    }
                }
                ?>
        </div>

        <div class="action-summary-deck">
            <div class="navigation-actions-cluster">
                <button class="btn-navigation-action btn-navigation-secondary" onclick="location.href='home.php'">
                    Continue Shopping
                </button>
                <?php if ($cart_num > 0) { ?>
                    <button onclick="checkoutCartBundle();" class="btn-navigation-action">
                        Proceed to Checkout
                    </button>
                <?php } ?>
            </div>

            <div class="summary-metrics-board">
                <div class="metric-row">
                    <span class="metric-label">Total Items:</span>
                    <span class="metric-value"><?php echo $cart_num; ?></span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Subtotal Cost:</span>
                    <span class="metric-value">Rs. <?php echo number_format($total, 2); ?></span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Delivery Fee:</span>
                    <span class="metric-value">Rs. <?php echo number_format($shipping, 2); ?></span>
                </div>
                <div class="metric-divider"></div>
                <div class="metric-row metric-total-highlight">
                    <span class="metric-label">Total Summary:</span>
                    <span class="metric-value">Rs. <?php echo number_format(($total + $shipping), 2); ?></span>
                </div>
            </div>
        </div>
    </main>

    <?php
    } else {
        echo "<script>window.location.href='index.php';</script>";
    }
    ?>

    <?php include "footer.php"; ?>

    <script>
    function updateCartQty(productId, cartId, delta) {
        // Locate inputs and structural deck variables
        var displayDiv = document.getElementById('qty_val_' + productId);
        var hiddenInput = document.getElementById('qty_num' + productId);

        var currentQty = parseInt(displayDiv.innerText);
        var newQty = currentQty + delta;

        // Restrict quantity bounds from falling below 1 unit trace
        if (newQty < 1) return;

        // Update UI elements instantly to preserve performance fluid metrics
        displayDiv.innerText = newQty;
        hiddenInput.value = newQty;

        // Route processing parameters via legacy asynchronous backend handler
        changeQTY(productId, cartId);
    }
    </script>

    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>

</html>