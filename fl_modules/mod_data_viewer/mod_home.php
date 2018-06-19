<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>

<h1><?php echo ucfirst(str_replace('bk_','',$tabella)) ; ?></h1>

<div style="margin: 5px 0;">
<form method="get" action="mod_opera.php">
Risultati per pagina <input type="text" name="step" value="<?php echo $step; ?>"  style="border: none; width: 40px;" /> 
 ordinati per 
<select name="ordinamento" style="border: none; ">
<?php 

foreach($ordinamento as $chiave => $valore){
	$selected = (@$_SESSION['ordinamento'] == $chiave) ? 'selected' : '';
	echo '<option value="'.$chiave.'" '.$selected.'>'.$valore.'</option>';
	
	} ?></select>   
<select name="ordinamento2" style="border: none; ">
<?php 

foreach($ordinamento as $chiave => $valore){
	$selected = (@$_SESSION['ordinamento2'] == $chiave) ? 'selected' : '';
	echo '<option value="'.$chiave.'" '.$selected.'>'.$valore.'</option>';
	
	} ?>

</select>
<input type="radio" name="ordine_type" value="ASC" id="cre" <?php if(@$_SESSION['ordine_type'] == 'ASC' || !isset($_SESSION['ordine_type'])) echo 'checked'; ?> /><label for="cre" style="padding: 8px 8px 5px 8px;">CRE</label> 
<input type="radio" name="ordine_type" value="DESC" id="dec" <?php if(@$_SESSION['ordine_type'] == 'DESC') echo 'checked'; ?> /><label for="dec" style="padding: 8px 8px 5px 8px;">DEC</label> 
<input type="submit" value="Mostra" class="button" /> 
</form>
<?php if($_SESSION['number'] == 1) { ?><input type="text" value="<?php echo $query; ?>" style=" width: 40%;" /><?php } ?>
</div>


<div id="filtri" class="filtri">
  <form method="get" action="" id="fm_filtri">
    <h2> Filtri</h2>
   <?php 
	foreach ($campi as $chiave => $valore) 
	{		
		
			if(!in_array($chiave,$nofilter)){
			if(select_type($chiave) != 19 && select_type($chiave) != 5 && select_type($chiave) != 2 && $chiave != 'id') { $cont = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$cont.'" placeholder="'.$valore.'" />'; }
			
			if((select_type($chiave) == 2 || select_type($chiave) == 19) && $chiave != 'id') {
				echo '<label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Tutti</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
			}}
	}
	 ?>    
    
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  </form>
</div>


<style>
#data-sheet { background: white; padding: 0px;  }
#data-sheet a { color: black; }
#data-sheet p { display: none; }
#data-sheet th { width: auto; }
#data-sheet td { position: relative; padding: 0;   }
#data-sheet input, #data-sheet select { 
/*position: absolute;
top: 0;
bottom: 0;
left: 0;
right: 0;*/
z-index: 555;overflow: hidden;}
#data-sheet .service_id, #data-sheet .original_job_number { width: 30px; }
#data-sheet input:focus { z-index: 666;}
</style>
<table id="data-sheet">
<?php 
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
	if(count($campi) < 1) { echo "<tr><td>Nessuna Tabella con questo nome</td></tr>";		}
	echo "<thead>";
	foreach ($campi as $chiave => $valore) 
	{					
			if(select_type($valore) != 5) echo '<th>'.$valore.'</th>';  
	
	}
	echo "</tr></thead><tbody>";
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
	$query = "SELECT * FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start, $step;";
	$risultato = mysql_query($query, CONNECT);

	
	while ($riga = mysql_fetch_assoc($risultato)) 
	{							
			echo "<tr>";
			foreach ($riga as $chiave => $valore) 
			{		
			$valore = strip_tags($valore);			
			if(select_type($chiave) != 5) {
				echo '<td>';
				$update = $valore;
				if(isset($riga['id'])  && $chiave != 'id') {
					$update =  '<input type="text" data-rel="'.$riga['id'].'" name="'.$chiave.'" value="'.$valore.'" class="updateField '.$chiave.'" />';
				if(select_type($chiave) == 2 || select_type($chiave) == 19){	
					$update = '<select data-rel="'.$riga['id'].'" name="'.$chiave.'" class="updateField '.$chiave.'">';
					foreach($$chiave as $val => $lab) { $selected = ($val == $valore) ? " selected=\"selected\" " : "" ; $update .= '<option '.$selected.'value="'.$val.'">'.$lab.'</option>'; } 
					$update .= '</select>';
				}}
				if(select_type($chiave) == 20){	
					$update =  '<input type="text" data-rel="'.$riga['id'].'" name="'.$chiave.'"  value="'.mydate($valore).'" class="updateField '.$chiave.'" />';
				}
				echo '<p>'.$valore.'</p>'.$update.'</td>'; ;
			}}
		    echo "</tr>"; 
	}

	echo "</tbody></table>";
	

?>
