<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>



<div id="filtri" class="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2 class="filter_header"> Filtri</h2>  <!--Partner: 
  
  <span style="position: relative;">
  <input type="text" id="operatore_text" name="operatore_text" value="<?php if(isset($_GET['operatore_text'])){ echo check($_GET['operatore_text']);} else { echo "Inserisci il Testo"; } ?>" onFocus="this.value=''; operatore.value=''" onkeydown=""  accesskey="a" tabindex="1"   onkeyup="return caricaProprietario(this.value,'contenuto-dinamico','operatore');" maxlength="200" class="txt_cerca" />
   <div id="contenuto-dinamico"><?php if(isset($_GET['operatore'])){ echo '<input type="hidden" name="operatore" value="'.$_GET['operatore'].'" />'; } ?> </div></span>
--> 
    
   
  
      <?php 
            
		     foreach($status_rdo as $valores => $label){ // Recursione Indici di Categoria
			$selected = (in_array($valores,$statuses)) ? ' checked="checked"' : '';
			echo '<input '.$selected.' type="checkbox" name="statuses_rdo[]" id="statuses_rdo'.$valores.'" value="'.$valores.'"><label for="statuses_rdo'.$valores.'">'.ucfirst($label).'</label>'; 
			
			}
		 ?>
   <br>
   
    <label> creato tra il</label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    <label> e il</label>
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
						
	 $query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
	

 		
	?>
    
    <p>NB: Per ricercare tramite codice, inserire solo la parte relativa al numero progressivo senza zeri Es. "<em>P16-</em>0<strong>49</strong><em>-UE-A-CVI</em>"</p>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <th style="width: 1%;"></th>
  <th>Estremi</th>
  <th>Descrizione</th>
  <th>Costi</th>
  <th>Stima</th>
  <th>Offerta</th>
  <th>Chiusura</th>
  <th>PT</th>
  <th>CM</th>
  <th  class="hideMobile"></th>
  <th  class="hideMobile"></th>
</tr>
<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	/*
	PT = costo/vendita * 100 %
	CM = vendita-costo/vendita * 100 %
	*/
	while ($riga = mysql_fetch_array($risultato)) 
	{					
		
				
			if($riga['status_rdo'] == 5) { 
			$colore = "class=\"tab_green\"";  
			} else if($riga['status_rdo'] == 3 || $riga['status_rdo'] == 4 || $riga['status_rdo'] == 7) { $colore = "class=\"tab_red \""; 
			} else { $colore = "class=\"tab_orange\""; }
			
			$elimina = ($riga['status_rdo'] > 0) ? "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>" : "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";
		    ($riga['data_scadenza'] < date('Y-m-d')) ? $note = "<span title=\"Offerta In scadenza\" class=\"c-red\"><i class=\"fa fa-exclamation-triangle fa-lg\"></i></span>" : $note = "";
			$scadenza = ($riga['data_scadenza'] == '0000-00-00') ? '-' :  mydate($riga['data_scadenza']);
			$emissione = ($riga['data_emissione'] == '0000-00-00') ? '-' :  mydate($riga['data_emissione']);	
			echo "<tr>";
			$nominativo = $cliente_id[$riga['cliente_id']].'</span> Rif. '.ucfirst($riga['rif_interno']);		
			$chiusura = "<strong>&euro; ".$riga['importo_ordine']."</strong>";
			$followup = get_followup(2,$riga['id']);
			
			echo "<td $colore><span class=\"Gletter\">".$status_rdo[$riga['status_rdo']]."</span></td>"; 			
			echo "<td>
			<span class=\"msg gray\" style=\"font-size: 100%; \">P".substr($riga['anno_fiscale'],2,2)."-".str_pad($riga['numero_progressivo'],3,0,STR_PAD_LEFT)."-<span>".substr($codice_cliente[$riga['cliente_id']],0,2)."-</span>".@substr($tipo_rdo[$riga['tipo_rdo']],0,1)."-".@substr($divisione_id[$riga['divisione_id']],0,3)."</span>";
			echo "<br>";
			echo " <span class=\"msg red\">".$prog_shore[$riga['prog_shore']]."</span>
			<span class=\"msg blue\" > ".substr($produzione[$riga['produzione']],0,2)."</span>
			<span class=\"msg gray\" >".$riga['business_unit']."</span> $followup</td>";
			echo "<td class=\"hideMobile\">$nominativo <br>
			<span title=\"".strip_tags(converti_txt($riga['note']))."\">".strip_tags(converti_txt($riga['descrizione']))."</span>
			</td>"; 
			echo "<td style=\"background: #E8FFE8;\">&euro; ".$riga['costi']."</td>"; 
			echo "<td style=\"background: #E8FFE8;\">&euro; ".$riga['stima']."</td>"; 
			echo "<td style=\"background: #E8FFE8;\"><strong>&euro; ".$riga['offerta']."</strong></td>"; 
			echo "<td style=\"background: #E8FFE8;\">$chiusura</td>"; 
			echo "<td  class=\"hideMobile\">".numdec(($riga['costi']/$riga['offerta']),2)." %</td>";
			echo "<td  class=\"hideMobile\">".numdec((($riga['importo_ordine']-$riga['costi'])/$riga['importo_ordine']),2)." %</td>";
			echo "<td  class=\"strumenti\">";
			echo "<a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Dettagli\"> <i class=\"fa fa-search\"></i> </a>
			<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?external&action=1&amp;id=".$riga['id']."\" title=\"Scheda di stampa\"> <i class=\"fa fa-print\"></i> </a>
			<a href=\"mod_inserisci.php?copy_record&amp;id=".$riga['id']."\" title=\"Copia\"><i class=\"fa fa-files-o\"></i></a>
 			$elimina</td>";
			echo "<td  class=\"hideMobile\" style=\"font-size: smaller; text-align: right;\" title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]."\"><br>AP. ".mydate($riga['data_apertura'])."<br />SCAD. ".$scadenza."<br />EMISS: ".$emissione."</td>";
		
		    echo "</tr>"; 
			
	}

	echo "</table>";
	

?>
