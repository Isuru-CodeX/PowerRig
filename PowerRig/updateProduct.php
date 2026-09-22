<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"]) && isset($_GET["id"])) {
    $pid = $_GET["id"];
    
    $product_rs = Database::search("SELECT * FROM `product` WHERE `id` = '".$pid."'");
    if($product_rs->num_rows == 0) { die("Product not found."); }
    $product = $product_rs->fetch_assoc();

    $color_rs = Database::search("SELECT `color_color_id` FROM `product_has_color` WHERE `product_id` = '".$pid."'");
    $current_color = ($color_rs->num_rows > 0) ? $color_rs->fetch_assoc()["color_color_id"] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PowerRig - Update Product Details</title>
    <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="home.css" />
    <link rel="stylesheet" href="dashboard.css" />

    <style>
    .product-form-block {
        background: var(--bg-surface);
        padding: 30px;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.02);
    }

    .form-label {
        color: var(--text-secondary);
        font-weight: 500;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .product-field {
        background: var(--bg-surface-accent) !important;
        border: 1px solid var(--border-low-opacity) !important;
        color: var(--text-primary) !important;
        padding: 12px 16px;
        font-size: 15px;
        border-radius: 8px !important;
    }

    .product-field:focus {
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 3px rgba(255, 74, 90, 0.15) !important;
    }

    .product-field:disabled {
        background: rgba(0, 0, 0, 0.25) !important;
        color: #888 !important;
        border-color: rgba(255, 255, 255, 0.05) !important;
        cursor: not-allowed;
    }

    select.product-field option {
        background: var(--bg-surface-accent);
        color: var(--text-primary);
    }

    .btn-accent-action {
        background-color: var(--accent) !important;
        color: var(--text-primary) !important;
        font-weight: 600;
        padding: 14px 28px;
        border-radius: 8px;
        border: none;
        transition: var(--transition-smooth);
    }

    .btn-accent-action:hover {
        background-color: var(--accent-hover) !important;
        transform: translateY(-1px);
    }

    .image-uploader-box {
        border: 2px dashed var(--border-low-opacity);
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        background: rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: var(--transition-smooth);
    }

    .image-uploader-box:hover {
        border-color: var(--accent);
        background: rgba(255, 74, 90, 0.02);
    }

    .img-preview-container img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    </style>
</head>

<body>
    <?php include "adminNavbar.php" ?>

    <section class="content">
        <?php include "adnav.php" ?>

        <main>
            <div class="product-form-block">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-white m-0"><i class="fas fa-edit me-2 text-danger"></i> Update Product Details</h3>
                    <a href="manageProducts.php" class="btn btn-outline-danger px-3 btn-sm" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left me-2"></i>Back to Inventory
                    </a>
                </div>

                <div class="row g-4">
                    
                    <div class="col-md-4">
                        <label class="form-label">Product Category</label>
                        <select class="form-select product-field" id="pCategory" onchange="loadCategoryFilters();">
                            <option value="0">Select Category</option>
                            <?php
                            $category_rs = Database::search("SELECT * FROM `category` ORDER BY `category_name` ASC");
                            while($category = $category_rs->fetch_assoc()){
                                $selected = ($category["category_id"] == $product["category_category_id"]) ? "selected" : "";
                                echo '<option value="'.$category["category_id"].'" '.$selected.'>'.$category["category_name"].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Brand Vendor</label>
                        <select class="form-select product-field" id="pBrand" onchange="loadFilteredModels();">
                            <option value="0">Select Brand</option>
                            <?php
                            $brand_rs = Database::search("SELECT * FROM `brand` ORDER BY `brand_name` ASC");
                            while($brand = $brand_rs->fetch_assoc()){
                                $selected = ($brand["brand_id"] == $product["brand_brand_id"]) ? "selected" : "";
                                echo '<option value="'.$brand["brand_id"].'" '.$selected.'>'.$brand["brand_name"].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Hardware Model Line</label>
                        <select class="form-select product-field" id="pModel">
                            <option value="0">Select Model</option>
                            <?php
                            $model_rs = Database::search("SELECT * FROM `model` ORDER BY `model_name` ASC");
                            while($model = $model_rs->fetch_assoc()){
                                $selected = ($model["model_id"] == $product["model_model_id"]) ? "selected" : "";
                                echo '<option value="'.$model["model_id"].'" '.$selected.'>'.$model["model_name"].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Product Custom Listing Title</label>
                        <input type="text" class="form-control product-field" id="pTitle" value="<?php echo htmlspecialchars($product["title"]); ?>" placeholder="Enter product title..." />
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Select Registered Accent Color</label>
                        <select class="form-select product-field" id="pColor">
                            <option value="0">Select Color</option>
                            <?php
                            $color_all_rs = Database::search("SELECT * FROM `color` ORDER BY `color_name` ASC");
                            while($clr = $color_all_rs->fetch_assoc()){
                                $selected = ($clr["color_id"] == $current_color) ? "selected" : "";
                                echo '<option value="'.$clr["color_id"].'" '.$selected.'>'.$clr["color_name"].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Stock Quantity Available</label>
                        <input type="number" class="form-control product-field" id="pQty" value="<?php echo $product["qty"]; ?>" placeholder="0" min="1" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Unit Cost Retail Price (Rs.)</label>
                        <input type="number" class="form-control product-field" id="pPrice" value="<?php echo $product["price"]; ?>" placeholder="0.00" step="0.01" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Default Local Delivery Fee (Colombo)</label>
                        <?php
                        $delivery_fee = "0.00";
                        $del_rs = Database::search("SELECT `delivery_fee` FROM `shippingcost_by_district` WHERE `district_district_id` = '5'");
                        if ($del_rs->num_rows > 0) {
                            $del_data = $del_rs->fetch_assoc();
                            $delivery_fee = $del_data["delivery_fee"];
                        }
                        ?>
                        <input type="text" id="productDelivery" class="form-control product-field" value="<?php echo $delivery_fee; ?>" disabled />
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Detailed Product Technical Specifications & Overview Description</label>
                        <textarea class="form-control product-field" id="pDescription" rows="6" placeholder="Write comprehensive specifications here..."><?php echo htmlspecialchars($product["description"]); ?></textarea>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label">Product Marketing Images (Max 6 Images)</label>
                        <div class="image-uploader-box" onclick="document.getElementById('productImageFiles').click();">
                            <i class="fas fa-cloud-upload-alt fa-3x text-danger mb-2"></i>
                            <p class="text-white mb-1">Click anywhere inside this area boundary to choose new images from system storage files</p>
                            <p class="text-muted small">Supported extensions: .jpg, .jpeg, .png, .webp (Maximum 6 images allowed)</p>
                            <input type="file" id="productImageFiles" class="d-none" multiple accept="image/*" onchange="previewProductImages();" />
                        </div>
                        <div class="row g-2 mt-3 img-preview-container" id="imagePreviewRow">
                            <?php
                            $img_rs = Database::search("SELECT `img_path` FROM `product_img` WHERE `product_id` = '".$pid."'");
                            while($img = $img_rs->fetch_assoc()){
                                echo '<div class="col-md-2 col-sm-4 col-6 position-relative mb-2">
                                        <img src="'.$img["img_path"].'" class="img-thumbnail" style="height:150px; width:100%; object-fit:cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);" />
                                      </div>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="col-md-12 text-end mt-4">
                        <button class="btn btn-accent-action px-5" onclick="executeFullProductUpdate(<?php echo $pid; ?>);">
                            <i class="fas fa-circle-check me-2"></i> Save Full Updates
                        </button>
                    </div>

                </div>
            </div>
        </main>
    </section>

    <script src="app.js"></script>
    <script src="bootstrap.bundle.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>

</html>
<?php
} else { 
    header("Location: adminLogin.php");
}
?>