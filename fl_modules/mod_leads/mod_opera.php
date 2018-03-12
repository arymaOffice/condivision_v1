<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$baseref = explode('?', $_SERVER['HTTP_REFERER']);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];


if(isset($_POST['azione'])){
$azione = check($_POST['azione']);
switch($azione) {

case 1:		 // assegna leads
$fileArray= array();
$assegna_a = check($_POST['assegna_leads']); 
$giorni = check($_POST['scadenza']);
$data_scadenza = date('Y-m-d', strtotime("+$giorni days"));
$tipo_account = get_tipo_utenza($assegna_a);
$campoProprietario = ($tipo_account[0] == 3) ? 'proprietario' : 'venditore';
$campo_scadenza = ($tipo_account[0] == 3) ? 'data_scadenza' : 'data_scadenza_venditore';


if(isset($_POST['leads'])) {
foreach($_POST['leads'] as $chiave => $valore) { 
$query = "UPDATE $tabella SET $campoProprietario = $assegna_a, data_assegnazione = NOW(), $campo_scadenza  = '$data_scadenza' WHERE id = $valore LIMIT 1";
mysql_query($query,CONNECT);
/*$query2 = "UPDATE $tabella SET status_potential = 1 WHERE id = $valore AND status_potential = 0 LIMIT 1";
mysql_query($query2,CONNECT);*/

} 
$_SESSION['NOTIFY'] = "Contatti assegnati a ".$proprietario[$assegna_a];
if($assegna_a > 1) { 
echo json_encode(array('action'=>'popup','class'=>'green','url'=>'../mod_notifiche/mod_invia.php?iframe=1&checkAll=on&oggetto=Assegnazione nuovi Leads&destinatario[]='.$assegna_a,'esito'=>"Contatti assegnati a ".$proprietario[$assegna_a])); 
} else { echo json_encode(array('action'=>'goto','class'=>'green','url'=>$_SESSION['POST_BACK_PAGE'],'esito'=>"Contatti Rilasciati")); 
}
@mysql_close(CONNECT);
exit;
} else {
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Nessun contatto selezionato")); 
@mysql_close(CONNECT);
exit;
}
break;

case 2:		 // assegna leads
$fileArray= array();
$assegna_a = check($_POST['assegna_venditore']); 
$giorni = check($_POST['scadenza']);
$data_scadenza = date('Y-m-d', strtotime("+$giorni days"));
$tipo_account = get_tipo_utenza($assegna_a);
$campoProprietario = ($tipo_account[0] == 3) ? 'proprietario' : 'venditore';
$campo_scadenza = ($tipo_account[0] == 3) ? 'data_scadenza' : 'data_scadenza_venditore';


if(isset($_POST['leads'])) {
foreach($_POST['leads'] as $chiave => $valore) { 
$query = "UPDATE $tabella SET $campoProprietario = $assegna_a, data_assegnazione = NOW(), $campo_scadenza  = '$data_scadenza' WHERE id = $valore LIMIT 1";
mysql_query($query,CONNECT);
//$query2 = "UPDATE $tabella SET status_potential = 1 WHERE id = $valore AND status_potential = 0 LIMIT 1";
//mysql_query($query2,CONNECT);

} 
$_SESSION['NOTIFY'] = "Contatti assegnati a ".$proprietario[$assegna_a];
if($assegna_a > 1) { 
echo json_encode(array('action'=>'popup','class'=>'green','url'=>'../mod_notifiche/mod_invia.php?iframe=1&checkAll=on&oggetto=Assegnazione nuovi Leads&destinatario[]='.$assegna_a,'esito'=>"Contatti assegnati a ".$proprietario[$assegna_a])); 
} else { echo json_encode(array('action'=>'goto','class'=>'green','url'=>$_SESSION['POST_BACK_PAGE'],'esito'=>"Contatti Rilasciati")); 
}
@mysql_close(CONNECT);
exit;
} else {
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Nessun contatto selezionato")); 
@mysql_close(CONNECT);
exit;
}
break;


case 3:		 // invia SMS
if(isset($_POST['leads'])) {
unset($_SESSION['destinatari']);	
foreach($_POST['leads'] as $chiave => $valore) { 
$_SESSION['destinatari'][$chiave] = check($valore);
}}
echo json_encode(array('action'=>'popup','class'=>'green','url'=>'mod_invia_sms.php?'.$_SERVER['QUERY_STRING'],'esito'=>"Elaborazione SMS")); 
mysql_close(CONNECT);
exit;
break;

case 4:		 // invia email
if(isset($_POST['leads'])) {
unset($_SESSION['destinatari']);	
foreach($_POST['leads'] as $chiave => $valore) { 
$_SESSION['destinatari'][$chiave] = check($valore);
}}
echo json_encode(array('action'=>'popup','class'=>'green','url'=>'mod_invia_mail.php?'.$_SERVER['QUERY_STRING'],'esito'=>"Elaborazione Email")); 
mysql_close(CONNECT);
exit;
break;

case 5:		 // Esport contatti
if(isset($_POST['leads'])) {
unset($_SESSION['destinatari']);	
foreach($_POST['leads'] as $chiave => $valore) { 
$_SESSION['destinatari'][$chiave] = check($valore);
}}
echo json_encode(array('action'=>'popup','class'=>'green','url'=>'mod_esporta.php?'.$_SERVER['QUERY_STRING'],'esito'=>"Esportazione Contatti")); 
mysql_close(CONNECT);
exit;
break;

case 6:		 // cambia status
$status = check($_POST['status_potential']);
if(isset($_POST['leads'])) {
foreach($_POST['leads'] as $chiave => $valore) { 
$query = "UPDATE `$tabella` SET `status_potential` = '$status', `in_use` = '0' , data_aggiornamento = '".date('Y-m-d H:i')."' , `operatore` =  '".$_SESSION['number']."' WHERE id = '$valore';";
mysql_query($query,CONNECT);
}}
$_SESSION['NOTIFY'] = "Contatti in stato: ".$status_potential[$status];
echo json_encode(array('action'=>'goto','class'=>'green','url'=>'./?status_potential='.$status,'esito'=>"Contatti Spostati")); 
mysql_close(CONNECT);
exit;
break;

}}




if(isset($_GET['status_potential'])){
	
	$status = check($_GET['status_potential']);
	$id = check($_GET['id']);
	$query = "UPDATE `$tabella` SET `status_potential` = '$status', `in_use` = '0' , data_aggiornamento = '".date('Y-m-d H:i')."' , `operatore` =  '".$_SESSION['number']."' WHERE id = '$id';";
	mysql_query($query,CONNECT);
	mysql_close(CONNECT);
	header("location: ".$_SESSION['POST_BACK_PAGE'].'&close');
	exit;}
	
if(isset($_GET['unlock']) || isset($_GET['notanswered'])){
	$id = check($_GET['id']);
	$query = "UPDATE `$tabella` SET `in_use` = '0', `data_aggiornamento` = '".date('Y-m-d H:i')."' , `operatore` =  '".$_SESSION['number']."' WHERE id = '$id';";
	if(!isset($_GET['notanswered'])) mysql_query($query,CONNECT);
	
	
	
	mysql_close(CONNECT);
	header("location: ".$_SESSION['POST_BACK_PAGE'].'&close=Contact Unlockled. Please close this tab.');
	exit;
	}


if(isset($_GET['synapsy'])) {
$_SESSION['synapsy'] = check($_GET['synapsy']);
$nominativo = GRD($tabella,$_SESSION['synapsy']);
$_SESSION['synapsy_info'] = 'Associa: '.$nominativo['nome'].' '.$nominativo['cognome'];
mysql_close(CONNECT);
header('Location: '.$_SESSION['POST_BACK_PAGE']);
exit;
}

if(isset($_GET['disaccoppia'])) {
mysql_query('DELETE FROM fl_synapsy WHERE id = '.check($_GET['disaccoppia']).' LIMIT 1',CONNECT);
mysql_close(CONNECT);
header('Location: '.$_SESSION['POST_BACK_PAGE']);
exit;
}

if(isset($_GET['unset'])) {
  unset($_SESSION['synapsy']);
  unset($_SESSION['synapsy_info']);
  mysql_close(CONNECT);
  header('Location: '.$_SESSION['POST_BACK_PAGE']);
  exit;
}

if(isset($_GET['connect'])) {
		  $connetti = check($_GET['connect']);
  		  $query = "INSERT INTO `fl_synapsy` (`type1`, `id1`, `type2`, `id2`, `valore`) VALUES ('$tab_id', '$connetti', '$tab_id', '".$_SESSION['synapsy']."', '1')";
		  mysql_query($query,CONNECT);
		  unset($_SESSION['synapsy']);
		  unset($_SESSION['synapsy_info']);
		  mysql_close(CONNECT);
		  header('Location: '.$_SESSION['POST_BACK_PAGE']);
		  exit;
}

if(isset($_POST['tipo_richiesta'])) {

$workflow_id = $tab_id;
$tipo_richiesta = check($_POST['tipo_richiesta']);
$note = (check($_POST['interlocutore']) != '') ? '[Interlocutore: '.check($_POST['interlocutore']).'] - '.check($_POST['note']) : check($_POST['note']);
$anagrafica_id = (isset($_POST['anagrafica_id'])) ? check($_POST['anagrafica_id']) : 0;
$data_scadenza = convert_data(check($_POST['data_scadenza']),1);
$parent_id = check($_POST['parent_id']);


$query = "INSERT INTO `fl_richieste` (`id`, `marchio`,`workflow_id`,`parent_id`, `anagrafica_rel`, `tipo_richiesta`, `data_apertura`, `data_chiusura`, `data_scadenza`, `note`, `operatore`, `data_creazione`, `data_aggiornamento`) 
VALUES (NULL, '0','$workflow_id', '$parent_id', '$anagrafica_id', '$tipo_richiesta', NOW(), '0000-00-00', '$data_scadenza', '$note', '".$_SESSION['number']."', NOW(), NOW());";
mysql_query($query,CONNECT);
if(mysql_error())  { echo mysql_error(); exit; }

if(isset($_POST['status_potential'])) {
$status = check($_POST['status_potential']);

$campo_scadenza = ($_SESSION['usertype'] == 4) ? 'data_scadenza_venditore' : 'data_scadenza';
//$cancella_scadenza = ($_SESSION['usertype'] == 3) ? 'data_scadenza_venditore' : 'data_scadenza';`$cancella_scadenza` = '',
$scadenza = (isset($data_scadenza)) ? " `$campo_scadenza` = '$data_scadenza', " : ''; //Imposta scadenza a 90gg per cliente non interessato o passato a concorrenza

$query = "UPDATE `$tabella` SET $scadenza `status_potential` = '$status', `in_use` = '0' , data_aggiornamento = '".date('Y-m-d H:i')."' , `operatore` =  '".$_SESSION['number']."' WHERE status_potential != 4 AND id = '$parent_id';";
mysql_query($query,CONNECT);

}

mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER'].'&esito=Operazione Registrata!'); 

exit;

}


if (isset($_POST['inviaSMS'])) 
{

  
  unset($_SESSION['destinatari']);
  $testo = $_POST['messaggio'];
  $workflow_id = $tab_id;
  $anagrafica_id = (isset($_POST['anagrafica_id'])) ? check($_POST['anagrafica_id']) : 0;

  $template = (isset($_POST['templateId'])) ? check($_POST['templateId']) : 0;
  $mittente = (isset($_POST['mittente'])) ? check($_POST['mittente']) : 0;
  if($mittente > 0) $mittente = GRD('fl_items',$mittente);
  $from = ($mittente > 0) ? $mittente['label'] : from;
  
  $sent = 0;
  $error = '';

  foreach($_POST['destinatario'] as $dest_id){ 
	
	$destinatario = GRD($tabella,$dest_id);
	$to = phone_format($destinatario['telefono'],'39');
	$messaggio = $testo;
	$messaggio = str_replace('[nome]',$destinatario['nome'].' ',$messaggio);
	$messaggio = str_replace('[cognome]',$destinatario['cognome'].' ',$messaggio);
	$messaggio = str_replace('[cry_lead_id]',base64_encode($destinatario['id']).' ',$messaggio);
	$messaggio = str_replace('[cry_customer_id]',base64_encode($destinatario['id']).' ',$messaggio);

  	if(is_numeric($to))  { 

  		$invio =  sms($to,$messaggio,$from);
		
		if($invio == 1){
		$sent++; 
		$saveSMS = "INSERT INTO `fl_sms` (`id`, `status`, `template`, `from`, `to`, `body`, `data_ricezione`, `data_creazione`) VALUES (NULL, '1', '$template', '$from', '$to', '$messaggio', '', NOW());"; // Registra SMS inviato
        mysql_query($saveSMS,CONNECT);
		$smsId = mysql_insert_id(); 
		$query = "INSERT INTO `fl_richieste` (`id`, `marchio`,`workflow_id`,`parent_id`, `anagrafica_rel`, `tipo_richiesta`, `data_apertura`, `data_chiusura`, `data_scadenza`, `note`, `operatore`, `data_creazione`, `data_aggiornamento`) 
		VALUES (NULL, '0','$workflow_id', '$dest_id', '$anagrafica_id', '9', NOW(), '0000-00-00', '0000-00-00', '[ID:$smsId] $messaggio', '".$_SESSION['number']."', NOW(), NOW());";
		mysql_query($query,CONNECT);
		} else { $error .= $invio; }

	} else { $error .= ' Alcuni numeri non sono corretti'; }

	

    }
    $send = 'Sms inviati: '.$sent.' '.$error;

mysql_close(CONNECT);
header("Location: $rct?$vars&success&esito=$send");
exit;	
	
}


if (isset($_POST['inviaEmail'])) 
{
  unset($_SESSION['destinatari']);
  

  $testo = converti_txt(check($_POST['messaggio'])); 
  $testo = preg_replace('/<!--\[if[^\]]*]>.*?<!\[endif\]-->/i', '', $testo); // Rimuove i commenti XML di Office
  $testo = str_replace('\r\n','', $testo);
  $testo = str_replace('\’','&rsquo;', $testo);
	

  $oggetto = check(str_replace('"','',$_POST['oggetto']));


  $workflow_id = $tab_id;
  $anagrafica_id = (isset($_POST['anagrafica_id'])) ? check($_POST['anagrafica_id']) : 0;
  $send = 0;
  
  foreach($_POST['destinatario'] as $dest_id){ 
 
  $destinatario = GRD($tabella,$dest_id);

  $messaggio = $testo;
  $messaggio = str_replace('[nome]',$destinatario['nome'].' ',$messaggio);
  $messaggio = str_replace('[cognome]',$destinatario['cognome'].' ',$messaggio);
  $mail_body = str_replace("[*CORPO*]",$messaggio,mail_template); 
  $messaggioSQL = strip_tags($messaggio);
  
  
  $sendM = smail($destinatario['email'],$oggetto,$mail_body);
  $send++;
  
  $query = "INSERT INTO `fl_richieste` (`id`, `marchio`,`workflow_id`,`parent_id`, `anagrafica_rel`, `tipo_richiesta`, `data_apertura`, `data_chiusura`, `data_scadenza`, `note`, `operatore`, `data_creazione`, `data_aggiornamento`) 
			VALUES (NULL, '0','$workflow_id', '$dest_id', '$anagrafica_id', '1', NOW(), '0000-00-00', '0000-00-00', '$messaggioSQL', '".$_SESSION['number']."', NOW(), NOW());";
  if($sendM == 1) { mysql_query($query,CONNECT); } else { smail(mail_admin,"Errore invio email lead ID: ".$dest_id, $sendM.' :::::::: '.$query); }

  
  }

smail(mail_admin,"Invio email a ".$send.' contatti ::. '.$oggetto, 'MESSAGGIO BASE INVIATO: '.$testo);
smail(mail_user,"Invio email a ".$send.' contatti ::. '.$oggetto, 'MESSAGGIO BASE INVIATO: '.$testo); 

$send = 'Mail inviate: '.$send;
mysql_close(CONNECT);
header("Location: $rct?$vars&success&esito=$send");
exit;	
	
}


if(isset($_GET['creaLeadVuoto'])){

	$parent_id = check($_GET['parent_id']);
	$workflow_data = GRD('fl_eventi_hrc',$parent_id );
	$anagrafica = GRD('fl_anagrafica',$workflow_data['anagrafica_cliente']);

	$query = "INSERT INTO fl_leads_hrc (`nome`, `cognome`, `telefono`, `email`, `status_potential`, `note`) VALUES 
	('".$anagrafica['nome']."','".$anagrafica['cognome']."','".$anagrafica['telefono']."','".$anagrafica['email']."',4,'Inserito da importazione automatica') " ;
	mysql_query($query);

	$lastInsert = mysql_insert_id();
	mysql_query("UPDATE fl_eventi_hrc SET lead_id = ".$lastInsert." WHERE id = ".$workflow_data['id']);
	mysql_close(CONNECT);
	header("location: ../mod_leads/mod_richieste.php?reQiD=".$lastInsert."&evento_id=".$parent_id);
	exit;

}

mysql_close(CONNECT);
header("location: ".$_SESSION['POST_BACK_PAGE']);
exit;




?>
