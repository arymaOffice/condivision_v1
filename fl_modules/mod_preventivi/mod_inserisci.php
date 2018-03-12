<?php 

require_once('../../fl_core/autentication.php');

$id = check($_GET['id']);


include('fl_settings.php');
// Variabili Modulo 
$preventivo = GRD('fl_preventivi',$id); 
$persona = ($preventivo['cliente_id'] > 1) ? GRD('fl_anagrafica',$preventivo['cliente_id']) : GRD($tables[106],$preventivo['potential_id']); 


if(isset($_GET['MoD'])) {

$MoD = base64_decode(check($_GET['MoD']));
$modello_preventivo = GRD('fl_modelli',$MoD);
$categoria_modello = $modello_preventivo['categoria_modello'];
$totale_preventivo = $modello_preventivo['valore_base'];
$oggetto_preventivo = $modello_preventivo['titolo'];
$testo_preventivo = $modello_preventivo['contenuto'];
$condizioni_preventivo = $modello_preventivo['condizioni_generali'];
$condizioni_pagamento = $modello_preventivo['condizioni_pagamento'];
$informativa_privacy = $modello_preventivo['informative'];
$SEL_tipo_preventivo = $modello_preventivo['categoria_modello'];

}

if(isset($_GET['POiD'])) {
$poid = base64_decode(check($_GET['POiD']));
$potential_id = $poid;
$persona = GRD($tables[106],$poid);
//$query = "UPDATE `fl_leads` SET `status_potential` = '7', `in_use` = '0' WHERE id = '".$poid."';";
//mysql_query($query,CONNECT);
//mysql_query("UPDATE `fl_appuntamenti` SET `issue`= 7 , data_aggiornamento = '".date('Y-m-d H:i:00')."' WHERE potential_rel = '".$poid."' LIMIT 1",CONNECT);
}

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");

?>





<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container">


<div id="content_scheda">

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<?php 
if($id != 1 || isset($potential_id)){
$telefono = phone_format($persona['telefono'],'39');
echo '<div class="info_dati"><h1><strong>'.$persona['nome'].' '.$persona['cognome'].'</strong></h1>';
if($id != 1) echo '<h3><span class="msg blue">'.@$tipo_preventivo[$preventivo['tipo_preventivo']].'</span><span class="msg gray">'.@$status_preventivo[$preventivo['status_preventivo']].'</span></h3>';
echo '<p>Tel: <a href="tel:'.@$cellulare.'">'.@$cellulare.'</a><a href="tel:'.@$telefono.'">'.@$telefono.'</a> mail: <a href="mailto:'.@$persona['email'].'">'.@$persona['email'].'</a></p></div>';
} ?>



<div id="map-canvas"></div>
<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">



<?php include('../mod_basic/action_estrai.php');  ?>

<?php  if(isset($_GET['POiD'])) {

echo '<input type="hidden" name="goto" value="../mod_preventivi/" />';

} ?>
</form>


<?php

if(isset($_GET['POiD'])) {
	
	//$dataEvento = check($_GET['data_evento']);

	echo "<script type=\"text/javascript\">	
	$('#tipo_preventivo').val('".$persona['interessato_a']."');
	$('#numero_adulti').val('".$persona['numero_persone']."');
	$('#oggetto_preventivo').val('Evento ".$persona['cognome']."');";
	if($categoria_modello > 1) echo "$('#tipo_preventivo').val('".$categoria_modello."');";
	echo "</script>";
}



?>

</div></body></html>
