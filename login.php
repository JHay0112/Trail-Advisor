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

?>

<div class="col-12" id="form-wrapper">
    <form class="col-12" data-parsley-validate action="res/handlers/loginuser.php" method="post">

        <input type="text" name="username" maxlength="30" class="col-12" placeholder="Username" required />

        <input id="password" type="password" name="password" minlength="6" maxlength="30" class="col-12" placeholder="Password" required />

        <input type="submit" value="Submit" class="col-12" />

    </form>
</div>

<?php require_once("res/foot.php"); ?>