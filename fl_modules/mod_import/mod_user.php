
<?php 
// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

$id = check($_GET['id']);
$attivita = GRD('fl_campagne_attivita',$id);

include("../../fl_inc/headers.php");?>







<h1>CSV Importer</h1>
<?php if(isset($_GET['esito'])) echo '<h2 class="msg green" style="display: block; padding: 10px; text-align: center;">'.check($_GET['esito']).'</h2>'; ?>
<!-- Tipo di codifica dei dati, DEVE essere specificato come segue -->
<form enctype="multipart/form-data" action="mod_upload.php" method="post" id="upload_csv" style=" text-align: center;">
    <!-- MAX_FILE_SIZE deve precedere campo di input del nome file -->
    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
  
  <input type="hidden" name="campagna_id" value="<?php echo $attivita['campagna_id']; ?>" />
  <input type="hidden" name="source_potential" value="<?php echo  $attivita['id']; ?>" /> 
  <input type="hidden" name="status_potential" value="0" />
  <input type="hidden" name="external" value="1" />
  <input type="file" name="file[]" id="file" class="inputfile" multiple accept=".csv" style="padding: 70px 80px;" onchange="form.submit();" />
</form>
    
<p>Clicca su sfoglia o trascina un file nel riquadro bianco per avviare il caricamento</p>
