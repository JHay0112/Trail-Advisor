<?php

    /*

        search.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Search through trails.

        Other Notes:

            N/a

    */

    // Page Attributes
    $page_attr = array(
        "title" => "Search",
        "author" => "Jordan Hay",
        "onload" => "genTrailMap(zoom = 12, select = true);"
    );

    require_once("res/connect.php");
    require_once("res/head.php");

    // Checking and setting longitude, latititude, page, and rows to load

    if(isset($_GET["lng"])) {
        $lng = (double)$_GET["lng"];
    } else {
        $lng = 172.63;
    }

    if(isset($_GET["lat"])) {
        $lat = (double)$_GET["lat"];
    } else {
        $lat = -43.53;
    }

    if(isset($_GET["page"])) {
        $page = (int)$_GET["page"];
    } else {
        $page = 0;
    }

    if(isset($_GET["rows_to_load"])) {
        $rows_to_load = (int)$_GET["rows_to_load"];

        // Rows to load cannot be less than 20
        if($rows_to_load < 20) {
            $rows_to_load = 20;
        }
    } else {
        $rows_to_load = 20;
    }

?>

<!-- Search form -->
<div class="col-12" id="form-wrapper">
    <form class="col-12" action="search.php#search-results" method="get">

        <label for="map">Select location on map to find nearby trails:</label>
        <div id="trail-map" name="map" class="col-12" style="height: 500px;"></div>

        <div class="col-6 form-wrapper">
            <label for="lat" class="col-12">Latitude:</label>
            <input id="lat" type="number" name="lat" min="-90" max="90" step="any" class="col-12" placeholder="Latitude" value="<?php print($lat) ?>" required readonly />
        </div>

        <div class="col-6 form-wrapper">
            <label for="lng" class="col-12">Longitude:</label>
            <input id="lng" type="number" name="lng" min="-180" max="180" step="any" class="col-12" placeholder="Longitude" value="<?php print($lng) ?>" required readonly />
        </div>

        <input type="submit" value="Search" class="col-12" />

    </form>
</div>

<h2 id="search-results">Search Results</h2>

<?php

    // Generating bounds for query
    $lower_limit = $page * $rows_to_load;
    $upper_limit = ($page + 1) * $rows_to_load;
        
    // This is one hell of a prepared statment to try and explain
    $stmt = mysqli_prepare($link, "SELECT trails.trail_id, trails.name AS trail_name, users.username AS creator, COUNT(trail_likes.trail_id) AS likes FROM trails INNER JOIN users ON trails.creator = users.user_id LEFT JOIN trail_likes ON trails.trail_id = trail_likes.trail_id GROUP BY trails.trail_id ORDER BY (ABS(trails.lat - ?) + ABS(trails.lng - ?)) ASC LIMIT ?, ?");

    // Check statement formed correctly
    if($stmt) {

        mysqli_stmt_bind_param($stmt, "ddii", $lat, $lng, $lower_limit, $upper_limit);
        mysqli_stmt_execute($stmt);
        $rows = mysqli_stmt_get_result($stmt);

    }

    // Check that rows have been filled
    if(isset($rows)) {

        print("<table class='col-12'>");

        print("<tr><th>Trail Name</th><th>Creator</th><th>Likes</th></tr>");

        foreach($rows as $row) {
            print("<tr onclick='window.location=\"trail.php?trail=".$row["trail_id"]."\"'>");
            print("<td>".$row["trail_name"]."</td>");
            print("<td>".$row["creator"]."</td>");
            print("<td>".$row["likes"]."</td>");
            print("</tr>");
        }

        print("</table>");

    } else {
        print("<p>No Results</p>");
    }

    require_once("res/foot.php");

?>