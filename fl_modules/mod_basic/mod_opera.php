<?php 

require_once('../../fl_core/autentication.php');
include('../../fl_core/dataset/items_rel.php');
include('../../fl_core/dataset/array_statiche.php');


$questionsAlert = '';
$questionsLog = '';
$domandeMail = '';

if(isset($_POST['action']) && check($_POST['action']) == 'addQuestion') {

	$workflow_id = check($_POST['workflow_id']);
	$record_id = check($_POST['record_id']);
	$note = str_replace("'","\'",check($_POST['note']));
	$nominativo = check($_POST['nominativo']);
	$totale = 0;
	$questionsDone = 0;

	foreach($questions as $chiave => $valore){  

		if(isset($_POST['question'.$chiave])){
			$question = $chiave;
			$answer =  '';
			$value = trim(check($_POST['question'.$chiave]));
			$totale += $value;

			/*********doamnde della mail*******/
			$domandeMail .= "<tr><th>".$questions[$question]."</th><th>".$value."</th></tr>";
			/*********************************/


			if($value > 0){
				$query = "INSERT INTO `fl_questions` (`id`, `workflow_id`, `record_id`, `question`, `answer`, `value`, `data_creazione`, `data_aggiornamento`, `operatore`)
				VALUES (NULL, '$workflow_id', '$record_id', '$question', '$answer', '$value', NOW(), CURRENT_TIMESTAMP, ".$_SESSION['number'].");";
				mysql_query($query,CONNECT);
				$questionsLog .= "<p style=\"font-size:12px !important;text-align:left !important\">".$questions[$question].": ".$value.'</p>';
				if($value == 1) $questionsAlert .= $questionsLog;
				$questionsDone++;
			}}
		}
		$questionsDone = ($questionsDone == 0)? 1: $questionsDone;

		$totale = numdec($totale/$questionsDone,2);
		$query = "INSERT INTO `fl_surveys` (`id`, `workflow_id`, `record_id`, `value`, `note`, `data_creazione`, `data_aggiornamento`, `operatore`) 
		VALUES (NULL, '$workflow_id ', '$record_id', '$totale', '$note', NOW(), CURRENT_TIMESTAMP, ".$_SESSION['number'].");";
		mysql_query($query,CONNECT);
		mysql_query("UPDATE fl_potentials SET status_potential = 4 WHERE id = $record_id",CONNECT);
		$potential = GQD('fl_potentials','telefono,telefono_alternativo,email,venditore,id','id  = '.$record_id);
		$venditore = GQD('fl_account','email,nominativo,id','id  = '.$potential['venditore']);
		$venditoreName = ($venditore['nominativo'] != NULL) ? $venditore['nominativo'] : ' (non assegnato) ';

//oggetti delle mail
$oggetto = "Valutazione Recall Cliente  ";  // alert & Venditore
$oggetto2 = "Grazie da ".client;  //cliente

//data della mail
$data = date('d.m.Y');
$img = '<img src="'.VALUTAZIONE_TOPBANNER.'" alt="Valutazione Cliente">';


/***************************************************************/
$messaggioInterno = "<p style='font-size:12px !important;text-align:left !important'>

Buongiorno,<br>
Bluemotive ha rilevato le seguenti valutazioni sull'attivit&agrave; \"RECALL\".<br>
relative al cliente <strong>$nominativo</strong> assistito dal consulente ".$venditoreName."
Recapiti del cliente: ".$potential['telefono']." ".$potential['telefono_alternativo']."
</p>
<table style='font-size:12px !important;' cellpadding='10'>
$domandeMail
</table>
<br>
<p style='font-size:12px !important; text-align:left !important'>
<b>Note: $note </b>
<p><a href=\"https://authos.bluemotive.it/fl_modules/mod_leads/mod_inserisci.php?id=$record_id\">Apri scheda cliente</a></p>
<br> 
</p> 
<br>
<p style='font-size:12px !important;text-align:left !important'>
Bluemotive vi ringrazia per l'attenzione.<br>
Buon proseguimento di giornata <br>
Bluemotive Recall - $data </p>
";
/***************************************************************/


/***************************************************************/
$messaggioCliente = "<br>$img <br>
<p>Gentile ".$nominativo.",</p><p>".client." &egrave; sempre molto attenta ai consigli che provengono dai preziosi Clienti come Lei.</p>
<p>Per questo motivo, con questa e-mail, desideriamo ringraziarLa per averci dedicato qualche minuto del Suo tempo.</p>
<p>Questa Sua attenzione ci permetter&agrave; di migliorare e di fornirVi sempre il nostro miglior servizio.</p> <br> 
<p>Buon proseguimento di giornata da ".client.", la Ford a Torino.</p>www.authos.it<br>
<p>I nostri migliori Saluti <br> Authos SpA</p>";
/***************************************************************/




/******************mail venditore*********************************/
$messaggioInterno  = str_replace("[*CORPO*]",$messaggioInterno,mail_template); 
$messaggioInterno = str_replace('{{oggetto}}', $oggetto, $messaggioInterno );


/******************mail cliente*********************************/
$messaggioCliente = str_replace("[*CORPO*]",$messaggioCliente,mail_template);
$messaggioCliente = str_replace('{{oggetto}}', $oggetto2, $messaggioCliente );





if($potential['venditore'] > 1 && isset($potential['venditore'])) {
	
	if(isset($venditore['email']) && filter_var($venditore['email'], FILTER_VALIDATE_EMAIL)) { 
	smail($venditore['email'],$oggetto.' invio a '.$venditore['email'],$messaggioInterno);
	smail(mail_admin,$oggetto.' invio a '.$venditore['email'],$messaggioInterno);
	smail('michele.cagnassone@authos.it',$oggetto.' invio a '.$venditore['email'],$messaggioInterno);
	smail('giuseppe.nacci@authos.it',$oggetto.' invio a '.$venditore['email'],$messaggioInterno);
	//smail('supporto@aryma.it',$oggetto,$messaggioInterno);
	}
}


/*Email Alert*/
if($questionsAlert != '' || isset($_POST['inviaAlert'])) {
	smail(mail_admin,$oggetto.'Alert',$messaggioInterno); 
	smail(mail_qos,$oggetto.'Alert',$messaggioInterno); 
	smail('mcagnassone@gmail.com',$oggetto.'Alert',$messaggioInterno); 
	smail('server@aryma.it',$oggetto.'Alert',$messaggioInterno); 
	smail('ced@authos.it',$oggetto.'Alert',$messaggioInterno); 
	smail('bdc@authos.it',$oggetto.'Alert',$messaggioInterno); 
	smail('michele.cagnassone@authos.it',$oggetto.'Alert',$messaggioInterno); 
	smail('giuseppe.nacci@authos.it',$oggetto.'Alert',$messaggioInterno); 
	smail('noreply@fordauthos.com',$oggetto.'Alert',$messaggioInterno); 
	

}


/* Email Cortesia */
if($totale == 5 && filter_var($potential['email'], FILTER_VALIDATE_EMAIL)) smail($potential['email'],$oggetto2,$messaggioCliente); // Se mail cliente e corretta invia






}


/***********************************
email cambiate il 08/03/2017 - Revisionato il 21/03/2017
mail_admin è quella di giuseppe. la trovate in fl_config/UTENTE/customer.php
***********************************/


mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit;

?>  
