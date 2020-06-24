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
        "permitted_users" => array("Admin", "Staff", "Standard"),
        "onload" => "genTrailMap(zoom = 12, select = true, additional_markers = [], geolocation = true);"
    );

    require("res/head.php");

    require("res/referralcase.php");

    $states = array(
        "invalidext" => "Invalid file uploaded, must be a JPG or PNG.",
        "newtrailfail" => "Failed to create a new trail, please try again.",
        "missingfields" => "Not all required fields were filled.",
        "traildeleted" => "Trail has been deleted."
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

                <label class="col-12" for="name">Trail Name:</label>
                <input type="text" id="name" name="name" maxlength="30" class="col-12" placeholder="Trail Name" required />

                <label class="col-12" for="description">Trail Description:</label>
                <textarea id="description" name="description" class="col-12" placeholder="Trail Description" required></textarea>

            </div>

            <div class="col-4 form-wrapper">

                <label class="col-12" for="img">Trail Image (PNG or JPG):</label>
                <input id="img" type="file" name="img" class="col-12" required />
            
            </div>
        
        </div>

    </fieldset>

    <fieldset class="col-12">

        <legend>Select Trail Location: </legend>

        <div class="col-12">

            <div class="col-12" class="map-wrapper">
                <a id="map-geolocation" class="button" href="#updatelocation">Find My Location&nbsp;&nbsp;<span class="fas fa-crosshairs"></a>
                <div id="trail-map"  class="col-12" ></div>
            </div>

            <div class="col-6 form-wrapper">
                <label for="lat" class="col-12">Latitude:</label>
                <input id="lat" type="number" name="lat" min="-90" max="90" step="any" class="col-12" placeholder="Latitude" data-parsley-trigger="keyup" data-parsley-type="number" value="-43.534" required />
            </div>

            <div class="col-6 form-wrapper">
                <label for="lng" class="col-12">Longitude:</label>
                <input id="lng" type="number" name="lng" min="-180" max="180" step="any" class="col-12" placeholder="Longitude" value="172.63" data-parsley-trigger="keyup" data-parsley-type="number" required />
            </div>
        
        </div>

    </fieldset>

    <input type="hidden" name="token" value="<?php print($token); ?>" />

    <input type="submit" value="Create Trail" class="col-12" />

</form>

<?php

    require("res/foot.php");

?>