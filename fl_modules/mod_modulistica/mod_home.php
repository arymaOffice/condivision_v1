<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
	$_SESSION['POST_BACK_PAGE'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];

?>

<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";

	$risultato = mysql_query($query, CONNECT);
 
		
	?>

  	 <table class="dati" summary="Dati" style=" width: 100%;">
      <tr>
        <th scope="col" class="titolo"><a href="./?sezione=<?php echo $sezione_id; ?>&amp;ordine=0">Titolo</a></th>
<th scope="col" class="home">Modifica</th>
        <th scope="col" class="home">Elimina</th>   
         <th scope="col" class="home">Aggiornato</th>   
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"4\">Nessun Record Inserito</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
			if($i==1){ $i=0; echo "<tr>"; } else { $i=1; echo "<tr class=\"alternate\">"; }		
			
			echo "<td><a data-fancybox-type=\"iframe\" class=\"fancybox\" href=\"mod_inserisci.php?external&action=1&amp;id=".$riga['id']."\" title=\"Modifica\" >".ucfirst($riga['titolo'])."</a><br />".$sezione[@$riga['sezione']];
			
			echo "</td>";		
				echo "<td><a data-fancybox-type=\"iframe\" class=\"fancybox\" href=\"mod_inserisci.php?external&action=1&amp;id=".$riga['id']."\" title=\"Sheet\" > <i class=\"fa fa-search\"> </a></td>";
		  echo "<td class=\"tasto\"><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\" onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
			echo "<td title=\"Aggiornato da: ". @$proprietario[$riga['proprietario']]."\">".mydate($riga['data_aggiornamento'])."</td>";
				
		    echo "</tr>";
			
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main); ?>
