<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

 
include("../../fl_inc/headers.php");?>



<?php if(!isset($_GET['external'])) include('../../fl_inc/testata.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/menu.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/module_menu.php'); 

	$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

	if($_SESSION['usertype'] == 0) {
	
	$query = "SELECT * FROM fl_admin WHERE id > 1";
	echo mysql_error();
	$risultato = mysql_query($query, CONNECT);
	
	?>
    


<table class="dati" summary="Dati">
  <tr>
    <th  class="center"><a href="./?ordine=3"></a></th>
<th><a href="./?ordine=0">Nome</a></th>
    <th><a href="./?ordine=2">Tipo Account</a></th>
    <th><a href="./?ordine=1">Email</a></th>
    <th>Scadenza Password</th>
<!--    <th>Anagrafica</th>
-->    <th>Accessi</th>
    <th></th>
    <th>Creato il</th>
    </th>
  </tr>
  <?php
			$count = 0;

	if(mysql_affected_rows() == 0){echo " <tr>     <td colspan=\"10\">Nessun utente trovato</td></tr>";}
	//$moduli = $data_set->data_retriever('fl_moduli','label','WHERE id > 1 AND attivo = 1');

	
	//if(!isset($riga['processo_id'])) { mysql_query('ALTER TABLE `fl_account` ADD `processo_id` INT NULL AFTER `tipo`, ADD `persona_id` INT NULL AFTER `processo_id` ',CONNECT); echo "DB Account Aggiornato!"; }
	//if(!isset($riga['numero_concessione'])) { mysql_query('ALTER TABLE `fl_account` ADD `numero_concessione` INT( 10 ) NULL AFTER `data_nascita` ,ADD `uid_isisbet` INT( 5 ) NULL AFTER `numero_concessione` ',CONNECT); echo "DB Account Aggiornato!"; }
	 
	$qud = "INSERT INTO `fl_account` (`id`, `cuid`, `uid`, `attivo`, `motivo_sospensione`, `anagrafica`, `marchio`, `tipo`, `processo_id`, `persona_id`, `email`, `nominativo`, `ip_accesso`, `user`, `password`, `data_nascita`, `numero_concessione`, `uid_isisbet`, `foto`, `visite`, `ip`, `data_creazione`, `data_scadenza`, `data_aggiornamento`, `operatore`, `aggiornamento_password`) 
	VALUES (NULL, '0', '0', '1', '', '0', '0', '1', '0', '0', '', '', '0', '', '', '', '0', '0', '', '0', '', '', '', '', '', '');";
	
	while ($riga = mysql_fetch_array($risultato)) 
	{  
	
	/*$data = ($riga['data'] != '') ? @date('Y-m-d',$riga['data']) : date('Y-m-d');
	$qud = "INSERT INTO `fl_account` (`id`, `cuid`, `uid`, `attivo`, `motivo_sospensione`, `anagrafica`, `marchio`, `tipo`, `processo_id`, `persona_id`, `email`, `nominativo`, `ip_accesso`, `user`, `password`, `data_nascita`, `numero_concessione`, `uid_isisbet`, `foto`, `visite`, `ip`, `data_creazione`, `data_scadenza`, `data_aggiornamento`, `operatore`, `aggiornamento_password`) 
	VALUES ('".$riga['id']."', '0', '0', '1', '', '".$riga['anagrafica_id']."', '".$riga['marchio']."', '".$riga['tipo']."', '0', '0', '".$riga['email']."', '".$riga['nominativo']."', '0', '".$riga['user']."', '".$riga['password']."', '".$riga['nascita']."', '".$riga['numero_concessione']."', '".$riga['uid_isisbet']."', '', '".$riga['visite']."', '".$riga['ip']."', '".$data."', '2050-12-31', NOW(), 1, NOW());";
	mysql_query($qud,CONNECT);*/
	
	 $query = "UPDATE fl_account SET password = '".$riga['password']."' WHERE id = ".$riga['id']." LIMIT 1";
	 mysql_query($query,CONNECT);
	 echo mysql_error();
/*
foreach($moduli as $chiave => $valore) {
if($chiave > 1) {
$modulo_id = GRD('fl_moduli',$chiave);
$default_value = $modulo_id['accesso_predefinito'];
$permesso = "INSERT INTO `fl_permessi` (`id`, `account_id`, `modulo_id`, `livello_accesso`) VALUES (NULL, '".$riga['id']."', '$chiave', '$default_value');";
mysql_query($permesso,CONNECT);
$permessi .= '<p>Permesso per '.$valore.': '.$livello_accesso[$default_value].'</p>';
}}
*/
	
	/*if($riga['attivo'] == 0) { $alert = "Non attivo"; $colore = "style=\"background: #DA3235; color: #FFF;\"";  } else { $alert = "Attivo"; $colore = "style=\"background: #3DA042; color: #FFF;\""; }
	$input = ($riga['id'] > 0 && filter_var($riga['email'], FILTER_VALIDATE_EMAIL)) ? '<input onClick="countFields(1);" type="checkbox" id="'.$riga['id'].'" name="destinatario[]" value="'.$riga['id'].'" checked="checked" /><label for="'.$riga['id'].'">'.$checkRadioLabel.'</label>' : '';
	($riga['id'] > 0 && filter_var($riga['email'], FILTER_VALIDATE_EMAIL)) ? $count++ : 0;

	$query = "SELECT * FROM `fl_anagrafica` WHERE `id` =".$riga['anagrafica']." LIMIT 1";
	$risultato2 = mysql_query($query, CONNECT);
    $profili = mysql_affected_rows();
	if($profili > 0) { $gest_profili = "<a href=\"../mod_anagrafica/mod_inserisci.php?external&id=".$riga['anagrafica']."\">Anagrafica</a>"; } else { $gest_profili = "--"; }
	$date = strtotime($riga['aggiornamento_password']."+90 day"); 
	$password_update = giorni(date('Y-m-d',$date));*/
	}}
	
	mysql_close(CONNECT);
	
	?>
