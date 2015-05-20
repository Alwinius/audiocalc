<?php
include("../mysqlc.php");

if(isset($_COOKIE["username"]) && ctype_alnum($_COOKIE["username"]) && isset($_POST["name"])) {
    $query="SELECT id FROM `audios` WHERE username='" . mysql_real_escape_string($_COOKIE["username"]) . "' AND name='" . mysql_real_escape_string($_POST["name"]) . "'";
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