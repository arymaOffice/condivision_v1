<?php
// Controllo Login
session_start(); 
require_once('../../fl_core/core.php'); 

$rcx = check($_SERVER['HTTP_REFERER']);


if (isset($_GET['step'])) 
{   
$_SESSION['step'] = check($_GET['step']);
}


if (isset($_GET['ordine_type'])) 
{   
$_SESSION['ordine_type'] = check($_GET['ordine_type']);
}


if(isset($_GET['dir_for_gallery'])){
$_SESSION['dir_for_gallery'] = $_GET['dir_for_gallery'];
}

if(isset($_GET['engage'])){
$_SESSION['itinerario_engage'] = check($_GET['id']);
}

if(isset($_GET['disengage'])){
unset($_SESSION['itinerario_engage']);
}

if (isset($_GET['lang'])) {
	$lang = check($_GET['lang']);
	setcookie("lang",$lang,time()+30000,"/");
}

if (isset($_GET['text'])) {
	$text = check($_GET['text']);
	if($text == 0){ $text = $_COOKIE['text']-5; } else { $text = $_COOKIE['text']+5;} 
	if($text < 0) $text = 0;
	setcookie("text",$text,time()+30000,"/");
}

if (isset($_GET['color'])) {
	$color = check($_GET['color']);
	setcookie("color",$color,time()+30000,"/");
}

if(isset($_GET['listmode'])){
if(!isset($_COOKIE['listmode'])) {
 setcookie("listmode",1,time()+30000,"/"); 
 } else {
 if($_COOKIE['listmode'] == 1) { setcookie("listmode",0,time()+30000,"/"); } else {setcookie("listmode",1,time()+30000,"/"); } 
 }
}

header("Location: $rcx"); 
exit;

?>