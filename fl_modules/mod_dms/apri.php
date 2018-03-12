<?php

require_once('../../fl_core/autentication.php');

$folder = DMS_ROOT.base64_decode(check($_GET['d']))."/";
$file = base64_decode(check($_GET['f']));
$file_fullpath = $folder.$file;

$dimensione_file = filesize($folder.$file); 
$filetype = filetype($folder.$file); 

if(strstr($file,".doc") || strstr($file,".docx")) {$filetype = "application/msword"; }


if(file_exists($file_fullpath)){


$fp = fopen($file_fullpath, "r") ;

//$action = (isset($_GET['show'])) ? $filetype : 'application/force-download';
//$disposition = (isset($_GET['show'])) ? 'inline' : 'attachment';
//if(strstr($file,".doc") || strstr($file,".docx")) { $filetype = "application/msword"; }



//header("Content-Type:  $action; name=\"$file\"");
//header("Cache-Control: private");
//readfile($dir_files.$file);
header('HTTP/1.0 200 OK');
header("Cache-Control: maxage=1");
header("Pragma: public");
header("Content-type: $filetype");
header("Content-Disposition: inline; filename=$file");
header('Accept-Ranges: bytes');
header("Content-Transfer-Encoding: binary");
header('Content-Length:' . filesize($file_fullpath));

ob_clean(); //Pulizia memoria
flush();

readfile($file_fullpath);

} else { echo "File non trovato ".$file_fullpath; }

?>