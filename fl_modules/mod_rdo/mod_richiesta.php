<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$parent_id = check($_GET['parent_id']);
$tipo_richiesta = check($_GET['tipo_richiesta']);

include("../../fl_inc/headers.php");

 ?>


<body style=" background: #FFFFFF;">
<?php if(isset($_GET['esito'])) { echo '<div id="results"><h2 class="red">'.check($_GET['esito']).'</h2></div>'; } else { ?>


<form id="scheda" action="mod_opera.php" method="post" enctype="multipart/form-data" style="width: 90%; margin: 0 auto; ">
<h1>Registra Attivit&agrave;</h1>

<h2>Data Attivit&agrave;</h2>
<input type="text" name="data_scadenza" value="<?php echo date('d/m/Y'); ?>" class="calendar" /><br>
<input type="text" name="interlocutore" placeholder="Interlocutore" value="" />
<input type="text" name="note" placeholder="Note" value="" />
<input type="submit" value="Registra Attivit&agrave;" class="button" /><br>
<input type="hidden" name="anagrafica_id" value="0" />
<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />  
<input type="hidden" name="tipo_richiesta" value="<?php echo $tipo_richiesta; ?>" />


</form><?php } ?>

</body></html>
