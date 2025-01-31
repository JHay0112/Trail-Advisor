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

    require("res/head.php"); 

    require("res/referralcase.php");

    $states = array(
        "newuserfailed" => "Creation of a new user failed. Please try again.",
        "missingfields" => "Not all required fields were filled.",
        "usernametaken" => "That username has already been taken by another user.",
        "invalidusername" => "Username must be alphanumeric (only english letters and numbers), and be between 5 and 30 characters long."
    );

    if(isset($_GET["referral_case"])) {
        print("<p>".referral($_GET["referral_case"], $states)."</p>");
    }

?>

<p>Requirements:</p>

<ul>
    <li>Username must be between 5 and 30 characters long.</li>
    <li>Username must be alphanumeric (consisting of only english letters and numbers).</li>
    <li>Password must be between 6 and 128 characters long.</li>
</ul>

<div class="col-12" id="form-wrapper">
    <form class="col-12" data-parsley-validate action="res/handlers/newuser.php" method="post">

        <label for="username" class="col-12">Username:</label>
        <input id="username" type="text" name="username" minlength="5" data-parsley-type="alphanum" maxlength="30" class="col-12" placeholder="Username" required />

        <label for="password" class="col-12">Password:</label>
        <input id="password" type="password" name="password" minlength="6" maxlength="128" class="col-12" placeholder="Password" required />

        <label for="password-validation" class="col-12">Repeat Password:</label>
        <input id="password-validation" type="password" name="password-validation" minlength="6" maxlength="128" class="col-12" placeholder="Repeat Password" data-parsley-equalto="#password" required />

        <input type="hidden" name="token" value="<?php print($token); ?>" />

        <input type="submit" value="Sign Up" class="col-12" />

    </form>
</div>

<?php require("res/foot.php"); ?>