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

    $link = mysqli_connect(
        "localhost", 
        "root", 
        "", 
        "trailadvisor"
    ) or die("Couldn't connect to DB server.");

?>
