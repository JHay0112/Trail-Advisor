<?php

    /*

        res/checkfields.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Importable function that checks that the required fields from a method (post or get) have been passed.

        Other Notes:

            N/a

    */

    // checkfields()
    //
    // Checks that required fields have been submitted, otherwise redirects with ?refferal_case=missingfields
    //
    // method - var - $_GET or $_POST
    // fields - array - array of names of required fields
    // redirect - str - page that will be redirected to incase of missing field
    //
    function check_fields($method, $required_fields, $redirect) {
        // Iterate through required fields
        foreach($required_fields as $field) {
            // Key should exist and the value should not be empty
            if((!array_key_exists($field, $method)) || ($method[$field] == "")) {
                print("<script> location = '".$redirect."?referral_case=missingfields'; </script>");
            }
        }
    }

?>