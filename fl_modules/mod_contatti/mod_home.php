<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>


<p class="show_filtri"><a href="#" class="filterToggle"><i class="fa fa-filter"></i> Filtri</a></p>
<div class="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2 class="filter_header"> Filtri</h2>  
  
    <label> creato tra il</label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    <label> e il</label>
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	

 		
	?>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <th style="width: 1%;"></th>
  <th><a href="./?ordine=0">Nominativo</a></th>
  <th>Contatti</th>
  <th></th>
  <th></th>
  <th></th>
  <th  class="hideMobile"></th>
</tr>
<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$deleted = 0;
	$incomplete = 0;
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
			
			$elimina = "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";
			
			
				
			echo "<tr>"; 
			$nominativo = ucfirst($riga['nominativo']);		
			echo "<td><span class=\"Gletter\"></span></td>"; 
			echo "<td><span class=\"color\">$nominativo</span><br><span class=\"msg green\">".$tipo_contatto[$riga['tipo_contatto']]."</span></td>";
			echo "<td><i class=\"fa fa-envelope-o\"></i> <a href=\"mailto:".$riga['email']."\">".$riga['email']."</a></td>
			<td><i class=\"fa fa-phone\"> </i> ".$riga['telefono']."</td><td> <i class=\"fa fa-mobile\"></i> ".$riga['cellulare']."</td>"; 
			echo "<td  class=\"strumenti\">";
			echo "<a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Gestione Contatto\"> <i class=\"fa fa-search\"></i> </a>$elimina
			<td  class=\"hideMobile\" style=\"font-size: smaller;\" title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]."\">Agg. ".$riga['data_aggiornamento']."<br />Creaz. ".$riga['data_creazione']."</td>";
		
		    echo "</tr>"; 
			}
		

	echo "</table>";
	

?>
<?php echo '<h2>Totale risultati: '.$tot_res.'</h2>'; ?> 