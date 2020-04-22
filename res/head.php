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
        "keywords" => "",
        "stylesheets" => array("css/styles.css", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"),
        "scripts" => array("js/main.js"),
        "favicon" => "img/favicon.png",
        "permitted_users" => array("Admin", "Staff", "Standard", "")
    );

    $page_attr = array_merge($def_page_attr, $page_attr); // Fill page_attr with default values if they have not been set in page_attr

?>