<?php 


// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ Header("Location: ../../login.php"); exit; }
//if($_SESSION['idh'] != $_SERVER['REMOTE_ADDR']) { echo "Non autorizzato ".$_SESSION['idh']." NOT VALID ".$_SERVER['REMOTE_ADDR']; exit; }
require('../../fl_core/settings.php'); 

$baseref = explode('?', $_SERVER['HTTP_REFERER']);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];

$tabella = "cms_articoli"; // ###################################  Modificare la tabella di sezione

// Modifica Stato se è settata $stato	
if(isset($_GET['pubblica'])) { 

if(!is_numeric($_GET['id'])) exit;

$id = $_GET['id'];	
$stato = check($_GET['pubblica']);

$query1 = "UPDATE $tabella SET homepage = '$stato' WHERE id = '$id'";

mysql_query($query1, CONNECT);	
mysql_close(CONNECT);

Header("Location: $rct?$vars");
exit;	

}

// Azzera Visite
if(isset($_GET['azzera'])) { 

if(!is_numeric($_GET['id'])) exit;

$id = $_GET['id'];	


$query1 = "UPDATE $tabella SET visite = 0 WHERE id = '$id'";

mysql_query($query1, CONNECT);	
mysql_close(CONNECT);

Header("Location: $rct?$vars");
exit;	

}



// Modifica Priorità
if(isset($_GET['priority'])) { 

if(!is_numeric($_GET['id'])) exit;

$id = $_GET['id'];	
$stato = check($_GET['priority']);

if($stato == 1){
$queryp = "UPDATE $tabella SET priority = priority+1 WHERE id = '$id'";
} else {
$queryp = "UPDATE $tabella SET priority = priority-1 WHERE id = '$id'";
}

mysql_query($queryp, CONNECT);	
mysql_close(CONNECT);

Header("Location: $rct?$vars");
exit;	

}

?>
