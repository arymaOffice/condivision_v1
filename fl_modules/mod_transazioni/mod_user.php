<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['last_referrer'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];;



?>
<h1>Ricerca Transazioni</h1>   
 



   
    <form id="" action="" method="get" style="margin-top: 6px;">
      <span id="cerca">
      <input  style="width: 100%; padding: 20px; text-align: center;" name="find" type="text" <?php if(!isset($_GET['cerca'])) echo 'onclick="this.value=\'\'"'; ?> value="<?php if(isset($_GET['cerca'])){ echo check($_GET['cerca']);} else { echo 'Inserisci uno dei seguenti: Numero telefonico, OrigId, AutchCode, Pin'; } ?>"   maxlength="200" class="txt_cerca" />
      <input type="submit" value="<?php echo SEARCH; ?>" class="button" style="width: 100%; padding: 20px;" />
     <p></p> </span>
    </form>
    
<?php
	

	if(isset($_GET['find'])) {  
	
	$start = paginazione(CONNECT_CLIENT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT_CLIENT);
	//echo $query;

 		
	?>

 
 
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
       <th scope="col">Stato</th>
       <th scope="col">Utente</th>
       <th scope="col">Data</th>
       <th scope="col">Operazione</th>
       <th scope="col">OrigId</th>
       <th scope="col">Importo</th>
       <th scope="col">Opera</th>
      </tr>
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessuna operazione trovata</td></tr>";		}
	$tot_res = 0;
	$incomplete = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
			$modifica_id = '';
			if($riga['stato_operazione'] == 0){
			$modifica_id = "<a href=\"../mod_service/conferma.php?annulla=1&OrigId=".$riga['origID']."&Time=".$riga['time']."&offercode=".$riga['offercode']."\" title=\"Modifica\"> ANNULLA </a>"; 
			$colore = "style=\" background: #FF3A17; color: #FFF;\"";
			} else if($riga['stato_operazione'] == 1){
			//$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> DETTAGLI </a>"; 
			$colore = "style=\" background: #5D9A42; color: #FFF;\"";
			} else {
			//$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> DETTAGLI </a>"; 
			$colore = "class=\"gray\"";
			}
		    $scontrino = ($riga['stato_operazione'] == 1) ? '<a style="display: block;" data-fancybox-type="iframe" class="fancybox_view" href="../mod_ricevute/?transID='.$riga['id'].'"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>' : ''; 

		
			echo "<tr><td $colore>".$status_pagamento[$riga['stato_operazione']]."</td>"; 
			echo "<td>".$proprietario[$riga['user']]."</td>";	
			echo "<td>".mydate($riga['data_creazione'])."</td>"; 
			echo "<td>".$rif_operazione[$riga['prodotto_id']]."</td>";	
			echo "<td>".$riga['origID']."</td>"; 
			echo "<td> &euro; ".numdec($riga['amount'],2)."</td>"; 
			
			echo "<td>$modifica_id</td>";
			echo "<td>$scontrino</td>";
			
					
		    echo "</tr>";
			
			
			
	}

	echo "</table>";
	

?>	


<?php $start = paginazione(CONNECT_CLIENT,$tabella,$step,$ordine,$tipologia_main,1);

} ?> 


