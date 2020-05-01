<?php

    /*

        trail.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Trail information.

        Other Notes:

            N/a

    */

    // Page Attributes
    $page_attr = array(
        "title" => "Trail Name",
        "author" => "Jordan Hay",
        "class" => "trail",
        "header_img" => "../img/header.jpg",
        "onload" => ""
    );

    require_once("res/connect.php");

    $error = false;

    // Check if the trail id has been set
    if(isset($_GET["trail"])) {
        // Set the trail id to be an int, any non int value in it will become zero
        $trail_id = (int)$_GET["trail"];

        if($trail_id != 0) {

            // Getting trail details
            $stmt = mysqli_prepare($link, "SELECT trails.name, users.user_id, users.username, trails.description, trails.lat, trails.lng FROM trails INNER JOIN users ON trails.creator = users.user_id WHERE trails.trail_id = ?;");

            // Check stmt is not malformed
            if($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $trail_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $trail_name, $creator_id, $creator, $trail_description, $lat, $lng);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
            }

            // Setting image path
            $trail_img = "img/trails/".$trail_id.".jpg";

            // Getting trail editor details, having a seperate query saves the database returning the same trail details multiple times
            $stmt = mysqli_prepare($link, "SELECT users.user_id, users.username FROM trail_editors INNER JOIN users ON trail_editors.user_id = users.user_id WHERE trail_editors.trail_id = ?;");

            // Check stmt is not malformed
            if($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $trail_id);
                mysqli_stmt_execute($stmt);
                $editors = mysqli_stmt_get_result($stmt); // Store result in associative array
            }

            mysqli_stmt_close($stmt);

        } else {
            // User ID was zero thus invalid
            $error = true;
        }
    } else {
        // No trail id was set
        $error = true;
    }

    // If an error has occured then we must fill the variables
    if($error) {
        $trail_id = 0;
        $trail_name = "Unknown Trail";
        $creator_id = 0;
        $creator = "Unknown";
        $trail_description = "This trail does not exist in our database.";
        $lat = 0;
        $lng = 0;
        $trail_img = "img/header.jpg";
    }

    $page_attr["title"] = $trail_name; // Get page title to match trail name
    $page_attr["header_img"] = $trail_img;
    $page_attr["onload"] = "genTrailMap(15, false, ".$lat.", ".$lng.");";
    // Nav Item
    $nav = array(
        $trail_name => array(
            "href" => "trail.php",
            "classes" => ""
        )
    );

    require_once("res/head.php");

?>

<div class="row">

    <section class="col-8">

        <h1><?php print($trail_name); ?></h1>
        <h4>Trail documented by: 
        <?php 
        
            if(in_array($user_info["user_type"], array("Admin", "Staff"))) {
                // If staff member is accessing page then give the staff links to user profiles
                print("<a href='profile.php?user_id=".$creator_id."'>".$creator."</a>");
            } else {
                // Print username without links for normal members
                print($creator);
            }
            
        ?>
        </h4>
        <?php 

            // If no one has edited the page then do not show that anyone has
            if(isset($editors)) {
                if(mysqli_num_rows($editors) != 0) {

                    print("<h4>Trail edited by: ");
            
                    foreach($editors as $editor) {
                        if(in_array($user_info["user_type"], array("Admin", "Staff"))) {
                            // If staff member is accessing page then give the staff links to user profiles
                            print("<a href='profile.php?user_id=".$editor["user_id"]."'>".$editor["username"]."</a> ");
                        } else {
                            // Print username without links
                            print($editor["username"]." ");
                        }
                    }

                    print("</h4>");

                }
            }
        ?>

        <p><?php print($trail_description); ?></p>

    </section>

    <aside class="col-4" style="background-image: url('<?php print($trail_img); ?>');"></aside>

</div>

<div id="trail-map" name="map" class="col-12" style="height: 500px;"></div>

<?php 

    if(in_array($user_info["user_type"], array("Admin", "Staff"))) {
        // If the user is staff or admin give them the option to edit or delete the trail
        print("<section class='col-12'>");
        print("<h2>Actions</h2>");
        print("<a href='edittrail.php?trail=".$trail_id."'>Edit Trail</a>");
        print("<a onclick='confirmAction(\"This will delete this trail.\", \"res/handlers/deletetrail.php?trail=".$trail_id."&token=".$token."\");' href='javascript:void(0);'>Delete Trail</a>");
        print("</section>");
    }

    require_once("res/foot.php"); 
    
?>