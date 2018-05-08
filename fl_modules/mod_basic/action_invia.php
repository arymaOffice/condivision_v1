<?php 

require_once('../../fl_core/autentication.php');

//Inserisci Aggiorna
if(isset($_POST['nome']) || isset($_POST['invio'])) {

// Campi Obbligatori
$obbligatorio = array('nome','messaggio','email');

$messaggio = "";



foreach($_POST as $chiave => $valore){
		
		
		if(in_array($chiave,$obbligatorio)) {
		if($valore == ""){
		$chiave = ucfirst($chiave);
		header("Location: $rct?$val&action=9&esito=Inserire valore cer il campo $chiave");
		exit;}}
		$messaggio .= "<p>$chiave =  $valore</p>";
		}
		
		
		
		$mail_to = mail_admin;
		$mail_subject = "..:: Richiesta di assistenza per: ".$_SERVER['HTTP_HOST'];
		if(isset($_POST['oggetto'])) { $mail_subject = "..:: ".check($_POST['oggetto'])." su: ".$_SERVER['HTTP_HOST'];}
		
		$data = date("d:m:Y - g:i");
		$ip = getenv("remote_addr");
		$messaggio .= "<p>Inviato in data: $data con IP: $ip</p>";
		$mail_body = str_replace("[*CORPO*]",'<h1>Richiesta di Assistenza</h1><p>&nbsp;</p><div style="text-align: left;">'.$messaggio.'</div>',mail_template); 
		
		if(smail($mail_to,$mail_subject,$mail_body,'')) {
		header("Location: ".getenv("HTTP_REFERER")."?action=9&esito=Messaggio Inviato!"); 
		} else {
		header("Location: ".$_SERVER['HTTP_REFERER']."?action=9&esito=Errore invio messaggio!");
		}
		exit; 

} 


?>  
