<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
if(check($_GET['id']) != 1) { 	$tab_div_labels = array('id'=>"Richiesta",'../mod_dms/uploader.php?PiD='.$dmsFolder.'&workflow_id='.$tab_id.'&NAme[]=Carta di Identita&NAme[]=Codice Fiscale&record_id=[*ID*]'=>'Documenti'); }

include("../../fl_inc/headers.php"); include("../../fl_inc/testata_mobile.php");
 ?>



<div id="container" style=" text-align: left;">

<div id="content_scheda">


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p id="esito" class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>
<h1>Scheda Richiesta</h1>
<?php 
if(check($_GET['id']) == 1) { echo "<p>Dopo il salvataggio dei dati utente sarà possibile caricare i documenti. Si prega di caricare tutto in una sola sessione.</p>"; }
include('../mod_basic/action_estrai.php');  ?>
<?php if(check($_GET['id']) == 1) { ?><input type="hidden" name="reload" value="../mod_attivazioni/mod_inserisci.php?t=<?php echo base64_encode('1'); ?>&id=" /><?php } ?>
<input type="hidden" name="dir_upfile" value="../../../set/files/clienti" />
<?php if(isset($_GET['external'])) { ?><input type="hidden" name="external" value="1" /><?php } ?>

</form>
</div>
</div></body></html>
