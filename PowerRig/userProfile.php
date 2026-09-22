<?php
include "connection.php";
session_start();

if (isset($_SESSION["user"])) {
    $user = $_SESSION["user"]["email"];

    $details_rs = Database::search("SELECT * FROM `user` WHERE `email`='" . $user . "'");
    $image_rs = Database::search("SELECT * FROM `profile_img` WHERE `user_email`='" . $user . "'");
    
    // CORRECTED: Joined district via user_has_address instead of city
    $address_rs = Database::search("SELECT * FROM `user_has_address`
    INNER JOIN `address` ON `user_has_address`.`address_address_id` = `address`.`address_id` 
    INNER JOIN `city` ON `address`.`city_city_id` = `city`.`city_id`
    INNER JOIN `district` ON `user_has_address`.`district_district_id` = `district`.`district_id`
    WHERE `user_has_address`.`user_email` = '" . $user . "'");

    $user_details = $details_rs->fetch_assoc();
    $image_details = $image_rs->fetch_assoc();
    $address_details = $address_rs->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PowerRig - Client Workspace</title>
    <link rel="shortcut icon" href="./resources/system/logo/PowerRig.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="bootstrap.css" />
    <link rel="stylesheet" href="home.css" />
    <link rel="stylesheet" href="dashboard.css" />
</head>

<body>
    <?php include "nav.php" ?>
    <?php include "navbar.php" ?>

    <section class="content">
        <main>
            <div class="head-title">
                <div class="left" style="width: 100%;">
                    <h1
                        style="font-family: var(--poppins); font-weight: 600; font-size: 36px; color: var(--text-primary); margin-bottom: 10px;">
                        My Personal Profile</h1>
                    <p style="color: var(--text-secondary);">Manage account security details, communication data, and
                        active delivery paths.</p>
                </div>
            </div>

            <ul class="box-info" style="margin-top: 30px; padding: 0;">
                <li>
                    <i class="fas fa-id-card-clip"></i>
                    <span class="text">
                        <h3><?php echo $user_details["fname"] . " " . $user_details["lname"]; ?></h3>
                        <p>Registered Entity Name</p>
                    </span>
                </li>
                <li>
                    <i class="fas fa-envelope-open-text"></i>
                    <span class="text">
                        <h3 style="font-size: 18px; word-break: break-all;"><?php echo $user; ?></h3>
                        <p>Routing Account Channel</p>
                    </span>
                </li>
                <li>
                    <i class="fas fa-clock-rotate-left"></i>
                    <span class="text">
                        <h3>
                            <?php 
                                    $joined = new DateTime($user_details["joined_date"]);
                                    echo $joined->format("Y-m-d");
                                ?>
                        </h3>
                        <p>Account Enrolment Epoch</p>
                    </span>
                </li>
            </ul>

            <div class="leaderboard-grid"
                style="margin-top: 40px; display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">

                <div class="leaderboard-card">
                    <h3>Profile Avatar</h3>

                    <div
                        style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; height: calc(100% - 60px);">
                        <div class="showcase-img-container"
                            style="width: 160px; height: 160px; border-radius: 50%; overflow: hidden; border: 4px solid var(--bg-surface-accent); box-shadow: 0 8px 24px rgba(0,0,0,0.2); margin-bottom: 20px;">
                            <img id="img"
                                src="<?php echo (!empty($image_details["img_path"])) ? $image_details["img_path"] : './resources/system/logo/Swift-art.png'; ?>"
                                style="width: 100%; height: 100%; object-fit: cover;" />
                        </div>

                        <input type="file" id="profileimage" accept="image/*" onchange="changeProfileimg();"
                            style="display: none;" />

                        <label for="profileimage" class="download-btn"
                            style="cursor: pointer; background-color: var(--accent) !important; color: var(--text-primary) !important; padding: 10px 24px; border-radius: 6px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 10px; border: none;">
                            <i class="fas fa-camera"></i> Change Avatar Graphic
                        </label>
                    </div>
                </div>

                <div class="leaderboard-card">
                    <h3>Identity Parameters</h3>
                    <div class="insights-grid" style="margin-bottom: 25px;">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" id="fname" value="<?php echo $user_details["fname"]; ?>"
                                class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" id="lname" value="<?php echo $user_details["lname"]; ?>"
                                class="form-control" />
                        </div>
                    </div>

                    <h3>Shipping Destination Registry</h3>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Street Address Line</label>
                        <input type="text" id="line" name="line"
                            value="<?php echo (!empty($address_details['address_line'])) ? $address_details['address_line'] : ''; ?>"
                            class="form-control" />
                    </div>

                    <div class="insights-grid" style="margin-bottom: 25px;">
                        <div class="form-group">
                            <label>District</label>
                            <select id="district" class="form-control">
                                <option value="0">Select District</option>
                                <?php
                                    $district_rs = Database::search("SELECT * FROM `district`");
                                    while ($district_data = $district_rs->fetch_assoc()) {
                                        $selected = (!empty($address_details["district_id"]) && $address_details["district_id"] == $district_data["district_id"]) ? "selected" : "";
                                        echo '<option value="' . $district_data["district_id"] . '" ' . $selected . '>' . $district_data["district_name"] . '</option>';
                                    }
                                    ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>City</label>
                            <select id="city" class="form-control">
                                <option value="0">Select City</option>
                                <?php
                                    $city_rs = Database::search("SELECT * FROM `city`");
                                    while ($city_data = $city_rs->fetch_assoc()) {
                                        $selected = (!empty($address_details["city_id"]) && $address_details["city_id"] == $city_data["city_id"]) ? "selected" : "";
                                        echo '<option value="' . $city_data["city_id"] . '" ' . $selected . '>' . $city_data["city_name"] . '</option>';
                                    }
                                    ?>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; margin-top: 30px;">
                        <button onclick="updateProfile();" class="download-btn"
                            style="border: none; padding: 12px 30px; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-floppy-disk" style="margin-right: 8px;"></i> Commit Account Updates
                        </button>
                    </div>
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
    header("Location: index.php");
}
?>