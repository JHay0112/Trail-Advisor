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
        "permitted_users" => array("Admin", "Staff"),
        "stylesheets" => array("css/styles.css", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css", "http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css"),
        "scripts" => array("js/lib/jquery.slim.min.js", "js/lib/parsley.min.js", "js/main.js", "http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"),
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

                <label class="col-12" for="name">Trail Name:</label>
                <input type="text" name="name" maxlength="30" class="col-12" placeholder="Trail Name" required />

                <label class="col-12" for="description">Trail Description:</label>
                <textarea type="text" name="description" class="col-12" placeholder="Trail Description" required></textarea>

            </div>

            <div class="col-4 form-wrapper">

                <label class="col-12" for="img">Trail Image (PNG or JPG):</label>
                <input type="file" name="img" class="col-12" required />
            
            </div>
        
        </div>

    </fieldset>

    <fieldset class="col-12">

        <legend>Select Trail Location: </legend>

        <div class="col-12">

            <div id="map" name="map" class="col-12" style="height: 500px;"></div>

            <label for="lat" class="col-6">Latitude:</label>
            <label for="lng" class="col-6">Longitude:</label>
            <input id="lat" type="number" readonly name="lat" min="-90" max="90" class="col-6" placeholder="Latitude" required />
            <input id="lng" type="number" readonly name="lng" min="-180" max="180" class="col-6" placeholder="Longitude" required />

            <script>

                // Script for leaflet map to update from coords and vice versa
                // Adapted from: https://gist.github.com/answerquest/03ade545b071b3e5ea4e

                var tileLayer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> Contributors'
                });

                //remember last position
                var rememberLat = document.getElementById('lat').value;
                var rememberLong = document.getElementById('lng').value;
                if(!rememberLat || !rememberLong) {rememberLat = -43.53; rememberLong = 172.63;}

                var map = new L.Map('map', {
                    'center': [rememberLat, rememberLong],
                    'zoom': 12,
                    'layers': [tileLayer]
                });

                var marker = L.marker([rememberLat, rememberLong],{
                draggable: true
                }).addTo(map);

                marker.on('dragend', function (e) {
                    updateLatLng(marker.getLatLng().lat, marker.getLatLng().lng);
                });

                map.on('click', function (e) {
                    marker.setLatLng(e.latlng);
                    updateLatLng(marker.getLatLng().lat, marker.getLatLng().lng);
                });

                function updateLatLng(lat, lng, reverse) {
                    if(reverse) {
                        marker.setLatLng([lat,lng]);
                        map.panTo([lat,lng]);
                    } else {
                    document.getElementById('lat').value = marker.getLatLng().lat;
                    document.getElementById('lng').value = marker.getLatLng().lng;
                    map.panTo([lat,lng]);
                    }
                }

            </script>
        
        </div>

    </fieldset>

    <input type="hidden" name="token" value="<?php print($token); ?>" />

    <input type="submit" value="Create Trail" class="col-12" />

</form>

<?php

    require_once("res/foot.php");

?>