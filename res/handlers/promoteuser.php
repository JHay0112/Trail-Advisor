<?php

    /*

        res/handlers/promoteuser.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Promotes selected user to staff.

        Other Notes:

            N/a

    */

    $token_required = true;
    $referral_path = "../../";
    $permitted_users = array("Admin");
    require("../initsession.php");
    require("../connect.php");
    require("../checkfields.php");

    // Check all required fields exist
    check_fields($_GET, array("user"), "../../profile.php");

    $user_id = (int)$_GET["user"];

    // If trail id is invalid send them back to edit dialogue
    if($user_id == 0) {
        print("<script> location = '../../profile.php?user_id=0'</script>");
    }

    // Prepare statment
    $stmt = mysqli_prepare($link, "UPDATE users SET user_type = 'Staff' WHERE user_id = ?");

    // Binding parameters and executing statement, checking if $stmt has formed first
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
    }

    print("<script>location = '../../profile.php?user_id=".$user_id."&referral_case=userpromoted'</script>");

?>