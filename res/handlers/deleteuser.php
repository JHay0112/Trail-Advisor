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
    $referral_path = "../../";
    $permitted_users = array("Admin", "Staff", "Standard");
    require("../initsession.php");
    require("../connect.php");

    // Preparing statement
    $stmt = mysqli_prepare($link, "DELETE users, trails, trail_editors, trail_likes FROM users LEFT JOIN trails ON users.user_id = trails.creator LEFT JOIN trail_editors ON users.user_id = trail_editors.user_id LEFT JOIN trail_likes ON users.user_id = trail_likes.user_id WHERE users.user_id = ?;");

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