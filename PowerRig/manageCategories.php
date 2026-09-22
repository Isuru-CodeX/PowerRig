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
        <title>PowerRig - Manage Categories</title>
        <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="home.css" />
        <link rel="stylesheet" href="dashboard.css" />
        
        <style>
            .category-panel-block {
                background: var(--bg-surface);
                padding: 24px;
                border-radius: 20px;
                margin-bottom: 24px;
                border: 1px solid rgba(255, 255, 255, 0.02);
            }
            .category-field {
                background: var(--bg-surface-accent) !important;
                border: 1px solid var(--border-low-opacity) !important;
                color: var(--text-primary) !important;
                padding: 12px 16px;
                font-size: 15px;
                border-radius: 8px !important;
            }
            .category-field:focus {a
                border-color: var(--accent) !important;
                box-shadow: 0 0 0 3px rgba(255, 74, 90, 0.15) !important;
            }
            select.category-field option {
                background: var(--bg-surface-accent);
                color: var(--text-primary);
            }
            .btn-action-trigger {
                background-color: var(--accent) !important;
                color: var(--text-primary) !important;
                font-weight: 600;
                padding: 12px 24px;
                border-radius: 8px;
                border: none;
                transition: var(--transition-smooth);
            }
            .btn-action-trigger:hover {
                background-color: var(--accent-hover) !important;
                transform: translateY(-1px);
            }
            .panel-title {
                font-family: var(--poppins);
                font-size: 16px;
                font-weight: 600;
                color: var(--text-secondary);
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
        </style>
    </head>

    <body>
        <?php include "adminNavbar.php" ?>

        <section class="content">
            <?php include "adnav.php" ?>

            <main>
                
                <div class="category-panel-block">
                    <h3 class="panel-title mb-3"><i class="fas fa-plus-circle me-2 text-danger"></i>Create Core Category</h3>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-9 col-sm-12">
                            <input type="text" id="newCategoryName" class="form-control category-field" placeholder="Enter new standalone category name (e.g., Laptop, Graphic Cards)..." />
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <button class="btn btn-action-trigger w-100" onclick="saveCoreCategory();">
                                <i class="fas fa-folder-plus me-2"></i>Save Category
                            </button>
                        </div>
                    </div>
                </div>

                <div class="category-panel-block">
                    <h3 class="panel-title mb-3"><i class="fas fa-link me-2 text-danger"></i>Forge Category to Brand Map Relation</h3>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-5 col-sm-12">
                            <select id="mapCategorySelect" class="form-select category-field">
                                <option value="0">-- Select Available Category --</option>
                                <?php
                                $cat_dropdown_rs = Database::search("SELECT * FROM `category` ORDER BY `category_name` ASC");
                                while ($cd_data = $cat_dropdown_rs->fetch_assoc()) {
                                    echo '<option value="' . $cd_data["category_id"] . '">' . $cd_data["category_name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-5 col-sm-12">
                            <select id="mapBrandSelect" class="form-select category-field">
                                <option value="0">-- Select Available Brand --</option>
                                <?php
                                $brand_dropdown_rs = Database::search("SELECT * FROM `brand` ORDER BY `brand_name` ASC");
                                while ($bd_data = $brand_dropdown_rs->fetch_assoc()) {
                                    echo '<option value="' . $bd_data["brand_id"] . '">' . $bd_data["brand_name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <button class="btn btn-action-trigger w-100" onclick="linkCategoryToBrand();">
                                <i class="fas fa-link me-2"></i>Link Map
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Active Categories & Brand Mapping Index</h3>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Category ID</th>
                                    <th style="width: 35%;">Category Name</th>
                                    <th style="width: 25%;">Mapped Brand</th>
                                    <th class="text-center" style="width: 25%;">Administrative Operations</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Left join query displaying all categories and their mappings seamlessly
                                $query = "SELECT c.category_id, c.category_name, b.brand_name, chb.category_has_brand_id 
                                          FROM `category` c
                                          LEFT JOIN `category_has_brand` chb ON c.category_id = chb.category_category_id
                                          LEFT JOIN `brand` b ON chb.brand_brand_id = b.brand_id
                                          ORDER BY c.category_id ASC";
                                          
                                $ledger_rs = Database::search($query);
                                while ($row_data = $ledger_rs->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="text-muted"># <?php echo $row_data["category_id"]; ?></span>
                                        </td>
                                        <td>
                                            <span class="text-white font-weight-bold" id="categoryNameText-<?php echo $row_data["category_id"]; ?>">
                                                <?php echo $row_data["category_name"]; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($row_data["brand_name"])) { ?>
                                                <span class="badge bg-secondary px-3 py-2 text-white" style="font-size: 13px; border-radius: 6px;">
                                                    <i class="fas fa-tag me-1 small"></i> <?php echo $row_data["brand_name"]; ?>
                                                </span>
                                            <?php } else { ?>
                                                <span class="text-warning font-italic" style="font-size: 14px; font-weight: 500;">
                                                    <i class="fas fa-exclamation-circle me-1 small"></i> None
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="status process style-btn" style="border: none; cursor: pointer;"
                                                        onclick="editCategoryName(<?php echo $row_data['category_id']; ?>, '<?php echo addslashes($row_data['category_name']); ?>', '<?php echo !empty($row_data['category_has_brand_id']) ? $row_data['category_has_brand_id'] : 0; ?>')">
                                                    <i class="fas fa-pen-to-square me-1"></i> Edit
                                                </button>
                                                <?php if (!empty($row_data["category_has_brand_id"])) { ?>
                                                    <button class="status pending style-btn" style="border: none; cursor: pointer;"
                                                            onclick="deleteCategoryMapping(<?php echo $row_data['category_has_brand_id']; ?>, true)">
                                                        <i class="fas fa-unlink me-1"></i> Drop Link
                                                    </button>
                                                <?php } else { ?>
                                                    <button class="status pending style-btn" style="border: none; cursor: pointer;"
                                                            onclick="deleteCategoryMapping(<?php echo $row_data['category_id']; ?>, false)">
                                                        <i class="fas fa-trash-can me-1"></i> Delete
                                                    </button>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </section>

        <script src="app.js"></script>
        <script src="script.js"></script>
        <script src="bootstrap.bundle.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    </body>

    </html>

    <?php
} else {
    header("Location: adminLogin.php");
}
?>