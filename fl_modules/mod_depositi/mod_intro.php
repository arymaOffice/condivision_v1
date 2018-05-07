<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = ROOT.$_SERVER['PHP_SELF'];
?>
<style>

input[type="radio"] + label {
    display: inline-block;
    margin: 10px 4px;
    padding: 8px;
	min-width: 200px;
    background-color: #E7E7E7;
    border-color: #DDD;
}
</style>
<h1>Nuova operazione contabile</h1>   
<br>
<form method="get" action="mod_inserisci.php" id="fm_intro">
  
<h2>Seleziona operazione
       <?php
	 
	 foreach($causale as $chiave => $valore) {
		 $checked = ($chiave == check($_GET['causale'])) ? 'checked="checked"' : '';
		if($chiave > 1 && $chiave != 85 && $chiave != 89) echo '<input type="radio" name="causale" id="'.$chiave.'1" value="'.$chiave.'" '.$checked.' /><label for="'.$chiave.'1">'.$causale[$chiave].'</label>';
		
	 } ?></h2>
   
 
<h2>Seleziona Affiliato
   <select name="proprietario" id="proprietario"  class="select2"  style="width: 50%;">
            <option value="-1">Seleziona ... </option>
			<?php 
              
		     foreach($proprietarioAttivo as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
		    if($valores > 1) echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select></h2>
     <input type="hidden" name="id" value="1"/>
     <input type="button" class="button" style="width: 100%; padding: 10px;" value="PROCEDI"  onClick="checksubmit();" /></span>
</form>


<script type="text/javascript">
 function checksubmit(){
if ($("#proprietario").attr('value') < 2) { alert('Seleziona un Affiliato'); } else { $("#fm_intro").submit(); }
 }
</script>