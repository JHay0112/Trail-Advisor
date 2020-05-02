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

    $rows_to_load = 2;

?>

<!-- Search form -->
<div class="col-12" id="form-wrapper">
    <form class="col-12" action="search.php#search-results" method="get">

        <label for="map">Select location on map to find nearby trails:</label>
        <div id="trail-map" name="map" class="col-12" style="height: 500px;"></div>

        <div class="col-6 form-wrapper">
            <label for="lat" class="col-12">Latitude:</label>
            <input id="lat" type="number" name="lat" min="-90" max="90" step="any" class="col-12" placeholder="Latitude" value="<?php print($lat) ?>" required readonly />
            <label for="lng" class="col-12">Longitude:</label>
            <input id="lng" type="number" name="lng" min="-180" max="180" step="any" class="col-12" placeholder="Longitude" value="<?php print($lng) ?>" required readonly />
        </div>

        <div class="col-6 form-wrapper">
            <label for="trails_to_load" class="col-12">Trails per page:</label>
            <input id="trails_to_load" type="number" name="rows_to_load" min="20" max="100" step="1" class="col-12" placeholder="Trails To Load" value="<?php print($rows_to_load) ?>" required />

            <label>&nbsp;</label> <!-- Using this to get spacing correct -->
            <button type="submit" class="col-12"><span class="fas fa-search fa-flip-horizontal"></span> Search</button>
        </div>

    </form>
</div>

<h2 id="search-results">Search Results</h2>

<?php

    // Generating bounds for query
    $offset = $page * $rows_to_load;
    // Getting the total amount of trails in trails table, used for arrows at page bottom
    $total_rows = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(trail_id) AS count FROM trails"))["count"];
        
    // This is one hell of a prepared statment to try and explain
    $stmt = mysqli_prepare($link, "SELECT trails.trail_id, trails.name AS trail_name, users.username AS creator, COUNT(trail_likes.trail_id) AS likes FROM trails INNER JOIN users ON trails.creator = users.user_id LEFT JOIN trail_likes ON trails.trail_id = trail_likes.trail_id GROUP BY trails.trail_id ORDER BY (ABS(trails.lat - ?) + ABS(trails.lng - ?)) ASC LIMIT ? OFFSET ?");

    // Check statement formed correctly
    if($stmt) {

        mysqli_stmt_bind_param($stmt, "ddii", $lat, $lng, $rows_to_load, $offset);
        mysqli_stmt_execute($stmt);
        $rows = mysqli_stmt_get_result($stmt);

    }

    // Check that rows have been filled
    if(isset($rows)) {

        print("<table class='col-12'>");

        print("<tr class='row-s'><th class='col-s-6'>Trail Name</th><th class='col-s-4'>Creator</th><th class='col-s-2 fas fa-thumbs-up'></th></tr>");

        foreach($rows as $row) {
            print("<tr class='row-s' onclick='window.location=\"trail.php?trail=".$row["trail_id"]."\"'>");
            print("<td class='col-s-6'>".$row["trail_name"]."</td>");
            print("<td class='col-s-4'>".$row["creator"]."</td>");
            print("<td class='col-s-2'>".$row["likes"]."</td>");
            print("</tr>");
        }

        print("</table>");

    } else {
        print("<p>No Results</p>");
    }

    // Generating buttons for the user to click to navigate results
    print("<div class='col-12' id='search-controls'>");

    // If the page is greater than zero allow to navigate back
    if($page > 0) {
        print("<a class=\"navigate-result button\" href=\"search.php?page=".($page - 1)."&load=".$rows_to_load."&lat=".$lat."&lng=".$lng."#search-controls\"><span class=\"fa fa-arrow-left\"></span> Previous Page</a>");
    }

    // If the amount of rows to load on the next page does not exceed the total rows in the trail then allow the user to access the next page
    if((($rows_to_load * ($page + 1)) < $total_rows)) {
        print("<a class=\"navigate-result button float-right\" href=\"search.php?page=".($page + 1)."&load=".$rows_to_load."&lat=".$lat."&lng=".$lng."#search-controls\">NextPage <span class=\"fa fa-arrow-right\"></span></a>");
    }

    print("</div>");

    require_once("res/foot.php");

?>