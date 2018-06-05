
<?php
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>




<h1>Panoramica Operatori </h1>



<?php
	
	echo '<a class="button" href="./"><i class="fa fa-user" aria-hidden="true"></i> Mostra le tue attività</a><br><br><br>';

	if($_SESSION['usertype'] < 4) {
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main  ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	

 		
	?>

	<h2>BDC </h2>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
 <th style="width: 1%;"></th>
 <th><a href="./?ordine=1">Attivita</a></th>
<?php
foreach($operatoribdc as $valores => $label){ // Recursione Indici di Categoria
				echo "<th> $label</th>";
			}
			?>
</tr>
<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$deleted = 0;
	$incomplete = 0;
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
			
			$colore = "class=\"tab_blue\"";
			$colore = ($riga['data_fine'] < date('Y-m-d')) ? "class=\"tab_red\"" :"class=\"tab_green\"";
		    if($riga['data_inizio'] > date('Y-m-d'))  $colore = "class=\"tab_orange\"";


			$actionViewMain = ($_SESSION['usertype'] == 3 || $_SESSION['usertype'] == 0) ? '' : 'action=25&';

				
			echo "<tr>"; 
			$oggetto = ucfirst($riga['oggetto']);		
			echo "<td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td><span class=\"color\"><a href=\"../mod_leads/?".$actionViewMain."status_potential=-1&source_potential[]=".$riga['id']."\" title=\"".ucfirst(strip_tags(converti_txt($riga['descrizione'])))."\">$oggetto</a></span></td>";
	
			foreach($operatoribdc as $valores => $label){ // Recursione Indici di Categoria


				$mieileads = ' AND fl_potentials.proprietario = '.$valores;
				$actionView = ($valores > 1) ? 'proprietario='.$valores.'&'.$actionViewMain : $actionViewMain;				

				$totale_leads = mk_count('fl_potentials','status_potential != 9 AND source_potential = '.$riga['id'].$mieileads);
				$totale_leads_dagestire = mk_count('fl_potentials','status_potential < 2 AND source_potential = '.$riga['id'].$mieileads);
				$totale_conversioni = mk_count('fl_potentials','status_potential = 4 AND source_potential = '.$riga['id'].$mieileads);
				$tasso_conversione = @numdec(@$totale_conversioni/@$totale_leads*100,2);
				$color_tot = ($totale_leads_dagestire > 0) ? 'color: red;' : 'color: green;';
				$status_p = ($valores > 0) ? 1 : 0;
				echo "<td class=\"center fontlarge\"><a title=\"Conversioni: $totale_conversioni ($tasso_conversione %) \" href=\"../mod_leads/?".$actionView."status_potential=$status_p&source_potential[]=".$riga['id']."\" style=\"$color_tot\">$totale_leads_dagestire</a> / $totale_leads</td>";
			}
		
		    echo "</tr>"; 

			}
		

	echo "</table>";


}


	

?> 








































<h2>Digital</h2>
 <?php
	

	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main  ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	

 		
	?>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
 <th style="width: 1%;"></th>
 <th><a href="./?ordine=1">Attivita</a></th>
<?php
foreach($operatoridgt as $valores => $label){ // Recursione Indici di Categoria
				echo "<th> $label</th>";
			}
			?>
</tr>
<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$deleted = 0;
	$incomplete = 0;
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
			
			$colore = "class=\"tab_blue\"";
			$colore = ($riga['data_fine'] < date('Y-m-d')) ? "class=\"tab_red\"" :"class=\"tab_green\"";
		    if($riga['data_inizio'] > date('Y-m-d'))  $colore = "class=\"tab_orange\"";


			$actionViewMain = ($_SESSION['usertype'] == 3 || $_SESSION['usertype'] == 0) ? '' : '';
				
			echo "<tr>"; 
			$oggetto = ucfirst($riga['oggetto']);		
			echo "<td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td><span class=\"color\"><a href=\"../mod_leads/?".$actionViewMain."status_potential=-1&source_potential[]=".$riga['id']."\" title=\"".ucfirst(strip_tags(converti_txt($riga['descrizione'])))."\">$oggetto</a></span></td>";
	
			foreach($operatoridgt as $valores => $label){ // Recursione Indici di Categoria
				
				$mieileads = ' AND fl_potentials.proprietario = '.$valores;
				$actionView = ($valores > 1) ? 'proprietario='.$valores.'&'.$actionViewMain : $actionViewMain;				

				$totale_leads = mk_count('fl_potentials','source_potential = '.$riga['id'].$mieileads);
				$totale_leads_dagestire = mk_count('fl_potentials','status_potential < 2 AND source_potential = '.$riga['id'].$mieileads);
				$totale_conversioni = mk_count('fl_potentials','status_potential = 4 AND source_potential = '.$riga['id'].$mieileads);
				$tasso_conversione = @numdec(@$totale_conversioni/@$totale_leads*100,2);
				$color_tot = ($totale_leads_dagestire > 0) ? 'color: red;' : 'color: green;';
				$status_p = ($valores > 0) ? 1 : 0;
				
				echo "<td class=\"center fontlarge\"><a title=\"Conversioni: $totale_conversioni ($tasso_conversione %) \" href=\"../mod_leads/?".$actionView."&status_potential=$status_p&source_potential[]=".$riga['id']."\" style=\"$color_tot\">$totale_leads_dagestire</a> / $totale_leads</td>";
			}
		
		    echo "</tr>"; 

			}
		

	echo "</table>";