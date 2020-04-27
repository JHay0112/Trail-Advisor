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

    require_once("res/head.php");
    
    require_once("res/referralcase.php");

    $states = array(
        "newuser" => "New user created! Please sign in with the credentials you created.",
        "useroutofbounds" => "Access to the page you attempted to access is not permitted at all for non-logged in users.<br />You can login using this page if you have an account that has the correct permissions to access that page.",
        "loginfail" => "Login Failed. Please try again.",
        "loggedout" => "You have been logged out."
    );

    if(isset($_GET["referral_case"])) {
        print("<p>".referral($_GET["referral_case"], $states)."</p>");
    }

?>

<div class="col-12" id="form-wrapper">
    <form class="col-12" data-parsley-validate action="res/handlers/loginuser.php" method="post">

        <input type="text" name="username" maxlength="30" class="col-12" placeholder="Username" required />

        <input id="password" type="password" name="password" minlength="6" maxlength="30" class="col-12" placeholder="Password" required />

        <input type="submit" value="Submit" class="col-12" />

    </form>
</div>

<?php require_once("res/foot.php"); ?>