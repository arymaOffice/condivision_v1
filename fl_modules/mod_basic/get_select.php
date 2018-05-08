<?php
@require_once('../../fl_core/autentication.php');
if(!strstr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])) { echo "Non si accettano richieste da remoto"; exit; }
if($_SESSION['usertype'] != 0) { echo "Ricerca non disponibile"; }
$cerca = check($_POST['cerca']);
$elemento = check($_POST['elemento']);
$input = check($_POST['input']);
$tabella = "fl_account";
$where = "WHERE id != 1";
$where .= ricerca_semplice('nominativo','').")";
$query = "SELECT id,user,nominativo FROM $tabella $where ORDER BY user ASC LIMIT 0,5;";
$risultato = mysql_query($query,CONNECT);
echo "<p>Suggerimenti:</p>";
$i = 1;

while ($riga = mysql_fetch_array($risultato)) {
$valore = $riga['user'];
$nominativo = $riga['nominativo'];
$value = $riga['id'];
echo "<p tabindex=\"$i\"><a href=\"#\" onclick=\"fill_selection('$valore','$elemento','$valore','$input');\">[".$valore."] $nominativo</a></p>";
$i++;
}

//echo time();
//echo "<br />".$_SERVER['HTTP_HOST'];

?>