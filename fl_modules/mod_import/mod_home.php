<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }


?>




<h1>CSV Importer</h1>
<!-- Tipo di codifica dei dati, DEVE essere specificato come segue -->
<form enctype="multipart/form-data" action="mod_upload.php" method="post" style="padding-top: 3%;">
    <!-- MAX_FILE_SIZE deve precedere campo di input del nome file -->
    <input type="hidden" name="MAX_FILE_SIZE" value="300000000000000" />
  
     
    <b>Carica file:</b> <input name="file[]" type="file" accept=".csv"/><br>
    <input type="submit" value="Carica File" class="button" />
</form>
    

