<?php

include("mysqlc.php");
include("backend/functions.inc.php");

function stats($username) {
    $content=  createhead($username);
    $content.=createnav("stats");
    $content.='<!-- Begin page content -->
    <div class="container">
      <div class="page-header">
        <h1>Statistics</h1>
      </div>';
    
    $content.='<h2>Work by month</h2><form class="form-inline"><div class="form-group">That\'s what you\'ve done so far in &nbsp;<select class="form-control input-sm monthsel">'
            . '<option>Current Month</option></select>:</div></form>';
    $content.='<p>Notice: A month here goes from the 16th of the previous month to the 15th of this month.</p>';
    $content.='<table class="table table-striped audiolist"><thead><tr><th>Name</th><th>Duration</th><th>Worked Time</th><th>Hourly Rate</th><th>Total pay</th><th>Finished</th></tr></thead><tbody>';
    
    $content.='</tbody></table>';
    $content.='<div class="container monthavg"><div class="row"><div class="col-xs-6">Total income for this month (before tax) is: </div><div class="col-xs-6"><img src="icons/coin_gold.png"/> <span class="total"></span></div></div>'
            . '<div class="row"><div class="col-xs-6">Total work hours this month: </div><div class="col-xs-6"><img src="icons/alarmclock.png"/> <span class="totalhrs"></span></div></div>'
            . '<div class="row"><div class="col-xs-6">Average hourly rate: </div><div class="col-xs-6"><img src="icons/coin_gold.png"/>/<img src="icons/alarmclock.png"/> <span class="avgrate"></span></div>';
    $content.= '</div></div>';
    
    $content.='<h2>Overall statistics</h2><p>Everything you\'ve done in your life.</p>';
    $query=mysql_query("SELECT ROUND(SUM(TIME_TO_SEC(wduration))/3600, 2) AS wduration, ROUND(SUM(TIME_TO_SEC(duration))/3600, 2) AS duration FROM `audios` WHERE username='" . $username . "' AND finished=1 GROUP BY username ORDER BY id ASC;");
    $result=  mysql_fetch_array($query);
    $content.='<div class="container monthavg"><div class="row"><div class="col-xs-6">Total income (before tax) is: </div><div class="col-xs-6"><img src="icons/coin_gold.png"/> ' . number_format($result["duration"]*56, 2) . '€</div></div>'
            . '<div class="row"><div class="col-xs-6">Total work hours: </div><div class="col-xs-6"><img src="icons/alarmclock.png"/> ' . number_format($result["wduration"], 2) . 'h</div></div>'
            . '<div class="row"><div class="col-xs-6">Total hours of edited audios: </div><div class="col-xs-6"><img src="icons/alarmclock.png"/> ' . number_format($result["duration"], 2) . 'h</div></div>'
            . '<div class="row"><div class="col-xs-6">Average hourly rate: </div><div class="col-xs-6"><img src="icons/coin_gold.png"/>/<img src="icons/alarmclock.png"/> ' . number_format(($result["duration"]*56)/$result["wduration"], 2) . '€/h</div>';
    $content.= '</div></div>';
    
    $content.='</div>';
    
    
    return $content;
}

if(isset($_COOKIE["username"]) && ctype_alnum($_COOKIE["username"])) {
    //ist schon eingeloggt
    $content=stats($_COOKIE["username"]);
}
else
{
    $content.="<head><title>Error</title></head><body><h1>Something went wrong.</h1>";
}

$content.=createfooter();
$content.=createend("stats");

//Ausgabe des Inhalts
echo $content;
?>