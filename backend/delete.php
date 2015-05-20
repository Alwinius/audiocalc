<?php
include("../mysqlc.php");
include("functions.inc.php");

$user=  checklogin();

if($user["is_logged_in"] && isset($_POST["name"])) {
    $query="SELECT id FROM `audios` WHERE username='" . mysql_real_escape_string($user["username"]) . "' AND name='" . mysql_real_escape_string($_POST["name"]) . "'";
    $result=  mysql_query($query);
    if($result) {
        $result=  mysql_fetch_array($result);
        $query2="DELETE FROM `taaudiocalc`.`audios` WHERE `audios`.`id` = " . $result["id"];
        if(mysql_query($query2)) {
            echo 1;
        }
        else
            echo 7;
    }
    else {
        echo 0;
    }
}
else {
    echo 0;
}