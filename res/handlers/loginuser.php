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

    require_once("../connect.php");

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

        // Redirect to home page
        print("
            <script>
                alert('Logged in! Redirecting to home page.');
                location = '../../index.php';
            </script>        
        ");
    } else {

        // Redirect back to login
        print("
            <script>
                alert('Login failed. Please try again.');
                location = '../../login.php';
            </script>        
        ");
    }

?>