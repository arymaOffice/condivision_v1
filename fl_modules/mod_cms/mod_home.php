<h1><?php echo $module_title; ?></h1>

<form method="get">
<?php $data_set->do_select('TABLE','fl_anagrafica','ragione_sociale','ANiD'); ?>
</form>

<?php
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
			
	?>
	 <table class="dati" summary="Dati">
      <tr>
        <th></th>
        
        <th></th>
        <th>Cliente</th>
       
        <th>Titolo</th>
        <th></th>
  		<th></th>

 
      </tr>
	  
	<?php 
	
	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$azione = "<a href=\"../mod_dms/?c=NQ==&workflow_id=".$riga['id']."\"><i class=\"fa fa-file\"></i></a>";
	$attivo = ($riga['attivo'] == 1) ? "class=\"tab_green\"" : "class=\"tab_red\" ";
	echo "<tr>"; 	
			
			echo "<td $attivo></td>";
			echo "<td></td>";
			echo "<td>".$anagrafica_id[$riga['anagrafica_id']]."</td>";
			echo "<td>".ucfirst($riga['titolo'])."</a></td>";	
			
			echo "<td>$azione</td>"; 
			echo "<td><a href=\"mod_inserisci.php?item_rel=0&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		    echo "</tr>";
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
