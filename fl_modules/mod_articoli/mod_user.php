<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>





    
<?php
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
?>

<table class="dati" summary="Dati">
  <tr>
    <th></th>
    <th>Titolo</th>
    <th>Data</th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$colore = ($riga['status_contenuto'] == 0) ? "class=\"tab_red\"" : "class=\"tab_green\"" ;
	if($riga['status_contenuto'] > 1) $colore = "class=\"tab_blue\"";


			echo "<tr>"; 				
			echo "<td $colore></td>";
			echo "<td><span title=\"\">".$riga['titolo']."</span></td>";	
			echo "<td>".mydate($riga['data_pubblicazione'])." da ".@$proprietario[$riga['operatore']]."</td>";
			echo "<td><a href=\"mod_visualizza.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
