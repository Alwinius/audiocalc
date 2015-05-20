<?php

include("../mysqlc.php");

if(isset($_POST["action"]) && $_POST["action"]=="updatewtime" &&  ctype_alnum($_COOKIE["username"]) && isset($_POST["name"]) && isset($_POST["wtime"])&& ctype_digit($_POST["wtime"])) {
    $wtime=$_POST["wtime"];
    $hours=floor($wtime/3600);
    $mins=floor(($wtime-$hours*3600)/60);
    $secs=floor($wtime-$hours*3600-$mins*60);
    $query="UPDATE `audios` SET `wduration`='" . $hours . ":" . $mins . ":" . $secs . "' WHERE username='" . mysql_real_escape_string($_COOKIE["username"]) . "' AND name='" . $_POST["name"] . "'";
    if(mysql_query($query)) {
        echo 1;
    }
    else {
        echo 0;
    }
}
else {
    echo 0;
}

