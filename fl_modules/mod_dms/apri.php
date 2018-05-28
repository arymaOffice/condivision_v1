<?php

// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ header("Location: ../../login.php"); exit; }
require('../../fl_core/settings.php');

$dir_files = DMS_ROOT.base64_decode(check($_GET['d']))."/";
$file = base64_decode(check($_GET['f']));
$dimensione_file = filesize($dir_files.$file); 
$filetype = filetype($dir_files.$file); 

if(strstr($file,".doc") || strstr($file,".docx")) {$filetype = "application/msword"; }

header('Content-Type: '.$filetype.'; filename="'.$file.'"');
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".$dimensione_file);
header('Content-Disposition: inline; filename="'.$file.'"');
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: private");
header("Pragma: no-cache");
readfile($dir_files.$file);

exit;

?>