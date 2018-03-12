<div class="filtri" style="height: auto;">
<form action="./" method="get">
<?php  $data_set->do_select('VALUES','fl_moduli','label','modulo_id',$modulo_id,'',1); ?>
</form>
</div>


<?php
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
						
	 $query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
			
	?>
	 <table class="dati" summary="Dati">
      <tr>
        <th>Codice</th>
        <th>Etichetta</th>
        <th>Link</th>
        <th>Attiva</th>
        <?php if($sezione_id == 0){  ?><th>Lista</th> <?php } ?>
  <th>Modifica</th>
 
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$articoli = 0;
	$attiva_sino = ($riga['attiva'] == 1) ? "Si" : "No";
	
	
	if($i==1){ $i=0; echo "<tr>"; } else { $i=1; echo "<tr class=\"alternate\">"; }		
			
			echo "<td>".$riga['id']."</td>";
			if($sezione_id != 0){ 
		    echo "<td><a href=\"mod_inserisci.php?item_rel=".$sezione_id."&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\" >".ucfirst($riga['label'])."</a></td>";
			} else {
			echo "<td><a href=\"./?item_rel=".$riga['id']."\">".ucfirst($riga['label'])."</a></td>";
			}
			echo "<td>".ucfirst($riga['menu_link'])."<br>".$modulo[$riga['modulo']]."</td>";
			echo "<td>$attiva_sino</td>"; 
			
			if($sezione_id != 0){ 
			echo "<td><a href=\"mod_inserisci.php?item_rel=".$sezione_id."&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
			} else {
			echo "<td>
			<a href=\"./?item_rel=".$riga['id']."\"><i class=\"fa fa-bars\"></i> </a></td>
			<td><a  href=\"mod_inserisci.php?item_rel=0&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-gear\"></i> </a></td>"; 
			
			echo "<td><a title=\"Impossibile eliminare\"><i class=\"fa fa-trash-o\"></i></a></td>";
			
			}

			
				
		    echo "</tr>";
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
