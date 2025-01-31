<?php

    /*

        res/handlers/togglelike.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Toggles whether the user likes or dislikes the trail.

        Other Notes:

            N/a

    */

    $token_required = true;
    $referral_path = "../../";
    $permitted_users = array("Admin", "Staff", "Standard");
    require("../initsession.php");
    require("../connect.php");
    require("../checkfields.php");

    // Check all required fields exist
    check_fields($_GET, array("trail"), "../../trail.php");

    $trail_id = (int)$_GET["trail"];

    // If trail id is invalid send them back to edit dialogue
    if($trail_id == 0) {
        print("<script> location = '../../trail.php?trail=0'</script>");
    }

    // Checking that user has not liked this trail before
    $stmt = mysqli_prepare($link, "SELECT trail_id FROM trail_likes WHERE trail_id = ? AND user_id = ?");

    // Binding parameters and executing statement, checking if $stmt has formed first
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $trail_id, $user_info["user_id"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
    }

    // If user has not liked this trail, add the like
    if(mysqli_stmt_num_rows($stmt) == 0) {
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($link, "INSERT INTO trail_likes (trail_id, user_id) VALUES (?, ?)");

        // Binding parameters and executing statement, checking if $stmt has formed first
        if($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $trail_id, $user_info["user_id"]);
            mysqli_stmt_execute($stmt);
        }
    } else {
        // else remove the like

        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($link, "DELETE FROM trail_likes WHERE trail_id = ? AND user_id = ?");

        // Binding parameters and executing statement, checking if $stmt has formed first
        if($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $trail_id, $user_info["user_id"]);
            mysqli_stmt_execute($stmt);
        }
    }

    print("<script>location = '../../trail.php?trail=".$trail_id."'</script>");

?>