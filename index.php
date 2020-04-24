<?php

    /*

        index.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Landing page for site.

        Other Notes:

            N/a

    */

    // Page Attributes
    $page_attr = array(
        "title" => "Home",
        "author" => "Jordan Hay"
    );

    // Navigation
    $nav = array(
        "Home" => array(
            "href" => "index.php", 
            "classes" => "active"
        )
    );

    require_once("res/head.php");

?>



<?php require_once("res/foot.php"); ?>