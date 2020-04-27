<?php

    /*

        profile.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Profile page with user information.

        Other Notes:

            N/a

    */

    // Page Attributes
    $page_attr = array(
        "author" => "Jordan Hay",
        "permitted_users" => array("Admin", "Staff", "Standard")
    );

    require_once("res/initsession.php");
    require_once("res/connect.php");

    if((isset($_GET["user_id"])) && ($_GET["user_id"] != $user_info["user_id"])) {
        // If the user accessing this page is not the user this page is about the user must be staff or admin
        $page_attr["permitted_users"] = array("Admin", "Staff");

        // Store the user id as an int, if it has been set as a non-int value in the url it will be set to be zero by this code.
        $user_id = settype($_GET["user_id"], "int");
        
        // If the user id is not zero, as zero means that something has gone wrong
        if($user_id != 0) {

            $stmt = mysqli_prepare($link, "SELECT `username`, `user_type` FROM `users` WHERE `id` = ?;");

            if($stmt) {
                mysqli_bind_param($stmt, "i", $user_id);
                mysqli_execute($stmt);
                mysqli_bind_result($stmt, $username, $user_type);
            }

            if(!isset($username)) {
                // If user has not been set then query was invalid thus ID was invalid
                $error = true;
            }

        } else {
            // User ID was zero thus ID invalid
            $error = true;
        }

        if($error) {
            $user_id = "Invalid";
            $username = "Unknown User";
            $user_type = "Invalid";
            $message = "User not found, account may have been deleted or the ID field may be incorrect.";
        }
    } else {
        // The user is viewing their on information, so load it from session variable
        $user_id = $user_info["user_id"];
        $username = $user_info["username"];
        $user_type = $user_info["user_type"];
    }

    $page_attr += array("title" => "Profile of ".$username);

    require_once("res/head.php");

    // Processing referrall cases
    require_once("res/referralcase.php");

    $states = array(
        "login" => "You have successfully been logged in as ".$username."."
    );

    if(isset($_GET["referral_case"])) {
        print("<p>".referral($_GET["referral_case"], $states)."</p>");
    }

    // If the system has generated a message it should be displayed
    if(isset($message)) {
        print("<p>".$message."</p>");
    }

    // Displaying the user's information
    print("<h3>".$user_type." User</h3>");
    print("<h3>User ID: ".$user_id."</h3>");

    // Action section
    print("<h2>Actions</h2>");

    // Actions for if the user is own
    if((!isset($_GET["user_id"])) || ($_GET["user_id"] == $user_info["user_id"])) {
        print("<a onclick='confirmAction(\"This will log you out.\", \"res/handlers/logoutuser.php?token=".$token."\");' href='javascript:void(0);'>Log out</a>");
    }

    require_once("res/foot.php"); 
    
?>