<?php

    /*

        login.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Page for users to login to website.

        Other Notes:

            N/a

    */

    // Page Attributes
    $page_attr = array(
        "title" => "Login",
        "author" => "Jordan Hay"
    );

    require("res/head.php");
    
    require("res/referralcase.php");

    $states = array(
        "newuser" => "New user created! Please sign in with the credentials you created.",
        "useroutofbounds" => "The action you attempted to take is restricted to logged-in users, please login or sign up and retry.",
        "loginfail" => "Login Failed. Please try again.",
        "loggedout" => "You have been logged out.",
        "deleteduser" => "User successfully deleted!",
        "missingfields" => "Not all required fields were filled.",
        "logintolike" => "You must login or sign up if you would like to like trails."
    );

    if(isset($_GET["referral_case"])) {
        print("<p>".referral($_GET["referral_case"], $states)."</p>");
    }

?>

<div class="col-12" id="form-wrapper">
    <form class="col-12" data-parsley-validate action="res/handlers/loginuser.php" method="post">

        <label for="username" class="col-12">Username:</label>
        <input id="username" type="text" name="username" maxlength="30" class="col-12" placeholder="Username" required />

        <label for="password" class="col-12">Password:</label>
        <input id="password" type="password" name="password" minlength="6" maxlength="128" class="col-12" placeholder="Password" required />

        <input type="hidden" name="token" value="<?php print($token); ?>" />

        <input type="submit" value="Login" class="col-12" />

    </form>
</div>

<?php require("res/foot.php"); ?>