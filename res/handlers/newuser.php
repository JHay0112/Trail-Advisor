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

    require_once("../connect.php");

    $username = strip_tags($_POST["username"]); // Stripping HTML from user name so that it does not cause issues when placed in page.
    $password = $_POST["password"];

    // Generate salte for user password
    $salt = md5(microtime(true)*1000000);

    // Hash password and salt together
    $hash = hash("sha256", $password.$salt);

    // Adding information into the database

    // Prepare statment
    $stmt = mysqli_prepare($link, "INSERT INTO users (username, hash, salt) VALUES (?, ?, ?);");

    // Binding parameters and executing statement, checking if $stmt has formed first
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $username, $hash, $salt);
        mysqli_stmt_execute($stmt);
    }

    // Alert user and redirect to login
    if (mysqli_stmt_affected_rows($stmt) == 1) {
        print("
            <script>
                alert('New user created, redirecting to login page.');
                location = '../../login.php';
            </script>        
        ");
    } else {
        print("fuck");
    }

?>