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

    // Check if referral_path has been set
    // Used to offset path for handlers etc
    if(!isset($referral_path)) {
        $referral_path = "";
    }

    // Check if token_required has been set
    if(!isset($token_required)) {
        $token_required = false;
    }

    // Checking CSRF token
    if(isset($_SESSION["token"])) {
        if(isset($_POST["token"])) {
            $invalid_token = strcmp($_SESSION["token"], $_POST["token"]);
        } elseif(isset($_GET["token"])) {
            $invalid_token = strcmp($_SESSION["token"], $_GET["token"]);
        } elseif($token_required) {
            $invalid_token = true;
        } else {
            $invalid_token = false;
        }
    } elseif($token_required) {
        $invalid_token = true;
    } else {
        $invalid_token = false;
    }

    // Generate a new token, we want a new one of these regardless of outcome
    $token = bin2hex(openssl_random_pseudo_bytes(24));
    // Store it in the session
    $_SESSION["token"] = $token;

    // Generating error message
    if($invalid_token) {
        print("<script>alert('Invalid Token! Stopping page generation.');</script>");
        exit();
    }

    // Checking if users are logged in
    if(isset($_SESSION["user_info"])) {
        $user_info = $_SESSION["user_info"];
        $logged_in = true;
    } else {
        $user_info = array("user_type" => "");
        $logged_in = false;
    }
    
    // Checking if user has the correct authority level to view this page
    if(!in_array($user_info["user_type"], $permitted_users)) {
        // If user does not have required permissions, alert the user to the issue and redirect to login page. In case that script fails, although I don't think it could, stop all code execution with the exit statement
        if($logged_in) {
            // If the user is logged in send them to their profile
            print("<script>location = '".$referral_path."profile.php?referral_case=useroutofbounds';</script>");
        } else {
            // If user is not logged in send them to login
            print("<script>location = '".$referral_path."login.php?referral_case=useroutofbounds';</script>");
        }
        exit();
    }

?>