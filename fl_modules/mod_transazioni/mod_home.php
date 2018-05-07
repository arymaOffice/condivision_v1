<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['last_referrer'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];;



?>
<h1>Registro Transazioni</h1>   
 
 
 <div class="filtri" id="filtri">

 	<h2>Filtri</h2>
<form method="get" action="" id="fm_filtri">
  
       <select name="operatore" id="operatore">
            <option value="-1">Tutti</option>
			<?php 
              
		     foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($userid == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
 <!--    <span style="position: relative;">
Operatore: <input type="text" id="operatore_text" name="operatore_text" value="<?php if(isset($_GET['operatore_text'])){ echo check($_GET['operatore_text']);} else { echo "Inserisci il Testo"; } ?>" onFocus="this.value=''; operatore.value=''" onkeydown=""  accesskey="a" tabindex="1"   onkeyup="return caricaProprietario(this.value,'contenuto-dinamico','operatore');" maxlength="200" class="txt_cerca" />
   <div id="contenuto-dinamico"><?php if(isset($_GET['operatore'])){ echo '<input type="hidden" name="operatore" value="'.$_GET['operatore'].'" />'; } ?> </div></span>
-->  

        Stato: <select name="status_pagamento" id="status_pagamento">
            <option value="-1">Mostra Tutti</option>
			<?php 
              
		     foreach($status_pagamento as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_pagamento_id == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
       
           Prodotto: <select name="rif_operazione" id="rif_operazione">
            <option value="-1">Mostra Tutti</option>
			<?php 
              
		     foreach($rif_operazione as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($rif_operazione_id == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
     creato tra il 
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" /><br>
     e  il 
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
        
       <input type="submit" value="Mostra" class="button" />

       </form>
 
      </div>
      

<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
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
       <th scope="col">Operazioni</th>
       <th scope="col">Scontrino</th>
      </tr>
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Record Inserito</td></tr>";		}
	$totale = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
			
			if($riga['stato_operazione'] == 0){
			$modifica_id = "<a href=\"../mod_service/conferma.php?annulla=1&OrigId=".$riga['origID']."&Time=".$riga['time']."&offercode=".$riga['offercode']."\" title=\"Modifica\"> ANNULLA </a>"; 
			$colore = "style=\" background: #FF3A17; color: #FFF;\"";
			} else if($riga['stato_operazione'] == 1){
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> DETTAGLI </a>"; 
			$colore = "style=\" background: #5D9A42; color: #FFF;\"";
			} else {
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> DETTAGLI </a>"; 
			$colore = "class=\"gray\"";
			}
			$totale += $riga['amount'];
		    if($riga['stato_operazione'] == 0 && $riga['data_creazione'] < date('Y-m-d', strtotime("-10 days")) ) { 
		    	$riga['origID'] .= " Annullata Automaticamente"; 
		    	mysql_query("UPDATE `$tabella` SET stato_operazione = 2 WHERE id = ".$riga['id']." LIMIT 1", CONNECT_CLIENT);

		    }
		    $scontrino = ($riga['stato_operazione'] == 1) ? '<a style="display: block;" data-fancybox-type="iframe" class="fancybox_view" href="../mod_ricevute/?transID='.$riga['id'].'"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>' : ''; 

			echo "<tr><td $colore>".$status_pagamento[$riga['stato_operazione']]."</td>"; 
			echo "<td>".$proprietario[$riga['user']]."</td>";	
			echo "<td>".mydatetime($riga['data_creazione'])."</td>"; 
			echo "<td>".$rif_operazione[$riga['prodotto_id']]."</td>";	
			echo "<td>".$riga['origID']."</td>"; 
			echo "<td> &euro; ".numdec($riga['amount'],2)."</td>"; 
			
			echo "<td>$modifica_id</td>";
			echo "<td>$scontrino</td>";
			
					
		    echo "</tr>";
			
			
			
	}
	echo "<tr><td colspan=\"5\">Riepilogo Foglio</td>
	<td>&euro; ".numdec($totale,2)."</td>
	<td></td></tr>";

	echo "</table>";
	

?>	


<?php $start = paginazione(CONNECT_CLIENT,$tabella,$step,$ordine,$tipologia_main,1); ?>
