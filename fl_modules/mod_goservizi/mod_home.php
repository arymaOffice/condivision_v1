<?php if($_SESSION['usertype'] > 0) exit; ?>

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
        <th>Fornitore</th>
        <th>Prodotto</th>
        <th>Valore Facciale</th>
        <th>Tipo</th>
		<th>TAG SRV</th>
        <th>TAG OfferCode</th>
        <th>EAN</th>
        <th>Ricavo</th>
        <th>Comm 1/2/3/4/5</th>
        <th>Agente</th>
        <th>Iva</th>

 
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	
	
	if($i==1){ $i=0; echo "<tr>"; } else { $i=1; echo "<tr class=\"alternate\">"; }		
			
			$colore = ($riga['attivo'] == 0) ? "class=\"tab_red\"" : "class=\"tab_green\"";  

			$prodotto = GRD('fl_prodotti',$riga['prodotto_id']);
			
			echo "<td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td><a href=\"../mod_prodotti/?prodotto_id=".$riga['prodotto_id']."\" title=\"Linea\">".@$prodotto['label']."</a></td>";
			echo "<td>".ucfirst($riga['label']).' '.numdec($riga['valore_facciale'],0)."</td>";
			echo "<td>&euro; ".numdec($riga['valore_facciale'],2)." [".$riga['amount']."]</td>";
			echo "<td>".@$categoria_prodotto[$prodotto['categoria_prodotto']]."</td>";
			echo "<td><a href=\"mod_inserisci.php?item_rel=0&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\">".@$prodotto['codice']."</a></td>";		
			echo "<td>".$riga['offercode']."</td>"; 
			echo "<td>".$riga['codice_ean']."</td>"; 
			echo "<td>&euro; ".numdec($riga['ricavo_netto'],2)."</td>";
			echo "<td>&euro; [".numdec(($riga['profilo1']*$riga['ricavo_netto'])/100,2)."]
			[".numdec(($riga['profilo2']*$riga['ricavo_netto'])/100,2)."]
			[".numdec(($riga['profilo3']*$riga['ricavo_netto'])/100,2)."]
			[".numdec(($riga['profilo4']*$riga['ricavo_netto'])/100,2)."]
			[".numdec(($riga['profilo5']*$riga['ricavo_netto'])/100,2)."]</td>";
			echo "<td>&euro; ".numdec(($riga['profilo_agente']*$riga['ricavo_netto'])/100,2)."</td>";
			echo "<td>".$aliquota_iva[$riga['aliquota_iva']]."</td>";
			echo "<td><a href=\"mod_inserisci.php?item_rel=0&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
	
			
				
		    echo "</tr>";
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
