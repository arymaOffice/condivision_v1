<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 





if(isset($_POST['id_materia'])) {

$id_materia = check($_POST['id_materia']);
$fornitore = check($_POST['fornitore']);
$prezzo_unitario = check($_POST['prezzo_unitario']);
$data_validita = (isset($_POST['data_validita'])) ? dbdatetime(check($_POST['data_validita'])) : 0;
$valuta = check($_POST['valuta']);
$data_scadenza = '2050-31-12';
$valore_di_conversione = check($_POST['valore_di_conversione']);
$codice_ean = check($_POST['codice_ean']);
$formato = check($_POST['formato']);
$unita_di_misura_formato = check($_POST['unita_di_misura_formato']);
$codice_fornitore = check($_POST['codice_fornitore']);
$giacenza = check($_POST['giacenza']);
$giacenza_minima = (isset($_POST['giacenza_minima'])) ? check($_POST['giacenza_minima']) : 0;


$sql = "INSERT INTO `fl_listino_acquisto` (`id`, `id_materia`, `fornitore`, `valuta`, `prezzo_unitario`, `data_validita`, `data_scadenza`, `data_creazione`, `data_aggiornamento`, `operatore`,
`valore_di_conversione`,`codice_ean`,`formato`,`unita_di_misura_formato`,`codice_fornitore`,`giacenza`,`giacenza_minima`) 
VALUES (NULL, '$id_materia', '$fornitore', '$valuta', '$prezzo_unitario', '$data_validita', '$data_scadenza', NOW(), NOW() 
, ".$_SESSION['number'].",'$valore_di_conversione','$codice_ean','$formato','$unita_di_misura_formato','$codice_fornitore','$giacenza','$giacenza_minima');";


mysql_query($sql);


}

if(isset($_POST['creaDaRicettario'])) {

$unita_di_misura = check($_POST['unita_di_misura']);
$record_id = check($_POST['record_id']);
$descrizione = check($_POST['descrizione']);
$categoria_materia = check($_POST['categoria_materia']);
$codice_articolo = check($_POST['codice_articolo']);
$ultimo_prezzo = check($_POST['ultimo_prezzo']);
$prezzo_medio = check($_POST['ultimo_prezzo']);

$query = "INSERT INTO `fl_materieprime` (
`id` ,
`attivo` ,
`codice_articolo` ,
`descrizione` ,
`valore_di_conversione` ,
`categoria_materia` ,
`unita_di_misura` ,
`ultimo_prezzo` ,
`prezzo_medio` ,
`tipo_materia` ,
`note`,
`data_creazione` ,
`data_aggiornamento`
)
VALUES (
NULL , '1', '$codice_articolo', '$descrizione',1, '$categoria_materia', '$unita_di_misura', '$ultimo_prezzo', '$prezzo_medio', '112', 'Creato da Ricettario', NOW(),NOW());";


mysql_query($query);

$merceCreata = mysql_insert_id();
mysql_query("UPDATE fl_ricettario SET merce_collegata = ".$merceCreata." WHERE id = ".$record_id);

}


mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit;

?>
