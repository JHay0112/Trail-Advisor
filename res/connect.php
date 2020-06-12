<?php

    /*

        res/connect.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Handles connection to database

        Other Notes:

            N/a

    */

    require("cred.php");

    $link = mysqli_connect($host, $username, $password, $db) or die("Couldn't connect to DB server.");

?>
