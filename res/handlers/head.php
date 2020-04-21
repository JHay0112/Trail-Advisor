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

    // Default page attributes, if a page attribute is not specified in the parent file it will be decided by these defaults
    $def_page_attr = array(
        "title" => "Unknown Page",
        "site_name" => "TrailAdvisor",
        "author" => "Unknown",
        "path" => "Unknown",
        "desc" => "No Description",
        "keywords" => "",
        "stylesheets" => array("css/styles.css", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"),
        "scripts" => array("js/main.js"),
        "favicon" => "img/favicon.png",
        "authority_levels" => array("Standard", "Staff", "Administrator");
        "minimum_authority" => "";
    );

    $page_attr = array_merge($def_page_attr, $page_attr); // Fill page_attr with default values if they have not been set in page_attr

?>