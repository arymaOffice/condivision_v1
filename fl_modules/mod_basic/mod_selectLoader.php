<?php 

require_once('../../fl_core/autentication.php');

$tabella = (!isset($_POST['gtx'])) ? 'fl_istat_comuni' : $tables[check(@$_POST['gtx'])];
$select = check($_POST['sel']);
$filter = check($_POST['filtro']);
$valore = check(@$_POST['valore']);
$filtro = ($filter != '') ? " $filter = '$valore' " : 'id > 1';

$query = "SELECT id,$select FROM $tabella WHERE $filtro GROUP BY $select";

if(!isset($_POST['valore'])) $query = "SELECT $select FROM $tabella WHERE 1 GROUP BY $select";

$risultato = mysql_query($query, CONNECT);

$content = array(0=>'Seleziona...');


while($riga = mysql_fetch_assoc($risultato)) {
$referenza = (isset($_POST['numeric'])) ? $riga['id'] : $riga[$select];
$content[$referenza] = $riga[$select];
}


echo json_encode($content);
mysql_close(CONNECT);
exit;	

?>
