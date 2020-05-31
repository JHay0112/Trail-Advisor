<?php

    /*

        index.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Landing page for site.

        Other Notes:

            N/a

    */

    // Page Attributes
    $page_attr = array(
        "title" => "Home",
        "author" => "Jordan Hay",
        "class" => "home"
    );

    require_once("res/head.php");

?>

<h1>Welcome to <?php print($page_attr["site_name"]); ?>!</h1>

<hr />

<p><?php print($page_attr["site_name"]); ?> is a website dedicated to the creation and sharing of trails for all people of all abilities. Whether you're a sunday stroller or a dedicated hiker <?php print($page_attr["site_name"]); ?> has trails for you to discover and enjoy. </p>

<p>Ready to get walking? You can search our database of trails <a href="search.php">here</a>.</p>

<hr />

<p>The details of trails are provided by our faithful users and moderated by our dedicated staff team.</p>

<p>Interested in contributing to our ever-growing database of trails? Just sign up <a href="signup.php">here</a>.</p>

<?php require_once("res/foot.php"); ?>