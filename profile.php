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

    $error = false;

    // Have to explicitly set this early
    $permitted_users = $page_attr["permitted_users"];
    require("res/initsession.php");
    require("res/connect.php");

    if((isset($_GET["user_id"])) && ($_GET["user_id"] != $user_info["user_id"])) {
        // If the user accessing this page is not the user this page is about the user must be staff or admin
        $page_attr["permitted_users"] = array("Admin", "Staff");

        // Store the user id as an int, if it has been set as a non-int value in the url it will be set to be zero by this code.
        $user_id = (int)$_GET["user_id"];
        
        // If the user id is not zero, as zero means that something has gone wrong
        if($user_id != 0) {

            $stmt = mysqli_prepare($link, "SELECT `username`, `user_type` FROM `users` WHERE `user_id` = ?;");

            if($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $username, $user_type);
                mysqli_stmt_fetch($stmt);
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

    require("res/head.php");

    // Processing referrall cases
    require("res/referralcase.php");

    $states = array(
        "login" => "You have successfully been logged in as ".$username.".",
        "deleteduser" => "User successfully deleted!",
        "userpromoted" => "User has been promoted to Staff User.",
        "userdemoted" => "User has been demoted to Standard User.",
        "useroutofbounds" => "You do not have the required authority to view that page.",
        "missingfields" => "Required fields were missing."
    );

    if(isset($_GET["referral_case"])) {
        print("<p>".referral($_GET["referral_case"], $states)."</p>");
    }

    // Displaying the user's information
    print("<h3>".$user_type." User</h3>");
    print("<h3>User ID: ".$user_id."</h3>");

    // Do not display actions for non-existant users
    if($user_id != "Invalid") {
        // Action section
        print("<h2>Actions</h2>");

        // Actions for if the user is own
        if((!isset($_GET["user_id"])) || ($_GET["user_id"] == $user_info["user_id"])) {
            print("<a onclick='confirmAction(\"This will log you out.\", \"res/handlers/logoutuser.php?token=".$token."\");' href='javascript:void(0);' class='button'>Log out</a>");
            print("<a onclick='confirmAction(\"This will delete your account, associated trails, trail edits, and trail likes.\", \"res/handlers/deleteuser.php?token=".$token."\");' href='javascript:void(0);' class='button'>Delete Account</a>");
        } elseif((isset($_GET["user_id"])) && ($_GET["user_id"] != $user_info["user_id"]) && ($user_info["user_type"] == "Admin") && ($user_type != "Admin") && ($user_type != "Invalid")) {
            // Actions for if user is not own and not invalid and the logged in user is an admin
            print("<a onclick='confirmAction(\"This will delete the account of ".$username." and all associated trails, trail edits, and trail likes.\", \"res/handlers/deleteuser.php?token=".$token."&user_id=".$user_id."\");' href='javascript:void(0);' class='button'>Delete Account</a>");

            if($user_info["user_type"] == "Admin") {
                // Admin only options
                switch($user_type) {
                    case("Standard"):
                        print("<a href='res/handlers/promoteuser.php?user=".$user_id."&token=".$token."' class='button'>Promote User</a>");
                        break;
                    case("Staff"):
                        print("<a href='res/handlers/demoteuser.php?user=".$user_id."&token=".$token."' class='button'>Demote User</a>");
                        break;
                }

            }
        }
    }

    require("res/foot.php"); 
    
?>