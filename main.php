<?php

include("mysqlc.php");

$content='<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Trumbowyg css  -->
    <link rel="stylesheet" href="js/trumbowyg/dist/ui/trumbowyg.min.css">
    <!-- Custom styles for this template -->
    <link href="css/mainpage.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->';

function overview($username) {
    $content="<title>" . $username . " - AudioCalc</title></head><body>";
    $content.='<div class="modal fade" id="deletequestion">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Serious Question</h4>
      </div>
      <div class="modal-body">
        <p>Do you really want do delete this entry from the database?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary" id="yesdel">Yes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';
//    $content.='<!-- Fixed navbar -->
//    <nav class="navbar navbar-default navbar-fixed-top">
//      <div class="container">
//        <div class="navbar-header">
//          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
//            <span class="sr-only">Toggle navigation</span>
//            <span class="icon-bar"></span>
//            <span class="icon-bar"></span>
//            <span class="icon-bar"></span>
//          </button>
//          <a class="navbar-brand" href="#">Project name</a>
//        </div>
//        <div id="navbar" class="collapse navbar-collapse">
//          <ul class="nav navbar-nav">
//            <li class="active"><a href="#">Home</a></li>
//            <li><a href="#about">About</a></li>
//            <li><a href="#contact">Contact</a></li>
//            <li class="dropdown">
//              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
//              <ul class="dropdown-menu" role="menu">
//                <li><a href="#">Action</a></li>
//                <li><a href="#">Another action</a></li>
//                <li><a href="#">Something else here</a></li>
//                <li class="divider"></li>
//                <li class="dropdown-header">Nav header</li>
//                <li><a href="#">Separated link</a></li>
//                <li><a href="#">One more separated link</a></li>
//              </ul>
//            </li>
//          </ul>
//        </div><!--/.nav-collapse -->
//      </div>
//    </nav>';
    $content.='<!-- Begin page content -->
    <div class="container">
      <div class="page-header">
        <h1>Dashboard</h1>
      </div>
      <p class="lead">Welcome, ' . $username . ' (<a href="logout.php">Logout</a>)<br/></p>';
    
    $content.='<h2>Worklog</h2><p>That\'s what you\'ve done so far:</p>';
    $content.='<p><table class="table table-striped audiolist"><thead><tr><th>&nbsp;&nbsp;&nbsp;Name</th><th>&nbsp;&nbsp;&nbsp;Duration</th><th>Worked Time</th><th>Finished (Hourly Rate - Date)</th><th>Actions</th></tr></thead><tbody>';
    $query="SELECT * FROM audios WHERE username = '" . $username . "' ORDER BY id ASC;";
    $result=  mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        $entry=1;
        $content.='<tr><td>' . $row["name"] . '</td><td>' . $row["duration"] . '</td><td>' . $row["wduration"] . '</td><td>';
        if($row["finished"]==1) {
            $duration=$row["duration"];
            $pay =strtotime("1970-01-01 $duration UTC")*56/3600;
            $wtime = $row["wduration"];
            $wtime=strtotime("1970-01-01 $wtime UTC");
            $wtime=($wtime==0) ? 1:$wtime; 
            $payh =$pay/$wtime*3600;
            $content.='<img src="icons/valid.png" alt="yes"/> (<img src="icons/coin_gold.png"/> ' . round($payh, 2) . '€/h - ' . date("d.m.Y", strtotime($row["created"])) . ')</td><td><a href="#" class="unfinishlink"><img src="icons/back.png" alt="Unfinish" title="Unfinish"/></a> <a href="#" class="deletelink"><img src="icons/del.png" alt="Delete from Database"'
                    . ' title="Delete From Database"/></a>';
        }    
        else {
            $content.='<img src="icons/crossout.png" alt="no"/></td><td><a href="" class="finishlink"><img src="icons/forward.png" alt="Finish" title="Finish"/></a> <a href="#" class="editlink"><img src="icons/pensil.png" alt="Continue With This File"'
                    . ' title="Continue With This File"/></a>';
        }
        $content.='</td></tr>';
    }
    $content.='<tr id="lastrow"><td><label class="sr-only" for="name">Name</label><input type="text" class="form-control" id="name" name="name" placeholder="T_IW32MT" data-content="Only letters, numbers and _ please." data-placement="bottom"/></td>';
    $content.='<td><label class="sr-only" for="duration">Duration</label><input type="text" class="form-control" id="duration" name="duration" placeholder="hh:mm:ss" maxlength="8" data-content="That\'s not a valid duration." data-placement="bottom"></td><td>00:00:00</td><td><img src="icons/crossout.png" alt="no"/></td><td><a href="#" id="savelink"><img src="icons/save_alt.png" alt="save" title="Save"/></a></td></tr></tbody></table></p></div>';
    
    $content.='<div class="container"><h2>Workplace</h2>';
    $content.='<p class="quote lead"></p>';
    $content.='<div class="overview row"><div class="col-xs-4"><img src="icons/hourglas_stop.png" alt="Hourglas-not working" class="hourglas"/></div><div class="col-xs-8 lead dashboard">'
            . '<div class="row"><div class="col-md-4 col-sm-7">Name:</div><div class="col-md-6 col-sm-5 activeaudio">None</div></div><div class="row"><div class="col-md-4 col-sm-7">Worked Time:</div><div class="col-md-6 col-sm-5 worktime">00:00:00</div></div><div class="row"><div class="col-md-4 col-sm-7">Since Last Stop:</div><div class="col-md-6 col-sm-5 pausetime">00:00:00</div></div><div class="row"><div class="col-sm-12 col-md-10 buttonrow"><button type="button" class="btn btn-success disabled pauseb">Pause</button><button type="button" class="stopb disabled btn btn-danger">Stop</button></div></div></div></div>';
    $content.='<div class="container lastc"><h2>Notepad</h2><p>Notes are saved nearly in real time. Current status: <img src="icons/valid.png" alt="Saved" id="notestatus"/></p><div class="notepad"></div></div>';
    
    $content.= '</div>';
    
    
    return $content;
}

if(isset($_POST["username"])) {
    if(ctype_alnum($_POST["username"]))
    {
        //user einloggen
        $expire=(isset($_POST["stay"]) && $_POST["stay"]=="yes") ? time()+60*60*24*7:0;
        setcookie("username", $_POST["username"], $expire);
        $content.=overview($_POST["username"]);
    }
    else
    {
        $content.="<title>Error</title></head><body><h1>You can only use letters and numbers for your username.</h1>";
    }
}
elseif(isset($_COOKIE["username"]) && ctype_alnum($_COOKIE["username"])) {
    //ist schon eingeloggt
    $content.=overview($_COOKIE["username"]);
}
else
{
    $content.="<title>Error</title></head><body><h1>Something went wrong.</h1>";
}

$content.='    <footer class="footer">
      <div class="container">
        <p class="text-muted">Created by Alwin Ebermann</p>
      </div>
    </footer>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.11.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/trumbowyg/dist/trumbowyg.min.js"></script>
  </body>
</html>

';

//Ausgabe des Inhalts
echo $content;
mysql_close();
?>