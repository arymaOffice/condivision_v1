<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>

<style>
.filter_box { width: 24%; }
.filter_box label { display: block;}
input,select {  width: 100%; }
.select2-container { width: 100%;  }
</style><h2> Ricerca Avanzata</h2>  
<p><a href="./">Torna a Lista</a></p>

<form method="get" action="" id="fm_filtri" style="background: #C9C6C6; padding: 20px; ">

<?php if(ATTIVA_ACCOUNT_ANAGRAFICA == 1) { ?>

  <div class="filter_box">  
  <label>  Tipo account:</label>
    <select name="tipo_account" id="tipo_account">
      <option value="-1">Tutti </option>
  	<?php 
              
		     foreach($tipo as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($tipo_account_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select>
    </div>
    

   
  <div class="filter_box">  
  <label>  Stato account:</label>
    <select name="status_account" id="status_account">
      <option value="-1">Tutti </option>
      <?php 
            $selected = (@$stato_account_id == 1) ? " selected=\"selected\"" : "";
		    echo "<option value=\"1\" $selected>Attivi</option>\r\n"; 
			$selected = (@$stato_account_id == 0) ? " selected=\"selected\"" : "";
			echo "<option value=\"0\" $selected>Sospesi</option>\r\n"; 
			
		 ?>
    </select>
    </div>
    
<div class="filter_box">  
<label>  Account:</label>
<input type="text" id="account" name="account" value="<?php if(isset($_GET['account'])){ echo check($_GET['account']);} else {  } ?>" onFocus="this.value='';" onkeydown=""  accesskey="a" tabindex="1"   onkeyup="return caricaProprietario(this.value,'contenuto-dinamico','account');" maxlength="200" class="txt_cerca" />
<div id="contenuto-dinamico"></div>
</div>

<?php } ?>    
    
    
   <?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			
			echo '<div class="filter_box">';
			
			if(select_type($chiave) != 19 && select_type($chiave) != 5 && select_type($chiave) != 2 && $chiave != 'id' && $chiave != 'action_back') { $cont = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$cont.'"  />'; }
			
			if((select_type($chiave) == 2 || select_type($chiave) == 19) && $chiave != 'id') {
				echo '  <label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select2"><option value="-1">Tutti</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
			}
			echo '</div>';
			
		
	}
	
	if( isset( $_GET['action_back']) ) echo '<input type="hidden" value="'.check($_GET['action_back']).'" name="action"  />';  ?>    



    <input type="submit" value="Ricerca" class="salva" id="filter_set" />
  
</form>

     
