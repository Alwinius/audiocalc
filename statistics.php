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
      </div>
      <p class="lead">Welcome, ' . $username . ' <br/></p>';
    
    $content.='<h2>Worklog</h2><p>That\'s what you\'ve done so far:</p>';

    $content.= '</div>';
    
    
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
$content.=createend();

//Ausgabe des Inhalts
echo $content;
?>