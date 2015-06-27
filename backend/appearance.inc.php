<?php

function createhead($title) {
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
    <link href="css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->';
    $content.="<title>" . $title . " - AudioCalc</title></head><body>";
    return $content;
}

function createnav($sel) {
    $content='<!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="main.php">AudioCalc</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">';
    if($sel==="dash") {
        $content.='<li class="active"><a href="#">Dashboard</a></li>';
    }
    else {
        $content.='<li><a href="main.php">Dashboard</a></li>';
    }
    if($sel==="stats") {
        $content.='<li class="active"><a href="#">Statistics</a></li>';
    }
    else {
        $content.='<li><a href="statistics.php">Statistics</a></li>';
    }
//    if($sel==="acc") {
//        $content.='<li class="active"><a href="#">Account Settings</a></li>';
//    }
//    else {
//        $content.='<li><a href="account.php">Account Settings</a></li>';
//    }
//    if($sel==="about") {
//        $content.='<li class="active"><a href="#">About This Webapp</a></li>';
//    }
//    else {
//        $content.='<li><a href="about.php">About This Webapp</a></li>';
//    }
    $content.='<li><a href="logout.php">Logout</a></li>';
    $content.='</ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>';
    return $content;
}

function createfooter() {
    $content='<footer class="footer">
      <div class="container">
        <p class="text-muted">Created by Alwin Ebermann</p>
      </div>
    </footer>';
    return $content;
}

function createend($page) {
    $content='    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.11.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/functions.inc.js"></script>
    <script src="js/trumbowyg/dist/trumbowyg.min.js"></script>';
    switch ($page) {
    case "main":
        $content.='<script src="js/main.js"></script>';
        break;
    case "stats":
        $content.='<script src="js/stats.js"></script>';
        break;
    }
    $content.='
  </body>
</html>';
    mysql_close();
    return $content;
}