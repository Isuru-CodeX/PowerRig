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
        <title>PowerRig - Manage Brands</title>
        <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="home.css" />
        <link rel="stylesheet" href="dashboard.css" />
        
        <style>
            /* Contextual adjustment to keep input components inside your custom Dark UI theme context */
            .brand-input-group {
                background: var(--bg-surface);
                padding: 24px;
                border-radius: 20px;
                margin-bottom: 24px;
            }
            .brand-field {
                background: var(--bg-surface-accent) !important;
                border: 1px solid var(--border-low-opacity) !important;
                color: var(--text-primary) !important;
                padding: 12px 16px;
                font-size: 15px;
                border-radius: 8px !important;
            }
            .brand-field:focus {
                border-color: var(--accent) !important;
                box-shadow: 0 0 0 3px rgba(255, 74, 90, 0.15) !important;
            }
            .btn-brand-add {
                background-color: var(--accent) !important;
                color: var(--text-primary) !important;
                font-weight: 600;
                padding: 12px 24px;
                border-radius: 8px;
                border: none;
                transition: var(--transition-smooth);
            }
            .btn-brand-add:hover {
                background-color: var(--accent-hover) !important;
                transform: translateY(-1px);
            }
        </style>
    </head>

    <body>
        <?php include "adminNavbar.php" ?>

        <section class="content">
            <?php include "adnav.php" ?>

            <main>
                <div class="brand-input-group">
                    <h3 class="text-white mb-3" style="font-family: var(--poppins); font-size: 20px; font-weight: 600;">Add New Hardware Brand</h3>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-8 col-sm-12">
                            <input type="text" id="brandName" class="form-control brand-field" placeholder="Enter brand name (e.g., ASUS, Corsair, MSI)..." />
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <button class="btn btn-brand-add w-100" onclick="addNewBrand();">
                                <i class="fas fa-plus-circle me-2"></i>Save Brand
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Available Brand Inventory</h3>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 15%;">ID Reference</th>
                                    <th style="width: 55%;">Brand Name</th>
                                    <th class="text-center" style="width: 30%;">Administrative Management</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Chronological Sorting Configuration Rule (Lists records chronologically from #1 to #10+)
                                $brand_rs = Database::search("SELECT * FROM `brand` ORDER BY `brand_id` ASC");
                                while ($brand_data = $brand_rs->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="text-muted"># <?php echo $brand_data["brand_id"]; ?></span>
                                        </td>
                                        <td>
                                            <span class="text-white font-weight-bold" id="brandNameText-<?php echo $brand_data["brand_id"]; ?>">
                                                <?php echo $brand_data["brand_name"]; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="status process style-btn" style="border: none; cursor: pointer;"
                                                        onclick="editBrandName(<?php echo $brand_data['brand_id']; ?>, '<?php echo addslashes($brand_data['brand_name']); ?>')">
                                                    <i class="fas fa-pen-to-square me-1"></i> Edit
                                                </button>
                                                <button class="status pending style-btn" style="border: none; cursor: pointer;"
                                                        onclick="deleteBrand(<?php echo $brand_data['brand_id']; ?>)">
                                                    <i class="fas fa-trash-can me-1"></i> Delete
                                                </button>
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