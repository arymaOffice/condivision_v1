<?php 

// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ header("Location: ../../login.php"); exit; }
require('../../fl_core/core.php'); 

if(isset($_GET['new'])) {

$relid = check($_GET['reild']);
$marchio = check($_GET['marchio']);
$tipo_account = check($_GET['tipo']);


$query = "INSERT INTO `fl_anagrafica` ( `id` , `status_anagrafica` , `marchio` , `tipo_affiliazione` , `nome` , `cognome` , `data_di_nascita` , `luogo_di_nascita` , `codice_fiscale` , `regione` , `provincia` , `comune` , `frazione` , `indirizzo` , `cap` , `telefono` , `cellulare` , `fax` , `email` , `forma_giuridica` , `ragione_sociale` , `partita_iva` , `codice_fiscale_legale` , `regione_sede` , `provincia_sede` , `comune_sede` , `frazione_sede` , `sede_legale` , `cap_sede` , `punto_vendita` , `regione_punto` , `provincia_punto` , `comune_punto` , `frazione_punto` , `indirizzo_punto` , `cap_punto` , `lat` , `lon` , `telefono_punto` , `email_punto` , `sito_web` , `account_affiliato` , `note` , `data_creazione` , `data_aggiornamento` , `operatore` , `proprietario` , `ip` )
VALUES (NULL , '3', '$marchio', '$tipo_account', '', '', '', '', '', '', '', '', '', NULL , NULL , '', '', '', '', '', '', '', '', '', '', '', '', NULL , NULL , '', '', '', '', '', NULL , NULL , '', '', '', '', 'http://', '', '', '$time', '$time', '$operatore', '$operatore', '$ip');";
mysql_query($query,CONNECT);

$risultato = mysql_query("SELECT id FROM `fl_anagrafica` WHERE operatore = ".$_SESSION['number']." ORDER BY id DESC LIMIT 1",CONNECT);
$riga = mysql_fetch_array($risultato);


$query_x = "UPDATE fl_account SET anagrafica_id = ".$riga['id']." WHERE id = $relid;";
if(!mysql_query($query_x,CONNECT)) { 
echo "<h1>Impossibile associare anagrafica</h1>"; 
}else{
header("Location: ../mod_account/?action=12&id=$relid#tab_anagrafica"); 
}
mysql_close(CONNECT);
exit;
}


if(isset($_POST['link_type'])) {
	
$link = check($_POST['link']);
$link_type = check($_POST['link_type']);
$label = check($_POST['label']);
$anagrafca_id = check($_POST['anagrafica_id']);
$workflow_id = check($_POST['workflow_id']);
$account_id = check($_POST['account_id']);


if (isset($_POST['link']) && !filter_var($link, FILTER_VALIDATE_URL)    ) {
mysql_close(CONNECT);
header("Location: mod_links.php?esito=Inserisci Link Corretto&anagrafica_id=".$anagrafca_id); 
//json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire un link corretto"));
exit;
}


$query = "INSERT INTO `fl_links` (`id`, `anagrafica_id`, `workflow_id`, `account_id`, `link_type`, `label`, `link`) 
VALUES (NULL, '$anagrafca_id', '$workflow_id', '$account_id', '$link_type', '$label', '$link');";
mysql_query($query,CONNECT);

mysql_close(CONNECT);
header("Location: mod_links.php?anagrafica_id=".$anagrafca_id); 
exit;

}

if(isset($_POST['video_type'])) {
	
$link = check($_POST['link']);
$video = check($_POST['video_type']);
$label = check($_POST['label']);
$anagrafca_id = check($_POST['anagrafica_id']);
$workflow_id = check($_POST['workflow_id']);
$account_id = check($_POST['account_id']);


if (isset($_POST['link']) && !filter_var($link, FILTER_VALIDATE_URL)    ) {
mysql_close(CONNECT);
header("Location: mod_video.php?esito=Inserisci Link Corretto&anagrafica_id=".$anagrafca_id); 
//json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire un link corretto"));
exit;
}


$query = "INSERT INTO `fl_video` (`id`, `anagrafica_id`, `workflow_id`, `account_id`, `link_type`, `label`, `link`) 
VALUES (NULL, '$anagrafca_id', '$workflow_id', '$account_id', '$link_type', '$label', '$link');";
mysql_query($query,CONNECT);

mysql_close(CONNECT);
header("Location: mod_video.php?anagrafica_id=".$anagrafca_id); 
exit;

}


if(isset($_POST['insert_conto'])) {
	
$intestatario = check($_POST['intestatario']);
$descrizione = check($_POST['descrizione']);
$anagrafca_id = check($_POST['anagrafica_id']);
$estremi = check($_POST['estremi']);


if (check($_POST['descrizione']) == '') {
mysql_close(CONNECT);
header("Location: mod_conti.php?esito=Inserisci Descrizione&anagrafica_id=".$anagrafca_id); 
//json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire un link corretto"));
exit;
}


$query = "INSERT INTO `fl_conti` (`id`, `anagrafica_id`, `stato_conto`,`intestatario`, `descrizione`, `estremi`, `data_creazione`) 
VALUES (NULL, '$anagrafca_id', 1, '$intestatario', '$descrizione', '$estremi', NOW());";
mysql_query($query,CONNECT);


mysql_close(CONNECT);
header("Location: mod_conti.php?anagrafica_id=".$anagrafca_id); 
exit;

}



mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;
					
?>  
