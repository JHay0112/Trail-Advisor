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
            $stmt = mysqli_prepare($link, "SELECT trails.name, users.user_id, users.username, trails.description, trails.lat, trails.lng, COUNT(trail_likes.trail_id) FROM trails INNER JOIN users ON trails.creator = users.user_id LEFT JOIN trail_likes ON trails.trail_id = trail_likes.trail_id WHERE trails.trail_id = ?;");

            // Check stmt is not malformed
            if($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $trail_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $trail_name, $creator_id, $creator, $trail_description, $lat, $lng, $likes);
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
                
                // Below code is used to simulate a get result which is not supported without mysqlnd
               
                mysqli_stmt_bind_result($stmt, $id, $name);
        
                $editors = array();

                while(mysqli_stmt_fetch($stmt)) {
                    array_push($editors, array(
                        "user_id" => $id,
                        "username" => $name
                    ));
                }
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
        $likes = 0;
        $trail_img = "img/header.jpg";
    }

    $page_attr["title"] = $trail_name; // Get page title to match trail name
    $page_attr["header_img"] = $trail_img;
    $page_attr["copyright"] = "Trail details supplied by ".$creator.", Site: &copy; TrailAdvisor 2020";
    $page_attr["onload"] = "genTrailMap(15);";
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
        <?php 

            // Likes button
            if(($logged_in) && (!$error)) {
                // Check if user has liked this trail

                $stmt = mysqli_prepare($link, "SELECT trail_id FROM trail_likes WHERE trail_id = ? AND user_id = ?");

                // Binding parameters and executing statement, checking if $stmt has formed first
                if($stmt) {
                    mysqli_stmt_bind_param($stmt, "ii", $trail_id, $user_info["user_id"]);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                }

                // If user has not liked this trail, give them the oppurtunity to like it
                if(mysqli_stmt_num_rows($stmt) == 0) {
                    print("<a href='res/handlers/togglelike.php?trail=".$trail_id."&token=".$token."' class='button'><span class='far fa-thumbs-up'></span> ".$likes." Likes</a>");
                } else {
                    print("<a href='res/handlers/togglelike.php?trail=".$trail_id."&token=".$token."' class='button'><span class='fas fa-thumbs-up'></span> ".$likes." Likes</a>");
                }
            } elseif(!$error) {
                print("<a class='button' href='login.php?referral_case=logintolike'><span class='far fa-thumbs-up'></span> ".$likes." Likes</a>");
            }
 
        ?>
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
            if(count($editors) != 0) {

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

        ?>

        <p><?php print($trail_description); ?></p>

    </section>

    <aside id="trail-img" class="col-4" style="background-image: url('<?php print($trail_img); ?>');"></aside>

</div>

<div id="trail-map" name="map" class="col-12" style="height: 500px;"></div>

<div class="col-6 form-wrapper">
    <label for="lat" class="col-12">Latitude:</label>

    <input id="lat" type="number" name="lat" min="-90" max="90" step="any" class="col-12" placeholder="Latitude" value="<?php print($lat) ?>" readonly />
</div>

<div class="col-6 form-wrapper">
    <label for="lng" class="col-12">Longitude:</label>

    <input id="lng" type="number" name="lat" min="-90" max="90" step="any" class="col-12" placeholder="Longitude" value="<?php print($lng) ?>" readonly />
</div>


<?php 

    if(in_array($user_info["user_type"], array("Admin", "Staff"))) {
        // If the user is staff or admin give them the option to edit or delete the trail
        print("<section class='col-12'>");
        print("<h2>Actions</h2>");
        print("<a href='edittrail.php?trail=".$trail_id."' class='button'>Edit Trail</a>");
        print("<a onclick='confirmAction(\"This will delete this trail.\", \"res/handlers/deletetrail.php?trail=".$trail_id."&token=".$token."\");' href='javascript:void(0);' class='button'>Delete Trail</a>");
        print("</section>");
    }

    require_once("res/foot.php"); 
    
?>