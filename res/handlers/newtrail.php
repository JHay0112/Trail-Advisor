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

    $token_required = true;
    require_once("../initsession.php");
    require_once("../connect.php");

    $name = strip_tags($_POST["name"]);
    $description = strip_tags($_POST["description"]);
    $lat = $_POST["lat"];
    $lng = $_POST["lng"];
    $upload = $_FILES["img"];

    $ext = strtolower(pathinfo($upload["name"], PATHINFO_EXTENSION)); // Get file extension

    // Check that it is actually an image and not just a file with .jpg or .png appended
    if(($image_info["mime"] != "image/jpeg") || ($image_info["mime"] != "image/jpeg")) {
        // Redirect to createtrail
        print("<script>location = '../../createtrail.php?referral_case=invalidext'</script>");
        exit();
    }

    // Prepare statment
    $stmt = mysqli_prepare($link, "INSERT INTO `trails` (`name`, `creator`, `description`, `lat`, `lng`) VALUES (?, ?, ?, ?, ?);");

    // Binding parameters and executing statement, checking if $stmt has formed first
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "sisii", $name, $user_info["user_id"], $description, $lat, $lng);
        mysqli_stmt_execute($stmt);
    }

    // If stmt executed properly
    if(mysqli_stmt_affected_rows($stmt) == 1) {

        $trail_id = mysqli_insert_id($link); // Gets ID of last inserted row

        // Uploading image

        $dest_dir = "../../img/trails/";
        $dest_file = $dest_dir.$trail_id.".jpg";

        // Creating image

        $image_info = getimagesize($upload["tmp_name"]);

        switch($image_info["mime"]) {
            case("image/jpeg"):
                $img = imagecreatefromjpeg($upload["tmp_name"]);
            case("image/png"):
                $img = imagecreatefrompng($upload["tmp_name"]);
        }

        // Compressing
        imagejpeg($img, $dest_file, 60);

        // Redirect to trail page
        print("<script>location = '../../trail.php?trail=".$trail_id."'</script>");
        exit();
    }

    print("<script>location = '../../createtrail.php?referral_case=newtrailfail'</script>");

?>