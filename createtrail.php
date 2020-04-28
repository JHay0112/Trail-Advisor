<?php

    /*

        management/createtrail.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Page for creating a new trail.

        Other Notes:

            N/a

    */

    // Page Attributes
    $page_attr = array(
        "title" => "Create Trail",
        "author" => "Jordan Hay",
        "permitted_users" => array("Admin", "Staff")
    );

    require_once("res/head.php");

    require_once("res/referralcase.php");

    $states = array(
        "invalidext" => "Invalid file uploaded, must be a JPG or PNG.",
        "newtrailfail" => "Failed to create a new trail, please try again."
    );

    if(isset($_GET["referral_case"])) {
        print("<p>".referral($_GET["referral_case"], $states)."</p>");
    }

?>

<div class="col-12" id="form-wrapper">
    <form class="col-12" data-parsley-validate action="res/handlers/newtrail.php" method="post" enctype="multipart/form-data">

        <input type="text" name="name" maxlength="30" class="col-12" placeholder="Trail Name" required />

        <input type="textarea" name="description" class="col-12" placeholder="Trail Description" required />

        <input type="number" name="lat" min="0" class="col-12" placeholder="Latitude" required />

        <input type="number" name="lng" min="0" class="col-12" placeholder="Longitude" required />

        <input type="file" name="img" required />

        <input type="hidden" name="token" value="<?php print($token); ?>" />

        <input type="submit" value="Create Trail" class="col-12" />

    </form>
</div>

<?php

    require_once("res/foot.php");

?>