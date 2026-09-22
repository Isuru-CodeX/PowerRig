<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "connection.php";

// Initialize base search query requirements
$query = "SELECT * FROM `product` WHERE 1=1";

// 1. Text Search Input Filters Mapping (FIXED: Safe string escaping without relying on clean())
if (isset($_GET["search"]) && !empty(trim($_GET["search"]))) {
    // If your Database class exposes the connection object (e.g., Database::$connection) you can use it, 
    // otherwise fallback to a standard native escape or addslashes to prevent errors.
    if (isset(Database::$connection)) {
        $search_text = mysqli_real_escape_string(Database::$connection, trim($_GET["search"]));
    } else {
        $search_text = addslashes(trim($_GET["search"]));
    }
    $query .= " AND (`title` LIKE '%" . $search_text . "%') ";
}

// Check if a specific landing category reference context exists
if (isset($_GET["category"]) && intval($_GET["category"]) > 0) {
    $category_id = intval($_GET["category"]);
    $query .= " AND `category_category_id` = '" . $category_id . "' ";
}

// 2. Sort Dropdown Filter Routing Implementation
if (isset($_GET["sort"]) && intval($_GET["sort"]) > 0) {
    $sort_method = intval($_GET["sort"]);
    
    if ($sort_method == 1) {
        // Price: Low to High
        $query .= " ORDER BY `price` ASC ";
    } else if ($sort_method == 2) {
        // Price: High to Low
        $query .= " ORDER BY `price` DESC ";
    } else if ($sort_method == 3) {
        // Newest Added Components
        $query .= " ORDER BY `id` DESC ";
    }
}

// Process calculated query against active engine layout
$product_rs = Database::search($query);
$product_num = $product_rs->num_rows;

if ($product_num == 0) {
    ?>
    <div class="w-100 text-center py-5">
        <p class="text-muted fs-5">No high-performance products matched your search parameters.</p>
    </div>
    <?php
} else {
    while ($product_data = $product_rs->fetch_assoc()) {
        // Image extraction mapping
        $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $product_data["id"] . "'");
        $img_data = $img_rs->fetch_assoc();
        
        // Review commentary metadata extraction
        $comment_row = Database::search("SELECT * FROM `comment` WHERE `product_id`='" . $product_data["id"] . "'");
        ?>
        <li class="col-xl-3 col-lg-4 col-md-6 col-sm-12 d-flex justify-content-center">
            <div class="shop-card w-100">
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