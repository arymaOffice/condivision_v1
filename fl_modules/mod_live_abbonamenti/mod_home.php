<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>

    
<?php
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
	$query = "SELECT $select,free,p.label as periodoLabel,abb.id as abbId FROM `$tabella` abb LEFT JOIN fl_periodi p ON abb.periodo = p.id WHERE abb.id != 1 ORDER BY abb.$ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
?>

<table class="dati" summary="Dati">
  <tr>
    <th></th>
    <th>Id</th>
    <th>Free</th>
    <th>Nome</th>
    <th>Durata</th>
    <th>Periodo</th>
    <th>Costo</th>
    <th></th>
    <th></th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$attivo = ($riga['attivo'] == 1) ? 'tab_green' : 'tab_red';
	$free = ($riga['free'] == 1) ? 'tab_yellow' : '';

			echo "<tr>"; 				
			echo "<td style=\"width:10px\" class=\"$attivo\"></td>";
			echo "<td>".$riga['abbId']."</td>";
			echo "<td style=\"width:10px\" class=\"$free\"></td>";
			echo "<td>".$riga['nome']."</td>";	
			echo "<td>".$riga['durata']."</td>";	
			echo "<td>".$riga['periodoLabel']."</td>";	
			echo "<td>".$riga['costo']."</td>";
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['abbId']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['abbId']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
