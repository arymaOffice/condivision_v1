<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

$id = check($_GET['id']);
if(check($_GET['id']) != 1) { 	$tab_div_labels = array('id'=>"Dettagli",'../mod_dms/uploader.php?PiD='.base64_encode(FOLDER_CONTABILI).'&workflow_id='.$tab_id.'&NAme[]=Ricevuta Versamento&record_id=[*ID*]'=>'Ricevuta'); }

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >



<div id="content_scheda">

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div id="map-canvas"></div>
<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">



<?php include('../mod_basic/action_estrai.php');  ?>

<input type="hidden" name="dir_upfile" value="icone_articoli" />


</form>



</div></body></html>
