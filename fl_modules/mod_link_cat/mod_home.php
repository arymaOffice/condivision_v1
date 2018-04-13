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
    <th>Id</th>
    <th>Descrizione</th>
    <th>Data creazione</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php

	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}

	while ($riga = mysql_fetch_array($risultato))
	{

	$attivo = ($riga['attivo'] != 0) ? 'tab_green' : 'tab_red';

			echo "<tr>";
			echo "<td class=\"$attivo\" style=\"width: 10px;\"></td>";
			echo "<td>".$riga['id']."</td>";
			echo "<td>".$riga['descrizione']."</td>";
			echo "<td>".mydate($riga['data_creazione'])."</td>";
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>";
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>";
		    echo "</tr>";
	}




?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
