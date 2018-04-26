<?php 

require_once('action_check.php');

//Elimina
if(isset($_GET['unset'])) { 

if(!is_numeric($_GET['unset']) || !is_numeric($_GET['gtx'])) exit;
$rcx = $_SERVER['HTTP_REFERER'];


$id = $_GET['unset'];	
$tabella = $tables[check($_GET['gtx'])];
$rcx = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $_SESSION['POST_BACK_PAGE'];
if(isset($_REQUEST['POST_BACK_PAGE'])) $rcx = $_SESSION['POST_BACK_PAGE'];

$file = (@$_GET['file'] != "" || @$_GET['file'] != 0) ? check($_GET['file']) : "nofile";	

$restore = "SELECT * FROM `$tabella` WHERE id = '$id' LIMIT 1";
$risultato = mysql_query($restore, CONNECT);
$restore = mysql_fetch_assoc($risultato);

$query = "DELETE FROM $tabella WHERE id = '$id' LIMIT 1";


if(@mysql_query($query,CONNECT)){

action_record('ELIMINA-BK',$tabella,$id,base64_encode(json_encode($restore)));
if(file_exists($file) && $tabella == "fl_files"){ @unlink($file); }
if($tabella == "fl_dms"){ @unlink(DMS_ROOT.$restore['parent_id'].'/'.$restore['file']); }

@mysql_close(CONNECT);
header("Location: $rcx"); 
exit;

} else { 

@mysql_close(CONNECT);
header("Location: $rcx&action=9&esito=Errore 1103: Errore cancellazione database!"); 
exit;

}



}

@mysql_close(CONNECT);	
exit;

?>  
