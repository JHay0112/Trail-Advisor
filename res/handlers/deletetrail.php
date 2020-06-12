<?php

    /*

        res/handlers/deletetrail.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Deletes the logged in user's account

        Other Notes:

            N/a

    */

    // Token verification
    $token_required = true;
    $referral_path = "../../";
    $permitted_users = array("Admin", "Staff");
    require("../initsession.php");
    require("../connect.php");

    // Cast ID to int
    $trail_id = (int)$_GET["trail"];

    // If id is zero it is invalid, redirect to trail page
    if($trail_id == 0) {
        print("<script>location = '../../trail.php?trail=0';</script>");
    }

    // Preparing statement
    $stmt = mysqli_prepare($link, "DELETE FROM trails WHERE trail_id = ?;");

    // Binding parameters and executing statement, checking if $stmt has formed first
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $trail_id);
        mysqli_stmt_execute($stmt);

        // Delete image from directory
        $image = "../../img/trails/".$trail_id.".jpg";
        unlink($image);
    } else {
        // Now alert user the trail has failed to be deleted
        print("<script>location = '../../trail.php?referral_case=traildeletefail';</script>");
    }

    // Now alert user the trail has been deleted
    print("<script>location = '../../createtrail.php?referral_case=traildeleted';</script>");

?>