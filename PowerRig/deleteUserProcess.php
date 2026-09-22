<?php
include "connection.php";
session_start();

// Ensure only authenticated admins can trigger deletions
if (isset($_SESSION["admin"]) && isset($_GET["email"])) {
    $email = $_GET["email"];

    // Check if the user exists before attempting deletion
    $user_rs = Database::search("SELECT * FROM `user` WHERE `email` = '" . $email . "'");
    
    if ($user_rs->num_rows > 0) {
        // First delete profile picture reference if it exists to preserve foreign key integrity
        Database::iud("DELETE FROM `profile_img` WHERE `user_email` = '" . $email . "'");
        
        // Remove the core user record
        Database::iud("DELETE FROM `user` WHERE `email` = '" . $email . "'");
        
        echo "success";
    } else {
        echo "User account not found.";
    }
} else {
    echo "Unauthorized administrative request access denied.";
}
?>