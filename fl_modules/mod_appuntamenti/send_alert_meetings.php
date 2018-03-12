<?php 



ini_set('display_errors',1);
error_reporting(E_ALL);

require_once('../../fl_core/settings.php');
include('fl_settings.php'); // Variabili Modulo 



    $sms_esito = '<h1><strong>ALERT SMS SENT</strong></h1><br>';
	$period = (isset($_GET['morning'])) ? " AND `meeting_time` < '14:00' " : " AND `meeting_time` > '14:00' ";
	$mail_subject = (isset($_GET['morning'])) ? " Morning Alerts " : " Afternoon Alerts ";

	$query = "SELECT * FROM `fl_meeting_agenda` WHERE `meeting_date` = '".date('Y-m-d')."' $period  ";
	
	if($risultato = mysql_query($query, CONNECT)) { 

	while ($riga = mysql_fetch_array($risultato)) 
	{

	$meeting_date = $riga['meeting_date'];
	$meeting_time = $riga['meeting_time'];
	$proprietario_id = $riga['proprietario'];
	$potential = get_potential( $riga['potential_rel'] ); 	
    $telefono = phone_format($potential['telefono']);
	$with = ($proprietario_id > 1) ?  " with ".$proprietario[$proprietario_id] : '';
    $sms = "Hi, ".$potential['nome']." this is to remember your meeting on ".mydate(@$meeting_date)." at ".@substr($meeting_time,0,5)." $with. Recruitment Office is at 4th Floor, 37/39 Oxford Street, W1D 2DU.";
	if($potential['marchio'] == 2) $sms = "Te confirmamos la entrevista del día ".mydate(@$meeting_date)." a las ".@substr($meeting_time,0,5)." horas. Estamos en la Calle de la Montera 9 planta 3 puerta 1. Metro Sol. Por favor no olvides traer tu Curriculum y DNI. Para más información puedes contactarme al número 675921837";
	$from = ($potential['marchio'] < 2) ? number1 : number3;

if(!isset($_GET['demo'])){
$msg = sms($potential['telefono'],$sms,$from); 
}


$sms_esito .= (@$msg = true) ? "SMS Sent to: ".$telefono.' '.$potential['nome'].'<br>'  : "SMS <strong>not</strong> sent to: ".$telefono.' '.$potential['nome'].'<br>'.$msg.'<br>';


}}
	
$mail_body = str_replace("[*CORPO*]",$sms_esito,mail_template); 

if(isset($_GET['demo'])) echo $mail_body;
smail('michelefazio@aryma.it', $mail_subject.$_SERVER['HTTP_HOST'].$_SERVER['REMOTE_ADDR'],$mail_body);

mysql_close(CONNECT);
exit;




?>
