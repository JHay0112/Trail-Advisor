<?php

    /*

        res/head.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            File to be included to generate the "head" of a page.

        Other Notes:

            N/a

    */

    /* 
    
        Default page attributes, if a page attribute is not specified in the parent file it will be decided by these defaults

        title: Page title
        site_name: Name of the site
        author: Who wrote the code in the page (used in meta data as well)
        desc: Page description (for meta data)
        keywords: Keywords for meta data (SEO)
        stylesheeets: Stylesheets used in the page
        scripts: JavaScript resources to be loaded in the page
        favicon: Page favicon
        permitted_users: Types of users who are allowed on the page
        copyright: The copyright statement for the page footer
        class: A class that will be applied to elements throughout the page
        header_img: Path to image to be displayed as the header img

    */

    require_once("initsession.php");

    $def_page_attr = array(
        "title" => "Unknown Page",
        "site_name" => "TrailAdvisor",
        "author" => "Unknown",
        "desc" => "No Description",
        "keywords" => "Trails, Find, Create, Edit, Make, Like, Tracks, Walks, Hiking",
        "stylesheets" => array("css/styles.css", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"),
        "scripts" => array("js/lib/jquery.slim.min.js", "js/lib/parsley.min.js", "js/main.js"),
        "favicon" => "img/favicon.png",
        "permitted_users" => array("Admin", "Staff", "Standard", ""),
        "copyright" => "&copy; Jordan Hay 2020",
        "class" => "",
        "header_img" => "img/header.jpg"
    );

    // Default options for the navigation system
    $def_nav = array(
        "Home" => array(
            "href" => "index.php",
            "classes" => ""
        ),
        "Search" => array(
            "href" => "search.php",
            "classes" => ""
        ),
        "Sign Up" => array(
            "href" => "signup.php",
            "classes" => "right-align"
        ),
        "Login" => array(
            "href" => "login.php",
            "classes" => "right-align"
        )
    );

    // Only attempt array merge if page_attr exists
    if(isset($page_attr)) {
        $page_attr = array_merge($def_page_attr, $page_attr); // Fill page_attr with default values if they have not been set in page_attr
    } else {
        // If not set just set these as equal
        $page_attr = $def_page_attr;
    }

    // Only attempt array merge if nav exists
    if(isset($nav)) {
        $nav = array_merge($def_nav, $nav); // Fill nav with default values if they have not been set in nav
    } else {
        // If not set just set these as equal
        $nav = $def_nav;
    }

    // Check that admin is a permitted user, if not add it before something goes wrong
    if(!in_array("Admin", $page_attr["permitted_users"])) {
        array_push($page_attr["permitted_users"], "Admin");
    }

    // Actions to be taken if user is logged in
    if($logged_in) {

        // User is logged in, do not display the option to login
        unset($nav["Login"]);
        // Nor the option to sign up
        unset($nav["Sign Up"]);
        // Add the option for the user to view their own profile
        $nav += array("Profile" => array("href" => "profile.php?user_id=".$user_info["user_id"], "classes" => "right-align"));

        // Actions for staff higher users
        if(($user_info["user_type"] == "Staff") || ($user_info["user_type"] == "Admin")) {
            // Add the option for the user to navigate to the management page
            $nav += array("Create Trail" => array("href" => "createtrail.php", "classes" => "right-align"));
        }
    }

    // Checking if user has the correct authority level to view this page
    if(!in_array($user_info["user_type"], $page_attr["permitted_users"])) {
        // If user does not have required permissions, alert the user to the issue and redirect to login page. In case that script fails, although I don't think it could, stop all code execution with the exit statement
        if($logged_in) {
            // If the user is logged in send them to their profile
            print("<script>location = 'profile.php?referall_case=useroutofbounds';</script>");
        } else {
            // If user is not logged in send them to login
            print("<script>location = 'login.php?referral_case=useroutofbounds';</script>");
        }
        exit();
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Meta Data -->
        <meta charset="UTF-8" />
        <meta name="keywords" content="<?php print($page_attr["keywords"]) ?>" />
        <meta name="author" content="<?php print($page_attr["author"]) ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!-- Stylesheet(s) -->
        <?php 
            // For every stylesheet in the page attr it will create a new html link for it.
            for($i = 0; $i < sizeof($page_attr["stylesheets"]); $i++) {
                print('<link rel="stylesheet" type="text/css" href="'.$page_attr["stylesheets"][$i].'" />');
            } 
        ?>

        <!-- Tab Data -->
        <link rel="icon" href="<?php print($page_attr["favicon"]) ?>" />
        <title><?php print($page_attr["title"]." - ".$page_attr["site_name"]); ?></title>
    </head>
    <body>

        <header style="background-image: url('<?php print($page_attr["header_img"]); ?>');"></header>

        <nav class="col-12" id="nav">

            <a href="javascript:void(0);" id="nav-responsive-button" onclick="toggleResponsiveNav()"></a>

            <?php 

                // Iterate through printing nav items
                foreach($nav as $name => $item) {

                    // If the nav item hyperreference matched the URI of the page mark it as the active page.
                    // This if statement compares if the end of the request URI matches the href of the item to determine whether or not this page is active. It does have an additional edge case programmed in for when index.php is accessed from "/"
                    // strtok is used to remove the queries from the end of the url and href
                    if((!substr_compare(strtok($_SERVER["REQUEST_URI"], "?"), strtok($item["href"], "?"), -strlen(strtok($item["href"], "?")))) || (($item["href"] == "index.php") &&  (!substr_compare(strtok($_SERVER["REQUEST_URI"], "?"), "/", -strlen("/"))))) {
                        $item["classes"] .= " active";
                    }

                    print("<a href='".$item["href"]."' class='".$item["classes"]." anchor'>".$name."</a>");
                }
            ?>
        </nav>

        <!-- Spacer div -->
        <div class="col-2"></div>

        <main class="col-8" id="content">

            <?php print("<h1 class='".$page_attr["class"]."' id='page-title'>".$page_attr["title"]."</h1>"); ?>
