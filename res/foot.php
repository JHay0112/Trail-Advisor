<?php 

    /*

        res/foot.php
        
        Author: Jordan Hay
        Version: 1.0

        Description:

            File to be included to generate the "foot" of a page.
            Requires head.php to have been used to generate a fully functioning foot.

        Other Notes:

            N/a

    */

?>

            <footer>
                <small><?php print($page_attr["title"]." - ".$page_attr["site_name"]." | ".$page_attr["copyright"]); ?></small>
            </footer>

        </main>
    </body>
</html>