<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
if(check($_GET['id']) != 1) { 	$tab_div_labels = array('id'=>"Richiesta",'../mod_dms/uploader.php?PiD='.$dmsFolder.'&workflow_id='.$tab_id.'&NAme[]=Carta di Identita&NAme[]=Codice Fiscale&record_id=[*ID*]'=>'Documenti'); }

include("../../fl_inc/headers.php"); include("../../fl_inc/testata_mobile.php");
 ?>



<div id="container" style=" text-align: left;">

<div id="content_scheda">


<h1>Scheda Richiesta</h1>
<?php include('../mod_basic/action_visualizza.php');  ?>
</div>
</div></body></html>
