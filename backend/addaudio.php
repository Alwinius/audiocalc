<?php
include("../mysqlc.php");
include("functions.inc.php");

$durationcheck="/^([0-9]){1,2}:[0-5][0-9]:[0-5][0-9]$/i";
$namecheck="/^([a-z]|[A-Z]|[_]|[0-9])*$/i";

$user=  checklogin();

if($user["is_logged_in"] && preg_match($namecheck, $_POST["name"]) && preg_match($durationcheck, $_POST["duration"]) && $_POST["duration"]!="00:00:00" && $_POST["duration"]!="0:00:00") {
    $query="INSERT INTO `taaudiocalc`.`audios` (`id`, `name`, `duration`, `wduration`, `username`, `finished`, `created`) VALUES ('0', '" . mysql_real_escape_string($_POST["name"])  . "', '" . $_POST["duration"] . "', '00:00:00', '" . mysql_real_escape_string($_COOKIE["username"]) . "', '0', CURRENT_TIMESTAMP);";
    $query= mysql_query($query);
    if($query) {
        echo 1;
    }
    else {
        echo 0;
    }
}
else
{
    echo 0;
}