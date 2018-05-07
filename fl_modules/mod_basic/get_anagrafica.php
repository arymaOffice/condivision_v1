<?php
@require_once('../../fl_core/autentication.php');
if(!strstr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])) { echo "Non si accettano richieste da remoto"; exit; }

$anagrafica_id = check($_POST['id']);
$tabella = "fl_anagrafica";
$query = "SELECT id,ragione_sociale,frazione_sede,sede_legale,cap_sede,provincia_sede,comune_sede,stato_sede,partita_iva,codice_fiscale_legale FROM $tabella WHERE id =  $anagrafica_id LIMIT 1;";
$risultato = mysql_query($query,CONNECT);
$fill = 0;

while ($riga =  @mysql_fetch_assoc($risultato)) {
echo json_encode($riga);
$fill = 1;
} 



if($fill == 0) echo json_encode(array('ragione_sociale'=>"Nessun cliente trovato"));

mysql_close(CONNECT);
exit;

?>