<?php

    /*

        res/handlers/logoutuser.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Logs current user out.

        Other Notes:

            N/a

    */

    require_once("../initsession.php");

    // Remove the session variables, this logs out the user.
    session_unset();

    // Now alert user they have been logged out.
    print("
        <script>
            alert('Logged out. Redirecting to home page.');
            location = '../../index.php';
        </script>
    ");

?>