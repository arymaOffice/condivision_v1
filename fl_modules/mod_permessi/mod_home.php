<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

?>

<h1>Gestione Permessi</h1>

<div class="filtri">
<form method="get" action="" id="fm_filtri">
  
  
 
        <select name="modulo" id="modulo"  class="select2" >
            <option value="-1">Tutti</option>
			<?php 
		    foreach($modulo_id as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($modulo_id_id == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>  
      
       <input type="submit" value="Mostra" class="button" />

       </form>
     
 
      </div>
      

<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	
			
	?>

	 <table class="dati" summary="Dati">
      <tr>
      <th>Account</th>
 	  <th>Modulo</th>
      <th>Livello Accesso</th>
      <th>Modifica</th>
      </tr>
	  
	<?php 
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	

			if($riga['livello_accesso'] == 0) { $colore = "style=\"background: #FF3A17; color: #FFF;\"";  } else { $colore = "style=\"background: #009900; color: #FFF;\""; }

	echo "<tr>";	
			
			echo "<td>".$account_id[$riga['account_id']]."</td>"; 
			echo "<td>".$modulo_id[$riga['modulo_id']]."</td>"; 
			echo "<td $colore>".$livello_accesso[$riga['livello_accesso']]."</td>"; 
			echo "<td><a href=\"?action=1&amp;id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i>  </a></td>"; 
			
				
		    echo "</tr>";
	}

	

?>	

</table>

<?php paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main); ?>