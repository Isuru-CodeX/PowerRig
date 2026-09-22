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
    <title>PowerRig - Manage Products</title>
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
            <div class="category-panel-block d-flex justify-content-between align-items-center mb-4">
                <h3 class="panel-title m-0"><i class="fas fa-boxes text-danger me-2"></i>Product Inventory Logbook</h3>
                <a href="addProduct.php" class="btn btn-action-trigger">
                    <i class="fas fa-plus me-2"></i>Add New Product
                </a>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Active Product Listings Ledger</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 10%;">Image</th>
                                <th style="width: 35%;">Product Title</th>
                                <th style="width: 15%;">Price</th>
                                <th style="width: 10%;">Quantity</th>
                                <th style="width: 15%;">Status</th>
                                <th class="text-center" style="width: 15%;">Administrative Operations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $product_rs = Database::search("SELECT p.*, p.id AS product_id, MIN(pi.img_path) AS img_path 
                                                            FROM `product` p 
                                                            LEFT JOIN `product_img` pi ON p.id = pi.product_id 
                                                            GROUP BY p.id ORDER BY p.id DESC");

                            if ($product_rs->num_rows == 0) {
                                echo "<tr><td colspan='6' class='text-center text-muted py-5'>No custom tech products logged in stock.</td></tr>";
                            } else {
                                while ($product = $product_rs->fetch_assoc()) {
                                    $img = (!empty($product["img_path"])) ? $product["img_path"] : "./resources/system/placeholder.png";
                                    $is_active = ($product["status_status_id"] == 1);
                            ?>
                            <tr>
                                <td>
                                    <img src="<?php echo $img; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
                                </td>
                                <td>
                                    <span class="text-white font-weight-bold d-block"><?php echo htmlspecialchars($product["title"]); ?></span>
                                    <small class="text-muted">ID: #<?php echo $product["product_id"]; ?></small>
                                </td>
                                <td>
                                    <span class="text-white fw-bold">Rs. <?php echo number_format($product['price'], 2); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary px-2 py-1 text-white" style="border-radius: 4px; font-size: 13px;">
                                        <?php echo $product['qty']; ?> pcs
                                    </span>
                                </td>
                                <td>
                                    <button class="status <?php echo $is_active ? 'process' : 'pending'; ?> style-btn" style="border: none; cursor: pointer;"
                                            onclick="toggleProductStatus(<?php echo $product['product_id']; ?>);">
                                        <i class="fas <?php echo $is_active ? 'fa-eye' : 'fa-eye-slash'; ?> me-1"></i> <?php echo $is_active ? "Active" : "Deactivated"; ?>
                                    </button>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="updateProduct.php?id=<?php echo $product['product_id']; ?>" class="status process style-btn text-decoration-none">
                                            <i class="fas fa-pen-to-square me-1"></i> Edit
                                        </a>
                                        <button class="status pending style-btn" style="border: none; cursor: pointer;" onclick="deleteProductRecord(<?php echo $product['product_id']; ?>)">
                                            <i class="fas fa-trash-can me-1"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php
                                }
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