<?php 


session_start();
header('Content-Type: text/html; charset=UTF-8');
ini_set('default_charset', 'utf-8');
$_SESSION['user'] = 'menu';
$_SESSION['number'] = 1;
$_SESSION['landing'] = 1;
define('NOSSL',1);


require_once("../../fl_core/core.php"); 
require_once(ROOTPATH."array_statiche.php");
$rct = 'index.php';


if(isset($_POST['oggetto']) && isset($_SESSION['form'])) {



$oggetto = check($_POST['oggetto']);
$dati = "<h4>".$oggetto."</h4><br />";
$messaggio = $dati;
$mail_message = '';





foreach($_POST as $chiave => $valore){
		if(!is_array($valore)){

			$valoritxt = $chiave.'_risposte';
			if(isset($$valoritxt)) { 
				$vas = $$valoritxt;
				$mail_value = $vas[$valore];
			} else {  $mail_value = cleanAll($valore); }

			$$chiave = check($valore);		
			$mail_message .= '<p>'.@$domande[$chiave].' = '.$mail_value.'</p>';
	    } else {
			$$chiave =implode(',',$valore);
			$mail_message .= '<p>'.@$domande[$chiave].' = '.cleanAll($$chiave).'</p>';
	    }
}


// Campi Obbligatori
$obbligatorio = array('domanda1','domanda2','domanda3');
$campi = array('domanda1', 'domanda2', 'domanda3','domanda4','domanda5','lead_rel');

foreach($campi as $chiave => $valore){
	if(!isset($$valore)) $$valore = ''; //Compila la variabile vuota per il DB
	
	if(in_array($$valore,$obbligatorio) && $$valore == '') { 
	echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Compila le domande obbligatorie!")); 
	exit;
	}
}

$lead_rel = base64_decode(check($_POST['lead_rel']));
$lead = @GRD($tables[106],$lead_rel);
$oggetto .= ' da '.@$lead['nome'].' '.@$lead['cognome'];
$messaggioSQL = '[FEEDBACK]'.strip_tags($mail_message);
$mail_body = str_replace("[*CORPO*]",$mail_message,mail_template); 




if(smail(mail_user,$oggetto,$mail_body)){

	
	$query = "INSERT INTO `fl_feedback` (`id`, `lead_rel`, `domanda1`, `domanda2`, `domanda3`, `domanda4`, `domanda5`, `commento`, `data_creazione`) 
			         VALUES (NULL, '$lead_rel', '$domanda1', '$domanda2', '$domanda3', '$domanda4','$domanda5',  '$commento', NOW());";

	if(mysql_query($query,CONNECT)) { $ok = '#';  
	mysql_query('UPDATE '.$tables[106].' SET status_potential = 2 WHERE id = '.$lead_rel.' LIMIT 1',CONNECT); 

	smail(mail_admin,$oggetto.' '.mysql_error(), $mail_body);
	if(defined('mail_copy')) smail(mail_copy,$oggetto, $mail_body);

	} else {  $ok = ''; smail(mail_admin,"Errore ::. invio Feedback su ".ROOT, $mail_body.mysql_error()); }



	echo json_encode(array('action'=>'info','class'=>'green','url'=>$ok,'esito'=>"Grazie per aver lasciato un feedback!")); 
	mysql_close(CONNECT);
	exit;

} else {
	mysql_close(CONNECT);
	echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Grazie per aver lasciato un feedback! Non riusciamo ad inviarlo. Riprova ")); 
	smail(mail_admin,"Errore ::. invio Feedback su ".ROOT, $mail_body);
	exit;
}				 

	unset($_SESSION['form']);				

}




?>