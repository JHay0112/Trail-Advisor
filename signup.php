<?php

    /*

        signup.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Page for users to signup to website.

        Other Notes:

            N/a

    */

    // Page Attributes
    $page_attr = array(
        "title" => "Sign Up",
        "author" => "Jordan Hay"
    );

    require_once("res/head.php");

?>

<h1>Sign Up</h1>

<?php 

    require_once("res/referralcase.php");

    $states = array(
        "newuserfailed" => "Creation of a new user failed. Please try again."
    );

    if(isset($_GET["referral_case"])) {
        print("<p>".referral($_GET["referral_case"], $states)."</p>");
    }

?>

<div class="col-12" id="form-wrapper">
    <form class="col-12" data-parsley-validate action="res/handlers/newuser.php" method="post">

        <input type="text" name="username" maxlength="30" class="col-12" placeholder="Username" required />

        <input id="password" type="password" name="password" minlength="6" maxlength="30" class="col-12" placeholder="Password" required />

        <input type="password" name="password-validation" minlength="6" maxlength="30" class="col-12" placeholder="Password Again" data-parsley-equalto="#password" required />

        <input type="submit" value="Submit" class="col-12" />

    </form>
</div>

<?php require_once("res/foot.php"); ?>