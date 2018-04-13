<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 



// Modifica Stato se è settata $stato	
if(isset($_POST['parent_id'])) { 

$stato_id = check($_POST['stato_hd']);
$parent_id = check($_POST['parent_id']);
$post = check($_POST['messaggio']);
$creazione = date('Y-m-d H:i:s');

if(trim($post) == '' && $stato_id != 0) {
echo json_encode(array('action'=>'info','url'=>'','class'=>'red','esito'=>"Scrivi un messaggio!")); 
@mysql_close(CONNECT);
exit; 
}

if($stato_id == 0) $post  .= '<br>CHIUSURA TICKET ASSISTENZA';
$chiusura = ($stato_id == 0) ? ", data_chiusura = '$creazione' " : '';
$op = ($_SESSION['usertype'] < 2) ? ', operatore = '.$_SESSION['number'] : '';

$query1 = "UPDATE fl_helpdesk SET stato_hd = $stato_id, data_aggiornamento = '$creazione' $chiusura $op WHERE `id` = $parent_id ";
mysql_query($query1,CONNECT);
	
$query2 = "INSERT INTO `fl_helpdesk_posts` (`id`, `parent_id`, `messaggio`, `account_id`, `data_creazione`, `letto`)
 VALUES (NULL, '$parent_id', '$post', '".$_SESSION['number']."',  '$creazione', '0');";
mysql_query($query2,CONNECT);	

if(mysql_error()){
echo json_encode(array('action'=>'info','url'=>'','class'=>'red','url'=>'','esito'=>"Errore di invio. Contatta il servizio assistenza")); 
} else {
echo json_encode(array('action'=>'realoadParent','url'=>'./mod_visualizza.php?id='.$parent_id,'class'=>'green','url'=>'','esito'=>"Ok")); 

$request = GRD($tabella,$parent_id);
$messaggio = '<h2>'.$request['oggetto'].'</h2>';
$messaggio .= '<p>'.html_entity_decode(converti_txt($request['messaggio'])).'</p>';
$messaggio .= '<p>Status: '.@$stato_hd[$stato_id].'</p>';
$messaggio .= '<p>Nuova Risposta: '.$post.'</p>';
$messaggio .= '<p>Intervento di '.$proprietario[$_SESSION['number']].' delle ore '.mydatetime($creazione).'</p>';
$messaggio = str_replace("[*CORPO*]",$messaggio,mail_template); 


smail($request['email_contatto'],"Risposta la Ticket ID: #".$parent_id,$messaggio);
smail(mail_admin,"Risposta la Ticket ID: #".$parent_id,$messaggio);

}

@mysql_close(CONNECT);
exit; 

}




mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER']),1); 
exit;

?>
