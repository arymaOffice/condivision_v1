<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];


?>
<h1>Archivio Notifiche</h1>
      
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";

	$risultato = mysql_query($query, CONNECT);
		
	?>

  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
        <th style="width: 1%;"></th>
        <th scope="col" class="titolo">Titolo</th>
         <th scope="col" class="home">Destinatario</th>
       
        <th scope="col" class="home">Creato il</th>   
      </tr>
	  
	<?php 
	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"8\">Nessun Contenuto Trovato</td></tr>";		}
	while ($riga = mysql_fetch_array($risultato)) 
	{
		   $read = get_letture(1,$riga['id'],0);

			echo "<tr>"; 	
			echo "<td class=\"\"><span class=\"Gletter\"></span></td>"; 
			echo "<td><a href=\"mod_visualizza.php?external&action=1&amp;id=".$riga['id']."\" title=\"Visualizza\" >".ucfirst($riga['titolo'])."</a></td>";		
			echo "<td>".@$destinatario[$riga['destinatario']]."</td>";
			echo "<td title=\"Aggiornato da: ". @$proprietario[$riga['proprietario']]."\">".mydate($riga['data_invio'])."</td>";
		    echo "</tr>";
			
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main); ?>
