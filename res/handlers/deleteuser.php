<?php

    /*

        res/handlers/deleteuser.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Deletes the logged in user's account

        Other Notes:

            N/a

    */

    // Token verification
    $token_required = true;
    require_once("../initsession.php");
    require_once("../connect.php");

    // Preparing statement
    $stmt = mysqli_prepare($link, "DELETE FROM `users` WHERE `user_id` = ?;");

    // User ID can only be specified as different to own if the user is an admin
    if((isset($_GET["user_id"])) && ($user_info["user_type"] == "Admin")) {
        $user_id = $_GET["user_id"];
        $destination = "../../profile.php?referral_case=deleteduser&user_id=".$user_id;
    } else {
        $user_id = $user_info["user_id"];
        // Remove the session variables, this logs out the user.
        session_unset();
        $destination = "../../login.php?referral_case=deleteduser";
    }

    // Binding parameters and executing statement, checking if $stmt has formed first
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
    }

    // Now alert user they have been logged out.
    print("<script>location = '".$destination."';</script>");

?>