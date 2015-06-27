<?php

include("../mysqlc.php");
include("functions.inc.php");
$user=  checklogin();

if($user["is_logged_in"] && isset($_POST["action"]) && $_POST["action"]=="getmonths") {
    //fetch all months in which the user has worked
    $query="SELECT created FROM audios WHERE username='" . $user["username"] . "' ORDER BY created ASC;";
    $result=  mysql_query($query);
    $months=array();
    while ($row = mysql_fetch_array($result)) {
        if (!in_array(getmonth($row["created"]), $months)) {
            array_push($months, getmonth($row["created"]));
        }
    }
    echo json_encode($months);
}
elseif ($user["is_logged_in"] && isset($_POST["action"]) && $_POST["action"]=="getmonth" && isset($_POST["month"]) && preg_match("/[A-Za-z]+\ [0-9]{4}/", $_POST["month"])) {
    $first_day=date("Y-m", strtotime($_POST["month"]." -1 month"))."-16 00:00:00";
    $last_day=date("Y-m", strtotime($_POST["month"]))."-15 23:59:59";
    $query="SELECT * FROM audios WHERE username='" . $user["username"] . "' AND finished = 1 AND created between '" . $first_day . "' AND '" . $last_day . "' ORDER BY created ASC";
    $result=  mysql_query($query);
    $entries=array();
    while ($row1 = mysql_fetch_array($result)) {
        $row1["created"]=date("d.m.Y", strtotime($row1["created"]));
        array_push($entries, $row1);
    }
    echo json_encode($entries);
}
else {
    echo 0;
}

