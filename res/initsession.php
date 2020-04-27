<?php

    /*

        res/initsession.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            File to be included to intialise a session.

        Other Notes:

            N/a

    */

    // Start session
    session_start();

    // Checking if users are logged in
    if(isset($_SESSION["user_info"])) {
        $user_info = $_SESSION["user_info"];
        $logged_in = true;
    } else {
        $user_info = array("user_type" => "");
        $logged_in = false;
    }

?>