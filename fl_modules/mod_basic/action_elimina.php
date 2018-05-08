<?php 

require_once('../../fl_core/autentication.php');

//Elimina
if(isset($_REQUEST['unset'])) { 

if(!is_numeric($_REQUEST['unset']) || !is_numeric($_REQUEST['gtx'])) exit;
$rcx = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $_SESSION['POST_BACK_PAGE'];
if(isset($_REQUEST['POST_BACK_PAGE'])) $rcx = $_SESSION['POST_BACK_PAGE'];

$id = check($_REQUEST['unset']);	
$tabella = $tables[check($_REQUEST['gtx'])];
$file = (@$_REQUEST['file'] != "" || @$_REQUEST['file'] != 0) ? check($_REQUEST['file']) : "nofile";	

$restore = "SELECT * FROM `$tabella` WHERE id = '$id' LIMIT 1";
$risultato = mysql_query($restore, CONNECT);
$restore = mysql_fetch_assoc($risultato);

$query = "DELETE FROM $tabella WHERE id = '$id' LIMIT 1";


if(@mysql_query($query,CONNECT)){

action_record('ELIMINA-BK',$tabella,$id,base64_encode(json_encode($restore)));
if(file_exists($file) && $tabella == "fl_files"){ @unlink($file); }
if($tabella == "fl_dms"){ @unlink(DMS_ROOT.$restore['parent_id'].'/'.$restore['file']); }

@mysql_close(CONNECT);
header("Location: ".$rcx); 
exit;

} else { 

echo mysql_error();
exit; 

@mysql_close(CONNECT);
header("Location: $rcx&action=9&esito=Errore 1103: Errore cancellazione database!".mysql_error()); 
exit;

}



}

@mysql_close(CONNECT);	
exit;

?>  
