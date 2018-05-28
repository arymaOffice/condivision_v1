<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 



$baseref = explode('?', $_SERVER['HTTP_REFERER']);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];

if(isset($_GET['set_meeting'])){
	
	$profile_rel = check($_GET['profile_rel']);
	$proprietario_id  = check($_GET['proprietario']);
	$meeting_date  = check($_GET['meeting_date']);
	$meeting_time  = check($_GET['meeting_time']);
	$potential = get_potential( $profile_rel ); 

	
	$query = "INSERT INTO `fl_meeting_agenda` (`id`, `meeting_date`, `meeting_time`, `potential_rel`, `note`, `issue`, `operatore`, `proprietario`, `data_creazione`, `data_aggiornamento`) 
	VALUES (NULL, '".$meeting_date."', '".$meeting_time."', '".$profile_rel."', '', '', '".$operatore."', '".$proprietario_id."', '".date('Y-m-d H:i:00')."','".date('Y-m-d H:i:00')."' )";
	if(mysql_query($query,CONNECT)) { 
	mysql_query("UPDATE `fl_potentials` SET `status_potential`= 2 , data_aggiornamento ='".date('Y-m-d H:i:00')."', `in_use` = '0' WHERE id = $profile_rel LIMIT 1",CONNECT);
	$with = ($proprietario_id > 1) ?  " with ".$proprietario[$proprietario_id] : '';
	$message = str_replace('[*CORPO*]',"<h3>Dear ".$potential['nome']."</h3><h4>We confirm your meeting on ".mydate($meeting_date)." at ".$meeting_time."$with. </h4><br>Our office is in <strong>37-39, Oxford Street W1D 2DU</strong> (Near Tottenham C.R. Station).<br>Dont forget to bring your CV.",mail_template);
	$mail_esito = (smail($potential['email'],"Confirmation of meeting ",$message)) ? 'Mail sent at '.$potential['email'] : 'Mail not sent!';
	
// Download the library and copy into the folder containing this file.
require('../../fl_set/librerie/twilio-php-master/Services/Twilio.php');

$sms = "Hi, this is to confirm your meeting on ".mydate(@$meeting_date)." at ".@$meeting_time." with ".@$proprietario[@$proprietario_id].".ETL Recruitment office is in 37-39, Oxford Street W1D 2DU.";
$account_sid = "AC9199afc4295cc9ca6037f2f7081433e6"; // Your Twilio account sid
$auth_token = "ce99ccefff907d9dc9e287898d13fb25"; // Your Twilio auth token
if(isset($_GET['sms'])){
$client = new Services_Twilio($account_sid, $auth_token);
$message = $client->account->messages->sendMessage(
  '+442030956487', // From a Twilio number in your account
  $potential['telefono'], // Text any number
  $sms

);
}

	
	
	mysql_close(CONNECT);
	 $sms_esito = (@$message->sid != '') ? "SMS Sent to: ".$potential['telefono'] : 'SMS not sent!';
	 include("../../fl_inc/headers.php"); 
	 echo '<body style=" background: #FFFFFF;"><div id="container" style=" text-align: left;">';
	echo '<h1><strong>Good Job '.$proprietario[$_SESSION['number']].'</strong></h1><p>'.$mail_esito.'  to confirm the meeting.<br>'.$sms_esito.' <br><br>Message:  '.$sms.'<br> <br>  <a href="javascript:parent.$.fancybox.close();">Close this job</a></p>';
	exit;
	
	 } else { echo mysql_error(); };
	
}

if(isset($_GET['issue'])){
	$id = check($_GET['id']);
	$issue = check($_GET['issue']);
	$profile_rel = check($_GET['profile_rel']);
	if(mysql_query("UPDATE `fl_meeting_agenda` SET `issue`= $issue , data_aggiornamento = '".date('Y-m-d H:i:00')."' WHERE id = $id LIMIT 1",CONNECT)) {
	if($issue < 3 || $issue > 4) $status_p = 2;
	if($issue == 3) $status_p = 1;
	if($issue == 4) $status_p = 3;
	$update = "UPDATE `fl_potentials` SET `status_potential`= '$status_p' , data_aggiornamento = '".date('Y-m-d H:i:00')."' WHERE id = $profile_rel LIMIT 1";
	//echo $update; exit;
	mysql_query($update,CONNECT);
	} else { echo mysql_error();}

	mysql_close(CONNECT);
	$close = (isset($_GET['notab'])) ? '' : '&close' ;
	header("location: ".$_SERVER['HTTP_REFERER'].$close);
	exit;
}
mysql_close(CONNECT);
header("location: ".$_SERVER['HTTP_REFERER']);
exit;




?>
