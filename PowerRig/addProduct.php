<?php
include "connection.php";
session_start();

if (isset($_SESSION["admin"])) {
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PowerRig - Add New Product</title>
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
                <h3 class="mb-4 text-white"><i class="fas fa-cart-plus me-2 text-danger"></i> Catalog New Inventory
                    Product</h3>

                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label">Product Category</label>
                        <select id="pCategory" class="form-select product-field" onchange="loadCategoryFilters();">
                            <option value="0">-- Select Category --</option>
                            <?php
                                $cat_rs = Database::search("SELECT * FROM `category` ORDER BY `category_name` ASC");
                                while ($cat = $cat_rs->fetch_assoc()) {
                                    echo '<option value="'.$cat["category_id"].'">'.$cat["category_name"].'</option>';
                                }
                                ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Brand Vendor</label>
                        <select id="pBrand" class="form-select product-field" onchange="loadFilteredModels();">
                            <option value="0">-- Select Brand --</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Hardware Model Line</label>
                        <select id="pModel" class="form-select product-field">
                            <option value="0">-- Select Model --</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Product Custom Listing Title</label>
                        <input type="text" id="pTitle" class="form-control product-field"
                            placeholder="e.g., ASUS ROG Strix G15 Gaming Laptop (16GB RAM / 1TB SSD)" />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Select Registered Accent Color</label>
                        <select id="pColor" class="form-select product-field">
                            <option value="0">-- Choose Color --</option>
                            <?php
                                $color_rs = Database::search("SELECT * FROM `color` ORDER BY `color_name` ASC");
                                while ($color = $color_rs->fetch_assoc()) {
                                    echo '<option value="'.$color["color_id"].'">'.$color["color_name"].'</option>';
                                }
                                ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Can't Find Color? Insert New Quick Label</label>
                        <div class="input-group">
                            <input type="text" id="newColorInput" class="form-control product-field"
                                placeholder="e.g., Titanium Grey" />
                            <button class="btn btn-outline-danger px-3" type="button" onclick="addNewColorQuick();">
                                <i class="fas fa-plus"></i> Inject Color
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Stock Quantity Available</label>
                        <input type="number" id="pQty" class="form-control product-field" placeholder="0" min="1" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Unit Cost Retail Price (Rs.)</label>
                        <input type="number" id="pPrice" class="form-control product-field" placeholder="0.00" min="1"
                            step="0.01" />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Default Local Delivery Fee (Colombo)</label>
                        <?php
                            // Query looking up row index 5 (Colombo) out of delivery data matrices
                            $delivery_fee = "0.00";
                            $del_rs = Database::search("SELECT `delivery_fee` FROM `shippingcost_by_district` WHERE `district_district_id` = '5'");
                            if ($del_rs->num_rows > 0) {
                                $del_data = $del_rs->fetch_assoc();
                                $delivery_fee = $del_data["delivery_fee"];
                            }
                            ?>
                        <input type="text" id="pDelivery" class="form-control product-field"
                            value="<?php echo $delivery_fee; ?>" disabled />
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Detailed Product Technical Specifications & Overview
                            Description</label>
                        <textarea id="pDescription" class="form-control product-field" rows="6"
                            placeholder="Provide system requirements, hardware features, parts metrics, package details layout specifications here..."></textarea>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="form-label">Product Marketing Images (Max 6 Images)</label>
                        <div class="image-uploader-box" onclick="document.getElementById('productImageFiles').click();">
                            <i class="fas fa-cloud-upload-alt fa-3x text-danger mb-2"></i>
                            <p class="text-white mb-1">Click anywhere inside this area boundary to choose images from
                                system storage files</p>
                            <p class="text-muted small">Supported extensions: .jpg, .jpeg, .png, .webp (Maximum 6 images
                                allowed)</p>
                            <input type="file" id="productImageFiles" class="d-none" multiple accept="image/*"
                                onchange="previewProductImages();" />
                        </div>
                        <div class="row g-2 mt-3 img-preview-container" id="imagePreviewRow">
                        </div>
                    </div>

                    <div class="col-md-12 text-end mt-4">
                        <button class="btn btn-accent-action px-5" onclick="processProductAddition();">
                            <i class="fas fa-circle-check me-2"></i> Save & Broadcast Product Listing
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