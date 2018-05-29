<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }


?>




<h1>CSV Importer & Leads export </h1>
<!-- Tipo di codifica dei dati, DEVE essere specificato come segue -->
<form enctype="multipart/form-data" action="mod_upload.php" method="post" id="upload_csv" style="padding-top: 3%; text-align: right; max-width: 400px; float: left;">
    <!-- MAX_FILE_SIZE deve precedere campo di input del nome file -->
    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
  
     
    <b>Seleziona Campagna  </b> 

  <select name="campagna_id">
    <option value="-1">Generica</option>
        
      <?php 
              
             foreach($campagna_id as $valores => $label){ // Recursione Indici di Categoria
             echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; 
            }
         ?>
    </select> 


   <br>
    <b>Seleziona Attività </b> 

    <select name="source_potential">
   	<option value="-1">Generica</option>
        
      <?php 
              
		     foreach($source_potential as $valores => $label){ // Recursione Indici di Categoria
			 echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select> 


<br> 
    <b>Status </b> 

  <select name="status_potential">
   
        
      <?php 
              
             foreach($status_potential as $valores => $label){ // Recursione Indici di Categoria
             echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; 
            }
         ?>
    </select> 

    <br>
    <br>
    <b>Load file:</b> <input name="file[]" type="file" id="upload_file" accept=".csv"/><br>
    <input type="submit" value="Carica File" class="button" />
</form>
    


<a href="../../fl_core/services/exportleadpp.php" class="button" style="margin-right: 400px;float: right;">Esporta leads dalla app</a>