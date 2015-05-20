<?php
include("../mysqlc.php");
include("functions.inc.php");
$user=  checklogin();

if($user["is_logged_in"] && $_POST["action"]=="finish") {
    $query="SELECT * FROM audios WHERE username = '" . mysql_real_escape_string($user["username"]) . "' AND name= '" . mysql_real_escape_string($_POST["name"])  . "';";
    $result= mysql_query($query);
    if(!$result) {
        echo 0;
    }
    else {
        $row=mysql_fetch_array($result);
        $id=$row["id"];
        $query2="UPDATE `taaudiocalc`.`audios` SET `finished` = '1' WHERE `audios`.`id` = " . $id . ";";
        if(mysql_query($query2)) {
            echo date("d.m.Y");
        }
        else {
            echo 0;
        }
    }
}
elseif(isset($_POST["action"]) && $_POST["action"]=="unfinish") {
    $query="SELECT * FROM audios WHERE username = '" . mysql_real_escape_string($user["username"]) . "' AND name= '" . mysql_real_escape_string($_POST["name"])  . "';";
    $result= mysql_query($query);
    if(!$result) {
        echo 0;
    }
    else {
        $row=mysql_fetch_array($result);
        $id=$row["id"];
        $query2="UPDATE `taaudiocalc`.`audios` SET `finished` = '0' WHERE `audios`.`id` = " . $id . ";";
        if(mysql_query($query2)) {
            echo 1;
        }
        else {
            echo 0;
        }
    }
}