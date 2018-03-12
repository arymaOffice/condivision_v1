<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

	echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>$_GET['set_meeting'])); 
	@mysql_close(CONNECT);
	exit;

if(isset($_GET['set_meeting'])){

	$profile_rel = check($_GET['profile_rel']);
	$proprietario_id  = check($_GET['proprietario']);
	$start_meeting  = dbdatetime(check($_GET['start_meeting']),1);
	$end_meeting  = date('Y-m-d H:i:00',strtotime($start_meeting .' +30 minutes'));
	$potential = GRD($tables[106], $profile_rel ); 
	$lang = (isset($_GET['lang'])) ? check($_GET['lang']) : 'en';
	$meeting_location = (isset($_GET['meeting_location'])) ? check($_GET['meeting_location']) : 0;


    $check = "SELECT start_meeting,end_meeting FROM fl_appuntamenti WHERE proprietario = $proprietario_id AND ((start_meeting BETWEEN '$start_meeting' AND '$end_meeting') OR (end_meeting BETWEEN '$start_meeting' AND '$end_meeting'));";
	mysql_query($check,CONNECT);
	$conflitti = mysql_affected_rows();
    $check = "SELECT start_date,end_date FROM fl_calendario WHERE proprietario = $proprietario_id AND (start_date < '$start_meeting' AND end_date > '$end_meeting');";
	mysql_query($check,CONNECT);
	$conflitti += mysql_affected_rows();
	
	if($conflitti > 0){
	echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Attenzione! Ci sono altri appuntamenti o impegni in questo orario")); 
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
	
	$query = "INSERT INTO `fl_appuntamenti` (`id`, `marchio`,`meeting_location`,  `start_meeting`, `end_meeting`, `potential_rel`,`nominativo`, `note`, `issue`,`callcenter`, `operatore`, `proprietario`, `data_creazione`, `data_aggiornamento`,`data_arrived`) 
	VALUES (NULL, '".$_SESSION['marchio']."' , '".$meeting_location."' ,'".$start_meeting."', '".$end_meeting."', '".$profile_rel."', '".$potential['nome']." ".$potential['cognome']."', '', '0','".$_SESSION['number']."', '".$_SESSION['number']."', '".$proprietario_id."', '".date('Y-m-d H:i:00')."','".date('Y-m-d H:i:00')."','0000-00-00 00:00:00')";
	
	if(mysql_query($query,CONNECT)) { 
	action_record('create','fl_appuntamenti',0,base64_encode($query));
	mysql_query("UPDATE `fl_potentials` SET `status_potential`= 2 , data_aggiornamento ='".date('Y-m-d H:i:00')."', `in_use` = 0, `proprietario` =  '".$proprietario_id."' WHERE id = $profile_rel LIMIT 1",CONNECT);

	actionTracer($tab_id,$profile_rel,5,2,$start_meeting);
	$with = ($proprietario_id > 1) ?  "Chiedi di ".$proprietario[$proprietario_id] : '  ';
	
	$message = str_replace('[*CORPO*]',"<h3>Gentile ".$potential['nome']."</h3>
	<h4>Ti confermiamo l'appuntamento per il ".$start_meeting." $with. </h4>",mail_template);
	
	
	$mail_esito = 'Invio mail disabilitato in DEMO'; //(smail($potential['email'],"Confirmation of Job meeting",$message,'','no-reply@etlapp.com','Recruitment Office')) ? 'Mail sent at '.$potential['email'] : 'Mail not sent '; 
   
    /*en*/ $sms = "Ti confermiamo l'appuntamento per il ".mydate(@$start_meeting).".  $with. ";
	mysql_close(CONNECT);
	
	echo json_encode(array('action'=>'info','class'=>'green','url'=>'','esito'=>"Appuntamento creato!")); 
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

?>  
