<?php 


ini_set('display_errors', 1);
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 



$baseref = explode('?', $_SERVER['HTTP_REFERER']);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];


if(isset($_GET['modulo'])) { 

$id = $_SESSION['number'];	
$modulo = $_GET['modulo'];	
$contenuto = $_GET['contenuto'];	
$query1 = "UPDATE fl_conferme SET conferma = 1 WHERE conferma = 0 AND proprietario = '$id' AND contenuto = $contenuto LIMIT 1";
mysql_query($query1, CONNECT);	

$query1 = "UPDATE `fl_notifiche` SET alert = 0 WHERE id = $contenuto  LIMIT 1;";
mysql_query($query1, CONNECT);	


mysql_close(CONNECT);

header("Location: ".$_SESSION['POST_BACK_PAGE']);
exit;	

}


if (isset($_POST['destinatario'])) 
{



	$modulo = check($_POST['modulo']);
	$titolo = check($_POST['titolo']);
	$messaggio = check($_POST['messaggio'],1);
	$alert =(isset($_POST['alert'])) ? check(@$_POST['alert']) : 0;
	$obbligatorio = (isset($_POST['obbligatorio'])) ? check(@$_POST['obbligatorio']) : 0;
	$invia_email = (isset($_POST['invia_email'])) ? check(@$_POST['invia_email']) : 0;
	$invia_sms = (isset($_POST['invia_sms'])) ? check(@$_POST['invia_sms']) : 0;
	
	if(trim($titolo) == ''){  $titolo = 'Notifica del '.date('d/m/Y H:i');

	}
	$send = '';
	
    foreach($_POST['destinatario'] as $destinatario){ 
	$send .= notifica($modulo,$_SESSION['number'],$destinatario,$titolo,$messaggio,$alert,$obbligatorio,$invia_email,$invia_sms);
	}
	
	
	
}

$_POST = NULL;
unset($_SESSION['messaggio']);

mysql_close(CONNECT);
header("Location: $rct?$vars&success&esito=Notifica impostata. Inviata a: $send");
exit;	




?>
