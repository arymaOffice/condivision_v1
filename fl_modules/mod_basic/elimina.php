<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

require_once('action_check.php');



if(isset($_GET['unset'])) { 

if(!is_numeric($_GET['unset']) || !is_numeric($_GET['gtx'])) exit;

$id = $_GET['unset'];	
$tabella = $tables[check($_GET['gtx'])];
if(isset($_GET['tipologia'])) $tipologia = check($_GET['tipologia']);
@$file = (@$_GET['file'] != "" || @$_GET['file'] != 0) ? check(@$_GET['file']) : "nofile";	
$parametri = $tabella;

if(file_exists($file) && $tabella == "fl_files"){ @unlink($file); }

delete($tabella,$id,$parametri);


}



function delete($tabella,$id,$parametri) {

$query = "DELETE FROM $tabella WHERE id = '$id' LIMIT 1";


if(mysql_query($query,CONNECT)) {


@mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit;

} else {

@mysql_close(CONNECT);
header("Location: $referer&action=9&esito=Errore 1103: Errore cancellazione database!"); 
exit;
}

} // end delete function





@mysql_close(CONNECT);

?>
