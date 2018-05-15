<?php 


// Controllo Login
session_start(); 
if(!isset($_SESSION['user'])){ header("Location: ../../login.php"); exit; }
require('../../fl_core/core.php'); 


	ini_set('display_errors',1);
    error_reporting(E_ALL);


$tabella = $tables[5]; // ###################################  Modificare la tabella di sezione
$rct = check($_SERVER['HTTP_REFERER']);


if(isset($_GET['new'])) {

$relid = check($_GET['reild']);
$marchio = check($_GET['marchio']);
$tipo_account = check($_GET['tipo']);
$now = do_date(0,2);

$query = "INSERT INTO `fl_anagrafica` (`id`, `status_anagrafica`, `marchio`, `tipo_profilo`, `nome`, `cognome`, `data_nascita`, `luogo_di_nascita`, `codice_fiscale`, `regione`, `provincia`, `comune`, `frazione`, `indirizzo`, `cap`, `telefono`, `cellulare`, `fax`, `email`, `forma_giuridica`, `ragione_sociale`, `partita_iva`, `codice_fiscale_legale`, `regione_sede`, `provincia_sede`, `comune_sede`, `frazione_sede`, `sede_legale`, `cap_sede`, `punto_vendita`, `regione_punto`, `provincia_punto`, `comune_punto`, `frazione_punto`, `indirizzo_punto`, `cap_punto`, `lat`, `lon`, `telefono_punto`, `email_punto`, `sito_web`, `tipo_documento`, `emesso_da`, `data_emissione`, `data_scadenza`, `numero_documento`, `comune_rilascio`, `account_affiliato`, `note`, `data_creazione`, `data_aggiornamento`, `operatore`, `proprietario`, `ip`) 
VALUES (NULL, '', '$marchio', '$tipo_account', '', '', '', '', '', '', '', '', '', NULL, NULL, '', '', '', '', '', '', '', '', '', '', '', '', NULL, NULL, '', '', '', '', '', NULL, NULL, '', '', '', '', 'http://', '', '', '', '', '', '', '', '', '$now', '$now', '', '', '');";
if(!mysql_query($query,CONNECT)) { echo "<h1>Impossibile associare anagrafica</h1>";  exit; }

$lastid = mysql_insert_id(CONNECT);
$query_x = "UPDATE fl_account SET anagrafica = ".$lastid." WHERE id = $relid;";
if(!mysql_query($query_x,CONNECT)) { echo "<h1>Impossibile associare anagrafica</h1>"; 
}else{
header("Location: ../mod_account/?action=12&id=$relid#tab_anagrafica"); exit;
}

}


// Modifica Password
if(isset($_POST['modifica_pass_auto'])){		 								

$id = check($_POST['modifica_pass_auto']);

$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

// Crea una password.
$hash = hash('sha512', time().$random_salt);
$password = substr(md5($hash),0,10);

//Funzione Connect 
$query = "SELECT * FROM `fl_account` WHERE `id` = $id LIMIT 1";
$risultato = mysql_query($query, CONNECT);

if(mysql_affected_rows() == 0){
	
mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']."?&id=$id&esito=Utente Inesistente!");
exit;

} else {

$riga = mysql_fetch_array($risultato);
$inviopass = $password;
$password = md5($password);
//Autenticazione avvenuta
$queryx = "UPDATE `fl_account` SET password = '$password', ip = '$ip', aggiornamento_password = NOW() WHERE id = $id LIMIT 1";

if(mysql_query($queryx, CONNECT)){

// intestazioni addizionali 
$mail_to = $riga['email'];
$mail_subject = ".:: Modifica Password su ".ROOT;
$mail_message = "<p>Modifica password avvenuta con successo! <br />
Le ricordiamo che pu&oacute; accedere all'area riservata da qui: <a href=\"".ROOT.$cp_admin."\" title\"Area Riservata\">".ROOT.$cp_admin."</a>.<br /> </p>
<p>Ecco i nuovi dati per il login: </p><br /> 
Password: <strong>$inviopass</strong></p>
<p>&nbsp;</p>";

$mail_body = str_replace("[*CORPO*]",$mail_message,mail_template); 

if(smail($mail_to, $mail_subject,$mail_body) ) {
header("Location: ".$rct."?&id=$id&success&esito=Password aggiornata e recapitata via email!&amp;indietro=1"); 
exit;
} else {
header("Location: ".$rct."?&id=$id&success&esito=Password aggiornata, recapito e-Mail fallito - RIPROVA!&amp;indietro=1"); 
exit; 
}				 

} else {	

mysql_close(CONNECT); 
header("Location: ".$rct."&id=$id&esito=Problemi nella modifica della password! (Contatta il Web Master)&menu=1"); 
exit;

}

} 

}


// Modifica Password
if(isset($_POST['modifica_pass'])){		 								

$id = check($_POST['modifica_pass']);
$pass1 = trim(check($_POST['password1']));
$pass2 =  trim(check($_POST['password2']));
$data = time();
$ip = $_SERVER['REMOTE_ADDR'];

if($pass1 === $pass2){		
if(!preg_match('/^[A-Za-z0-9]{8,50}$/',$pass1)){
mysql_close(CONNECT);
header("Location: ".$rct."&id=$id&esito=La password deve contenere valori alfanumerici e deve essere di lunghezza tra 8 e 50 caratteri&menu=1)."); 
exit();
}				

$password = md5($pass1);
$query = "SELECT * FROM `fl_account` WHERE `id` = $id LIMIT 1";

$risultato = mysql_query($query, CONNECT);

if(mysql_affected_rows() == 0){
	
mysql_close(CONNECT);
header("Location: ".$rct."&id=$id&esito=Utente Inesistente!");
exit;

} else {
	

$riga = mysql_fetch_array($risultato);
if($password == $riga['password']) {
mysql_close(CONNECT);
header("Location: ".$rct."&id=$id&esito=Non puoi utilizzare la password precedente!");
exit;
}


$user = $riga['user'];
$inviopass = trim($pass1);
$pass1 = md5($inviopass);

$queryx = "UPDATE `fl_account` SET `password`= '$pass1',`ip`='$ip',`data_aggiornamento`=NOW(),`operatore`=".$_SESSION['number'].",`aggiornamento_password`=NOW() WHERE id = $id LIMIT 1";

if(mysql_query($queryx, CONNECT)){

//echo $inviopass." -------".$queryx; exit;
// intestazioni addizionali 
$mail_to = $riga['email'];
$mail_subject = ".:: Modifica Password per $user su ".ROOT;
$mail_message = "<p>Modifica password avvenuta con successo! <br />
Le ricordiamo che pu&oacute; accedere all'area riservata da qui: <a href=\"".ROOT.$cp_admin."\" title\"Area Riservata\">".ROOT.$cp_admin."</a>.<br /> </p>
<p>Ecco i nuovi dati per il login: </p><br /> 
Password: <strong>$inviopass</strong></p>
<p>&nbsp;</p>";

$mail_body = str_replace("[*CORPO*]",$mail_message,mail_template); 

if(smail($mail_to, $mail_subject,$mail_body ) ) {
if($_SESSION['aggiornamento_password'] < -80) {
header("Location: ".ROOT.$cp_admin."fl_core/login.php"); 
} else {
header("Location: ".$rct."&id=$id&success&esito=Password aggiornata e recapitata via email!&amp;indietro=1"); 
}
exit;

} else {
if($_SESSION['aggiornamento_password'] < -80) {
header("Location: ".ROOT.$cp_admin."fl_core/login.php"); 
} else {
header("Location: ".$rct."&id=$id&success&esito=Password aggiornata, recapito e-Mail fallito!&amp;indietro=1"); 
}
exit; 
}				 


} else {	

mysql_close(CONNECT); 
header("Location: ".$rct."&id=$id&esito=Problemi nella modifica della password! (Contatta il Web Master)".mysql_error()); 
exit;

}


} 

} else {

}
header("Location: ".$rct."&id=$id&esito=Corrispondenza password errata!");
exit;
}



// Elimina Record
if(isset($_GET['delete'])) { 


if(!is_numeric($_GET['id'])) exit;

$id = $_GET['id'];	
$file = ($_GET['file'] != "" || $_GET['file'] != 0) ? check($_GET['file']) : "nofile";	

$query2 = "DELETE FROM $tabella WHERE id = '$id' LIMIT 1";

mysql_query($query2, CONNECT);	
mysql_close(CONNECT);

header("Location: ".$rct);
exit;	




}


//principale
if(isset($_POST['account'])){


$tipo = check($_POST['account']);	
$attivo = check($_POST['attivo']);	
$persona_id = check($_POST['persona_id']);	
		
$nominativo = check($_POST['nominativo']);
$ip_accesso = check($_POST['ip_accesso']);
$anagrafica = check($_POST['anagrafica']);
$sede = 0;
$marchio = check($_POST['marchio']);
$foto = "";
$giorno = check(@$_POST['giorno']);
$mese = check(@$_POST['mese']);
$anno = check(@$_POST['anno']);
$email  = check($_POST['email']);	
$email2  = check($_POST['email2']);
$user  = (isset($_POST['user'])) ? trim(str_replace(' ','',check($_POST['user']))) : $email; 		  
$password =  trim(check($_POST['password']));
$password2 =  trim(check($_POST['password2']));
$data = time();
$ip = getenv("REMOTE_ADDR");


// Check Email
if($email != $email2) { 
//header("Location: ./?esito=Email, corrispondenza errata.&menu=1"); 
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Email, corrispondenza errata")); 
mysql_close(CONNECT);
exit;}	

if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
//header("Location: ./?esito=Inserire una email valida.&menu=1"); 
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire una email valida")); 
mysql_close(CONNECT);
exit;}

if(isset($_POST['user'])) {
if(!preg_match('/^[A-Za-z0-9]{3,25}$/',$user)){
//header("Location: ./?esito=Username Non valido. Deve contenere valori alfanumerici e deve essere di lunghezza tra 6 e 20 caratteri&menu=1)."); 
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Username Non valido. Deve contenere valori alfanumerici e deve essere di lunghezza tra 3 e 25 caratteri")); 
mysql_close(CONNECT);
exit;
}				
}

// Impostazione Automatica Password
if(isset($_POST['auto_pass'])){ 

$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
// Crea una password.
$hash = hash('sha512', time().$random_salt);
$password =  trim(substr(md5($hash),0,10));

$pass = md5($password);
$inviopass = $password;	

} else {

//Controllo Password
if(!preg_match('/^[A-Za-z0-9]{8,50}$/',$password)){
//header("Location: ./?esito=La password deve contenere valori alfanumerici e deve essere di lunghezza tra 8 e 15 caratteri&menu=1)."); 
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"La password deve contenere valori alfanumerici e deve essere di lunghezza tra 8 e 50 caratteri")); 
mysql_close(CONNECT);
exit;
}

if($password === $password2) { 
$pass = md5($password);
$inviopass = $password;	
}	else {					   
//header("Location: ./?esito=Password, corrispondenza errata.&menu=1"); 
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Password, corrispondenza errata")); 
mysql_close(CONNECT);
exit;
}
}



// Funzione 
if($nominativo != "" && $email != ""){

$nominativo = ucfirst($nominativo);
$data_nascita = $anno.'-'.$mese.'-'.$giorno;
$query = "SELECT user,email FROM `fl_account`  WHERE `user`  = '$user' || `email`  = '$email'";
$risultato = mysql_query($query,CONNECT);

if(mysql_affected_rows() > 0){
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Esiste un utente con questa mail o username")); 
mysql_close(CONNECT);
exit;
}



$sql = "INSERT INTO `fl_account` (`id` ,`cuid`,`uid`,`motivo_sospensione`,`attivo` ,`anagrafica` ,`marchio` ,`tipo` ,`processo_id` , `persona_id` ,`email` ,`nominativo` ,`ip_accesso` ,`user` ,`password` ,`foto` ,`visite` ,`ip` ,`data_creazione`,`data_scadenza`,`data_aggiornamento`,`operatore`, `aggiornamento_password`)
					 VALUES ( NULL ,1,1, '','$attivo', '$anagrafica', '$marchio', '$tipo',0,'$persona_id','$email','$nominativo','$ip_accesso', '$user', '$pass','$foto', '0', '$ip', NOW(), '2050-12-31', NOW(), '".$_SESSION['number']."',  NOW() );";

if($anagrafica == 0) { 

}



if(mysql_query($sql, CONNECT)){
//Inserita
$account_id = mysql_insert_id();

$permessi = '';

include('../../fl_core/dataset/array_statiche.php');
require('../../fl_core/class/ARY_dataInterface.class.php');
$data_set = new ARY_dataInterface();
$moduli = $data_set->data_retriever('fl_moduli','label','WHERE id > 1 AND attivo = 1');


foreach($moduli as $chiave => $valore) {
if($chiave > 1) {
$modulo_id = GRD('fl_moduli',$chiave);
$default_value = $modulo_id['accesso_predefinito'];
$permesso = "INSERT INTO `fl_permessi` (`id`, `account_id`, `modulo_id`, `livello_accesso`) VALUES (NULL, '$account_id', '$chiave', '$default_value');";
mysql_query($permesso,CONNECT);
$permessi .= '<p>Permesso per '.$valore.': '.@$livello_accesso[$default_value].mysql_error().'</p>';
}}


mysql_query("UPDATE `fl_anagrafica` SET `account_affiliato` = '$user' WHERE `id` = $anagrafica LIMIT 1;",CONNECT);

//mkdir("$files".ucfirst($user),0777);
// intestazioni addizionali 

$mail_to= $email;
$mail_subject = ".:: Attivazione account ".sitename."";
$mail_message = "<h3>Il tuo account  &egrave; attivo</h3>
<p>Puoi accedere al gestionale da qui: <a href=\"".ROOT.$cp_admin."\" title\"Area Riservata\">".ROOT.$cp_admin."</a>.<br /> </p>
<h3>Credenziali di accesso</h3> 
User: <strong>$user</strong><br />
Password: <strong>$inviopass</strong>
<p>N.B. La password, potr&agrave; essere modificata una volta eseguito il login.</p>
<p>&nbsp;</p>";

$mail_body = str_replace("[*CORPO*]",$mail_message,mail_template); 
$mail_body2 = str_replace("[*CORPO*]",$mail_message.$permessi,mail_template); 


if(smail($mail_to,$mail_subject,$mail_body,'') ) {
smail(mail_admin,$mail_subject,$mail_body2,'');
//header("Location: ./?success&esito=Nuovo Utente Creato e Mail inviata correttamente!&indietro=1"); 
echo json_encode(array('action'=>'goto','class'=>'green','url'=>check(@$_POST['reload']).$account_id.'&esito=Nuovo utente Creato&success','esito'=>"Nuovo Utente Creato e Mail inviata correttamente!")); 
mysql_close(CONNECT);
exit;

} else {
//header("Location: ./?success&esito=Nuovo Utente Creato e Recapito e-Mail fallito!&indietro=1"); 
echo json_encode(array('action'=>'goto','class'=>'green','url'=>check(@$_POST['reload']).$account_id.'&esito=Nuovo utente Creato&success','esito'=>"Nuovo Utente Creato. Recapito e-Mail fallito.")); 
mysql_close(CONNECT);
exit;
}				 


} else {	

//header("Location: ./?esito=Errore 1101: Problemi nell'inserimento in database.(Contatta il Web Master)&menu=1"); 
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Errore 1101: Problemi nell'inserimento in database.".mysql_error())); 
mysql_close(CONNECT);
exit;

}


// Mancato inserimento Campi		  
} else {

echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserisci campi obbligatori!")); 
mysql_close(CONNECT);
exit;


}//chiudi funzione 

}//chiudi principale

if(isset($_GET['user_id']) && $_SESSION['usertype'] == 0) {
$userid = check($_GET['user_id']);

$profilo = "INSERT INTO `fl_profili` (`id` ,`attivo` ,`proprietario`) VALUES (NULL , '0', '$userid');";
$profilod = $userid;
if(mysql_query($profilo, CONNECT)) $profilod = "Creazione profilo utente avvenuta.";
header("Location: ./?esito=$profilod!&indietro=1"); 
exit; 

}
					
?>  
