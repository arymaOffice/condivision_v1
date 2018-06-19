<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

?>

<?php if(@$home_items != "") { echo "<strong>Articoli in Homepage</a>"; } ?>
</p>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	
	$query3 = "SELECT priority FROM `$tabella` $tipologia_main ORDER BY priority DESC LIMIT 1";
	
	$resultf = mysql_query($query3, CONNECT);

	$rog = mysql_fetch_array($resultf);
	
 
		
	?>

 
		
		<h2><?php
        
		foreach($sezione as $valores => $label){ // Recursione Indici di Categoria
			if($sezione_id == $valores) echo ucfirst($label)."\r\n";
			}
		
			
			?></h2>
 
  	 <table class="dati" summary="Dati" style=" width: 100%;">
      <tr>
        <th scope="col" class="titolo"><a href="./?ordine=0">Titolo</a></th>
        <th scope="col" class="home">Aggiornato</th>   
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Record Inserito</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
			if($i==1){ $i=0; echo "<tr>"; } else { $i=1; echo "<tr class=\"alternate\">"; }		
			
		
			echo "<td class=\"titolo\"><a  href=\"?action=12&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."\" title=\"Tags: ".$riga['tags']."\">".ucfirst($riga['titolo'])."</a><br />";
			echo "Disponibilit&agrave; cerchie: ";
			get_gruppi($riga['gruppo'],$gruppo);
			echo "</td>";		
			echo "<td title=\"Aggiornato da: ".@$proprietario[$riga['operatore']]."\">".date("d/m/y H:i",$riga['data_aggiornamento'])."</td>"; 
				
		    echo "</tr>";
			
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main); ?>