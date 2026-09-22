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
        <title>PowerRig - Manage Delivery Fees</title>
        <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <link rel="stylesheet" href="bootstrap.css">
        <link rel="stylesheet" href="home.css" />
        <link rel="stylesheet" href="dashboard.css" />
        
        <style>
            .delivery-panel-block {
                background: var(--bg-surface);
                padding: 24px;
                border-radius: 20px;
                margin-bottom: 24px;
                border: 1px solid rgba(255, 255, 255, 0.02);
            }
            .delivery-field {
                background: var(--bg-surface-accent) !important;
                border: 1px solid var(--border-low-opacity) !important;
                color: var(--text-primary) !important;
                padding: 12px 16px;
                font-size: 15px;
                border-radius: 8px !important;
            }
            .delivery-field:focus {
                border-color: var(--accent) !important;
                box-shadow: 0 0 0 3px rgba(255, 74, 90, 0.15) !important;
            }
            select.delivery-field option {
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
                
                <div class="delivery-panel-block">
                    <h3 class="panel-title mb-3"><i class="fas fa-truck me-2 text-danger"></i>Configure District Shipping Fee</h3>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-5 col-sm-12">
                            <select id="deliveryDistrictSelect" class="form-select delivery-field">
                                <option value="0">-- Choose Regional District --</option>
                                <?php
                                // Pulling list of districts from system storage
                                $district_rs = Database::search("SELECT * FROM `district` ORDER BY `district_name` ASC");
                                while ($d_data = $district_rs->fetch_assoc()) {
                                    echo '<option value="' . $d_data["district_id"] . '">' . $d_data["district_name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-5 col-sm-12">
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-secondary">Rs.</span>
                                <input type="number" id="deliveryFeeInput" class="form-control delivery-field" placeholder="0.00" min="0" />
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <button class="btn btn-action-trigger w-100" onclick="saveDeliveryFee();">
                                <i class="fas fa-save me-2"></i>Apply Fee
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Active Regional Delivery Tariffs Index</h3>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 20%;">Configuration ID</th>
                                    <th style="width: 40%;">District Target Region</th>
                                    <th style="width: 20%;">Assigned Delivery Cost</th>
                                    <th class="text-center" style="width: 20%;">Administrative Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Left joining configuration matrices directly with matching core district strings
                                $query = "SELECT s.delivery_id, s.delivery_fee, d.district_name 
                                          FROM `shippingcost_by_district` s
                                          INNER JOIN `district` d ON s.district_district_id = d.district_id
                                          ORDER BY d.district_name ASC";
                                          
                                $ledger_rs = Database::search($query);
                                if ($ledger_rs->num_rows == 0) {
                                    ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No shipping tariff rules have been allocated yet.</td>
                                    </tr>
                                    <?php
                                } else {
                                    while ($row_data = $ledger_rs->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="text-muted"># <?php echo $row_data["delivery_id"]; ?></span>
                                            </td>
                                            <td>
                                                <span class="text-white font-weight-bold">
                                                    <i class="fas fa-map-marker-alt text-danger me-2"></i><?php echo $row_data["district_name"]; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-dark px-3 py-2 text-success border border-success" style="font-size: 14px; font-weight: 600;">
                                                    Rs. <?php echo number_format($row_data["delivery_fee"], 2); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button class="status process style-btn" style="border: none; cursor: pointer;"
                                                            onclick="editDeliveryFee(<?php echo $row_data['delivery_id']; ?>, '<?php echo $row_data['district_name']; ?>', '<?php echo $row_data['delivery_fee']; ?>')">
                                                        <i class="fas fa-pen-to-square me-1"></i> Edit
                                                    </button>
                                                    <button class="status pending style-btn" style="border: none; cursor: pointer;"
                                                            onclick="deleteDeliveryConfig(<?php echo $row_data['delivery_id']; ?>)">
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