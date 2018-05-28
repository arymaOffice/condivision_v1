<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

if(isset($_GET['protect'])) { $tab_id = 121; $tabella = $tables[$tab_id]; }

unset($chat);
include("../../fl_inc/headers.php"); 
include("../../fl_inc/testata_mobile.php");


$titolare= '';

$veicolo_id = (isset($_GET['protect'])) ? check($_GET['veicolo_id']) : check($_GET['id']);

$veicolo = @GRD('fl_veicoli',$veicolo_id);

if($veicolo['workflow_id'] > 1 && $veicolo['parent_id'] > 1) {
		  $account = @GRD(@$tables[$veicolo['workflow_id']],$veicolo['parent_id']);
		  $mod = ($veicolo['workflow_id'] == 58) ? 'mod_anagrafica' : 'mod_leads';
		  $titolare = '<div class="info_dati"><h1 style="display: inline-block; margin: 0 0 5px;" class="nominativo"><strong><a href="../'.$mod.'/mod_inserisci.php?id='.$account['id'].'">'.$account['nome'].' '.$account['cognome'].'</a></strong></h1>
		  <p style="margin: 5px 0px;">
		  <i class="fa fa-phone" style="padding: 3px;"></i><a href="tel:'.@$account['telefono'].'" class="setAction" style="color: black;" data-gtx="'.base64_encode($tab_id).'" data-id="'.base64_encode($account['id']).'" data-azione="2"  data-esito="2" data-note="Avvio Chiamata">'.@$account['telefono'].'</a> 
		  <i class="fa fa-envelope-o" style="padding: 3px;"></i><a style="color: black;" href="mailto:'.@$account['email'].'" class="setAction" data-gtx="'.base64_encode($tab_id).'" data-id="'.base64_encode($account['id']).'" data-azione="3"  data-esito="5"  data-note="Avvio Composizione Email">'.@$account['email'].'</a>
		  </p>
		  
		  <p style="margin: 5px 0px;">'.@$veicolo['marca'].' '.@$veicolo['modello']." ".@$veicolo['colore'].'
		  <span class="msg orange">'.@$tipologia_veicolo[$veicolo['tipologia_veicolo']].'</span>
		  <span class="msg blue">'.@$alimentazione[$veicolo['alimentazione']].'</span></p>
		  </div>';

 }

?>


<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >
<div id="content_scheda">


<?php echo $titolare; ?>

<style>
.fancybox-wrap { 
  top: 25px !important; 
}</style>
<h1>Seleziona estensione di garanzia Ford Protect</h1>   

<div style="text-align:  center;">

<a href="#" class="touch green_push" onclick="$('#prodotti').load('3.php?veicolo_id=<?php echo $veicolo_id; ?>');">3 +</a>
<a href="#" class="touch green_push" onclick="$('#prodotti').load('5.php?veicolo_id=<?php echo $veicolo_id; ?>');">5 +</a>

</div>

<div id="prodotti"></div>	



</body></html>


