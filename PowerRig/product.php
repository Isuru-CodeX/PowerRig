<?php
// FIX: Start the session safely before any HTML or whitespace is output 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "connection.php";

// Standardizing fallback checks to safely read landing configurations if routed without params
if (isset($_GET["id"])) {
    $category_id = intval($_GET["id"]);
    
    // Count the total products matching this explicit category to configure page divisions
    $product_rs = Database::search("SELECT * FROM `product` WHERE `category_category_id` = '" . $category_id . "'");
    $product_num = $product_rs->num_rows;
    
    $pageno = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
} else {
    // If no category configuration is found, look up total inventory metrics gracefully
    $product_rs = Database::search("SELECT * FROM `product`");
    $product_num = $product_rs->num_rows;
    $category_id = 0;
    $pageno = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
}

if ($pageno < 1) $pageno = 1;

// Fetch category text headers accurately based on your SQL schema definitions
$category_name = "Entire Catalog Collection";
if ($category_id > 0) {
    $cat_name_rs = Database::search("SELECT `category_name` FROM `category` WHERE `category_id` = '" . $category_id . "'");
    if ($cat_name_rs->num_rows > 0) {
        $cat_data = $cat_name_rs->fetch_assoc();
        $category_name = $cat_data["category_name"];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerRig | <?php echo htmlspecialchars($category_name); ?></title>

    <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="home.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        /* Structural Adjustments matching the home workspace palette */
        body {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
        }

        /* Premium Integrated Advanced Search Cluster Stylesheet */
        .search-engine-centerpiece {
            background-color: var(--bg-surface);
            border: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 12px;
            padding: 24px;
            margin-top: 40px;
            margin-bottom: 50px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .premium-search-field {
            background-color: var(--bg-surface-accent) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: var(--text-primary) !important;
            border-radius: 8px !important;
            padding: 12px 16px !important;
            font-family: 'Urbanist', sans-serif;
            font-weight: 500;
            font-size: 14px;
        }

        .premium-search-field:focus {
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 3px rgba(255, 74, 90, 0.15) !important;
        }

        .action-button-group {
            display: flex;
            gap: 12px;
        }

        .btn-search-trigger {
            background-color: var(--accent) !important;
            color: var(--text-primary) !important;
            font-weight: 700;
            border-radius: 8px !important;
            padding: 12px 24px !important;
            border: none !important;
            transition: var(--transition-smooth);
        }

        .btn-search-trigger:hover {
            background-color: var(--accent-hover) !important;
        }

        .btn-advanced-route {
            background-color: var(--bg-surface-accent) !important;
            color: var(--text-secondary) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 8px !important;
            padding: 12px 20px !important;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: var(--transition-smooth);
        }

        .btn-advanced-route:hover {
            color: var(--accent) !important;
            border-color: rgba(255, 74, 90, 0.3) !important;
        }

        /* Product Catalog Typography Layouts */
        .catalog-title-wrapper {
            margin-bottom: 35px;
            position: relative;
            padding-left: 16px;
        }

        .catalog-title-wrapper::before {
            content: '';
            position: absolute;
            left: 0;
            top: 4px;
            bottom: 4px;
            width: 4px;
            background-color: var(--accent);
            border-radius: 2px;
        }

        .catalog-title-wrapper h2 {
            font-size: 28px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }
        
        /* Custom Pagination Styling */
        .custom-pagination .page-link {
            background-color: #1e222b;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin: 0 4px;
            border-radius: 6px;
        }

        .custom-pagination .page-item.active .page-link {
            background-color: #ff4a5a;
            border-color: #ff4a5a;
            color: white;
        }

        .custom-pagination .page-item.disabled .page-link {
            background-color: #0f1115;
            color: #a0a5b5;
        }
    </style>
</head>

<body id="top">

    <header class="site-header-wrapper" data-header>
        <?php include "header.php"; ?>
    </header>

    <main class="container">
        
        <div class="search-engine-centerpiece">
            <div class="row g-3">
                <div class="col-lg-6 col-md-12">
                    <input type="text" id="basic_search" class="form-control premium-search-field" onkeyup="searchCatalog();" placeholder="Search product specs, specialized brands, hardware titles...">
                </div>
                <div class="col-lg-3 col-md-6">
                    <select id="sort_filter" class="form-select premium-search-field text-capitalize" onchange="searchCatalog();">
                        <option value="0">Sort Engine Routing</option>
                        <option value="1">Price: Low to High</option>
                        <option value="2">Price: High to Low</option>
                        <option value="3">Newest Added Components</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="action-button-group">
                        <button class="btn w-50 btn-search-trigger" onclick="searchCatalog();">Search</button>
                        <a href="advancedSearch.php" class="btn w-50 btn-advanced-route">
                            <ion-icon name="options-outline"></ion-icon> Advanced
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="section product-section-grid-layout">
            <div class="catalog-title-wrapper">
                <h2><?php echo htmlspecialchars($category_name); ?></h2>
            </div>

            <ul class="product-grid-list-matrix row list-unstyled g-4" id="pid">
                <?php
                // Configured for 20 Products Per Page Pagination
                $results_per_page = 20;
                $number_of_pages = ceil($product_num / $results_per_page);
                $page_first_result = ($pageno - 1) * $results_per_page;

                // Explicit SQL Category validation processing query
                if ($category_id > 0) {
                    $filtered_query = "SELECT * FROM `product` WHERE `category_category_id` = '" . $category_id . "' LIMIT " . $results_per_page . " OFFSET " . $page_first_result;
                } else {
                    $filtered_query = "SELECT * FROM `product` LIMIT " . $results_per_page . " OFFSET " . $page_first_result;
                }
                
                $page_rs = Database::search($filtered_query);
                $page_num = $page_rs->num_rows;

                if ($page_num == 0) {
                ?>
                    <div class="w-100 text-center py-5">
                        <p class="text-muted fs-5">No high-performance products matched this catalog sector context.</p>
                    </div>
                <?php
                } else {
                    while ($product_data = $page_rs->fetch_assoc()) {
                        
                        // Image extraction query rules mapping paths safely
                        $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $product_data["id"] . "'");
                        $img_data = $img_rs->fetch_assoc();
                        
                        // Count reviews
                        $comment_row = Database::search("SELECT * FROM `comment` WHERE `product_id`='" . $product_data["id"] . "'");
                ?>
                        <li class="col-xl-3 col-lg-4 col-md-6 col-sm-12 d-flex justify-content-center mb-4">
                            <div class="shop-card w-100" style="max-width: 280px;">
                                <div class="card-banner img-holder">
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
                                        <span class="span">Rs. <?php echo number_format($product_data["price"], 2); ?></span>
                                    </div>

                                    <h3>
                                        <a href="productDetails.php?id=<?php echo $product_data['id']; ?>" class="card-title"><?php echo htmlspecialchars($product_data["title"]); ?></a>
                                    </h3>

                                    <?php if (intval($product_data['qty']) > 0) { ?>
                                        <p class="status-in-stock">In Stock</p>
                                    <?php } else { ?>
                                        <p class="status-out-stock">Out of Stock</p>
                                    <?php } ?>

                                    <div class="card-rating">
                                        <p class="rating-text"><?php echo $comment_row->num_rows ?> client reviews</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                <?php
                    }
                }
                ?>
            </ul>

            <?php if ($number_of_pages > 1) { 
                $link_base = ($category_id > 0) ? "?id=" . $category_id . "&page=" : "?page=";
            ?>
                <div id="pagination_area" class="d-flex justify-content-center align-items-center mt-5 mb-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination custom-pagination justify-content-center">
                            
                            <li class="page-item <?php if ($pageno <= 1) { echo 'disabled'; } ?>">
                                <a class="page-link" href="<?php echo ($pageno <= 1) ? '#' : $link_base . ($pageno - 1); ?>" aria-label="Previous">
                                    <span aria-hidden="true"><i class="fas fa-chevron-left fa-sm"></i></span>
                                </a>
                            </li>

                            <?php
                            for ($x = 1; $x <= $number_of_pages; $x++) {
                                if ($x == $pageno) {
                                    ?>
                                    <li class="page-item active">
                                        <a class="page-link" href="<?php echo $link_base . $x; ?>"><?php echo $x; ?></a>
                                    </li>
                                    <?php
                                } else {
                                    ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $link_base . $x; ?>"><?php echo $x; ?></a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>

                            <li class="page-item <?php if ($pageno >= $number_of_pages) { echo 'disabled'; } ?>">
                                <a class="page-link" href="<?php echo ($pageno >= $number_of_pages) ? '#' : $link_base . ($pageno + 1); ?>" aria-label="Next">
                                    <span aria-hidden="true"><i class="fas fa-chevron-right fa-sm"></i></span>
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>
            <?php } ?>
        </section>
    </main>

    <?php include "footer.php"; ?>

    <script src="home.js"></script>
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
    <script>
        function searchCatalog() {
            var searchText = document.getElementById("basic_search").value;
            var sortValue = document.getElementById("sort_filter").value;
            
            // Read active category ID context dynamically
            var urlParams = new URLSearchParams(window.location.search);
            var categoryId = urlParams.get('id') || 0;

            var request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var responseText = request.responseText;
                    
                    // Replace grid content
                    document.getElementById("pid").innerHTML = responseText;
                    
                    // Auto-hide native pagination if parameters are actively engaged to prevent visual overlap bugs
                    var paginationBlock = document.getElementById("pagination_area");
                    if (paginationBlock) {
                        if (searchText.trim() !== "" || sortValue !== "0") {
                            paginationBlock.style.display = "none";
                        } else {
                            paginationBlock.style.display = "flex";
                        }
                    }
                }
            };

            request.open("GET", "searchProductsProcess.php?search=" + encodeURIComponent(searchText) + "&sort=" + sortValue + "&category=" + categoryId, true);
            request.send();
        }
    </script>
</body>

</html>