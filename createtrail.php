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

<form class="col-12 form-wrapper" data-parsley-validate action="res/handlers/newtrail.php" method="post" enctype="multipart/form-data">

    <fieldset class="col-12">

        <legend>General Trail Information:</legend>

        <div class="row">

            <div class="col-8 form-wrapper">

                <input type="text" name="name" maxlength="30" class="col-12" placeholder="Trail Name" required />

                <textarea type="text" name="description" class="col-12" placeholder="Trail Description" required></textarea>

            </div>

            <div class="col-4 form-wrapper">

                <label class="col-12" for="img">Trail Image (PNG or JPG):</label>
                <input type="file" name="img" class="col-12" required />
            
            </div>
        
        </div>

    </fieldset>

    <fieldset class="col-12">

        <legend>Trail Location: </legend>

        <div class="row">

            <div class="col-6 form-wrapper">

                <input type="number" name="lat" min="0" class="col-12" placeholder="Latitude" required />

                <input type="number" name="lng" min="0" class="col-12" placeholder="Longitude" required />

            </div>

            <div class="col-6 form-wrapper">

                <!-- Map -->
            
            </div>
        
        </div>

    </fieldset>

    <input type="submit" value="Create Trail" class="col-12" />


    <input type="hidden" name="token" value="<?php print($token); ?>" />

</form>

<?php

    require_once("res/foot.php");

?>