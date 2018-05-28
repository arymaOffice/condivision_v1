<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>

<div id="filtri" class="filtri">
  <form method="get" action="" id="fm_filtri">
    <h2> Filtri</h2>
    
    <?php 
	foreach ($campi as $chiave => $valore) 
	{					
			if(!in_array($valore,$hidden) && $chiave != 'id') $data_set->do_select('VALUES',$tabella,$valore,$valore,'','','','','',0);
	
	}
	 ?>
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  </form>
</div>

<style>
#data-sheet { background: white; padding: 5px;  }
#data-sheet a { color: black; }
#data-sheet p { display: none; }
#data-sheet th { width: auto; }
#data-sheet td { position: relative; padding: 0; height: 25px; width: 100px;  }
#data-sheet input, #data-sheet select { 
/*position: absolute;
top: 0;
bottom: 0;
left: 0;
right: 0;*/
z-index: 555;overflow: hidden;}

#data-sheet input:focus { z-index: 666;}

.tablesorter-blue th, .tablesorter-blue thead td {
    font: 12px/18px Arial, Sans-serif;
    font-weight: bold;
    background-color: #f5f5f5;
    border-collapse: collapse;
    padding: 4px;
    text-shadow: none;
}

</style>
<table id="data-sheet">
<?php 
	
	if(count($campi) < 1) { echo "<tr><td>Nessuna Tabella con questo nome</td></tr>";		}
	echo "<thead><tr>";
	foreach ($campi as $chiave => $valore) 
	{					
			if(!in_array($valore,$hidden)) echo '<th>'.$valore.'</th>';  
	
	}
	echo "</tr></thead><tbody>";
	
	$priority = array('Low','High','Top Urgent');
	$select = array('priority','cliente_id','comops','tipo_richiesta');

	$start = paginazione(CONNECT,$tabella,$step,"$ordine ASC",$tipologia_main,1);
	$query = "SELECT * FROM `$tabella` $tipologia_main ORDER BY `$ordine` DESC LIMIT $start, $step;";
	$risultato = mysql_query($query, CONNECT);
	
	while ($riga = mysql_fetch_assoc($risultato)) 
	{					
			echo "<tr>";
			foreach ($riga as $chiave => $valore) 
			{					
			if(!in_array($chiave,$hidden)) {
				echo '<td>';
				$update = $valore;
				if(isset($riga['id'])  && $chiave != 'id') {
					$update =  '<input type="text" data-rel="'.$riga['id'].'" name="'.$chiave.'" value="'.$valore.'" class="updateField" />';
				if(in_array($chiave,$select)){	
					$update = '<select data-rel="'.$riga['id'].'" name="'.$chiave.'" class="updateField">';
					foreach($$chiave as $val => $lab) { $selected = ($val == $valore) ? " selected=\"selected\" " : "" ; $update .= '<option '.$selected.'value="'.$val.'">'.$lab.'</option>'; } 
					$update .= '</select>';
				}}
				echo '<p>'.$valore.'</p>'.$update.'</td>'; ;
			}}
		    echo "</tr>"; 
	}

	echo "</tbody></table>";
	

?>
