<?php 
require_once('../../fl_core/autentication.php');

$tabella = (!isset($_REQUEST['gtx'])) ? 'fl_istat_comuni' : $tables[check(@$_REQUEST['gtx'])];
$select = check($_REQUEST['sel']);
$filter = check($_REQUEST['filtro']);
$valore = check(@$_REQUEST['valore']);

$query = "SELECT $select FROM $tabella WHERE $filter = '$valore' GROUP BY $select";

if(!isset($_REQUEST['valore'])) $query = "SELECT $select FROM $tabella WHERE 1 GROUP BY $select";

$risultato = mysql_query($query, CONNECT);

$content = array();

if(mysql_error()) { 

$content[0] = 'Errore: '.mysql_error(); 

} else {


while($riga = mysql_fetch_assoc($risultato)) {
$var = urlencode($riga[$select]);	
$content[$var] = $var;
}


}

echo json_encode($content);
mysql_close(CONNECT);
exit;	

?>
