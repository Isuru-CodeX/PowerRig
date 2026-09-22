<?php
include 'connection.php';

$searchTerm = $_GET['searchTerm'];

$product_row = Database::search("SELECT * FROM `product` WHERE title LIKE '%" . $searchTerm . "%'");

for ($i = 0; $i < $product_row->num_rows; $i++) {
    $product_data = $product_row->fetch_assoc();

    ?>

    <li style="margin: 10px;">
        <div class="shop-card">
            <div class="card-banner img-holder" style="width: 230px; height: 320px;">
                <?php
                $img_row = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $product_data["id"] . "'");
                $img_data = $img_row->fetch_assoc();
                ?>
                <img src="<?php echo $img_data["img_path"] ?>" loading="lazy" class="img-cover">

                <?php
                if ($product_data["qty"] == 0) {
                    ?>
                    <span class="badge">Out of stock</span>
                    <?php
                }
                ?>

                <div class="card-actions">

                    <a class="action-btn" aria-label="compare" onclick="changeStatus(<?php echo $product_data['id']; ?>)">
                        <?php
                        if ($product_data["status_status_id"] == 1) {
                            ?>
                            <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
                            <?php
                        } else {
                            ?>
                            <ion-icon name="checkmark-outline" aria-hidden="true"></ion-icon>
                            <?php
                        }
                        ?>
                    </a>

                </div>
            </div>

            <div class="card-content">

                <div class="price">
                    <span class="span">Rs. <?php echo $product_data["price"] ?>.00</span>
                </div>

                <h3>
                    <a href="#" class="card-title"><?php echo $product_data["title"] ?></a>
                </h3>

                <div class="card-rating">
                    <?php
                    $comment_row = Database::search("SELECT * FROM `comment` WHERE `product_id`='" . $product_data["id"] . "'")
                        ?>

                    <p class="rating-text"><?php echo $comment_row->num_rows ?> reviews</p>

                </div>

            </div>

        </div>
    </li>
    <?php

    ?>

    <?php
}
?>