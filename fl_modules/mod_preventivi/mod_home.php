<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>


<div id="filtri" class="filtri"> 
<h2> Filtri</h2> 

<form method="get" action="" id="fm_filtri">
 

<!--Partner: 
  
  <span style="position: relative;">
  <input type="text" id="operatore_text" name="operatore_text" value="<?php if(isset($_GET['operatore_text'])){ echo check($_GET['operatore_text']);} else { echo "Inserisci il Testo"; } ?>" onFocus="this.value=''; operatore.value=''" onkeydown=""  accesskey="a" tabindex="1"   onkeyup="return caricaProprietario(this.value,'contenuto-dinamico','operatore');" maxlength="200" class="txt_cerca" />
   <div id="contenuto-dinamico"><?php if(isset($_GET['operatore'])){ echo '<input type="hidden" name="operatore" value="'.$_GET['operatore'].'" />'; } ?> </div></span>
--> 
    
   <br>
  Stato Offerta:
      <?php 
            
		     foreach($status_preventivo as $valores => $label){ // Recursione Indici di Categoria
			$selected = (in_array($valores,$statuses)) ? ' checked="checked"' : '';
			echo '<input '.$selected.' type="checkbox" name="statuses_rdo[]" id="statuses_rdo'.$valores.'" value="'.$valores.'"><label for="statuses_rdo'.$valores.'">'.ucfirst($label).'</label>'; 
			
			}
		 ?>
   
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
    

<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <th style="width: 1%;"></th>
  <th>Estremi</th>
  <th>Descrizione</th>
  <th>Offerta</th>
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
		
			if($riga['status_preventivo'] == 0) { $colore = "tab_orange";  }
			if($riga['status_preventivo'] == 0) { $colore = "tab_blue";  }
			if($riga['status_preventivo'] == 1) { $colore = "tab_orange";  }
			if($riga['status_preventivo'] == 2) { $colore = "tab_red";  }
			if($riga['status_preventivo'] == 3) { $colore = "tab_green";  }
			if($riga['status_preventivo'] == 4) { $colore = "tab_red";  }
			
			$nextAction = get_nextAction(71,$riga['id']);
			$nextAction = ($nextAction['id'] != NULL) ? '<i class="fa fa-share"></i>'.mydatetime($nextAction['data_aggiornamento']).' '.$nextAction['note'].'<br>' :  '<i class="fa fa-tags"></i>'.'Creato: '.mydate($riga['data_creazione']);

			
			$elimina = ($riga['status_preventivo'] > 0) ? "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>" : "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";
			$giorni = giorni($riga['data_creazione']);
			$attesa = ($giorni > 0) ? '<span class="msg green">'.$giorni.' giorni</span>' : '<span class="msg red">SCADUTO</span>';	

			$scadenza = ($riga['data_scadenza'] == '0000-00-00') ? '-' :  mydate($riga['data_scadenza']);
			echo "<tr>";
			$nominativo = ($riga['cliente_id'] > 1) ? @$cliente_id[$riga['cliente_id']] : @$potential_id[$riga['potential_id']];		
			$chiusura = "<strong>&euro; ".$riga['totale_preventivo']."</strong>";
			$followup = get_followup(2,$riga['id']);
			$inviaPdf = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view_small\" href=\"mod_send.php?id=".$riga['id']."&lead_id=".$riga['potential_id']."&cliente_id=".$riga['cliente_id']."\" title=\"Invio Offerta\"><i class=\"fa fa-paper-plane-o\"></i></a>";

			echo "<td class=\"$colore\"></td>"; 			
			echo "<td><span class=\"\">
			<h2><a title=\"Scheda Contatto\"  href=\"../mod_leads/mod_inserisci.php?id=".$riga['potential_id']."\">".checkValue($nominativo)."</a></h2>
			</span>
			<span class=\"\" style=\"font-size: 100%; \"><strong>P".substr($riga['data_creazione'],2,2)."-".str_pad($riga['id'],3,0,STR_PAD_LEFT)."-".@substr(trim($tipo_preventivo[$riga['tipo_preventivo']]),0,2)."</strong> del <span title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]."\">  ".mydate($riga['data_preventivo'])."</span></span>";
			echo "<br>";
			echo "<span class=\"msg $colore\">".$status_preventivo[$riga['status_preventivo']]."</span> $attesa <span class=\"msg gray\" >".@$tipo_preventivo[$riga['tipo_preventivo']]."</span>
			
			<span title=\"".strip_tags(converti_txt($riga['note']))."\"></span>
			</td>";
			echo "<td class=\"hideMobile\">".$riga['oggetto_preventivo']." <br>".$nextAction."
			<strong></strong></span>
			</td>"; 
			echo "<td style=\"background: #E8FFE8;\"><strong>&euro; ".$riga['totale_preventivo']."</strong></td>"; 
			echo "<td  class=\"strumenti\">";
			echo "<a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Dettagli\"> <i class=\"fa fa-search\"></i> </a>
			<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_stampa.php?external&action=1&amp;id=".$riga['id']."\" title=\"Scheda di stampa\"> <i class=\"fa fa-print\"></i> </a>
			$inviaPdf
			<a href=\"mod_inserisci.php?copy_record&amp;id=".$riga['id']."\" title=\"Copia\"><i class=\"fa fa-files-o\"></i></a>
 			$elimina</td>";
		
		    echo "</tr>"; 
			
	}

	echo "</table>";
	

?>
