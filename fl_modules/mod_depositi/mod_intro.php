<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = ROOT.$_SERVER['PHP_SELF'];
?>
<style>

input[type="radio"] + label {
    display: inline-block;
    margin: 10px 4px;
    padding: 2px 12px;
	min-width: 200px;
    background-color: #E7E7E7;
    border-color: #DDD;
}
</style>
<h1>Nuova operazione contabile</h1>   
<form method="get" action="" id="fm_intro">
  
<h4>Seleziona operazione</h4>
     <p>  <?php
	 
	 foreach($causale as $chiave => $valore) {
		 $checked = ($chiave == check($_GET['causale'])) ? 'checked="checked"' : '';
		if($chiave > 1 && $chiave != 85 && $chiave != 89) echo '<input type="radio" name="causale" id="'.$chiave.'1" value="'.$chiave.'" '.$checked.' /><label for="'.$chiave.'1">'.$causale[$chiave].'</label>';
		
	 } ?></p>
   
   <select name="proprietario" id="proprietario"  class="select2"  style="width: 50%;">
            <option value="-1">Seleziona affiliato</option>
			<?php 
              
		     foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
		    if($valores > 1) echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
     <input type="hidden" name="external" value="1"/>
     <input type="hidden" name="action" value="1"/>
     <input type="hidden" name="id" value="1"/>
     <input type="button" class="button" value="Inserisci"  onClick="checksubmit();" /></span>
</form>
<ol>
<li>Seleziona una delle operazioni disponibili</li>
<li>Seleziona un affiliato</li>
<li>Clicca su Inserisci per procedere con l'operazione</li>
</ol>

<script type="text/javascript">
 function checksubmit(){
if ($("#proprietario").attr('value') < 2) { alert('Seleziona un Affiliato'); } else { $("#fm_intro").submit(); }
 }
</script>