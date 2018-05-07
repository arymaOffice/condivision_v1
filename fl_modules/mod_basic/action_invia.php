<?php 

require_once('action_check.php');

//Inserisci Aggiorna
if(isset($_POST['nome']) || isset($_POST['invio'])) {

// Campi Obbligatori
$obbligatorio = array('nome','messaggio','email');

$messaggio = "";

	foreach($_POST as $chiave => $valore){
		
		
		if(in_array($chiave,$obbligatorio)) {
		if($valore == ""){
		$chiave = ucfirst($chiave);
		Header("Location: $rct?$val&action=9&esito=Inserire valore cer il campo $chiave");
		exit;}}
		
		$messaggio .= "<p>$chiave =  $valore</p>";
	
		}
		
					
					  $data = date("d:m:Y - g:i");
					  $ip = getenv("remote_addr");
					  
						  
					  $messaggio .= "<p>Inviato in data: $data con IP: $ip</p>";
										  
					  $posta = "michelefazio@aryma.it";
					  $subject = "ASSISTENZA su: ".$_SERVER['HTTP_HOST'];
					  mail($posta,$subject,"<html><head></head><body style=\"font-family: Arial; font-size: 12px; margin: 0px; \"><h1>Richiesta di Assistenza</h1><p>&nbsp;</p><p>&nbsp;</p>
					  $messaggio<br /></body></html>","From: Admin<info@aryma.it>\nContent-Type: text/html; charset=iso-8859-1");
			



Header("Location: ./?action=9&esito=Messaggio Inviato!"); 
exit; 
} //endif		


?>  
