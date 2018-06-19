<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 


if(isset($_GET['verifica_mail'])){
	
	$id = base64_decode(check($_GET['verifica_mail']));
	$code = base64_encode($id.time());
	$query = "UPDATE $tabella SET codice_verifica_mail = '$code' WHERE id = $id LIMIT 1";
	if(!mysql_query($query,CONNECT)) { 
	echo "Impossibile procedere".mysql_error();  exit;
	} else {
	$record = GRD($tabella,$id);
	$mail_message = "<h2>Richiesta di verifica email account Betitaly</h2><p>Per procedere con l'adeguata verifica del tuo account clicca sul <strong> codice di sblocco</strong> sottostante:<br> <a href=\"https:".ROOT.$cp_admin."fl_sync/?auth=$code\">https:".ROOT.$cp_admin."fl_sync/?auth=$code</a></p>";
	$mail_body = str_replace("[*CORPO*]",$mail_message,mail_template);
	if(!smail($record['email'],'Richiesta di verifica email account Betitaly',$mail_body)) echo "Impossibile Inviare la mail";
	}
}

mysql_close(CONNECT);
header("location: ".$_SESSION['POST_BACK_PAGE']);
exit;




?>
