<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = ROOT.$_SERVER['PHP_SELF'];

?>

<h1>Nuovo Bonus</h1>   
<form method="get" action="" id="fm_intro">
  
  
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
     <input type="hidden" name="tipo_operazione"  value="<?php echo check($_GET['tipo_operazione']); ?>" />
     <input type="button" class="button" value="Inserisci"  onClick="checksubmit();" /></span>
</form>

<script type="text/javascript">
 function checksubmit(){
if ($("#proprietario").attr('value') < 2) { alert('Seleziona un Affiliato'); } else { $("#fm_intro").submit(); }
 }
</script>