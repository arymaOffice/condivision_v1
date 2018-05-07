<?php 


// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ header("Location: ../../login.php"); exit; }
if($_SESSION['idh'] != $_SERVER['REMOTE_ADDR']) { echo "Non autorizzato ".$_SESSION['idh']." NOT VALID ".$_SERVER['REMOTE_ADDR']; exit; }
require('../../fl_core/settings.php'); 
$baseref = explode('?', $_SERVER['HTTP_REFERER']);

$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];


if(isset($_GET['elimina'])) {
	
$relid = check($_GET['id']);

$query_x = "UPDATE fl_admin SET attivo = 0, motivo_sospensione = 'Eliminazione Cliente', data_aggiornamento = NOW(), operatore = ".$_SESSION['number']." WHERE anagrafica = $relid;";

if(!mysql_query($query_x,CONNECT)) { 

echo json_encode(array('class'=>'red','esito'=>0,'info_txt'=>'Impossibile disattivare account'.mysql_error())); 

}else{
	
	$yu = mysql_affected_rows();
	$query_y = "UPDATE fl_anagrafica SET concessione_fido = 0, status_anagrafica = 4, data_aggiornamento = NOW(), operatore = ".$_SESSION['number']." WHERE id = $relid;";	
	if(mysql_query($query_y,CONNECT)) { 
	action_record('delete','fl_anagrafica',$relid ,base64_encode($query_y));
	echo json_encode(array('class'=>'green','esito'=>1,'info_txt'=>'Anagrafica eliminata, disattivati '.$yu.' account'));
	}else{ 
	echo json_encode(array('class'=>'red','esito'=>0,'info_txt'=>'Impossibile eliminare anagrafica'.mysql_error()));
	}
}

}


if(isset($_GET['blocco'])) {
	
$relid = check($_GET['id']);
$blocco = check($_GET['blocco']);

$query_x = "UPDATE fl_anagrafica SET concessione_fido = $blocco, data_aggiornamento = NOW(), operatore = ".$_SESSION['number']." WHERE id = $relid;";

if(!mysql_query($query_x,CONNECT)) { 
	mysql_close(CONNECT);
	header("Location: $rct?$vars");
}else{
	action_record('blocco/sblocco','fl_anagrafica',$relid,base64_encode($query_x));
	mysql_close(CONNECT);
	header("Location: $rct?$vars");

}
}



exit;
					
?>  
