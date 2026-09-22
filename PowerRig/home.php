<?php
include "connection.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerRig | Ultimate PC Parts, Gaming Gear & Accessories Store</title>

    <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">
    <link rel="stylesheet" href="home.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
</head>

<body id="top">

    <header class="site-header-wrapper" data-header>
        <?php include "header.php"; ?>
    </header>

    <main>
        <article>

            <section class="section carousel-hero" aria-label="Product Showcase Gallery">
                <div class="carousel-container">

                    <div class="carousel-slider-track" data-carousel-track>

                        <div class="carousel-slide active" data-carousel-slide
                            style="background-image: linear-gradient(90deg, rgba(0,0,0,0.55) 85%, rgba(0,0,0,0.1) 100%), url('./resources/system/carousel/bnr_1.jpg');">
                            <div class="carousel-content-box">
                                <span class="carousel-tag">Elite Performance</span>
                                <h1 class="h1 carousel-title">Ultimate Custom <br>Gaming Rigs</h1>
                                <p class="carousel-text">Engineered for competitive superiority. Built with liquid
                                    cooling loops and ultra high frame-rate rendering capabilities.</p>
                                <a href="#shop" class="btn-carousel">Configure System</a>
                            </div>
                        </div>

                        <div class="carousel-slide" data-carousel-slide
                            style="background-image: linear-gradient(90deg, rgba(0,0,0,0.55) 85%, rgba(0,0,0,0.1) 100%), url('./resources/system/carousel/bnr_2.jpg');">
                            <div class="carousel-content-box">
                                <span class="carousel-tag">Next-Gen Speed</span>
                                <h1 class="h1 carousel-title">Modular PC Parts <br>& Processors</h1>
                                <p class="carousel-text">Maximize system metrics with high-efficiency graphics cards,
                                    titanium power blocks, and premium motherboards.</p>
                                <a href="#shop" class="btn-carousel">Browse Internal Parts</a>
                            </div>
                        </div>

                        <div class="carousel-slide" data-carousel-slide
                            style="background-image: linear-gradient(90deg, rgba(0,0,0,0.55) 85%, rgba(0,0,0,0.1) 100%), url('./resources/system/carousel/bnr_3.jpg');">
                            <div class="carousel-content-box">
                                <span class="carousel-tag">Pro-Grade Peripherals</span>
                                <h1 class="h1 carousel-title">Gaming Headsets <br>& Accessories</h1>
                                <p class="carousel-text">Immerse yourself deep into high-fidelity battle spaces.
                                    Zero-latency mechanical controls and surround-sound positional acoustics.</p>
                                <a href="#shop" class="btn-carousel">Upgrade Peripheral Set</a>
                            </div>
                        </div>

                        <div class="carousel-slide" data-carousel-slide
                            style="background-image: linear-gradient(90deg, rgba(0,0,0,0.55) 85%, rgba(0,0,0,0.1) 100%), url('./resources/system/carousel/bnr_4.jpg');">
                            <div class="carousel-content-box">
                                <span class="carousel-tag">Certified Efficiency</span>
                                <h1 class="h1 carousel-title">High-Performance <br>PC Power Supplies</h1>
                                <p class="carousel-text">Deliver clean, stable current to your graphics cards with 80+
                                    Gold and Titanium certified modular power units.</p>
                                <a href="#shop" class="btn-carousel">Explore Power Units</a>
                            </div>
                        </div>

                        <div class="carousel-slide" data-carousel-slide
                            style="background-image: linear-gradient(90deg, rgba(0,0,0,0.55) 85%, rgba(0,0,0,0.1) 100%), url('./resources/system/carousel/bnr_5.png');">
                            <div class="carousel-content-box">
                                <span class="carousel-tag">Precision Control</span>
                                <h1 class="h1 carousel-title">Ultra-Lightweight <br>Gaming Mice</h1>
                                <p class="carousel-text">Dominate your matches with zero-latency wireless sensors,
                                    ergonomic honeycomb grips, and high-DPI optical pixel tracking.</p>
                                <a href="#shop" class="btn-carousel">View Gaming Mice</a>
                            </div>
                        </div>

                    </div>

                    <button class="carousel-arrow left" aria-label="Previous Slide" data-carousel-prev>
                        <ion-icon name="chevron-back-outline"></ion-icon>
                    </button>
                    <button class="carousel-arrow right" aria-label="Next Slide" data-carousel-next>
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </button>

                    <div class="carousel-indicator-bar" data-carousel-indicators></div>

                </div>
            </section>

            <section class="section shop" id="shop" aria-label="shop" data-section>
                <div class="container">

                    <?php
          $category_row = Database::search("SELECT * FROM `category`");

          while ($category_data = $category_row->fetch_assoc()) {
            $query = "SELECT * FROM `product` WHERE 
            `category_category_id`='" . $category_data["category_id"] . "' 
            AND `status_status_id`='1' ORDER BY `datetime_added` DESC";
            $product_num = Database::search($query);
          ?>

                    <div class="title-wrapper">
                        <div class="title-wrap-header">
                            <h2 class="h2 section-title"><?php echo $category_data["category_name"] ?></h2>

                            <a href='<?php echo "product.php?id=" . ($category_data["category_id"]) . "&page=" . (0) . "&count=" . ($product_num->num_rows); ?>'
                                class="btn-link">
                                <span class="span">View Entire Catalog</span>
                                <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                            </a>
                        </div>

                        <ul class="has-scrollbar scroll-card">
                            <?php
    $product_row = Database::search($query . ' LIMIT 10 OFFSET 0');
    for ($i = 0; $i < $product_row->num_rows; $i++) {
        $product_data = $product_row->fetch_assoc();
    ?>
                            <li class="scrollbar-item scroll-card">
                                <div class="shop-card">
                                    <div class="card-banner img-holder">
                                        <?php
                    $img_row = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $product_data["id"] . "'");
                    $img_data = $img_row->fetch_assoc();
                    ?>
                                        <img src="<?php echo $img_data["img_path"] ?? './resources/system/logo/PowerRig.png'; ?>"
                                            loading="lazy" class="img-cover" alt="PC Hardware Asset">

                                        <div class="card-actions">
                                            <button class="action-btn" aria-label="add to cart"
                                                onclick="addToCart(<?php echo $product_data['id']; ?>);">
                                                <ion-icon name="cart-outline" aria-hidden="true"></ion-icon>
                                            </button>
                                            <button class="action-btn" aria-label="add to wishlist"
                                                onclick='addToWishlist(<?php echo $product_data["id"]; ?>);'>
                                                <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                                            </button>
                                            <a class="action-btn" aria-label="view product"
                                                href='<?php echo "productDetails.php?id=" . ($product_data["id"]); ?>'>
                                                <ion-icon name="eye-outline" aria-hidden="true"></ion-icon>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="card-content">
                                        <div class="price">
                                            <span class="span">Rs.
                                                <?php echo number_format($product_data["price"], 2); ?></span>
                                        </div>

                                        <h3>
                                            <a href="#" class="card-title"><?php echo $product_data["title"] ?></a>
                                        </h3>

                                        <?php if (intval($product_data['qty']) > 0) { ?>
                                        <p class="status-in-stock">In Stock</p>
                                        <?php } else { ?>
                                        <p class="status-out-stock">Out of Stock</p>
                                        <?php } ?>

                                        <div class="card-rating">
                                            <?php
                        $comment_row = Database::search("SELECT * FROM `comment` WHERE `product_id`='" . $product_data["id"] . "'");
                        ?>
                                            <p class="rating-text"><?php echo $comment_row->num_rows ?> client reviews
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php
    }
    ?>
                        </ul>

                    </div>
                    <?php
          }
          ?>

                </div>
            </section>

            <section class="section home-promo-matrix" aria-label="Special Hardware Showcase">
                <div class="container">
                    <div class="promo-grid">

                        <a href="#shop" class="promo-card-wrapper">
                            <div class="promo-image-card">
                                <img src="./resources/system/img_cards/bnr_1.jpg" alt="Hardware Promotion 1"
                                    loading="lazy">
                            </div>
                        </a>

                        <a href="#shop" class="promo-card-wrapper">
                            <div class="promo-image-card">
                                <img src="./resources/system/img_cards/bnr_2.jpg" alt="Hardware Promotion 2"
                                    loading="lazy">
                            </div>
                        </a>

                        <a href="#shop" class="promo-card-wrapper">
                            <div class="promo-image-card">
                                <img src="./resources/system/img_cards/bnr_3.jpg" alt="Hardware Promotion 3"
                                    loading="lazy">
                            </div>
                        </a>

                    </div>
                </div>
            </section>

            <section class="section brand-slider-section" aria-label="Partner Brands Hub">
                <div class="container">
                    <div class="brand-slider-wrapper">
                        <div class="brand-track">
                            <div class="brand-item"><img src="./resources/system/brand/mf_1.png" alt="MSI"></div>
                            <div class="brand-item"><img src="./resources/system/brand/mf_2.png" alt="Intel"></div>
                            <div class="brand-item"><img src="./resources/system/brand/mf_3.png" alt="AMD"></div>
                            <div class="brand-item"><img src="./resources/system/brand/mf_4.png" alt="Logitech"></div>
                            <div class="brand-item"><img src="./resources/system/brand/mf_5.png" alt="ASUS"></div>
                            <div class="brand-item"><img src="./resources/system/brand/mf_6.png" alt="Kingston"></div>
                            <div class="brand-item"><img src="./resources/system/brand/mf_7.png" alt="HP"></div>
                            <div class="brand-item"><img src="./resources/system/brand/mf_8.png" alt="Razer"></div>
                            <div class="brand-item"><img src="./resources/system/brand/mf_10.png" alt="Lenovo"></div>
                            <div class="brand-item"><img src="./resources/system/brand/mf_11.png" alt="Acer"></div>
                            <div class="brand-item"><img src="./resources/system/brand/mf_12.png" alt="Jedel"></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section feature" aria-label="feature" data-section>
                <div class="container">

                    <h2 class="h2-large section-title">Engineered to Build Elite Stations</h2>

                    <ul class="flex-list">
                        <li class="flex-item">
                            <div class="feature-card">
                                <div class="feat-icon-container">
                                    <ion-icon name="hardware-chip-outline"></ion-icon>
                                </div>
                                <h3 class="h3 card-title">Ultimate Power Processing</h3>
                                <p class="card-text">
                                    Source ultra performance multi-thread CPUs, premium graphic rendering units, and
                                    liquid cold radiator cooling architectures configured for high productivity.
                                </p>
                            </div>
                        </li>

                        <li class="flex-item">
                            <div class="feature-card">
                                <div class="feat-icon-container">
                                    <ion-icon name="construct-outline"></ion-icon>
                                </div>
                                <h3 class="h3 card-title">Modular Custom Rigs</h3>
                                <p class="card-text">
                                    Customize and scale performance metrics. We house professional expandable chassis
                                    structures, certified performance power hubs, and lightning fast SSD modules.
                                </p>
                            </div>
                        </li>

                        <li class="flex-item">
                            <div class="feature-card">
                                <div class="feat-icon-container">
                                    <ion-icon name="headset-outline"></ion-icon>
                                </div>
                                <h3 class="h3 card-title">Zero-Latency Acoustics</h3>
                                <p class="card-text">
                                    Gain spatial competitive awareness with pro-grade surround sound acoustics, high-DPI
                                    optical track mice, and tactile multi-key mechanical decks.
                                </p>
                            </div>
                        </li>
                    </ul>

                </div>
            </section>

        </article>
    </main>

    <?php include "footer.php"; ?>

    <a href="#top" class="back-top-btn" aria-label="Jump to screen ceiling" data-back-top-btn>
        <ion-icon name="arrow-up" aria-hidden="true"></ion-icon>
    </a>

    <script src="home.js"></script>
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</body>

</html>