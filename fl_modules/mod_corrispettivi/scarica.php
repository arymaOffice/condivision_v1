<?php

// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ header("Location: ../../login.php"); exit; }
require('../../fl_core/settings.php');

$dir_files = "output/";
$file = "Corrispettivi.xls";
$dimensione_file = filesize($dir_files.$file); 
$filetype = filetype($dir_files.$file); 


header("Content-type: application/vnd.ms-excel");
header("Content-disposition: $file"); 
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".$dimensione_file);
readfile($dir_files.$file);

exit;

?>