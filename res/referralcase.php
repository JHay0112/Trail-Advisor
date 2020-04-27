<?php

    /*

        res/referralcase.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            Handles special messages that are generated depending on a referral case.

        Other Notes:

            N/a

    */


    // Handles special messages that are generated depending on a referral case.
    //
    // $case - str/int - points to a variable that gives a state.
    // $states - array - possible states as key and message to be returned on state as value
    // 
    function referral($case, $states) {

        // Check if case is in states
        if(array_key_exists($case, $states)) {
            $message = $states[$case];
        } else {
            // If case is not in states then return an empty message
            $message = "";
        }
    
        return($message);
    }

?>