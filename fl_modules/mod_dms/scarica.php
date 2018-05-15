<?php

// Controllo Login
require_once('../../fl_core/autentication.php');

$dir_files =  DMS_ROOT.base64_decode(check($_GET['d']))."/";
$file = base64_decode(check($_GET['f']));
$dimensione_file = filesize($dir_files.$file); 
$filetype = filetype($dir_files.$file); 


header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".$dimensione_file);
header("Content-Disposition: attachment; filename=\"".$file."\"");
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: private");
header("Pragma: no-cache");
readfile($dir_files.$file);

exit;

?>