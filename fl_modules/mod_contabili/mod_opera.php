<?php 

// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ header("Location: ../../login.php"); exit; }
require('../../fl_core/core.php'); 


if (isset($_POST['proprietario'])) 
{
 
   $stimate = 0;
   $create = 0;
   $caricati = 0;
   $destinatari = '';


  foreach($_POST['proprietario'] as $account_id){ 
	
	$stimate++;
	$account_id = check($account_id);
	$numero_settimana = check($_POST[$account_id.'numero_settimana']);
	$periodo_inizio = convert_data(check($_POST[$account_id.'periodo_inizio']),1);
	$periodo_fine = convert_data(check($_POST[$account_id.'periodo_fine']),1);
	$importo = check($_POST[$account_id.'importo']);

    $query = "INSERT INTO `fl_settimane_contabili` (`id`, `numero_settimana`, `periodo_inizio`, `periodo_fine`, `importo`, `operatore`, `proprietario`, `data_creazione`, `data_aggiornamento`) 
	VALUES (NULL, '$numero_settimana' , '$periodo_inizio', '$periodo_fine', '$importo', '".$_SESSION['number']."', '$account_id', NOW(),NOW());";
	
	if(mysql_query($query)) { $destinatari .=  '&destinatario[]='.$account_id; 
	$create++; 
	$file = (isset($_FILES[$account_id.'file'])) ?  loadFile($_FILES[$account_id.'file'],'',$account_id.'_report_settimane') : 0;  
	$caricati = $caricati+$file;}

	
	}
	unset($_SESSION['nuova_settimana']);
	$_SESSION['NOTIFY'] = "Settimane contabili create";
	$_SESSION['messaggio'] = "Gentile cliente,trasmettiamo l'estratto conto della settimana contabile in corso, la visione della stessa può avvenire collegandovi al seguente Link <a href=\"".ROOT.MAIN."?redirect=fl_modules/mod_contabili/?a=dashboard\">Accedi da qui</a> Cordiali saluti GI.LU.PI. SRL";
	echo json_encode(array('action'=>'popup','class'=>'green','url'=>'../mod_notifiche/mod_invia.php?iframe=1&checkAll=on&oggetto=Notifica creazione settimana contabile'.$destinatari,'esito'=>"Create $create di $stimate ($caricati file caricati)")); 
	exit;
	
}
	echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Seleziona uno o più account")); 
	exit;

					
?>  


<?php


mysql_close(CONNECT);
header("Location: ./?esito=Create $create di $stimate ($caricati file caricati)"); 
exit;


?>