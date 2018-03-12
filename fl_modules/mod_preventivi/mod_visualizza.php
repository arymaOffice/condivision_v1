<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

$id = check($_GET['id']);
$preventivo = GRD('fl_preventivi',$id); 
$persona = ($preventivo['cliente_id'] > 1) ? GRD('fl_anagrafica',$preventivo['cliente_id']) : GRD('fl_leads',$preventivo['potential_id']); 

include("../../fl_inc/headers.php"); ?>


<body style=" background: #FFFFFF;">

<div id="print_container" class="preventivo">




<h1>Preventivo <?php echo "P".substr($preventivo['data_creazione'],2,2)."-".str_pad($preventivo['id'],3,0,STR_PAD_LEFT)."-".@substr($tipo_preventivo[$preventivo['tipo_preventivo']],0,1); ?> <a href="#" class="noprint" onClick=" window.print();"> <i class="fa fa-print"></i></a></h1>





<div class="decorathio"><?php echo html_entity_decode(converti_txt($preventivo['testo_preventivo'])); ?></div>



<div class="elaborathio">
<h1>Dettaglio preventivo</h1>
<br class="clear">
<div class="col-sm-5">
<?php if(isset($persona) && $persona['id'] != NULL){ $telefono = phone_format($persona['telefono'],'39'); ?>
<h2 style="margin: 0;">Cliente: <strong><?php echo $persona['nome'].' '.$persona['cognome']; ?></strong></h2>
<p>Tel: <a href="tel:<?php echo $telefono; ?>"><?php echo $telefono; ?> </a><a href="tel:<?php echo $telefono; ?>"><?php echo $persona['telefono']; ?></a><br>
Email: <a href="mailto:<?php echo @$persona['emails']; ?>"><?php echo @$persona['emails']; ?></a></p>
<p>Città: <?php echo $persona['citta']; ?></p><br>
<?php } ?>
</div>


<div class="col-sm-5">
<label><strong>Tipo Preventivo: </strong></label><?php echo $tipo_preventivo[$preventivo['tipo_preventivo']]; ?> <br>
<label><strong>Data Preventivo: </strong></label><?php echo mydate($preventivo['data_preventivo']); ?> <br>
<label><strong>Adulti n. </strong></label><?php echo $preventivo['numero_adulti']; ?> <br>
<label><strong>Bambini n. </strong></label><?php echo $preventivo['numero_bambini']; ?> <br>
<label><strong>Date proposte:</strong></label> <?php echo $preventivo['date_proposte']; ?></div>
<br class="clear">
<br class="clear">
<h1><?php echo html_entity_decode(converti_txt($preventivo['oggetto_preventivo'])); ?></h1>

<h2>Prezzo menù proposto: &euro; <?php echo $preventivo['totale_preventivo']; ?></h2>
<br class="clear">

<h2 class="deco-title">Note</h2>
<?php echo html_entity_decode(converti_txt($preventivo['note'])); ?>
<br class="clear">
<h2 class="deco-title">Condizioni di vendita</h2>
<?php echo html_entity_decode(converti_txt($preventivo['condizioni_preventivo'])); ?>
<br class="clear">
<h2 class="deco-title">Condizioni di pagamento</h2>
<?php echo html_entity_decode(converti_txt($preventivo['condizioni_pagamento'])); ?>
<br class="clear">


<div class="privacytxt"><?php echo html_entity_decode(converti_txt($preventivo['informativa_privacy'])); ?></div>
</div>

</div></body></html>
