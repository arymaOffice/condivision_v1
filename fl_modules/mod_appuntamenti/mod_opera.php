<?php 


$meeting_page = 1;
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 


if(isset($_POST['set_meeting'])){

	$profile_rel = check($_POST['profile_rel']);
	$proprietario_id  = (isset($_POST['proprietario'])) ? check($_POST['proprietario']) : $_SESSION['number'];
	$note  = check($_POST['note']);
	$start_meeting  = dbdatetime(check($_POST['start_meeting']),1);
	$end_meeting  = date('Y-m-d H:i:00',strtotime($start_meeting .' +30 minutes'));
	$potential = GRD($tables[106], $profile_rel ); 
	$lang = (isset($_POST['lang'])) ? check($_POST['lang']) : 'en';
	$meeting_location = (isset($_POST['meeting_location'])) ? check($_POST['meeting_location']) : 0;


    $check = "SELECT start_meeting,end_meeting FROM fl_appuntamenti WHERE proprietario = $proprietario_id AND ((start_meeting BETWEEN '$start_meeting' AND '$end_meeting') OR (end_meeting BETWEEN '$start_meeting' AND '$end_meeting'));";
	mysql_query($check,CONNECT);
	$conflitti = mysql_affected_rows();
    $check = "SELECT start_date,end_date FROM fl_calendario WHERE proprietario = $proprietario_id AND (start_date < '$start_meeting' AND end_date > '$end_meeting');";
	mysql_query($check,CONNECT);
	$conflitti += mysql_affected_rows();
	
	if($conflitti > 0){
	echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Attenzione ".$proprietario[$_SESSION['number']]." Ci sono altri appuntamenti o impegni in questo orario")); 
	@mysql_close(CONNECT);
	exit;



	include("../../fl_inc/headers.php"); 
	echo '<body style=" background: #FFFFFF;"><div id="container" style=" text-align: center;">';
	echo '<h1><strong>Attenzione '.$proprietario[$_SESSION['number']].'</strong>!</h1>
	<p>Ci sono altri appuntamenti o impegni in questo orario<br><br>
	<a href="javascript:history.back();" class="small_touch blue_push">Indietro</a></p>';
	@mysql_close(CONNECT);
	exit;
	}
	$data_scadenza = $start_meeting; //date('Y-m-d', strtotime($start_meeting . "+0 days")); // 5/11/2016 la data di scadenza è reimpostata al giorno del meeting.
	$data_scadenza_bdc = $start_meeting;//date('Y-m-d', strtotime($start_meeting . "+0 days")); // 5/11/2016.

	$query = "INSERT INTO `fl_appuntamenti` (`id`, `marchio`,`meeting_location`,  `start_meeting`, `end_meeting`, `potential_rel`,`nominativo`, `note`, `issue`,`callcenter`, `operatore`, `proprietario`, `data_creazione`, `data_aggiornamento`,`data_arrived`) 
	VALUES (NULL, '".$_SESSION['marchio']."' , '".$meeting_location."' ,'".$start_meeting."', '".$end_meeting."', '".$profile_rel."', '".$potential['nome']." ".$potential['cognome']."', '".$note."', '0','".$_SESSION['number']."', '".$_SESSION['number']."', '".$proprietario_id."', '".date('Y-m-d H:i:00')."','".date('Y-m-d H:i:00')."','0000-00-00')";
	
	if(mysql_query($query,CONNECT)) { 
	action_record('create','fl_appuntamenti',0,base64_encode($query));
	mysql_query("UPDATE ".$tables[106]." SET `status_potential`= 7 , data_aggiornamento ='".date('Y-m-d H:i:00')."', `in_use` = 0, `venditore` =  '".$proprietario_id."', `data_scadenza_venditore` = '".$data_scadenza."', data_scadenza = '".$data_scadenza_bdc."'  WHERE id = $profile_rel LIMIT 1",CONNECT);
	actionTracer($tab_id,$profile_rel,5,2,$start_meeting);

	$query = "INSERT INTO `fl_richieste` (`id`, `marchio`,`workflow_id`,`parent_id`, `anagrafica_rel`, `tipo_richiesta`, `data_apertura`, `data_chiusura`, `data_scadenza`, `note`, `operatore`, `data_creazione`, `data_aggiornamento`) 
	VALUES (NULL, '0','106', '$profile_rel', '0', '5', NOW(), '0000-00-00', '$start_meeting', '$note', '".$_SESSION['number']."', NOW(), NOW());";
	mysql_query($query,CONNECT);


	$with = ($proprietario_id > 1) ?  "Chiedi di ".$proprietario[$proprietario_id] : '  ';
	
	$message = str_replace('[*CORPO*]',"<h3>Gentile ".$potential['nome']."</h3>
	<h4>Ti confermiamo l'appuntamento per il ".$start_meeting." $with. </h4>",mail_template);
	
	
	$mail_esito = 'Invio mail disabilitato in DEMO'; 


    /*en*/ $sms = "Ti confermiamo l'appuntamento per il ".mydate(@$start_meeting).".  $with. ";
	mysql_close(CONNECT);
	$_SESSION['NOTIFY'] = "Hai impostato un appuntamento per ".$potential['nome']." ".$potential['cognome'];
	echo json_encode(array('action'=>'goto','class'=>'green','url'=>'../mod_leads/mod_inserisci.php?id='.$potential['id'],'esito'=>"Appuntamento creato!")); 
	@mysql_close(CONNECT);
	exit;

	$sms_esito = 'SMS: Non attivi';//(@$msg = true) ? "SMS Sent to: ".$potential['telefono'].' - From: '.$from : '<span class="red">SMS not sent! </span> '.$msg;
    include("../../fl_inc/headers.php"); 
	echo '<body style=" background: #FFFFFF;"><div id="container" style=" text-align: center;">';
	echo '<h1><strong>Ottimo lavoro '.$proprietario[$_SESSION['number']].'</strong>!</h1>
	<p>'.$mail_esito.' per conferma appuntamento.<br>'.$sms_esito.' <br><br>SMS:  '.$sms.'<br><br>
	<a href="'.$_SESSION['POST_BACK_PAGE'].'" class="small_touch blue_push">Chiudi</a></p>';
	@mysql_close(CONNECT);
	exit;
	
	 } else { echo mysql_error(); exit;	};
	
}

if(isset($_GET['issue'])){
	unset($_SESSION['meeting_doing']);
	unset($_SESSION['potential_rel']);
	
	$id = check($_GET['id']);
	$issue = check($_GET['issue']);
	$profile_rel = check($_GET['profile_rel']);
	$date_arrived = ', `data_arrived` = NOW() ';
	$meeting_issue = "UPDATE `fl_appuntamenti` SET `operatore` = '".$_SESSION['number']."', `issue` = $issue , data_aggiornamento = '".date('Y-m-d H:i:00')."' $date_arrived WHERE id = $id LIMIT 1";
	
	if(mysql_query($meeting_issue,CONNECT)) {
	action_record('modify','fl_appuntamenti',$id,base64_encode($meeting_issue));
	
	if($issue == 3) { $status_p = 8;
	actionTracer(16,$profile_rel,8,'','Non si presenta');
	$update = "UPDATE `".$tables[106]."` SET `status_potential`= '$status_p' , data_aggiornamento = '".date('Y-m-d H:i:00')."' WHERE id = $profile_rel LIMIT 1";
	mysql_query($update,CONNECT);
		$query = "INSERT INTO `fl_richieste` (`id`, `marchio`,`workflow_id`,`parent_id`, `anagrafica_rel`, `tipo_richiesta`, `data_apertura`, `data_chiusura`, `data_scadenza`, `note`, `operatore`, `data_creazione`, `data_aggiornamento`) 
	VALUES (NULL, '0','106', '$profile_rel', '0', '8', NOW(), '0000-00-00', '0000-00-00', 'Non si presenta ad appuntamento', '".$_SESSION['number']."', NOW(), NOW());";
	mysql_query($query,CONNECT);

	}

	if($issue == 7) { $status_p = 1;
	actionTracer(16,$profile_rel,8,'','Visita per appuntamento');
	echo $update = "UPDATE `".$tables[106]."` SET data_visita = NOW(), `status_potential`= '$status_p' , data_aggiornamento = '".date('Y-m-d H:i:00')."' WHERE id = $profile_rel LIMIT 1";
	mysql_query($update,CONNECT);
	$query = "INSERT INTO `fl_richieste` (`id`, `marchio`,`workflow_id`,`parent_id`, `anagrafica_rel`, `tipo_richiesta`, `data_apertura`, `data_chiusura`, `data_scadenza`, `note`, `operatore`, `data_creazione`, `data_aggiornamento`) 
	VALUES (NULL, '0','106', '$profile_rel', '0', '10', NOW(), '0000-00-00', '0000-00-00', 'Visita per appuntamento', '".$_SESSION['number']."', NOW(), NOW());";
	mysql_query($query,CONNECT);

	}


	} else { echo mysql_error();}

	mysql_close(CONNECT);
	$close = (isset($_GET['notab'])) ? '' : '&close' ;
	header("Location: ../mod_leads/mod_inserisci.php?id=".$profile_rel);
	exit;
}


mysql_close(CONNECT);



?>
