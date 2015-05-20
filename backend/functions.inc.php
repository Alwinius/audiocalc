<?php

while (! file_exists('backend')) {
    chdir('..');   
}

include("backend/usermanagement.inc.php");
include("backend/appearance.inc.php");
include("backend/basic.inc.php");