<?php 



require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$baseref = explode('?', $_SERVER['HTTP_REFERER']);

$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];


// Modifica Stato se è settata $stato	
if(isset($_GET['pubblica'])) { 

if(!is_numeric($_GET['id'])) exit;

$id = $_GET['id'];	
$stato = check($_GET['pubblica']);

$query1 = "UPDATE $tabella SET fatto = '$stato' WHERE id = '$id'";

mysql_query($query1, CONNECT);	
mysql_close(CONNECT);

Header("Location: $rct?$vars");
exit;	

}

// Azzera Visite
if(isset($_POST['jorel'])) { 

if(!is_numeric($_POST['jorel'])) exit;

$jorel = $_POST['jorel'];	
$oggetto = @check($_POST['oggetto']);	
$descrizione = @check($_POST['descrizione']);	
$proprietarioid = $_POST['proprietario'];
$letto = (isset($_POST['letto'])) ? $_POST['letto'] : 0;
if(isset($_POST['status_assistenza'])) {
$stato  =  ", status_assistenza = ".$_POST['status_assistenza']."";
$stato_text  = "<p class=\"small\">Cambio Stato: ".$status_assistenza[$_POST['status_assistenza']]."</small>";
} else {
$stato  =  "";
$stato_text = "";
}

$query = "INSERT INTO `fl_assistenza` (`id`, `jorel`, `letto`,`proprietario`, `status_assistenza`, `cat`, `urgenza`, `oggetto`, `descrizione`, `data_chiusura`, `data_creazione`, `data_aggiornamento`, `operatore`, `ip`) VALUES (NULL, $jorel, $letto, $proprietarioid, 0, 0, 0, '$oggetto', '$descrizione $stato_text ', NOW(), NOW(), NOW(), ".$_SESSION['number'].", '$ip');";
$query2 = "UPDATE fl_assistenza SET data_aggiornamento = NOW(),operatore = ".$_SESSION['number']." $stato WHERE id = $jorel LIMIT 1;";
mysql_query($query, CONNECT);	
mysql_query($query2, CONNECT);	
mysql_close(CONNECT);
echo $query; exit;
Header("Location: $rct?$vars");
exit;	

}



mysql_query($queryp, CONNECT);	
mysql_close(CONNECT);

Header("Location: $rct?$vars");
exit;	



?>
