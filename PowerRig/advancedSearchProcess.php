<?php
session_start();
include "connection.php";

$txt = $_POST["t"];
$cat = $_POST["c"];
$brand = $_POST["b"];
$model = $_POST["m"];
$color = $_POST["col"];
$min = $_POST["min"];
$max = $_POST["max"];
$sort = $_POST["s"];

// Base Query
$query = "SELECT DISTINCT p.* FROM `product` p ";

// If Color is selected, we need to JOIN the mapping table
if ($color != 0) {
    $query .= " INNER JOIN `product_has_color` phc ON p.id = phc.product_id ";
}

// Ensure we only show Active items
$query .= " WHERE p.status_status_id = '1' ";

if (!empty($txt)) {
    $search_text = addslashes(trim($txt));
    $query .= " AND p.title LIKE '%" . $search_text . "%' ";
}
if ($cat != 0) {
    $query .= " AND p.category_category_id = '" . intval($cat) . "' ";
}
if ($brand != 0) {
    $query .= " AND p.brand_brand_id = '" . intval($brand) . "' ";
}
if ($model != 0) {
    $query .= " AND p.model_model_id = '" . intval($model) . "' ";
}
if ($color != 0) {
    $query .= " AND phc.color_color_id = '" . intval($color) . "' ";
}
if (!empty($min)) {
    $query .= " AND p.price >= '" . floatval($min) . "' ";
}
if (!empty($max)) {
    $query .= " AND p.price <= '" . floatval($max) . "' ";
}

// Append Sorting logic
if ($sort == 1) {
    $query .= " ORDER BY p.price ASC ";
} else if ($sort == 2) {
    $query .= " ORDER BY p.price DESC ";
} else if ($sort == 3) {
    $query .= " ORDER BY p.qty DESC ";
} else {
    $query .= " ORDER BY p.datetime_added DESC ";
}

$product_rs = Database::search($query);
$product_num = $product_rs->num_rows;

if ($product_num == 0) {
    echo '<div class="w-100 text-center py-5">
            <ion-icon name="search-outline" style="font-size: 64px; color: var(--text-secondary);"></ion-icon>
            <p class="text-muted fs-5 mt-3">No custom rigs or parts match your exact criteria.</p>
          </div>';
} else {
    while ($product_data = $product_rs->fetch_assoc()) {
        
        $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $product_data["id"] . "'");
        $img_data = $img_rs->fetch_assoc();
        $img_path = $img_data["img_path"] ?? './resources/system/logo/PowerRig.png';
        
        $comment_row = Database::search("SELECT * FROM `comment` WHERE `product_id`='" . $product_data["id"] . "'");
        $stock_class = (intval($product_data['qty']) > 0) ? "status-in-stock" : "status-out-stock";
        $stock_text = (intval($product_data['qty']) > 0) ? "In Stock" : "Out of Stock";
        
        ?>
        <li class="col-xl-4 col-lg-6 col-md-6 col-sm-12 d-flex justify-content-center mb-4">
            <div class="shop-card w-100" style="max-width: 280px;">
                <div class="card-banner img-holder">
                    <img src="<?php echo $img_path; ?>" loading="lazy" class="img-cover" alt="Product Image">
                    <div class="card-actions">
                        <button class="action-btn" aria-label="add to cart" onclick="addToCart(<?php echo $product_data['id']; ?>);">
                            <ion-icon name="cart-outline" aria-hidden="true"></ion-icon>
                        </button>
                        <button class="action-btn" aria-label="add to wishlist" onclick="addToWishlist(<?php echo $product_data['id']; ?>);">
                            <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                        </button>
                        <a class="action-btn" aria-label="view product" href="productDetails.php?id=<?php echo $product_data['id']; ?>">
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
                    <p class="<?php echo $stock_class; ?>"><?php echo $stock_text; ?></p>
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