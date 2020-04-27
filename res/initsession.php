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

?>