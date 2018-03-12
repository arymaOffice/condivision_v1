<?php
@require_once('../../fl_core/autentication.php');
if(!strstr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])) { echo "Non si accettano richieste da remoto"; exit; }

$cerca = check($_POST['cerca']);
$tabella = "fl_users";
$where = "WHERE id != 1";
$where .= ricerca_semplice('ragione_sociale','');
$query = "SELECT * FROM $tabella $where ORDER BY ragione_sociale ASC LIMIT 0,5;";
$risultato = mysql_query($query,CONNECT);
echo "<h3>Suggerimenti:</h3>";
$i = 1;
while ($riga = mysql_fetch_array($risultato)) {
$valore = $riga['ragione_sociale'];
echo "<p tabindex=\"$i\"><a href=\"./?cerca=$valore\">".$valore."</a></p>";
$i++;
}




?>
