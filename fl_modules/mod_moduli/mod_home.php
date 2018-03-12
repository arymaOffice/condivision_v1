<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>

<h1>Gestione Moduli applicativi</h1>

<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	
			
	?>

	 <table class="dati" summary="Dati">
      <tr>
              <th>Attivo</th>
  <th>Codice</th>
        <th>Modulo</th>
          <th>Accesso Predef. Staff</th>
            <th>Accesso Utenti</th>
             <th>Menu modulo</th>
        <th>Modifica</th>
      </tr>
	  
	<?php 
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	
		if($riga['attivo'] == 0) { $alert = "Non attivo"; $colore = "style=\"background: #FF3A17; color: #FFF;\"";  } else { $alert = "Attivo"; $colore = "style=\"background: #009900; color: #FFF;\""; }

	
	echo "<tr>";	
			
			echo "<td $colore >". $alert ."</td>"; 
			echo "<td class=\"\">".$riga['id']."</td>";
			echo "<td class=\"\">".ucfirst($riga['label'])."</td>";		
			echo "<td class=\"\">".$accesso_predefinito[$riga['accesso_predefinito']]."</td>"; 
			echo "<td class=\"\">".$accesso_utenza[$riga['accesso_utenza']]."</td>"; 
			echo "<td class=\"\"><a href=\"../mod_menu/?menu_id=2&modulo_id=".$riga['id']."\"><i class=\"fa fa-bars\"></i></a></td>"; 
			
			echo "<td class=\"\"><a href=\"?action=1&amp;id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i>  </a></td>"; 
			
				
		    echo "</tr>";
	}

	

?>	

</table>

<?php paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main); ?>