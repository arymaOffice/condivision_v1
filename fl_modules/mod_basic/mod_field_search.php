<?php
@require_once('../../fl_core/autentication.php');
//if(!strstr(@$_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])) { echo "Non si accettano richieste da remoto"; exit; }

$tabella = $tables[check($_GET['gtx'])];


$where = "WHERE id != 1 AND ( ";
foreach($_GET['q'] AS $chiave => $valore) {
	foreach($_GET['w'] AS $chiave2 => $valore2) {
	$where .= ' '.$valore2.' LIKE \''.check($valore).'%\' OR';
}}
$where = rtrim ($where,'OR')." ) ";

$query = "SELECT * FROM $tabella $where LIMIT 0,5;";
$risultato = mysql_query($query,CONNECT);
echo "<h3>Seleziona elemento:</h3>";
$i = 1;
while ($riga = mysql_fetch_array($risultato)) {
$valore = '';
foreach($_GET['w'] AS $chiave2 => $valore2) { $valore .= ' '.$riga[$valore2]; }

echo "<div tabindex=\"$i\" class=\"rowSelection\"><a href=\"javascript:void(0);\" onclick=\"loadLead('".$riga['id']."','".$valore."','".$riga['email']."','".$riga['telefono']."','".$riga['source_potential']."');\">".$valore."</a></div>";
$i++;
}


mysql_close(CONNECT);

?>
