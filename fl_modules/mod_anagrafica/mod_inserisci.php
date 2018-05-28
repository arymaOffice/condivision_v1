<?php 

require_once('../../fl_core/autentication.php');

$id = check($_GET['id']);

if($id > 1) {
$profilo = @GRD('fl_anagrafica',@$id); 
//$user_photo = "../../../set/img/photo_cv/".$profilo['id'].".jpg";	
//$user_photo = (@!file_exists($user_photo)) ? '<p style="font-size: 300%; padding: 20px; color: #CACACC"><i class="fa fa-smile-o"></i></p>' : '<p class="user_photo"><span class="user_corda"><img  data-file="'.$user_photo.'" src="'.$user_photo.'" alt="Img" /></span></p>';
}


include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
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
} else { echo '<h1>Nuovo Cliente</h1>'; }

?>




</div>

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div id="map-canvas"></div>
<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">



<?php 
if(isset($_GET['j'])) {
	
	$potential_id = base64_decode(check($_GET['j']));
	echo '<input type="hidden" name="potential_rel_id" value="'.$potential_id.'" />';
}
if(isset($_GET['m'])) {
	
	$meeting_id = base64_decode(check($_GET['m']));
	echo '<input type="hidden" name="meeting_rel_id" value="'.$meeting_id.'" />';
}


include('../mod_basic/action_estrai.php');  ?>

<input type="hidden" name="dir_upfile" value="icone_articoli" />


</form>

<?php
if(isset($_GET['j'])) {
	
	$potential_id = base64_decode(check($_GET['j']));
	$new_dati = GRD('fl_potentials',$potential_id);
	mysql_query($query,CONNECT);
	//mysql_query("UPDATE `fl_appuntamenti` SET `issue`= 2 , data_aggiornamento = '".date('Y-m-d H:i:00')."' WHERE potential_rel = '".$potential_id."' LIMIT 1",CONNECT);

	
	//$query = "UPDATE `fl_potentials` SET `priorita_contatto` = 0, `status_potential` = '4', `in_use` = '0', is_customer = '1' WHERE  `is_customer` < 2 AND id = '".$potential_id."';";
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

<script type="text/javascript">
/*Avvio*/
$(document).ready(function() {

function loadProvince(from,where){ 
  var post = 'sel=provincia&filtro=regione&valore='+$(from).val();
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
    console.log(post);
	$(where).empty();
    var data = $.parseJSON(response);
	  $.each(data, function(i, value) {
           $(where).append('<option = "'+value+">"+value+"</option>");
		   $(where).focus();
        });
   });
}

function loadComuni(from,where){ 
  var post = 'sel=comune&filtro=provincia&valore='+$(from).val();
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
   console.log(post);
	$(where).empty();
    var data = $.parseJSON(response);
	  $.each(data, function(i, value) {
           $(where).append('<option = "'+value+">"+value+"</option>");
		   $(where).focus();
        });
   });
}

function loadCap(from,where){ 
  var post = 'sel=cap&filtro=comune&valore='+$(from).val();
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
    //console.log(response);
	$(where).empty();
    var data = $.parseJSON(response);
	  $.each(data, function(i, value) {
           $(where).val(value);
		   $(where).focus();
        });
   });
}



$('#regione_residenza').change(function(){
 	loadProvince('#regione_residenza','#provincia_residenza');
});

$('#regione_sede').change(function(){
 	loadProvince('#regione_sede','#provincia_sede');
});

$('#regione_punto').change(function(){
 	loadProvince('#regione_punto','#provincia_punto');
});

$('#provincia_residenza').change(function(){
 	loadComuni('#provincia_residenza','#comune_residenza');
});

$('#provincia_sede').change(function(){
 	loadComuni('#provincia_sede','#comune_sede');
});

$('#provincia_punto').change(function(){
 	loadComuni('#provincia_punto','#comune_punto');
});

$('#comune_residenza').change(function(){
 	loadCap('#comune_residenza','#cap_residenza');
});

$('#comune_sede').change(function(){
 	loadCap('#comune_sede','#cap_sede');
});

$('#comune_punto').change(function(){
 	loadCap('#comune_punto','#cap_punto');
});



});</script>


</div></div></body></html>
