<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>




<div class="filtri" id="filtri"> 
<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  

<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>
<?php if(isset($_GET['start'])) echo '<input type="hidden" value="'.check($_GET['start']).'" name="start" />'; ?>

   <?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){
			
			

			if((select_type($chiave) == 2 || select_type($chiave) == 19 || select_type($chiave) == 9 || select_type($chiave) == 8 || select_type($chiave) == 12) && $chiave != 'id') {
				
								
				echo '<div class="filter_box">';
				echo '  <label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Non impostato</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
				echo '</div>';
			} else if( $chiave != 'id') { $valtxt = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; 
			echo '<div class="filter_box">';
			echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$valtxt.'" />'; echo '</div>';}

			
			
			} 
		
	}
	 ?>    

    <label> Periodo da</label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    <label> al </label>
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>
    
<?php
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
?>

<table class="dati" summary="Dati">
  <tr>
	<th></th>
	<th>Id</th>
    <th>Titolo</th>
    <th>Genitore</th>
    <th></th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$azione = (0) ? '' : '';
	$conv=converti_txt($riga['descrizione']);
	if($riga['attivo'] == 0) {  $colore = "class=\"tab_red\"";  } else { $colore = "class=\"tab_green\""; }
	$strip=strip_tags($conv);
	$desc=substr($strip,0,255);
	$articoli = mk_count('fl_articoli','id > 1 AND categoria_id = '.$riga['id']);
	$subcat = mk_count($tabella,' id > 1 AND parent_id = '.$riga['id']);

			echo "<tr>"; 				
			echo "<td $colore></td>";
			echo "<td>".$riga['id']."</td>";
			echo "<td><h2>".$riga['label']."</h2><p>
			<a href=\"./?parent_id=".$riga['id']."\" class=\"color: c-orange\">Mostra Sottocategorie (".$subcat.")</a> | <a href=\"../mod_articoli/?status_contenuto=-1&categoria_id=".$riga['id']."\" class=\"color: c-orange\">Mostra Articoli (".$articoli.")</a>
			</p>".$desc."</td>";	
			echo "<td>".$parent_id[$riga['parent_id']]."</td>";
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
