<?php
session_start();
include "connection.php";

if (!isset($_SESSION["user"])) {
    echo ("Session expired. Please log in again.");
    exit();
}

$email = $_SESSION["user"]["email"];
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$line = $_POST["line"];
$district = $_POST["district"];
$city = $_POST["city"];

if (empty($fname)) {
    echo ("Please Enter Your First name.");
} else if (empty($lname)) {
    echo ("Please Enter Your Last name.");
} else if (empty($line)) {
    echo ("Please Enter Your address line.");
} else if ($district == "0") {
    echo ("Please Select Your District.");
} else if ($city == "0") {
    echo ("Please Select Your City.");
} else {

    // 1. Update Core Identity Parameters 
    Database::iud("UPDATE `user` SET `fname`='" . $fname . "', `lname`='" . $lname . "' WHERE `email`='" . $email . "'");

    // 2. Resolve Existing Address Bindings
    $address_rs = Database::search("SELECT * FROM `user_has_address` WHERE `user_email`='" . $email . "'");

    if ($address_rs->num_rows == 1) {
        // Mapping exists: Extract keys and execute safe updates
        $address_data = $address_rs->fetch_assoc();
        $address_id = $address_data["address_address_id"];

        // Sync shared components
        Database::iud("UPDATE `address` SET `address_line`='" . $line . "', `city_city_id`='" . $city . "' WHERE `address_id`='" . $address_id . "'");
        Database::iud("UPDATE `user_has_address` SET `district_district_id`='" . $district . "' WHERE `user_has_address_id`='" . $address_data["user_has_address_id"] . "'");
    } else {
        // No current mapping: Generate record paths safely
        Database::iud("INSERT INTO `address` (`address_line`, `city_city_id`) VALUES ('" . $line . "', '" . $city . "')");
        $generated_address_id = Database::$connection->insert_id;

        Database::iud("INSERT INTO `user_has_address` (`address_address_id`, `user_email`, `district_district_id`) VALUES ('" . $generated_address_id . "', '" . $email . "', '" . $district . "')");
    }

    // 3. Process Profile Avatar Binary Uploads
    if (isset($_FILES["i"]) && !empty($_FILES["i"]["tmp_name"])) {
        $image = $_FILES["i"];
        $image_extenction = $image["type"];
        $allowed_image_extensions = array("image/jpeg", "image/png", "image/svg+xml");

        if (in_array($image_extenction, $allowed_image_extensions)) {
            $new_image_extension = "";
            if ($image_extenction == "image/jpeg") {
                $new_image_extension = ".jpeg";
            } else if ($image_extenction == "image/png") {
                $new_image_extension = ".png";
            } else if ($image_extenction == "image/svg+xml") {
                $new_image_extension = ".svg";
            }

            $file_name = "./resources/product/" . $fname . "_" . uniqid() . $new_image_extension;
            move_uploaded_file($image["tmp_name"], $file_name);

            $profile_img_rs = Database::search("SELECT * FROM `profile_img` WHERE `user_email` = '" . $email . "'");

            if ($profile_img_rs->num_rows == 1) {
                Database::iud("UPDATE `profile_img` SET `img_path`='" . $file_name . "' WHERE `user_email`='" . $email . "'");
            } else {
                Database::iud("INSERT INTO `profile_img` (`img_path`, `user_email`) VALUES ('" . $file_name . "', '" . $email . "')");
            }
        } else {
            echo ("Invalid image format selection.");
            exit();
        }
    }

    echo ("success");
}
?>