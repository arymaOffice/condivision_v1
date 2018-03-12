<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$parent_id = check($_GET['parent_id']);
$tipo_richiesta = check($_GET['tipo_richiesta']);
$nochat = 1;
$data_scadenza =  date('d/m/Y', strtotime("+2 days"));

include("../../fl_inc/headers.php");

 ?>


<body style=" background: #FFFFFF;">
<?php if(isset($_GET['esito'])) { echo '<div id="results"><h2 class="red">'.check($_GET['esito']).'</h2></div>'; 
echo '<script type="text/javascript"> parent.$.fancybox.close();</script>'; 
} else { ?>


<form id="scheda2" action="mod_opera.php" method="post" enctype="multipart/form-data" style="width: 90%; margin: 0 auto; ">
<h1>Registra Attivit&agrave;</h1>

<h2>Data Attivit&agrave;</h2>
<?php if(isset($_GET['status_potential'])) { 
$status_potential = check($_GET['status_potential']);
if($status_potential == 3 || $status_potential == 6) {
echo '<span class="msg red">Data preimpostata a 90 giorni per il Recall</span>';
$data_scadenza = date('d/m/Y', strtotime("+90 days"));
}?>
<input type="hidden" name="status_potential" value="<?php echo $status_potential; ?>" />
<?php } ?>

Imposta una nuova scadenza <input type="text" name="data_scadenza" value="<?php echo $data_scadenza; ?>" class="calendar" /><br>
<input type="text" name="interlocutore" placeholder="Interlocutore" value="" />
<input type="text" name="note" placeholder="Note" value="" />
<input type="submit" value="Registra Attivit&agrave;" class="button" /><br>
<input type="hidden" name="anagrafica_id" value="0" />
<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />  
<input type="hidden" name="tipo_richiesta" value="<?php echo $tipo_richiesta; ?>" />



</form><?php } ?>

</body></html>
