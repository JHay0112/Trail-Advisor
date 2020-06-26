<?php

    /*

        res/handlers/newtrail.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Creates a new trail.

        Other Notes:

            N/a

    */

    // $token_required = true;
    $referral_path = "../../";
    $permitted_users = array("Admin", "Staff", "Standard");
    require("../initsession.php");
    require("../connect.php");
    require("../checkfields.php");

    check_fields($_POST, array("name", "description", "lat", "lng"), "../../createtrail.php");
    check_fields($_FILES, array("img"), "../../createtrail.php");

    $name = addslashes(strip_tags($_POST["name"]));
    $description = addslashes(strip_tags($_POST["description"]));
    $lat = (float)$_POST["lat"];
    $lng = (float)$_POST["lng"];
    $upload = $_FILES["img"];
    $image_type = image_type_to_mime_type(exif_imagetype($upload["tmp_name"]));
    $allowed_ext = array("image/jpeg", "image/png"); // Array of allowed image extensions

    // Check that it is actually an image and not just a file with .jpg or .png appended
    if(!in_array($image_type, $allowed_ext)) {
        print("<script>location = '../../createtrail.php?referral_case=invalidext'</script>");
        exit();
    }

    // Prepare statment
    $stmt = mysqli_prepare($link, "INSERT INTO `trails` (`name`, `creator`, `description`, `lat`, `lng`) VALUES (?, ?, ?, ?, ?);");

    // Binding parameters and executing statement, checking if $stmt has formed first
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "sisdd", $name, $user_info["user_id"], $description, $lat, $lng);
        mysqli_stmt_execute($stmt);
    }

    // If stmt executed properly it will affect 1 row
    if(mysqli_stmt_affected_rows($stmt) == 1) {

        $trail_id = mysqli_insert_id($link); // Gets ID of last inserted row

        // Uploading image

        $dest_dir = "../../img/trails/";
        $dest_file = $dest_dir.$trail_id.".jpg";

        // Creating image

        if($image_type == "image/jpeg") {
            $img = imagecreatefromjpeg($upload["tmp_name"]);
        } elseif($image_type == "image/png") {
            $img = imagecreatefrompng($upload["tmp_name"]);
        }

        // Set image to be web standard of 72dpi
        // REQUIRES PHP 7 >= 7.2.0
        imageresolution($img, 72);

        // Compress image and save to $dest_file
        imagejpeg($img, $dest_file, 40);

        // Redirect to trail page
        print("<script>location = '../../trail.php?trail=".$trail_id."'</script>");
        exit();
    } else {
        // No rows affected so return to create trail with fail referral case
        print("<script>location = '../../createtrail.php?referral_case=newtrailfail'</script>");
    }

?>