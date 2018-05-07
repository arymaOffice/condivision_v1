<?php if($_SESSION['usertype'] > 0) exit; ?>

<div class="filtri" style="height: auto;"> </div>


<?php
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
        <th>Id</th>
        <th>Linea</th>
        <th>Categoria</th>
        <th>Tipo</th>
        <th>Codice SRV</th>
        <th>Prodotti</th>
        <th></th>
  		<th></th>

 
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$template = ($riga['codice'] != '' && file_exists('../mod_ricevute/template_scontrini/'.trim($riga['codice']).'.php')) ? "<a target=\"_blank\" href=\"../mod_ricevute/?srv=".$riga['codice']."\">".$riga['codice']."</a>" : '<a href="../mod_ricevute/?srv='.$riga['codice'].'" style="color: red">'.$riga['codice'].'</a>';
	if($riga['codice'] == '') $template = '<a href="#" style="color: red">INSERISCI CODICE!</a>';
			
			echo "<td class=\"tab_blue\"></td>";
			echo "<td>".$riga['id']."</td>";
			echo "<td><a href=\"../mod_prodotti/?prodotto_id=".$riga['id']."\">".ucfirst($riga['label'])."</a></td>";	
			echo "<td>".@$categoria_prodotto[$riga['categoria_prodotto']]."</td>";
			echo "<td>".$tipo_prodotto[$riga['tipo_prodotto']]."</td>";
			echo "<td>$template</td>"; 
			echo "<td><a href=\"../mod_goservizi/?prodotto_id=".$riga['id']."\"><i class=\"fa fa-bars\"></i></a></td>"; 
			echo "<td><a href=\"mod_inserisci.php?item_rel=0&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		   	echo "</tr>";
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
