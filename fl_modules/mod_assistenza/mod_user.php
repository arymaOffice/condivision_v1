<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
	$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>


<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	//echo $query;
	$risultato = mysql_query($query,CONNECT);
	
		
	?>
       

  
<table class="dati">
      <tr>
        <th>Oggetto | <a href="./?ordine=1">Operatore</a></th>
        <th><a href="./?ordine=5">Status</a></th>
        <th><a href="./?ordine=0">Data Apertura</a></th>
        <th>Data ultima attivit&agrave;</th>
      
 
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Record Inserito</td></tr>";		}
	
	$entrate = 0;
	$uscite = 0;
	$saldo = 0;
	$saldo_parziale = 0;
	

	
	while ($riga = mysql_fetch_array($risultato)) 
	{
			
		$manute = "SELECT * FROM `$tabella` WHERE jorel = ".$riga['id']." AND operatore != ".$_SESSION['number']." AND letto = 0;";
		mysql_query($manute,CONNECT);
		$new_mail = (mysql_affected_rows() > 0) ? "not_read" : "read";
		
 			echo "<tr>";
			
			echo "<td><a href=\"mod_scheda.php?action=8&amp;proprietario=".$riga['proprietario']."&amp;jorel=$jorel&amp;id=".$riga['id']."\" title=\"".$riga['descrizione']."\">".$riga['oggetto']."</a><br />".$proprietario[$riga['proprietario']]."</td>";
		    echo "<td>".$status_assistenza[$riga['status_assistenza']]."</td>";
			echo "<td title=\"Creato da: ". @$proprietario[$riga['proprietario']]."\">".mydate($riga['data_creazione'])."</td>";
			echo "<td  class=\"$new_mail\" title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]."\"><a href=\"mod_scheda.php?action=8&amp;jorel=$jorel&amp;id=".$riga['id']."\" title=\"".strip_tags(converti_txt($riga['descrizione']))."\">".mydate($riga['data_aggiornamento'])."</a></td>";
	
			
		
		
		    echo "</tr>";
	}
	

	

	

?>
</table>
