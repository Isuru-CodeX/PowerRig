<?php

session_start();
include "connection.php";

if (isset($_SESSION["user"])) {
    
    $mail = $_SESSION["user"]["email"];
    $pid = $_POST["id"];
    $comment = $_POST["comment"];
    $profile_img_result = Database::search("SELECT * FROM profile_img WHERE user_email = '".$mail."'");

    if ($profile_img_result) {  // Check if the query was successful
        if ($profile_img_result->num_rows > 0) {
            if (empty($comment)) {
                echo "Please enter a comment";
            } else {
    
                $d = new DateTime();
                $tz = new DateTimeZone("Asia/Colombo");
                $d->setTimezone($tz);
                $date = $d->format("Y-m-d H:i:s");
    
                Database::iud("INSERT INTO comment(product_id,comment,comment_date,user_email) VALUES 
                ('".$pid."','".$comment."','".$date."','".$mail."')");
    
                echo "success";
            }
        } else {
            echo "please insert a profile image";
        }
    } else {
        // Handle the case where the query fails
        echo "Error: Could not execute the query. Please check your database connection and SQL syntax.";
    }
}
?>