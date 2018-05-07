<?php 

require_once('../../fl_core/autentication.php');

//Elimina
if(isset($_GET['unset'])) { 

if(!is_numeric($_GET['unset']) || !is_numeric($_GET['gtx'])) exit;
$rcx = $_SERVER['HTTP_REFERER'];


$id = $_GET['unset'];	
$tabella = $tables[check($_GET['gtx'])];
$file = (isset($_GET['file']) && $_GET['file'] != '') ? check($_GET['file']) : "nofile";	

$restore = "SELECT * FROM `$tabella` WHERE id = '$id' LIMIT 1";
$risultato = mysql_query($restore, CONNECT);
$restore = mysql_fetch_array($risultato);

$query = "DELETE FROM $tabella WHERE id = '$id' LIMIT 1";


if(@mysql_query($query,CONNECT)){

action_record('ELIMINA-BK',$tabella,$id,base64_encode(json_encode($restore)));
if(file_exists($file) && $tabella == "fl_files"){ @unlink($file); }
@mysql_close(CONNECT);
header("Location: $rcx"); 
exit;

} else { 

@mysql_close(CONNECT);
header("Location: $ref&action=9&esito=Errore 1103: Errore cancellazione database!"); 
exit;

}



}

@mysql_close(CONNECT);	
exit;

?>  
