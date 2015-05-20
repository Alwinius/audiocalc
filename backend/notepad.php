<?php

include("../mysqlc.php");

if(isset($_COOKIE["username"]) && ctype_alnum($_COOKIE["username"]) && isset($_POST["action"])) {
    if($_POST["action"]=="get") {
        $query="SELECT * FROM users WHERE username='" . mysql_real_escape_string($_COOKIE["username"]) . "';";
        $result=  mysql_query($query);
        if(mysql_num_rows($result)===0) {
            //user  not in db
            $query2="INSERT INTO `taaudiocalc`.`users` (`userid`, `username`, `lastchange`) VALUES ('0', '" . mysql_real_escape_string($_COOKIE["username"]) . "', CURRENT_TIMESTAMP);";
            mysql_query($query2);
            $query3="INSERT INTO `taaudiocalc`.`notes` (`id`, `userid`, `content`, `updated`) VALUES ('0', '" . mysql_insert_id() . "', '', CURRENT_TIMESTAMP);";
            mysql_query($query3);
            echo '$noentry$';
        }
        else {
            $userid=  mysql_fetch_array($result)["userid"];
            $query2="SELECT * FROM notes WHERE userid=" . $userid;
            $result2=  mysql_query($query2);
            if(mysql_num_rows($result2)===0) {
                //noch kein Eintrag für notes - also einen anlegen
                $query3="INSERT INTO `taaudiocalc`.`notes` (`id`, `userid`, `content`, `updated`) VALUES ('0', '" . $userid . "', '', CURRENT_TIMESTAMP);";
                mysql_query($query3);
                echo '$noentry$';
            } 
            else {
                $note=  mysql_fetch_array($result2);
                if($note["content"]=="") {
                    echo '$noentry$';
                }
                else {
                    echo $note["content"];
                }
            }
        }
    }
    elseif($_POST["action"]=="set") {
        $query="UPDATE `taaudiocalc`.`notes` LEFT JOIN users on users.userid=notes.userid "
                . "SET `content` = '" . mysql_real_escape_string($_POST["content"]) . "' WHERE `users`.`username` = '" . mysql_real_escape_string($_COOKIE["username"]) . "';";
        $result=mysql_query($query);
        if($result) {
            echo 1;
        }
        else {
            echo $query;
        }
    }
}
else {
    echo 0;
}
