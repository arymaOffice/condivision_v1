<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>

    
<?php
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
	$query = "SELECT $select,lcat.descrizione as catName, GROUP_CONCAT( `folder_number` ) as folders  FROM `$tabella` ads JOIN fl_link_cat lcat ON lcat.id = categoria_ads LEFT JOIN fl_dms dms ON dms.id = folder_number  GROUP BY categoria_ads ORDER BY lcat.$ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
?>

<table class="dati" summary="Dati">
  <tr>
    <th>Categoria </th>
    <th> Cartelle pubblicit&agrave </th>
    <th></th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{

			echo "<tr>"; 				
			echo "<td>".$riga['catName']."</td>";	
			echo "<td>".$riga['folders']."</td>";
			echo "<td><a href=\"mod_inserisci.php?categoria_ads=".$riga['categoria_ads']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>";  
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
