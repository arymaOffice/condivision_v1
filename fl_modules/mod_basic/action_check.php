<?php

// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ header("Location: ../../login.php"); exit; }
require_once('../../fl_core/core.php'); 


$ref =  @$_SERVER['HTTP_REFERER'];
$baseref = explode('?', @$ref);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];





?>