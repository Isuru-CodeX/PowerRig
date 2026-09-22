<?php
include "connection.php";
// Omitted session_start() to allow header.php to handle initial routing sequence flawlessly
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerRig | My Wishlist</title>
    
    <!-- Structural Style Assets Linking -->
    <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/svg+xml">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="wishlist.css">
    <link rel="stylesheet" href="bootstrap.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght=400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body id="top">

    <!-- Sequential Layout Node 01: Global Navigation Header Block -->
    <header class="site-header-wrapper" data-header>
        <?php include "header.php"; ?>
    </header>

    <?php
    if (isset($_SESSION["user"])) {
        $user = $_SESSION["user"]["email"];
        $total = 0;
    ?>

        <!-- Sequential Layout Node 02: Main Workspace Track System -->
        <main class="wishlist-main-workspace">
            <div class="wishlist-table-container">
                
                <!-- Core Column Labels Grid Matrix -->
                <div class="row table-header-row align-items-center ps-3">
                    <div class="col-md-2 th-label">Image</div>
                    <div class="col-md-4 th-label">Product Name</div>
                    <div class="col-md-2 th-label text-md-center">Price</div>
                    <div class="col-md-2 th-label text-md-center">Availability</div>
                    <div class="col-md-2 th-label text-md-end pe-4">Action</div>
                </div>

                <?php
                $wishlist_rs = Database::search("SELECT * FROM `wishlist` WHERE `user_email`='" . $user . "'");
                $wishlist_num = $wishlist_rs->num_rows;

                if ($wishlist_num == 0) {
                ?>
                    <div class="text-center py-5">
                        <p class="text-muted mb-0">Your wishlist is currently empty.</p>
                    </div>
                <?php
                } else {
                    for ($x = 0; $x < $wishlist_num; $x++) {
                        $wishlist_data = $wishlist_rs->fetch_assoc();

                        $product_rs = Database::search("SELECT * FROM `product` INNER JOIN `product_img` ON 
                                `product`.`id`=`product_img`.`product_id` WHERE `id`='" . $wishlist_data["product_id"] . "'");
                        $product_data = $product_rs->fetch_assoc();

                        $total = $total + $product_data["price"];
                        $max_stock = intval($product_data["qty"]);
                ?>

                        <!-- Tulip Matrix Product Item Row Track -->
                        <div class="row wishlist-item-row ps-3 g-0 wishlist-product-track" data-product-id="<?php echo $product_data['id']; ?>">
                            
                            <div class="col-md-2 d-flex justify-content-center justify-content-md-start">
                                <div class="product-media-frame">
                                    <img src="<?php echo $product_data["img_path"]; ?>" alt="Hardware Product Frame Asset">
                                </div>
                            </div>

                            <div class="col-md-4 d-flex align-items-center">
                                <div class="item-title-text"><?php echo $product_data["title"]; ?></div>
                            </div>

                            <div class="col-md-2 text-md-center d-flex align-items-center justify-content-center">
                                <span class="item-price-display">Rs. <?php echo number_format($product_data["price"], 2); ?></span>
                            </div>

                            <!-- Restored Availability Status Layer -->
                            <div class="col-md-2 text-md-center d-flex align-items-center justify-content-center">
                                <?php if ($max_stock > 0) { ?>
                                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-2">In Stock</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-2">Out of Stock</span>
                                <?php } ?>
                            </div>

                            <div class="col-md-2 text-md-end d-flex align-items-center justify-content-center justify-content-md-end pe-md-4">
                                <button class="btn-action-delete" onclick="addToWishlist(<?php echo $product_data['id']; ?>); setTimeout(() => { location.reload(); }, 300);" title="Remove Item">
                                    <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
                                </button>
                            </div>
                        </div>

                <?php
                    }
                }
                ?>
            </div>

            <!-- Context Processing Control Bar & Calculation Panel -->
            <div class="action-summary-deck">
                <div class="navigation-actions-cluster">
                    <button class="btn-navigation-action btn-navigation-secondary" onclick="location.href='home.php'">
                        Continue Shopping
                    </button>
                    <?php if ($wishlist_num > 0) { ?>
                        <button class="btn-navigation-action" onclick="moveAllWishlistToCart();">
                            Proceed To Checkout
                        </button>
                    <?php } ?>
                </div>

                <div class="summary-metrics-board">
                    <div class="metric-row">
                        <span class="metric-label">Total Items:</span>
                        <span class="metric-value"><?php echo $wishlist_num; ?></span>
                    </div>
                    <div class="metric-row metric-total-highlight">
                        <span class="metric-label">Subtotal Value:</span>
                        <span class="metric-value">Rs. <?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
            </div>
        </main>

    <?php
    } else {
        echo "<script>window.location.href='index.php';</script>";
    }
    ?>

    <!-- Sequential Layout Node 03: Global Interface Footer Component -->
    <?php include "footer.php"; ?>

    <!-- Control Operations Optimization Layer -->
    <script>
        // Sequential async processor to route explicit product arrays to active sessions safely
        async function moveAllWishlistToCart() {
            var productTracks = document.querySelectorAll('.wishlist-product-track');
            if (productTracks.length === 0) return;

            var itemsQueue = [];
            productTracks.forEach(function(row) {
                itemsQueue.push(row.getAttribute('data-product-id'));
            });

            // Asynchronous XML request dispatcher wrapper
            function sendRequest(url) {
                return new Promise((resolve) => {
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", url, true);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4) resolve();
                    };
                    xhr.send();
                });
            }

            // Execute processing queue step-by-step to prevent racing condition drops
            for (let id of itemsQueue) {
                await sendRequest("addToCartProcess.php?id=" + id + "&qty=1");
                await sendRequest("wishlistProcess.php?id=" + id);
            }

            // Route user directly to cart checkout workspace view matrix
            window.location.href = 'cart.php';
        }
    </script>

    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>

</html>