<?php 

require_once('../../fl_core/autentication.php');

if(!isset($_REQUEST['gtx'])){

    $tabella = 'fl_istat_comuni';

}else{
    $tables[check(@$_REQUEST['gtx'])];
}

$select = check($_REQUEST['sel']);

$filter = check($_REQUEST['filtro']);
$valore = check(@$_REQUEST['valore']);
$filtro = ($filter != '') ? " $filter = '$valore' " : 'id > 1';

$query = "SELECT id,$select FROM $tabella WHERE $filtro GROUP BY $select";

if(!isset($_REQUEST['valore'])) $query = "SELECT $select FROM $tabella WHERE 1 GROUP BY $select";

$risultato = mysql_query($query, CONNECT);

$content = array(0=>'Seleziona...');


while($riga = mysql_fetch_assoc($risultato)) {
$referenza = (isset($_REQUEST['numeric'])) ? $riga['id'] : $riga[$select];
$content[$referenza] = $riga[$select];
}


echo json_encode($content);
mysql_close(CONNECT);
exit;	

?>
