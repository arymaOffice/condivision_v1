<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];;
?>
<h1>Fatture <?php echo $proprietario[$proprietario_id]; ?></h1>   

 
 <div class="filtri" id="filtri">
<h2>Filtri</h2>

<form method="get" action="" id="fm_filtri">
  
    
      
  
       <br>Emessa tra il 
       <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="8" />
 
        <br>e il  <br>  <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="8" /> 

       <input type="submit" value="Mostra" class="button" />

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
    
       <th scope="col">Stato</th>
       <th scope="col">Numero</th>
       <th scope="col">Data Fattura</th>
       <th scope="col">Imponibile</th>
       <th scope="col">IVA</th>
       <th scope="col">Totale</th>
       <th scope="col">Commissioni</th>
      <th scope="col">Fattura</th>          
        </tr>
	<?php 
	
	$i = 1;
	$dare = 0;
	$avere = 0;
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$totale_foglio = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
		
			$fattura = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"scarica.php?dir=fatture&file=".$riga['filename']."&id_fattura=".base64_encode($riga['id'])."\" title=\"Visualizza\"> <i class=\"fa fa-file-text\"></i>"; 

			if($riga['pagato'] == 0){
			$colore = "class=\"gray\"";
			if($riga['totale_fattura'] > 0 ) $conferma = "<a class=\"button green\" href=\"./?action=21&set=1&amp;id=".$riga['id']."\" title=\"Conferma\"> Invia </a>";
			$cred = 'c-gray';
			$cgreen = 'c-gray';
			$colore = "class=\"gray\"";
			} else if($riga['pagato'] == 1){
			$colore = "class=\"green\"";
			$cgreen = 'c-green';
			} else {
			$colore = "class=\"gray\"";
			$cred = 'c-gray';
			$cgreen = 'c-gray';

			}
	
			echo "<tr><td $colore></td>"; 
			echo "<td>".$riga['numero_documento']."</td>";	
			echo "<td style=\"\" title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]." aggiornato il ".mydatetime($riga['data_aggiornamento'])."\">".mydate($riga['data_documento'])."</td>"; 
				echo "<td>&euro; ".$riga['imponibile']."</td>";	
			echo "<td>&euro; ".$riga['iva']."</td>";
			echo "<td>&euro; ".$riga['totale_fattura']."</td>";
						echo "<td>&euro; ".$riga['commissione']."</td>";

			echo "<td>$fattura</td>";
		    echo "</tr>";
			
			
			
	}
	echo "</table>";
	

?>	



<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);  ?>
