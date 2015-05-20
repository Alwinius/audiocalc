<?php

function checklogin() {
    $username=(string)filter_input(INPUT_POST, "username");
    $username2=(string)filter_input(INPUT_COOKIE, "username");
    if($username && preg_match("/^[a-zA-Z0-9_\-$.+]{5,29}$/i", $username)) {
        //User is trying to log in
        $query="SELECT * FROM users WHERE username='" . mysql_real_escape_string($username) . "';";
        $result=  mysql_query($query);
        if(mysql_num_rows($result)==1) {
            //user exists
            $expire=  filter_input(INPUT_POST, "stay")=="yes" ? time()+60*60*24*7:0;
            setcookie("username", $username, $expire);
            $results=  mysql_fetch_array($result);
            return array("is_logged_in"=>true,
                "username"=>$username,
                "id"=>$results["userid"]);
        }
        else {
            //user is not in DB
            $expire=  filter_input(INPUT_POST, "stay")=="yes" ? time()+60*60*24*7:0;
            $query2="INSERT INTO users(userid, username, lastchange) VALUES(0, '" . mysql_real_escape_string($username). "', CURRENT_TIMESTAMP);";
            $result2=  mysql_query($query2);
            if($result2) {
                // user is now in db
                setcookie("username", $username, $expire);
                return array("is_logged_in"=>true,
                "username"=>$username,
                "id"=>  mysql_insert_id());
            }
            else {
                // error -> user doesnt get logged in
                return array("is_logged_in"=>false,
                    "username"=>"",
                    "id"=>"");
            }
        }
    }
    elseif($username2 && preg_match("/^[a-zA-Z0-9_\-$.+]{5,29}$/i", $username2)) {
        //user is already logged in (or at least he has the cookie)
        $query="SELECT * FROM users WHERE username='" . mysql_real_escape_string($username2) . "';";
        $result=  mysql_query($query);
        if(mysql_num_rows($result)==1) {
            return array("is_logged_in"=>true,
            "username"=>$username2,
            "id"=>  mysql_insert_id());
            }
        else {
            return array("is_logged_in"=>false,
                 "username"=>"",
                 "id"=>"");
        }
    }
    else {
        return array("is_logged_in"=>false,
            "username"=>"",
            "id"=>"");
    }
}