<?php

include("mysqlc.php");
include("backend/functions.inc.php");

//first we check the login
$user=  checklogin();

if($user["is_logged_in"]) {
    $content=createhead($user["username"]);
    $content.=createnav("dash");
    $content.='<!-- Begin page content -->
    <div class="container">
      <div class="page-header">
        <h1>Dashboard</h1>
      </div>
      <p class="lead">Welcome, ' . $user["username"] . ' <br/></p>';
    
    $content.='<h2>Worklog</h2><p>That\'s what you\'ve done so far in this month:</p>';
    $content.='<p>Notice: A month here goes from the 16th of the previous month to the 15th of this month.</p>';
    $content.='<p><table class="table table-striped audiolist"><thead><tr><th>&nbsp;&nbsp;&nbsp;Name</th><th>&nbsp;&nbsp;&nbsp;Duration</th><th>Worked Time</th><th>Finished (Hourly Rate - Date)</th><th>Actions</th></tr></thead><tbody>';
    if(date("j")>15) {
        $first_day=date("Y-m")."-16 00:00:00";;
    }
    else {
        $first_day=date("Y-m", strtotime("now -1 month"))."-16 00:00:00";
    }
    $query="SELECT * FROM audios WHERE username = '" . mysql_real_escape_string($user["username"]) . "' AND (created > \"$first_day\" OR finished = 0) ORDER BY id ASC;";
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
            $content.='<img src="icons/valid.png" alt="yes"/> (<img src="icons/coin_gold.png"/> ' . number_format($payh, 2) . '€/h - ' . date("d.m.Y", strtotime($row["created"])) . ')</td><td><a href="#" class="unfinishlink"><img src="icons/back.png" alt="Unfinish" title="Unfinish"/></a> <a href="#" class="deletelink"><img src="icons/del.png" alt="Delete from Database"'
                    . ' title="Delete From Database"/></a>';
        }    
        else {
            $content.='<img src="icons/crossout.png" alt="no"/></td><td><a href="#" class="finishlink"><img src="icons/forward.png" alt="Finish" title="Finish"/></a> <a href="#workplace" class="editlink"><img src="icons/pensil.png" alt="Continue With This File"'
                    . ' title="Continue With This File"/></a>';
        }
        $content.='</td></tr>';
    }
    $content.='<tr id="lastrow"><td><label class="sr-only" for="name">Name</label><input type="text" class="form-control" id="name" name="name" placeholder="T_IW32MT" data-content="Only letters, numbers and _ please." data-placement="bottom"/></td>';
    $content.='<td><label class="sr-only" for="duration">Duration</label><input type="text" class="form-control" id="duration" name="duration" placeholder="hh:mm:ss" maxlength="8" data-content="That\'s not a valid duration." data-placement="bottom"></td><td>00:00:00</td><td><img src="icons/crossout.png" alt="no"/></td><td><a href="#" id="savelink"><img src="icons/save_alt.png" alt="save" title="Save"/></a><a name="workplace"></a></td></tr></tbody></table></p></div>';
    
    $content.='<div class="container"><h2>Workplace</h2>';
    $content.='<p class="quote lead"></p>';
    $content.='<div class="overview row"><div class="col-xs-4"><img src="icons/hourglas_stop.png" alt="Hourglas-not working" class="hourglas"/></div><div class="col-xs-8 lead dashboard">'
            . '<div class="row"><div class="col-md-4 col-sm-7">Name:</div><div class="col-md-6 col-sm-5 activeaudio">None</div></div><div class="row"><div class="col-md-4 col-sm-7">Worked Time:</div><div class="col-md-6 col-sm-5 worktime">00:00:00</div></div><div class="row"><div class="col-md-4 col-sm-7">Since Last Stop:</div><div class="col-md-6 col-sm-5 pausetime">00:00:00</div></div><div class="row"><div class="col-sm-12 col-md-10 buttonrow"><button type="button" class="btn btn-success disabled pauseb">Pause</button><button type="button" class="stopb disabled btn btn-danger">Stop</button></div></div></div></div>';
    $content.='<div class="container lastc"><h2>Notepad</h2><p>Notes are saved nearly in real time. Current status: <img src="icons/valid.png" alt="Saved" id="notestatus"/></p><div class="notepad"></div></div>';
    
    $content.= '</div>';
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
    $content.=createfooter();
    $content.=createend("main");
    
    echo $content;
        
}
else {
    $content=createhead("Error");
    $content.=createnav("dash");
    $content.='<div class="container">
      <div class="page-header"><h1>No, this is not allowed!</h1></div></div>';
    $content.=createfooter();
    $content.=createend('main');
    
    echo $content;
}

?>