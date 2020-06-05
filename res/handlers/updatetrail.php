<?php

    /*

        res/handlers/updatetrail.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Updates an existing trails info.

        Other Notes:

            N/a

    */

    $token_required = true;
    $referral_path = "../../";
    $permitted_users = array("Admin", "Staff");
    require_once("../initsession.php");
    require_once("../connect.php");
    require_once("../checkfields.php");

    // Check all required fields exist
    check_fields($_POST, array("trail_id", "name", "description", "lat", "lng"), "../../edittrail.php");

    $trail_id = (int)$_POST["trail_id"];
    $name = strip_tags($_POST["name"]);
    $description = strip_tags($_POST["description"]);
    $lat = (float)$_POST["lat"];
    $lng = (float)$_POST["lng"];

    // If trail id is invalid send them back to edit dialogue
    if($trail_id == 0) {
        print("<script> location = '../../editrail.php?trail=".$trail_id."'</script>");
    }

    // Prepare statment
    $stmt = mysqli_prepare($link, "UPDATE trails SET name = ?, description = ?, lat = ?, lng = ? WHERE trail_id = ?");

    // Binding parameters and executing statement, checking if $stmt has formed first
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "ssddi", $name, $description, $lat, $lng, $trail_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Prepare new statment, this looks for the user being an editor or creator of the trail in question
    $stmt = mysqli_prepare($link, "SELECT trail_id FROM trail_editors WHERE trail_id = ? AND user_id = ?");

    // Binding parameters and executing statement, checking if $stmt has formed first
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $trail_id, $user_info["user_id"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
    }

    // if no rows returned user has not edited this trail before so add them to editors list
    if(mysqli_stmt_num_rows($stmt) == 0) {
        mysqli_stmt_close($stmt);

        // Prepare new statment to add new editor to trail
        $stmt = mysqli_prepare($link, "INSERT INTO trail_editors (trail_id, user_id) VALUES (?, ?)");

        // Binding parameters and executing statement, checking if $stmt has formed first
        if($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $trail_id, $user_info["user_id"]);
            mysqli_stmt_execute($stmt);
        }
    }

    print("<script>location = '../../trail.php?trail=".$trail_id."'</script>");

?>