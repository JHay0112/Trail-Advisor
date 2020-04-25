<?php

    /*

        management/panel.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Management panel for staff users.

        Other Notes:

            N/a

    */

    // Page Attributes
    $page_attr = array(
        "title" => "Panel",
        "author" => "Jordan Hay",
        "permitted_users" => array("Admin", "Staff")
    );

    require_once("../res/head.php");

    require_once("../res/foot.php");

?>