<?php 
require_once('../../fl_core/autentication.php');

$select = check($_POST['sel']);
$filter = check($_POST['filtro']);
$valore = check(@$_POST['valore']);

$query = "SELECT $select FROM fl_istat_comuni WHERE $filter = '$valore' GROUP BY $select";

if(!isset($_POST['valore'])) $query = "SELECT $select FROM fl_istat_comuni WHERE 1 GROUP BY $select";

$risultato = mysql_query($query, CONNECT);

$content = array(0=>'Seleziona...');
while($riga = mysql_fetch_assoc($risultato)) {
$content[$riga[$select]] = $riga[$select];
}


echo json_encode($content);
exit;	

?>
