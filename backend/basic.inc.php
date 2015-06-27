<?php


function getmonth($timestamp) {
    if(date("j", strtotime($timestamp))>15) {
        return date("F Y", strtotime($timestamp . " +1 month"));
    }
    else {
        return date("F Y", strtotime($timestamp));
    }
}