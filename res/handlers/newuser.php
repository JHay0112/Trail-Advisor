<?php

    /*

        res/handlers/newuser.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Creates a new site user.

        Other Notes:

            N/a

    */

    $token_required = true;
    $permitted_users = array("Admin", "Staff", "Standard", "");
    require_once("../initsession.php");
    require_once("../connect.php");
    require_once("../checkfields.php");

    check_fields($_POST, array("username", "password"), "../../signup.php");

    $username = strip_tags($_POST["username"]); // Stripping HTML from user name so that it does not cause issues when placed in page.
    $password = $_POST["password"];

    // If username is not alphanumeric and between 5 and 30 characters then redirect back to signup
    if(!preg_match('/^[a-zA-Z0-9]{5, 30}$/', $username)) {
        print("<script>location = '../../signup.php?referral_case=invalidusername';</script>");
    }

    // Check that username is unique here
    $stmt = mysqli_prepare($link, "SELECT COUNT(`user_id`) FROM `users` WHERE `username` = ?;");

    if($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $count = mysqli_stmt_store_result($stmt);

        // If the count of users is more than zero that username is already taken
        if($count > 0) {
            print("<script>location = '../../signup.php?referral_case=usernametaken';</script>");
            exit();
        }

        mysqli_stmt_close($stmt);
    }

    // Generate salt for user password
    $salt = md5(microtime(true)*1000000);

    // Hash password and salt together
    $hash = hash("sha256", $password.$salt);

    // Adding information into the database

    // Prepare statment
    $stmt = mysqli_prepare($link, "INSERT INTO users (`username`, `hash`, `salt`) VALUES (?, ?, ?);");

    // Binding parameters and executing statement, checking if $stmt has formed first
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $username, $hash, $salt);
        mysqli_stmt_execute($stmt);
    }

    // Alert user and redirect to login
    if (mysqli_stmt_affected_rows($stmt) == 1) {
        print("<script>location = '../../login.php?referral_case=newuser';</script>");
    } else {
        print("<script>location = '../../signup.php?referral_case=newuserfailed';</script>");
    }

?>