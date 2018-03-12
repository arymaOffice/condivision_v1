<?php 

require_once('../../fl_core/autentication.php');

include('fl_settings.php');
	
$nochat = 1;
include("../../fl_inc/headers.php");

?>


<style type="text/css"> body { background: white;  }</style>
<h2>Offerte</h2>   

<?php

	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
	
 		
?> 
 

<table class="dati" summary="Dati" style=" width: 95%;">
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
			if($riga['status_preventivo'] == 1) { $colore = "tab_blue";  }
			if($riga['status_preventivo'] == 2) { $colore = "tab_blue";  }
			if($riga['status_preventivo'] == 3) { $colore = "tab_red";  }
			if($riga['status_preventivo'] == 4) { $colore = "tab_green";  }
			if($riga['status_preventivo'] == 5) { $colore = "tab_red";  }
			
			$nextAction = get_nextAction(71,$riga['id']);
			$nextAction = ($nextAction['id'] != NULL) ? '<i class="fa fa-share"></i>'.mydatetime($nextAction['data_aggiornamento']).' '.$nextAction['note'].'<br>' :  '<i class="fa fa-tags"></i>'.'Creato: '.mydate($riga['data_creazione']);

			
			$elimina = ($riga['status_preventivo'] > 0) ? "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>" : "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";
			$giorni = giorni($riga['data_scadenza']);
			$note = ($giorni > 0) ? '<span class="msg green">'.$giorni.' giorni</span>' : '<span class="msg red">SCADUTO</span>';	

			$scadenza = ($riga['data_scadenza'] == '0000-00-00') ? '-' :  mydate($riga['data_scadenza']);
			echo "<tr>";
			$nominativo = ($riga['cliente_id'] > 1) ? @$cliente_id[$riga['cliente_id']] : @$potential_id[$riga['potential_id']];		
			$chiusura = "<strong>&euro; ".$riga['totale_preventivo']."</strong>";
			$followup = get_followup(2,$riga['id']);
			$inviaPdf = "<a data-fancybox-type=\"iframe\" class=\"facyboxParent\" href=\"mod_send.php?id=".$riga['id']."&lead_id=".$riga['potential_id']."&cliente_id=".$riga['cliente_id']."\" title=\"Invio Offerta\"><i class=\"fa fa-paper-plane-o\"></i></a>";

			echo "<td class=\"$colore\"></td>"; 			
			echo "<td><span class=\"\">
			<h2><a title=\"Scheda Contatto\"  href=\"../mod_leads/mod_inserisci.php?id=".$riga['potential_id']."\">".checkValue($nominativo)."</a></h2>
			</span>
			<span class=\"\" style=\"font-size: 100%; \"><strong>P".substr($riga['data_creazione'],2,2)."-".str_pad($riga['id'],3,0,STR_PAD_LEFT)."-".@substr($tipo_preventivo[$riga['tipo_preventivo']],0,1)."</strong></span>";
			echo "<br>";
			echo "<span class=\"msg $colore\">".$status_preventivo[$riga['status_preventivo']]."</span> $attesa <span class=\"msg gray\" >".@$tipo_preventivo[$riga['tipo_preventivo']]."</span>
			
			<span title=\"".strip_tags(converti_txt($riga['note']))."\"></span>
			</td>";
			echo "<td class=\"hideMobile\">".$riga['oggetto_preventivo']." <br>".$nextAction."
			<strong></strong></span>
			</td>"; 
			echo "<td style=\"background: #E8FFE8;\"><strong>&euro; ".$riga['totale_preventivo']."</strong></td>"; 
			echo "<td  class=\"strumenti\">
			<a data-fancybox-type=\"iframe\" class=\"facyboxParent\" href=\"mod_stampa.php?external&action=1&amp;id=".$riga['id']."\" title=\"Scheda di stampa\"> <i class=\"fa fa-print\"></i> </a>
 			$inviaPdf </td>";
			echo "<td  class=\"hideMobile\" style=\"font-size: smaller; text-align: right;\" title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]."\"><br>AP. ".mydate($riga['data_preventivo'])."<br />SCAD. ".$scadenza."</td>";
		    echo "</tr>"; 
			
	}

	echo "</table>";
	


?>
