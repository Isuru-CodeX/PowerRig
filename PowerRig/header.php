<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerRig</title>
    <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/svg+xml">

    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="header.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>
    <header class="main-header">
        <div class="container header-container">
            
            <div class="header-left-group">
                <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
                    <span class="line line-1"></span>
                    <span class="line line-2"></span>
                    <span class="line line-3"></span>
                </button>

                <a href="home.php" class="header-logo">
                    <img src="./resources/system/logo/PowerRig.png" alt="PowerRig">
                </a>
            </div>

            <div class="header-actions">

                <button class="header-action-btn" aria-label="favourite items" onclick="location.href='wishlist.php'">
                    <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                    <?php
                    if (isset($_SESSION["user"])) {
                        $user_email = $_SESSION["user"]["email"];
                        $wishlist_num = Database::search("SELECT COUNT(`wishlist_id`) AS `count` FROM wishlist WHERE user_email = '" . $user_email . "'");
                        $wishlist_data = $wishlist_num->fetch_assoc();
                    ?>
                        <span class="btn-badge animated-badge"><?php echo $wishlist_data["count"]; ?></span>
                    <?php
                    } else {
                    ?>
                        <span class="btn-badge">0</span>
                    <?php
                    }
                    ?>
                </button>

                <button class="header-action-btn cart-btn" aria-label="shopping cart" onclick="location.href='cart.php'">
                    <?php
                    if (isset($_SESSION["user"])) {
                        $user_email = $_SESSION["user"]["email"];
                        $cart_row = Database::search("SELECT product.`price`, cart.qty FROM cart INNER JOIN product ON cart.product_id=product.id WHERE cart.user_email = '" . $user_email . "'");
                        $cart_total = 0;
                        $cart_count = 0;
                        while ($cart_data = $cart_row->fetch_assoc()) {
                            $total = intval($cart_data["price"]) * intval($cart_data["qty"]);
                            $cart_total += $total;
                            $cart_count += intval($cart_data["qty"]);
                        }
                    ?>
                        <span class="cart-total-value">Rs.<?php echo number_format($cart_total, 2); ?></span>
                        <div class="icon-wrap">
                            <ion-icon name="cart-outline" aria-hidden="true"></ion-icon>
                            <span class="btn-badge animated-badge"><?php echo $cart_count; ?></span>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="icon-wrap">
                            <ion-icon name="cart-outline" aria-hidden="true"></ion-icon>
                            <span class="btn-badge">0</span>
                        </div>
                    <?php
                    }
                    ?>
                </button>

                <button class="header-profile-btn" aria-label="user profile" onclick="location.href='userProfile.php'">
                    <?php
                    // Check if user is logged in and contains an active profile image file path
                    if (isset($_SESSION["user"]) && !empty($_SESSION["user"]["profile_img"])) {
                        echo '<img src="' . htmlspecialchars($_SESSION["user"]["profile_img"]) . '" alt="Profile Picture" class="profile-avatar">';
                    } else {
                        // Fallback sleek icon styling when profile image is unavailable
                        echo '<div class="avatar-fallback"><ion-icon name="person-outline" aria-hidden="true"></ion-icon></div>';
                    }
                    ?>
                </button>

            </div>
        </div>
    </header>

    <div class="sidebar-wrapper">
        <div class="mobile-navbar" data-navbar>
            <div class="sidebar-top">
                <a href="#" class="logo">
                    <img src="./resources/system/logo/Swift-art.png" alt="Book Haven">
                </a>
                <button class="nav-close-btn" aria-label="close menu" data-nav-toggler>
                    <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
                </button>
            </div>

            <ul class="navbar-list">
                <li><a href="home.php" class="navbar-link" data-nav-link>Home</a></li>
                <li><a href="advancesearch.php" class="navbar-link" data-nav-link>Advance Search</a></li>
                <li><a href="cart.php" class="navbar-link" data-nav-link>Cart</a></li>
                <li><a href="wishlist.php" class="navbar-link" data-nav-link>Wishlist</a></li>
            </ul>
        </div>
        <div class="nav-overlay" data-nav-toggler data-overlay></div>
    </div>
</body>
</html>