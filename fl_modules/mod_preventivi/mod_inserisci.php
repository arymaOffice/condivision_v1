<?php 

require_once('../../fl_core/autentication.php');

$id = check($_GET['id']);


include('fl_settings.php'); // Variabili Modulo 
$preventivo = GRD('fl_rdo',$id); 
if($preventivo['cliente_id'] > 1) { $persona = GRD('fl_anagrafica',$preventivo['cliente_id']); } else { $persona = GRD('fl_potentials',$preventivo['potential_id']); }

if(isset($_GET['POiD'])) {
$poid = base64_decode(check($_GET['POiD']));
$persona = GRD('fl_potentials',$poid);
$query = "UPDATE `fl_potentials` SET `status_potential` = '7', `in_use` = '0' WHERE id = '".$poid."';";
//mysql_query($query,CONNECT);
mysql_query("UPDATE `fl_appuntamenti` SET `issue`= 7 , data_aggiornamento = '".date('Y-m-d H:i:00')."' WHERE potential_rel = '".$poid."' LIMIT 1",CONNECT);


}

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >



<div id="content_scheda">

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<?php 
if($id != 1 || isset($potential_id)){
$telefono = phone_format($persona['telefono'],'39');
echo '<div class="info_dati"><h1>'.$persona['id'].' <strong>'.$persona['nome'].' '.$persona['cognome'].'</strong></h1>';
if($id != 1) echo '<h3><span class="msg blue">'.@$tipo_preventivo[$preventivo['tipo_preventivo']].'</span><span class="msg gray">'.@$status_preventivo[$preventivo['status_preventivo']].'</span>'.$preventivo['marca'].' '.$preventivo['modello'].'</h3>';
echo '<p>Tel: <a href="tel:'.@$cellulare.'">'.@$cellulare.'</a><a href="tel:'.@$telefono.'">'.@$telefono.'</a> mail: <a href="mailto:'.@$persona['emails'].'">'.@$persona['emails'].'</a></p></div>';
} ?>



<div id="map-canvas"></div>
<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php if(isset($_GET['copy_record'])) { echo '<input type="hidden" name="copy_record" value="1" />
<div class="msg orange">ATTENZIONE! Stai creando una copia di questo elemento</div>' ;
} ?>


<?php include('../mod_basic/action_estrai.php');  ?>

<input type="hidden" name="function" value="cambia_stato_lead" />
<input type="hidden" name="status_auto" value="7" />

</form>



</div></body></html>
