<?php

    /*

        management/searchusers.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Search through users for staff.

        Other Notes:

            N/a

    */

    // Page Attributes
    $page_attr = array(
        "title" => "Search Users",
        "author" => "Jordan Hay",
        "permitted_users" => array("Admin", "Staff")
    );

    require_once("res/head.php");

    require_once("res/foot.php");

?>