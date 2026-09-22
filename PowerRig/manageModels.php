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
        <title>PowerRig - Manage Models</title>
        <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="home.css" />
        <link rel="stylesheet" href="dashboard.css" />
        
        <style>
            .model-panel-block {
                background: var(--bg-surface);
                padding: 24px;
                border-radius: 20px;
                margin-bottom: 24px;
                border: 1px solid rgba(255, 255, 255, 0.02);
            }
            .model-field {
                background: var(--bg-surface-accent) !important;
                border: 1px solid var(--border-low-opacity) !important;
                color: var(--text-primary) !important;
                padding: 12px 16px;
                font-size: 15px;
                border-radius: 8px !important;
            }
            .model-field:focus {
                border-color: var(--accent) !important;
                box-shadow: 0 0 0 3px rgba(255, 74, 90, 0.15) !important;
            }
            select.model-field option {
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
                
                <div class="model-panel-block">
                    <h3 class="panel-title mb-3"><i class="fas fa-plus-circle me-2 text-danger"></i>Create Core Model</h3>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-9 col-sm-12">
                            <input type="text" id="newModelName" class="form-control model-field" placeholder="Enter new standalone hardware model name (e.g., ROG Strix, Vengeance)..." />
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <button class="btn btn-action-trigger w-100" onclick="saveCoreModel();">
                                <i class="fas fa-box-open me-2"></i>Save Model
                            </button>
                        </div>
                    </div>
                </div>

                <div class="model-panel-block">
                    <h3 class="panel-title mb-3"><i class="fas fa-link me-2 text-danger"></i>Forge Model to Category Map Relation</h3>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-5 col-sm-12">
                            <select id="mapModelSelect" class="form-select model-field">
                                <option value="0">-- Select Available Model --</option>
                                <?php
                                $model_dropdown_rs = Database::search("SELECT * FROM `model` ORDER BY `model_name` ASC");
                                while ($md_data = $model_dropdown_rs->fetch_assoc()) {
                                    echo '<option value="' . $md_data["model_id"] . '">' . $md_data["model_name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-5 col-sm-12">
                            <select id="mapCategorySelect" class="form-select model-field">
                                <option value="0">-- Select Available Category --</option>
                                <?php
                                $cat_dropdown_rs = Database::search("SELECT * FROM `category` ORDER BY `category_name` ASC");
                                while ($cd_data = $cat_dropdown_rs->fetch_assoc()) {
                                    echo '<option value="' . $cd_data["category_id"] . '">' . $cd_data["category_name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <button class="btn btn-action-trigger w-100" onclick="linkModelToCategory();">
                                <i class="fas fa-link me-2"></i>Link Map
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Active Models & Category Mapping Index</h3>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Model ID</th>
                                    <th style="width: 35%;">Model Name</th>
                                    <th style="width: 25%;">Mapped Category</th>
                                    <th class="text-center" style="width: 25%;">Administrative Operations</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Left join query displaying all standalone or mapped model entries seamlessly
                                $query = "SELECT m.model_id, m.model_name, c.category_name, chm.category_has_model_id 
                                          FROM `model` m
                                          LEFT JOIN `category_has_model` chm ON m.model_id = chm.model_model_id
                                          LEFT JOIN `category` c ON chm.category_category_id = c.category_id
                                          ORDER BY m.model_id ASC";
                                          
                                $ledger_rs = Database::search($query);
                                while ($row_data = $ledger_rs->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="text-muted"># <?php echo $row_data["model_id"]; ?></span>
                                        </td>
                                        <td>
                                            <span class="text-white font-weight-bold" id="modelNameText-<?php echo $row_data["model_id"]; ?>">
                                                <?php echo $row_data["model_name"]; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($row_data["category_name"])) { ?>
                                                <span class="badge bg-secondary px-3 py-2 text-white" style="font-size: 13px; border-radius: 6px;">
                                                    <i class="fas fa-layer-group me-1 small"></i> <?php echo $row_data["category_name"]; ?>
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
                                                        onclick="editModelName(<?php echo $row_data['model_id']; ?>, '<?php echo addslashes($row_data['model_name']); ?>', '<?php echo !empty($row_data['category_has_model_id']) ? $row_data['category_has_model_id'] : 0; ?>')">
                                                    <i class="fas fa-pen-to-square me-1"></i> Edit
                                                </button>
                                                <?php if (!empty($row_data["category_has_model_id"])) { ?>
                                                    <button class="status pending style-btn" style="border: none; cursor: pointer;"
                                                            onclick="deleteModelMapping(<?php echo $row_data['category_has_model_id']; ?>, true)">
                                                        <i class="fas fa-unlink me-1"></i> Drop Link
                                                    </button>
                                                <?php } else { ?>
                                                    <button class="status pending style-btn" style="border: none; cursor: pointer;"
                                                            onclick="deleteModelMapping(<?php echo $row_data['model_id']; ?>, false)">
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