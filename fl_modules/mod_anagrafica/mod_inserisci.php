<?php 

require_once('../../fl_core/autentication.php');
$loadSelectComuni = 1;
$id = ($_SESSION['usertype'] > 1) ? $_SESSION['anagrafica'] : check($_GET['id']);
if($_SESSION['usertype'] > 1) $force_id = $_SESSION['anagrafica']; 

if($id > 1) {
$profilo = @GRD('fl_anagrafica',@$id); 
//$user_photo = "../../../set/img/photo_cv/".$profilo['id'].".jpg";	
//$user_photo = (@!file_exists($user_photo)) ? '<p style="font-size: 300%; padding: 20px; color: #CACACC"><i class="fa fa-smile-o"></i></p>' : '<p class="user_photo"><span class="user_corda"><img  data-file="'.$user_photo.'" src="'.$user_photo.'" alt="Img" /></span></p>';
}


include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
if(!isset($_GET['view'])) include("../../fl_inc/testata_mobile.php");
 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">


<div id="container" >



<div id="content_scheda">

<div class="info_dati">
<?php if($id > 1) { 
$telefono = phone_format($profilo['telefono'],'39');
echo '<h1><strong>'.$profilo['ragione_sociale'].'</strong> ('.$profilo['nome'].' '.$profilo['cognome'].')</h1>';
if(ALERT_DOCUMENTO_SCADUTO == 1)  echo '<h2>Tipo Delega: <span class="msg gray">'.@$pagamenti_f24[@$profilo['pagamenti_f24']].'</span></h2>';
echo '<p>Telefono: <a href="tel:'.@$telefono.'">'.@$telefono.'</a> mail: <a href="mailto:'.@$profilo['email'].'" >'.@$profilo['email'].'</a></h2>';
} else { echo '<h1>Nuovo '.$tipo_profilo[ $tipo_profilo_id].'</h1>'; }

?>




</div>

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div id="map-canvas"></div>
<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">



<?php include('../mod_basic/action_estrai.php');  ?>
<?php if(isset($_GET['view'])) echo '<input type="hidden" name="info" value="1" />';  ?>
<input type="hidden" name="dir_upfile" value="icone_articoli" />


</form>

<?php
if(isset($_GET['j'])) {
	
	$potential_id = base64_decode(check($_GET['j']));
	$new_dati = GRD('fl_potentials',$potential_id);
	//if(mysql_query("UPDATE `fl_meeting_agenda` SET `issue`= 2 , data_aggiornamento = '".date('Y-m-d H:i:00')."' WHERE potential_rel = '".$potential_id."' LIMIT 1",CONNECT)) {
	//}
	
	$query = "UPDATE `fl_potentials` SET `status_potential` = '4', `in_use` = '0', is_customer = '1' WHERE id = '".$potential_id."';";
	mysql_query($query,CONNECT);
	
	echo "<script type=\"text/javascript\">
	
	$('#nome').val('".$new_dati['nome']."');
	$('#ragione_sociale').val('".$new_dati['company']."');
	$('#cognome').val('".$new_dati['cognome']."');
	$('#telefono').val('".$new_dati['telefono']."');
	$('#email').val('".$new_dati['email']."');
	$('#stato_sede').val('".$new_dati['paese']."');
	$('#sede_legale').val('".$new_dati['indirizzo']."');
	$('#cap_sede').val('".$new_dati['cap']."');
	$('#comune_sede').val('".$new_dati['citta']."');
	$('#tipo_profilo').val('2');
	
	</script>";
}
?>


</div></div></body></html>
