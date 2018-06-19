<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
if(check($_GET['id']) != 1) { 	$tab_div_labels = array('id'=>"Richiesta",'../mod_dms/uploader.php?PiD='.$dmsFolder.'&workflow_id='.$tab_id.'&NAme[]=Ricevuta pagamento&record_id=[*ID*]'=>'Documenti'); }

include("../../fl_inc/headers.php"); include("../../fl_inc/testata_mobile.php");
 ?>



<div id="container" style=" text-align: left;">

<div id="content_scheda">


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p id="esito" class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>
<h1>Scheda Richiesta</h1>
<span class="c-red">ATTENZIONE!</span>
<p>Non verranno accettate ricevute di bonifico con data valuta differente da quella dell´esecuzione del bonifico stesso, inoltre se verranno inserite ricevute non conformi o alterate verrà esperita ogni utile azione stragiudiziale e giudiziale volta al recupero delle somme a qualsiasi titolo. 
</p>
<?php 
if(check($_GET['id']) == 1) { echo "<p>Dopo il salvataggio dei dati utente sarà possibile caricare i documenti. Si prega di caricare tutto in una sola sessione.</p>"; }
include('../mod_basic/action_estrai.php');  ?>
<?php if(check($_GET['id']) == 1) { ?><input type="hidden" name="reload" value="../mod_ricevute_pagamento/mod_inserisci.php?t=<?php echo base64_encode('1'); ?>&id=" /><?php } ?>
<?php if(isset($_GET['external'])) { ?><input type="hidden" name="external" value="1" /><?php } ?>

</form>
</div>
</div></body></html>
