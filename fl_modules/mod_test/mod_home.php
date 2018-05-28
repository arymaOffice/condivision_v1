<?php

	if($_SESSION['parent_id'] != 1) $relazione = GRD($tables[$_SESSION['workflow_id']],$_SESSION['parent_id']);
	if($_SESSION['parent_id'] != 1)  $relazione2 = GRD('fl_corsi',$relazione['course_id']);
?>


<h1>Questionari <?php if($_SESSION['parent_id'] != 1) echo "<strong>".$relazione2['title']."</strong> [".$relazione['titolo']."]"; ?></h1>
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
        <th>Titolo</th>
        <th>Modulo</th>
        <th>Quesiti</th>
        <th>Dettagli</th>
  		<th></th>

 
      </tr>
	  
	<?php 
	
	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$relazione = GRD($tables[$riga['workflow_id']],$riga['parent_id']);
	$relazione2 = GRD('fl_corsi',$relazione['course_id']);
	echo "<tr>"; 	
			
			echo "<td class=\"tab_green\"></td>";
			echo "<td><strong>".ucfirst($riga['titolo'])."</strong><br>".$modalita_esecuzione[$riga['modalita_esecuzione']]."</td>";
			echo "<td><strong>".$relazione2['title']."</strong> [".$relazione['titolo']."]</td>";
			echo "<td><a href=\"../mod_quesiti/?tId=".base64_encode($riga['id'])."\" title=\"Gestisci Quesiti\" > <i class=\"fa fa-pencil-square-o\"></i></a></td>"; 
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		    echo "</tr>";
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
