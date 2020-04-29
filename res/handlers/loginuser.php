<?php

    /*

        res/handlers/loginuser.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Logs the user out.

        Other Notes:

            N/a

    */

    $token_required = true;
    require_once("../initsession.php"); // For CSRF protection
    require_once("../connect.php");
    require_once("../checkfields.php");

    check_fields($_POST, array("username", "password"), "../../login.php");

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare DB query
    $stmt = mysqli_prepare($link, "SELECT * FROM `users` WHERE `username` = ?;");

    // Binding parameters and executing statement, checking if $stmt has formed first
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $user_id, $username, $hash, $salt, $user_type);
    } 

    while(mysqli_stmt_fetch($stmt)) {
        $input_hash = hash("sha256", $password.$salt);
    }

    // If the hash's are equal then tell the user it's valid
    if(hash_equals($hash, $input_hash)) {

        // Insert user info into session
        session_start();
        $_SESSION["user_info"] = array(
            "user_id" => $user_id,
            "username" => $username,
            "user_type" => $user_type
        );

        // Redirect to profile page
        print("<script>location = '../../profile.php?referral_case=login';</script>");
    } else {
        // Redirect back to login
        print("<script>location = '../../login.php?referral_case=loginfail';</script>");
    }

?>