<?php
// Added session start so we can grab the user's logged-in session for accurate delivery fees
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "connection.php";

define('MOCK_AVERAGE_RATING', 4.6);

if (isset($_GET["id"]) && !empty($_GET["id"])) {

  // Sanitize the product ID to mitigate SQL injection vectors
  $product_id = intval($_GET["id"]);

  $product_rs = Database::search("SELECT `product`.*, `category`.`category_name`, `brand`.`brand_name`, `model`.`model_name`
    FROM `product`
    INNER JOIN `category` ON `product`.`category_category_id` = `category`.`category_id`
    INNER JOIN `brand` ON `product`.`brand_brand_id` = `brand`.`brand_id`
    INNER JOIN `model` ON `product`.`model_model_id` = `model`.`model_id`
    WHERE `product`.`id` = '" . $product_id . "'");

  if ($product_rs->num_rows == 1) {
    $product_data = $product_rs->fetch_assoc();
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="home.css" />
      <link rel="stylesheet" href="productDetails.css" />
      <title><?php echo htmlspecialchars($product_data["title"]); ?> | PowerRig</title>
      
      <style>
        /* Interactive Quantity Counter Styling */
        .qty-deck-btn:hover {
            color: var(--pd-accent) !important;
        }
      </style>
    </head>

    <body class="amazon-inspired-dark-body" id="pd-root">

      <div class="header-global-container">
        <?php include "header.php"; ?>
      </div>

      <main class="pd-layout">

        <div class="pd-col-gallery">
          <?php
          $image_rs = Database::search("SELECT `img_path` FROM `product_img` WHERE `product_id`='" . $product_id . "'");
          $img = array();
          while ($image_data = $image_rs->fetch_assoc()) {
            $img[] = $image_data["img_path"];
          }
          $has_images = !empty($img);
          $first_image = $has_images ? $img[0] : 'resources/placeholder.png';
          ?>

          <div class="pd-gallery-sticky">
            <?php if ($has_images) { ?>
              <div class="pd-filmstrip">
                <?php foreach ($img as $index => $path) {
                  $activeState = ($index === 0) ? 'is-active' : '';
                ?>
                  <button type="button"
                          class="pd-thumb <?php echo $activeState; ?>"
                          data-index="<?php echo $index; ?>"
                          aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                          aria-label="View image <?php echo $index + 1; ?> of <?php echo count($img); ?>">
                    <img src="<?php echo htmlspecialchars($path); ?>" alt="" loading="lazy">
                  </button>
                <?php } ?>
              </div>
            <?php } ?>

            <div class="pd-stage" id="pdStage">
              <img src="<?php echo htmlspecialchars($first_image); ?>"
                   id="pdStageImg"
                   class="pd-stage-img"
                   alt="<?php echo htmlspecialchars($product_data["title"]); ?>">
              <?php if ($has_images && count($img) > 1) { ?>
                <span class="pd-stage-counter" id="pdStageCounter">1 / <?php echo count($img); ?></span>
              <?php } ?>
              <span class="pd-stage-hint"><ion-icon name="search-outline"></ion-icon> Hover to inspect</span>
            </div>
          </div>
        </div>

        <div class="pd-col-info">
          <span class="pd-breadcrumb"><ion-icon name="layers-outline"></ion-icon> <?php echo htmlspecialchars($product_data["category_name"]); ?></span>
          <h1 class="pd-title"><?php echo htmlspecialchars($product_data["title"]); ?></h1>

          <div class="pd-rating-row">
            <?php
            $comment_rs = Database::search("SELECT `comment`.*, `user`.`fname`, `user`.`lname`, `profile_img`.`img_path` AS avatar_path
              FROM `comment`
              INNER JOIN `user` ON `comment`.`user_email` = `user`.`email`
              LEFT JOIN `profile_img` ON `profile_img`.`user_email` = `comment`.`user_email`
              WHERE `comment`.`product_id`='" . $product_id . "'
              ORDER BY `comment`.`comment_date` DESC");
            ?>
            <a href="#pd-reviews" class="pd-review-link"><?php echo $comment_rs->num_rows; ?> verified evaluations</a>
          </div>

          <hr class="pd-rule">

          <div class="pd-price-panel">
            <span class="pd-price-currency pd-mono">Rs.</span>
            <span class="pd-price-whole"><?php echo number_format(floor($product_data["price"])); ?></span>
            <span class="pd-price-fraction">.00</span>
          </div>
          <p class="pd-price-note">Inclusive of all local distribution tariffs and processing fees.</p>

          <hr class="pd-rule">

          <div class="pd-specs">
            <div class="pd-spec-row"><span class="pd-spec-label">Brand</span><span class="pd-spec-value"><?php echo htmlspecialchars($product_data["brand_name"]); ?></span></div>
            <div class="pd-spec-row"><span class="pd-spec-label">Model</span><span class="pd-spec-value"><?php echo htmlspecialchars($product_data["model_name"]); ?></span></div>
            <div class="pd-spec-row"><span class="pd-spec-label">Sold By</span><span class="pd-spec-value">PowerRig Official Store</span></div>
          </div>

          <hr class="pd-rule">

          <div class="pd-about">
            <h3 class="pd-about-heading">About this item</h3>
            <div class="pd-about-text">
              <?php echo nl2br(htmlspecialchars($product_data["description"])); ?>
            </div>
          </div>
        </div>

        <div class="pd-col-buybox">
          <div class="pd-buybox" id="pdBuyBox">
            <div class="pd-buybox-price-row">
              <span class="pd-price-label">Total Value:</span>
              <span class="pd-buybox-price pd-mono" id="pd_dynamic_price">Rs. <?php echo number_format($product_data["price"], 2); ?></span>
            </div>

            <?php
            // Calculate dynamic delivery fee based on user district using exact cart logic
            $delivery_fee = floatval($product_data["default_delivery_fee"]);

            if (isset($_SESSION["user"])) {
                $user_email = $_SESSION["user"]["email"];
                
                $address_rs = Database::search("SELECT `user_has_address`.`district_district_id` AS did FROM `user_has_address`
                    INNER JOIN `address` ON `user_has_address`.`address_address_id`=`address`.`address_id`
                    INNER JOIN `city` ON `address`.`city_city_id`=`city`.`city_id`
                    WHERE `user_has_address`.`user_email`='" . $user_email . "'");

                if ($address_rs->num_rows > 0) {
                    $address_data = $address_rs->fetch_assoc();
                    $shippingCost_row = Database::search("SELECT `delivery_fee` FROM `shippingcost_by_district` WHERE `district_district_id`='" . $address_data["did"] . "'");

                    if ($shippingCost_row->num_rows > 0) {
                        $shippingCost_data = $shippingCost_row->fetch_assoc();
                        $delivery_fee = floatval($shippingCost_data["delivery_fee"]);
                    }
                }
            }
            ?>
            <p class="pd-delivery-note"><ion-icon name="rocket-outline"></ion-icon> Estimated delivery: Rs. <?php echo number_format($delivery_fee, 2); ?></p>

            <?php if ($product_data["qty"] > 0) { ?>
              <span class="pd-stock-line is-live"><span class="pd-stock-dot is-live"></span> In Stock</span>
              <p class="pd-stock-sub">Secure yours now. Operational arrays are running online.</p>
            <?php } else { ?>
              <span class="pd-stock-line is-out"><span class="pd-stock-dot is-out"></span> Currently Unavailable</span>
              <p class="pd-stock-sub">Production pipeline limits reached. Monitor for replenishment updates.</p>
            <?php } ?>

            <?php
            $product_color_row = Database::search("SELECT `color_id`,`color_name`
            FROM `product_has_color`
            INNER JOIN `product` ON `product_has_color`.`product_id`=`product`.`id`
            INNER JOIN `color` ON `product_has_color`.`color_color_id` = `color`.`color_id`
            WHERE `product`.`id`='" . $product_id . "'");

            if ($product_color_row->num_rows > 0) {
            ?>
              <div class="pd-field">
                <label for="pdColorSelect" class="pd-field-label">Variation Line</label>
                <select id="pdColorSelect" class="pd-select">
                  <?php while ($product_color_data = $product_color_row->fetch_assoc()) { ?>
                    <option value="<?php echo htmlspecialchars($product_color_data["color_id"]); ?>"><?php echo htmlspecialchars($product_color_data["color_name"]); ?></option>
                  <?php } ?>
                </select>
              </div>
            <?php } ?>

            <?php if ($product_data["qty"] > 0) { ?>
              <div class="pd-field">
                <label class="pd-field-label">Quantity</label>
                <div class="qty-control-deck" style="display: flex; align-items: center; gap: 15px; background: var(--pd-input); padding: 8px 12px; border-radius: var(--pd-radius-sm); width: fit-content; border: 1px solid var(--pd-border);">
                  <button type="button" class="qty-deck-btn" onclick="updatePDQty(-1)" style="background: transparent; color: var(--pd-text-dim); border: none; font-size: 1.2rem; cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center; transition: color 0.2s var(--pd-ease);">
                    <ion-icon name="remove-outline"></ion-icon>
                  </button>
                  <div class="qty-deck-value pd-mono" id="pd_qty_display" style="font-size: 1.2rem; font-weight: 700; width: 24px; text-align: center; color: var(--pd-text);">1</div>
                  <button type="button" class="qty-deck-btn" onclick="updatePDQty(1)" style="background: transparent; color: var(--pd-text-dim); border: none; font-size: 1.2rem; cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center; transition: color 0.2s var(--pd-ease);">
                    <ion-icon name="add-outline"></ion-icon>
                  </button>
                </div>
                <input type="hidden" id="qty_input<?php echo $product_id; ?>" value="1">
              </div>
            <?php } ?>

            <div class="pd-btn-stack">
              <?php if ($product_data["qty"] > 0) { ?>
                <button type="button" class="pd-btn pd-btn-cart" onclick="addToCartPD(<?php echo $product_id; ?>);">
                  <ion-icon name="cart"></ion-icon> Add to Cart
                </button>
                <button type="button" class="pd-btn pd-btn-buy" id="payhere-payment" onclick="payNow(<?php echo $product_id; ?>);">
                  <ion-icon name="play-forward"></ion-icon> Proceed to Checkout
                </button>
              <?php } ?>
            </div>

            <div class="pd-secure-note">
              <ion-icon name="lock-closed"></ion-icon>
              <span>Secure cryptographic processing encryption protocol layers applied.</span>
            </div>
          </div>
        </div>

      </main>

      <div class="pd-sticky-bar" id="pdStickyBar">
        <span class="pd-sticky-bar-price pd-mono">Rs. <?php echo number_format($product_data["price"]); ?></span>
        <div class="pd-sticky-bar-actions">
          <?php if ($product_data["qty"] > 0) { ?>
            <button type="button" class="pd-btn pd-btn-cart" onclick="addToCartPD(<?php echo $product_id; ?>);" aria-label="Add to cart">
              <ion-icon name="cart"></ion-icon>
            </button>
            <button type="button" class="pd-btn pd-btn-buy" onclick="payNow(<?php echo $product_id; ?>);">Buy Now</button>
          <?php } ?>
        </div>
      </div>

      <section class="section related-products-carousel-row">
        <div class="pd-carousel-wrap">
          <div class="title-wrap-header">
            <h2 class="h2">Related Products</h2>
            <?php
            $category_id = intval($product_data["category_category_id"]);
            $related_count_rs = Database::search("SELECT COUNT(*) AS total FROM `product` WHERE `category_category_id`='" . $category_id . "' AND `id`!='" . $product_id . "'");
            $related_count = (int) $related_count_rs->fetch_assoc()["total"];
            ?>
            <a href="product.php?id=<?php echo $category_id; ?>&page=0&count=<?php echo $related_count; ?>" class="btn-link">
              <span>Explore related pipeline collections</span>
              <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
            </a>
          </div>

          <ul class="has-scrollbar">
            <?php
            $related_rs = Database::search("SELECT `product`.*, MIN(`product_img`.`img_path`) AS img_path
              FROM `product`
              LEFT JOIN `product_img` ON `product_img`.`product_id` = `product`.`id`
              WHERE `product`.`category_category_id`='" . $category_id . "' AND `product`.`id`!='" . $product_id . "'
              GROUP BY `product`.`id`
              LIMIT 10");

            while ($related_data = $related_rs->fetch_assoc()) {
              $related_img = !empty($related_data["img_path"]) ? $related_data["img_path"] : 'resources/placeholder.png';
            ?>
              <li class="scrollbar-item pd-reveal d-flex justify-content-center">
                  <div class="shop-card w-100" style="max-width: 280px;">
                      <div class="card-banner img-holder">
                          <img src="<?php echo htmlspecialchars($related_img); ?>"
                              loading="lazy" class="img-cover" alt="PC Hardware Asset">

                          <div class="card-actions">
                              <button class="action-btn" aria-label="add to cart"
                                  onclick="addToCart(<?php echo $related_data['id']; ?>);">
                                  <ion-icon name="cart-outline" aria-hidden="true"></ion-icon>
                              </button>
                              <button class="action-btn" aria-label="add to wishlist"
                                  onclick='addToWishlist(<?php echo $related_data["id"]; ?>);'>
                                  <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                              </button>
                              <a class="action-btn" aria-label="view product"
                                  href='<?php echo "productDetails.php?id=" . ($related_data["id"]); ?>'>
                                  <ion-icon name="eye-outline" aria-hidden="true"></ion-icon>
                              </a>
                          </div>
                      </div>

                      <div class="card-content">
                          <div class="price">
                              <span class="span">Rs. <?php echo number_format($related_data["price"], 2); ?></span>
                          </div>

                          <h3>
                              <a href="productDetails.php?id=<?php echo $related_data['id']; ?>" class="card-title"><?php echo htmlspecialchars($related_data["title"]); ?></a>
                          </h3>

                          <?php if (intval($related_data['qty']) > 0) { ?>
                              <p class="status-in-stock">In Stock</p>
                          <?php } else { ?>
                              <p class="status-out-stock">Out of Stock</p>
                          <?php } ?>

                          <div class="card-rating">
                              <?php 
                                $related_comment_row = Database::search("SELECT * FROM `comment` WHERE `product_id`='" . $related_data["id"] . "'");
                              ?>
                              <p class="rating-text"><?php echo $related_comment_row->num_rows ?> client reviews</p>
                          </div>
                      </div>
                  </div>
              </li>
            <?php } ?>
          </ul>
        </div>
      </section>

      <section id="pd-reviews" class="section">
        <div class="pd-carousel-wrap pd-reviews-layout">

          <div class="pd-reviews-summary">
            <h2 class="h2">Customer Feedback</h2>
            <p class="pd-summary-sub">Verified customer feedback records parsed across centralized user logs.</p>
          </div>

          <div class="pd-reviews-feed">
            <?php
            if ($comment_rs->num_rows == 0) {
            ?>
              <div class="pd-empty-state">
                <ion-icon name="chatbubbles-outline"></ion-icon>
                <p>No reviews yet for this item.</p>
                <span>Be the first to share how it performed for you.</span>
              </div>
            <?php
            } else {
              while ($comment_data = $comment_rs->fetch_assoc()) {
                $avatar = !empty($comment_data["avatar_path"]) ? $comment_data["avatar_path"] : './resources/system/logo/default-avatar.png';
                $reviewer_name = trim($comment_data["fname"] . " " . $comment_data["lname"]);
            ?>
                <div class="pd-review-card pd-reveal">
                  <div class="pd-review-user">
                    <div class="pd-avatar">
                      <img src="<?php echo htmlspecialchars($avatar); ?>" alt="">
                    </div>
                    <span class="pd-reviewer-name"><?php echo htmlspecialchars($reviewer_name); ?></span>
                  </div>
                  <div class="pd-review-meta">
                    <div class="pd-review-stars"><ion-icon name="star"></ion-icon><ion-icon name="star"></ion-icon><ion-icon name="star"></ion-icon><ion-icon name="star"></ion-icon><ion-icon name="star"></ion-icon></div>
                    <span class="pd-review-date">Reviewed on <?php echo htmlspecialchars($comment_data["comment_date"]); ?></span>
                  </div>
                  <div class="pd-review-body">
                    <p><?php echo nl2br(htmlspecialchars($comment_data["comment"])); ?></p>
                  </div>
                </div>
            <?php
              }
            }
            ?>
          </div>

        </div>
      </section>

      <script src="home.js"></script>
      <script src="script.js"></script>
      <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

      <script>
        (function () {
          // --- Image Gallery Logic ---
          var pdImages = <?php echo json_encode(array_values($img)); ?>;
          var pdProductTitle = <?php echo json_encode($product_data["title"]); ?>;

          var stage = document.getElementById('pdStage');
          var stageImg = document.getElementById('pdStageImg');
          var counter = document.getElementById('pdStageCounter');
          var thumbs = document.querySelectorAll('.pd-thumb');
          var total = pdImages.length;

          function setActiveThumb(index) {
            thumbs.forEach(function (thumb) {
              var isActive = parseInt(thumb.dataset.index, 10) === index;
              thumb.classList.toggle('is-active', isActive);
              thumb.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
          }

          function switchImage(index) {
            if (!pdImages[index] || !stageImg) return;
            stageImg.style.opacity = '0';
            window.setTimeout(function () {
              stageImg.src = pdImages[index];
              stageImg.alt = pdProductTitle + ' — view ' + (index + 1) + ' of ' + total;
              stageImg.style.opacity = '1';
              if (counter) counter.textContent = (index + 1) + ' / ' + total;
              setActiveThumb(index);
            }, 110);
          }

          thumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
              switchImage(parseInt(thumb.dataset.index, 10));
            });
          });

          if (stage && stageImg && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
            stage.addEventListener('mousemove', function (e) {
              var rect = stage.getBoundingClientRect();
              var x = ((e.clientX - rect.left) / rect.width) * 100;
              var y = ((e.clientY - rect.top) / rect.height) * 100;
              stageImg.style.transformOrigin = x + '% ' + y + '%';
            });
            stage.addEventListener('mouseleave', function () {
              stageImg.style.transformOrigin = 'center center';
            });
          }

          // --- Dynamic Quantity and Price Calculation ---
          var basePrice = <?php echo $product_data["price"]; ?>;
          var maxQty = <?php echo min(10, $product_data["qty"]); ?>;
          
          window.updatePDQty = function(delta) {
            var displayDiv = document.getElementById('pd_qty_display');
            var hiddenInput = document.getElementById('qty_input<?php echo $product_id; ?>');
            var priceDisplay = document.getElementById('pd_dynamic_price');
            
            var currentQty = parseInt(displayDiv.innerText);
            var newQty = currentQty + delta;
            
            // Constrain between 1 and the Max Available Limit
            if (newQty < 1) newQty = 1;
            if (newQty > maxQty) newQty = maxQty;
            
            // Update UI components
            displayDiv.innerText = newQty;
            hiddenInput.value = newQty;
            
            // Recalculate Total Base
            var newTotal = basePrice * newQty;
            
            // Format to standard comma-separated local price (2 decimals)
            priceDisplay.innerText = "Rs. " + newTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
          };

          // --- UI Intersections ---
          var revealItems = document.querySelectorAll('.pd-reveal');
          var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
          if (reduceMotion || !('IntersectionObserver' in window)) {
            revealItems.forEach(function (el) { el.classList.add('is-visible'); });
          } else {
            var revealObserver = new IntersectionObserver(function (entries) {
              entries.forEach(function (entry, i) {
                if (entry.isIntersecting) {
                  entry.target.style.transitionDelay = (Math.min(i, 6) * 0.06) + 's';
                  entry.target.classList.add('is-visible');
                  revealObserver.unobserve(entry.target);
                }
              });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
            revealItems.forEach(function (el) { revealObserver.observe(el); });
          }

          var stickyBar = document.getElementById('pdStickyBar');
          var buyBox = document.getElementById('pdBuyBox');
          if (stickyBar && buyBox && 'IntersectionObserver' in window) {
            var barObserver = new IntersectionObserver(function (entries) {
              entries.forEach(function (entry) {
                stickyBar.classList.toggle('is-visible', !entry.isIntersecting);
              });
            }, { threshold: 0 });
            barObserver.observe(buyBox);
          }
        })();
      </script>
    </body>

    </html>
<?php
  } else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="home.css" />
      <link rel="stylesheet" href="productDetails.css" />
      <title>Product Not Found | PowerRig</title>
    </head>

    <body class="amazon-inspired-dark-body" id="pd-root">
      <div class="header-global-container">
        <?php include "header.php"; ?>
      </div>
      <div class="pd-state-page">
        <ion-icon name="cube-outline"></ion-icon>
        <h1>We couldn't find that product</h1>
        <p>It may have been removed, or the link you followed is out of date.</p>
        <a class="pd-btn pd-btn-cart" href="home.php">Back to Shop</a>
      </div>
      <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    </body>

    </html>
<?php
  }
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="home.css" />
      <link rel="stylesheet" href="productDetails.css" />
      <title>Invalid Request | PowerRig</title>
    </head>

    <body class="amazon-inspired-dark-body" id="pd-root">
      <div class="header-global-container">
        <?php include "header.php"; ?>
      </div>
      <div class="pd-state-page">
        <ion-icon name="alert-circle-outline"></ion-icon>
        <h1>No product was specified</h1>
        <p>This page needs a product id in the link to know what to show.</p>
        <a class="pd-btn pd-btn-cart" href="home.php">Back to Shop</a>
      </div>
      <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    </body>

    </html>
<?php
}
?>