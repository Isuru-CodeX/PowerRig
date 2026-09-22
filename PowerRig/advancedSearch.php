<?php
session_start();
include "connection.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerRig | Advanced Search</title>

    <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="home.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
    body {
        background-color: var(--bg-primary) !important;
        color: var(--text-primary) !important;
    }

    .filter-panel {
        background-color: var(--bg-surface);
        border: 1px solid rgba(255, 255, 255, 0.04);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        position: sticky;
        top: 100px;
    }

    .filter-title {
        font-weight: 700;
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding-bottom: 10px;
    }

    .premium-search-field {
        background-color: var(--bg-surface-accent) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: var(--text-primary) !important;
        border-radius: 8px !important;
        font-family: 'Urbanist', sans-serif;
        font-size: 14px;
    }

    .premium-search-field:focus {
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 3px rgba(255, 74, 90, 0.15) !important;
    }

    .btn-search-trigger {
        background-color: var(--accent) !important;
        color: var(--text-primary) !important;
        font-weight: 700;
        transition: var(--transition-smooth);
        width: 100%;
        margin-top: 15px;
    }

    .btn-search-trigger:hover {
        background-color: var(--accent-hover) !important;
    }

    .catalog-title-wrapper h2 {
        font-size: 28px;
        font-weight: 800;
        border-left: 4px solid var(--accent);
        padding-left: 15px;
    }
    </style>
</head>

<body onload="runAdvancedSearch();">

    <header class="site-header-wrapper" data-header>
        <?php include "header.php"; ?>
    </header>

    <main class="container" style="margin-top: 40px; margin-bottom: 60px;">
        <div class="row g-4">

            <div class="col-lg-3 col-md-4">
                <div class="filter-panel">
                    <h4 class="filter-title"><i class="fas fa-sliders-h me-2"></i> Filters</h4>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Keyword Search</label>
                        <input type="text" id="adv_txt" class="form-control premium-search-field"
                            placeholder="Search title or specs..." onkeyup="runAdvancedSearch();">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Category</label>
                        <select id="adv_cat" class="form-select premium-search-field"
                            onchange="updateFiltersCascade();">
                            <option value="0">All Categories</option>
                            <?php
                            $cat_rs = Database::search("SELECT * FROM `category`");
                            while ($cat_data = $cat_rs->fetch_assoc()) {
                                echo "<option value='".$cat_data["category_id"]."'>".$cat_data["category_name"]."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Brand</label>
                        <select id="adv_brand" class="form-select premium-search-field" onchange="runAdvancedSearch();">
                            <option value="0">All Brands</option>
                            <?php
                            $brand_rs = Database::search("SELECT * FROM `brand`");
                            while ($brand_data = $brand_rs->fetch_assoc()) {
                                echo "<option value='".$brand_data["brand_id"]."'>".$brand_data["brand_name"]."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Model</label>
                        <select id="adv_model" class="form-select premium-search-field" onchange="runAdvancedSearch();">
                            <option value="0">All Models</option>
                            <?php
                            $model_rs = Database::search("SELECT * FROM `model`");
                            while ($model_data = $model_rs->fetch_assoc()) {
                                echo "<option value='".$model_data["model_id"]."'>".$model_data["model_name"]."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Color Theme</label>
                        <select id="adv_color" class="form-select premium-search-field" onchange="runAdvancedSearch();">
                            <option value="0">Any Color</option>
                            <?php
                            $color_rs = Database::search("SELECT * FROM `color`");
                            while ($color_data = $color_rs->fetch_assoc()) {
                                echo "<option value='".$color_data["color_id"]."'>".$color_data["color_name"]."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small">Min Price (Rs.)</label>
                            <input type="number" id="adv_min" class="form-control premium-search-field" min="0"
                                placeholder="Min" onkeyup="runAdvancedSearch();">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small">Max Price (Rs.)</label>
                            <input type="number" id="adv_max" class="form-control premium-search-field" min="0"
                                placeholder="Max" onkeyup="runAdvancedSearch();">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Sort Strategy</label>
                        <select id="adv_sort" class="form-select premium-search-field" onchange="runAdvancedSearch();">
                            <option value="0">Newest First</option>
                            <option value="1">Price: Low to High</option>
                            <option value="2">Price: High to Low</option>
                            <option value="3">Quantity: High to Low</option>
                        </select>
                    </div>

                    <button class="btn btn-search-trigger" onclick="runAdvancedSearch();">Apply Filters</button>
                    <button class="btn btn-outline-secondary w-100 mt-2 border-0"
                        onclick="window.location.reload();">Clear All</button>

                </div>
            </div>

            <div class="col-lg-9 col-md-8">
                <div class="catalog-title-wrapper mb-4">
                    <h2>Advanced Catalog Results</h2>
                </div>

                <ul class="product-grid-list-matrix row list-unstyled g-4" id="adv_results_container">
                    <div class="w-100 text-center py-5">
                        <div class="spinner-border text-danger" role="status"></div>
                        <p class="text-muted mt-2">Compiling Catalog Database...</p>
                    </div>
                </ul>
            </div>

        </div>
    </main>

    <?php include "footer.php"; ?>

    <script src="home.js"></script>
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>