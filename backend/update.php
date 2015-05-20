<?php

include("../mysqlc.php");
include("functions.inc.php");

$user=  checklogin();


if($user["is_logged_in"] && $_POST["action"]=="updatewtime" && isset($_POST["name"]) && isset($_POST["wtime"])&& ctype_digit($_POST["wtime"])) {
    $wtime=$_POST["wtime"];
    $hours=floor($wtime/3600);
    $mins=floor(($wtime-$hours*3600)/60);
    $secs=floor($wtime-$hours*3600-$mins*60);
    $query="UPDATE `audios` SET `wduration`='" . $hours . ":" . $mins . ":" . $secs . "' WHERE username='" . mysql_real_escape_string($user["username"]) . "' AND name='" . $_POST["name"] . "'";
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

