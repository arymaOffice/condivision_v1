<?php 


// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ Header("Location: ../../login.php"); exit; }
require('../../fl_core/settings.php');

//Funzione Connect 
$connect = connect($host, $login, $pwd, $db);

$baseref = explode('?', $_SERVER['HTTP_REFERER']);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];

$tabella = $tables[15]; // ###################################  Modificare la tabella di sezione



// Modifica Stato se è settata $stato	
if(isset($_GET['homepage'])) { 

if(!is_numeric($_GET['id'])) exit;

$id = $_GET['id'];	
$stato = check($_GET['homepage']);

$query1 = "UPDATE $tabella SET homepage = '$stato' WHERE id = '$id'";

mysql_query($query1, $connect);	
mysql_close($connect);

Header("Location: $rct?$vars");
exit;	

}

// Elimina Record
if(isset($_GET['delete'])) { 

if(!is_numeric($_GET['id'])) exit;

$id = $_GET['id'];	
//$file = ($_GET['file'] != "" || $_GET['file'] != 0) ? check($_GET['file']) : "nofile";	

$query2 = "DELETE FROM $tabella WHERE id = '$id' LIMIT 1";

mysql_query($query2, $connect);	
mysql_close($connect);

//if(file_exists($allegati.$file)){unlink($allegati.$file);}
Header("Location: $rct?$vars");
exit;	

}


@mysql_close($connect);


?>  
