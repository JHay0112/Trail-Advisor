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

    $token_required = true;
    $referral_path = "../../";
    $permitted_users = array("Admin", "Staff", "Standard");
    require_once("../initsession.php");

    // Remove the session variables, this logs out the user.
    session_unset();

    // Now alert user they have been logged out.
    print("<script>location = '../../login.php?referral_case=loggedout';</script>");

?>