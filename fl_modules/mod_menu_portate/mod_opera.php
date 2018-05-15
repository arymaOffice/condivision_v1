<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

$newPiatto = '';


if(isset($_GET['cMp'])) {


		$evento_id = check($_GET['cMp']); 
		$query = "DELETE FROM `fl_ricettario_fabbisogno` WHERE evento_id = ".base64_decode($evento_id);
		mysql_query($query,CONNECT);

		mysql_close(CONNECT);
		header("Location: ".$_SERVER['HTTP_REFERER'].'#cMp'.$evento_id); 
		exit;
}



// Modifica Stato se è settata $stato	
if(isset($_GET['stato_menu_portata'])) { 
$stato_menu_portata = check($_GET['stato_menu_portata']);
$id = check($_GET['id']);
$query1 = "UPDATE $tabella SET stato_menu_portata = $$stato_menu_portata WHERE `id` = $id";
mysql_query($query1,CONNECT);	
}

if(isset($_GET['conferma'])) { 
$id = check($_GET['conferma']);
$query1 = "UPDATE $tabella SET confermato = 1 WHERE `id` = $id";
mysql_query($query1,CONNECT);	
}

if(isset($_GET['creaVarianteMenu'])) { 

$menuId = check($_GET['creaVarianteMenu']);
$idPiatto = check($_GET['idPiatto']);
$synId = check($_GET['synId']);

// Creo copia e aggiorno il piatto
$newPiatto = copy_record('fl_ricettario',$idPiatto);
$aggiornaPiatto = "UPDATE fl_ricettario SET variante = $idPiatto WHERE `id` = $newPiatto";
mysql_query($aggiornaPiatto,CONNECT);	

//Aggiorno l'associazione piatto/menu
$query1 = "UPDATE fl_synapsy SET id2 = $newPiatto WHERE `id` = $synId";
mysql_query($query1,CONNECT);	

}



if(isset($_GET['menuId'])) {

$menuId = check($_GET['menuId']);

$queryPortate = "SELECT r.variante,r.portata,r.id,r.nome,r.valore_di_conversione,lasts.valore,lasts.note,lasts.priority,lasts.id AS synId FROM fl_ricettario r JOIN ( SELECT  s.id,s.id1,s.id2,s.valore,s.note,s.priority FROM fl_synapsy s WHERE s.type2 = 119 AND s.type1 = 123 and s.id1 = $menuId) lasts ON r.id = lasts.id2 ORDER BY (r.portata) ASC, lasts.priority ASC, lasts.id ASC ";
$resultPortate = @mysql_query($queryPortate,CONNECT);

$totCosto = 0;
$totVendita = 0;
while ($row = @mysql_fetch_array($resultPortate)) {

	$query = "SELECT * FROM `fl_ricettario_diba` WHERE `ricetta_id` = ".$row['id']." ORDER BY id DESC";
	$risultato1 = mysql_query($query, CONNECT);
	$foodCost = 0;
	while ($componenti = mysql_fetch_assoc($risultato1)) 
	{ 
	$materiaprima = GRD('fl_materieprime',$componenti['materiaprima_id']);
  	//$quotazione = GQD('fl_listino_acquisto','valuta, prezzo_unitario, data_validita',' id_materia = '.$materiaprima['id'].' ORDER BY data_validita DESC,data_creazione DESC LIMIT 1');
  	$costo =  ($materiaprima['ultimo_prezzo']*$componenti['quantita'])/$materiaprima['valore_di_conversione'];
  	$foodCost += $costo;
    } 
    $totCosto += $foodCost;
	$totVendita += $foodCost*$row['valore_di_conversione'];


}

$query = "UPDATE fl_menu_portate SET food_cost = $totCosto, prezzo_base = $totVendita, data_aggiornamento = NOW() WHERE id = ".$menuId ;
mysql_query($query,CONNECT);	

//ALTER TABLE `fl_menu_portate` ADD `food_cost` INT NOT NULL AFTER `prezzo_base` ,ADD `food_cost_consuntivo` INT NOT NULL AFTER `food_cost` ;
mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit;

}



if(isset($_GET['extra_coperti'])) {
$_SESSION['extra_coperti'] = check($_GET['extra_coperti']);
mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit;
}




if(isset($_POST['creaFabbisogno'])) {

foreach ($_POST['ricetta_id'] as $key => $value) {

	$ricetta_id = $_POST['ricetta_id'][$key];
	$evento_id = $_POST['evento_id'][$key];
	$quantita = $_POST['quantita'][$key];

	$query = "INSERT INTO `fl_ricettario_fabbisogno` (`id`, `ricetta_id`, `versione`, `evento_id`, `quantita`, `ordine_id`, `data_creazione`,`operatore`) 
	VALUES (NULL, '$ricetta_id', '1', '$evento_id', '$quantita', '0', NOW(),'".$_SESSION['operatore']."'); ";

	if($quantita != 0) mysql_query($query,CONNECT);

}

mysql_close(CONNECT);
header("Location: mod_revisione_fabbisogno.php"); 
exit;
}



mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER'].'#p'.$newPiatto); 
exit;

?>
