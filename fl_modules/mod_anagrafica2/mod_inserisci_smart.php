<?php 

require_once('../../fl_core/autentication.php');
$loadSelectComuni = 1;
$id = ($_SESSION['usertype'] > 1) ? $_SESSION['anagrafica'] : check($_GET['id']);
if($_SESSION['usertype'] > 1) $force_id = $_SESSION['anagrafica']; 

include('fl_settings.php'); // Variabili Modulo 
$tab_div_labels = array('id'=>'Persona','forma_giuridica'=>"Dati Fiscali");

include("../../fl_inc/headers.php");

 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">


<div id="container" >



<div id="content_scheda">

<div class="info_dati">
<?php if($id > 1) { 
} else { echo '<h1>Nuovo '.$tipo_profilo[$tipo_profilo_id].'</h1>'; }

?>




</div>

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<style type="text/css">
	
/* Personalizzati */
#box_citta,#box_indirizzo {
    width: 83%;
    float: left;
    clear: none;
}

#box_citta label {

}

#box_citta  input,#box_indirizzo input {
	width: 55%;
}

#box_provincia,#box_cap {
width: 133px;
float: right;
padding: 0px 16px 0 0;
clear: none;
}
#box_provincia label {
    width: auto;
    padding: 0;
}
#box_provincia input {
width: 45px;
}



#box_cap {
    width: 133px;
    float: right;
    padding: 0px 16px 0 0;
}
#box_cap label {
    width: auto;
    padding: 0;
}
#box_cap input {
width: 74px;
}


#box_telefono,#box_recapito_alternativo {
	width: 50%;
    clear: none;
    display: inline-block;
}



</style>
<div id="map-canvas"></div>

<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">



<?php include('../mod_basic/action_estrai.php');  ?>

<?php if(isset($_GET['view'])) echo '<input type="hidden" name="info" value="1" />';  

if(!isset($_GET['associa_evento']) && isset($_GET['j'])) { 
	$potential_id = base64_decode(check($_GET['j']));  echo '<input type="hidden" name="reload" value="../mod_leads/mod_inserisci.php?id='.$potential_id.'&customer_rel=">';
}

if(isset($_GET['associa_evento'])) { 
	$associa_evento = check($_GET['associa_evento']); 
	$anagraficaNumero = (!isset($_GET['anagraficaNumero'])) ? 1 : check($_GET['anagraficaNumero']); 
	echo '<input type="hidden" name="reload" value="../mod_anagrafica/mod_inserisci_smart.php?anagraficaNumero='.$anagraficaNumero.'&associaEventoCliente='.$associa_evento.'&id=">';
} else { echo '<input type="hidden" name="info" value="1" />'; }



if(isset($_GET['associaEventoCliente'])) { 
	$associaEventoCliente = check($_GET['associaEventoCliente']);
	$anagraficaNumero = check($_GET['anagraficaNumero']);
	$anagraficaNumero = ($anagraficaNumero == 1 ) ? 'anagrafica_cliente' : 'anagrafica_cliente2' ;
	$query = "UPDATE ".$tables[6]." SET  $anagraficaNumero = $id WHERE id = $associaEventoCliente LIMIT 1";
	mysql_query($query,CONNECT);
	$cliente = GRD($tables[48],$id);
	if(trim($cliente['nome']." ".$cliente['cognome']) == '') $cliente['nome'] = "Senza Nome";

	echo "<script>
	parent.$('#".$anagraficaNumero."').html('<i class=\"fa fa-user\"></i>".$cliente['nome']." ".$cliente['cognome']."'); 
	parent.$('#".$anagraficaNumero."').attr('href','../mod_anagrafica/mod_inserisci_smart.php?id=".$cliente['id']."');
	</script>'; ";
}


?>
<input type="hidden" name="dir_upfile" value="icone_articoli" />


</form>

<?php
if(isset($_GET['j']) && $id == 1) {
	
	$potential_id = base64_decode(check($_GET['j']));
	$new_dati = GRD($tables[106],$potential_id);

	
	$query = "UPDATE `".$tables[106]."` SET `status_potential` = '4', `in_use` = '0', is_customer = '1' WHERE id = '".$potential_id."';";
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
