<?php

    /*

        edittrail.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Allow admin and staff users to edit trails.

        Other Notes:

            N/a

    */

    require("res/connect.php");
    require("res/checkfields.php");

    // If these fields aren't there send back to search page
    check_fields($_GET, array("trail"), "../../search.php");

    $error = false;

    // Cast to int
    $trail_id = (int)$_GET["trail"];

    if($trail_id != 0) {
        $stmt = mysqli_prepare($link, "SELECT name, creator, description, lat, lng FROM trails WHERE trail_id = ? GROUP BY trail_id");

        // Check stmt formed
        if($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $trail_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $trail_name, $creator_id, $trail_description, $lat, $lng);
            mysqli_stmt_fetch($stmt);
        } else {
            $error = true;
        }
    } else {
       $error = true;
    }

    if(!isset($trail_name)) {
        $error = true;
    } 

    // Error handling 
    if($error) {
        $trail_name = "Unknown Trail";
        $trail_description = "This trail does not exist!";
        $creator_id = 0;
        $lat = 0;
        $lng = 0;
    }

    // Page Attributes
    $page_attr = array(
        "title" => "Edit Trail",
        "author" => "Jordan Hay",
        "permitted_users" => array("Admin", "Staff", "Standard"),
        "onload" => "genTrailMap(zoom = 12, select = true);"
    );

    // Add page to nav
    $nav = array($page_attr["title"] => array("href" => "edittrail.php?trail=".$trail_id, "classes" => ""));

    require("res/head.php");

    // Check if the user is the trail creator or a staff member
    if(($user_info["user_id"] != $creator_id) && (!in_array($user_info["user_type"], array("Admin", "Staff")))) {
        // If not then redirect them
        print("<script>location = '".$referral_path."profile.php?referral_case=useroutofbounds';</script>");
        exit();
    }

    require("res/referralcase.php");

    $states = array(
        "edittrailfail" => "Failed to edit trail, please try again.",
        "missingfields" => "Not all required fields were filled.",
        "invalidname" => "Trail name contains values that are not english letters, numbers, apostrophes, or spaces.",
        "invalidtrail" => "The trail you tried to edit was invalid."
    );

    if(isset($_GET["referral_case"])) {
        print("<p>".referral($_GET["referral_case"], $states)."</p>");
    }

?>

<form class="col-12 form-wrapper" data-parsley-validate action="res/handlers/updatetrail.php" method="post" enctype="multipart/form-data">

    <fieldset class="col-12">

        <legend>Edit General Trail Information:</legend>

        <label class="col-12" for="name">Trail Name:</label>
        <input type="text" id="name" name="name" maxlength="30" class="col-12" placeholder="Trail Name" value="<?php print($trail_name); ?>" />

        <label class="col-12" for="description">Trail Description:</label>
        <textarea id="description" name="description" class="col-12" placeholder="Trail Description" required><?php print($trail_description); ?></textarea>

    </fieldset>

    <fieldset class="col-12">

        <legend>Edit Trail Location: </legend>

        <div class="col-12">

            <div id="trail-map"  class="col-12" ></div>

            <div class="col-6 form-wrapper">
                <label for="lat" class="col-12">Latitude:</label>
                <input id="lat" type="number" name="lat" min="-90" max="90" step="any" class="col-12" placeholder="Latitude" value="<?php print($lat); ?>" data-parsley-trigger="keyup" data-parsley-type="number" required />
            </div>

            <div class="col-6 form-wrapper">
                <label for="lng" class="col-12">Longitude:</label>
                <input id="lng" type="number" name="lng" min="-180" max="180" step="any" class="col-12" placeholder="Longitude" value="<?php print($lng); ?>" data-parsley-trigger="keyup" data-parsley-type="number" required />
            </div>
        
        </div>

    </fieldset>

    <input type="hidden" name="trail_id" value="<?php print($trail_id); ?>" />
    <input type="hidden" name="token" value="<?php print($token); ?>" />

    <input type="submit" value="Edit Trail" class="col-12" <?php if($error) {print("disabled");} // If an error has occured disable this button ?> />

</form>

<?php require("res/foot.php"); ?>