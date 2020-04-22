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
        path: Path to the page as shown in url
        desc: Page description (for meta data)
        keywords: Keywords for meta data (SEO)
        stylesheeets: Stylesheets used in the page
        scripts: JavaScript resources to be loaded in the page
        favicon: Page favicon
        permitted_users: Types of users who are allowed on the page

    */

    $def_page_attr = array(
        "title" => "Unknown Page",
        "site_name" => "TrailAdvisor",
        "author" => "Unknown",
        "path" => "Unknown",
        "desc" => "No Description",
        "keywords" => "Trails, Find, Create, Edit, Make, Like, Tracks, Walks, Hiking",
        "stylesheets" => array("css/styles.css", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"),
        "scripts" => array("js/main.js"),
        "favicon" => "img/favicon.png",
        "permitted_users" => array("Admin", "Staff", "Standard", "")
    );

    $page_attr = array_merge($def_page_attr, $page_attr); // Fill page_attr with default values if they have not been set in page_attr

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

        <!-- Script(s) -->
        <?php 
            // For every script in the page attr it will create a new html script tage for it.
            for($i = 0; $i < sizeof($page_attr["scripts"]); $i++) {
                print('<script src="'.$page_attr["scripts"][$i].'"></script>');
            }
        ?>

        <!-- Tab Data -->
        <link rel="icon" href="<?php print($page_attr["favicon"]) ?>" />
        <title><?php print($page_attr["title"]." - ".$page_attr["site_name"]); ?></title>
    </head>
    <body>