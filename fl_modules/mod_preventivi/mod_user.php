<?php 

require_once('../../fl_core/autentication.php');

include('fl_settings.php');
	
$nochat = 1;
	include("../../fl_inc/headers.php");

	?>



<h2>Preventivi</h2>   

<?php

	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
	
 		
?> 
 

<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <th style="width: 1%;"></th>
  <th>Estremi</th>
  <th>Offerta</th>
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
		
			if($riga['status_preventivo'] == 0) { $colore = "class=\"tab_orange\"";  }
			if($riga['status_preventivo'] == 1) { $colore = "class=\"tab_blue\"";  }
			if($riga['status_preventivo'] == 2) { $colore = "class=\"tab_red\"";  }
			if($riga['status_preventivo'] == 3) { $colore = "class=\"tab_green\"";  }
			if($riga['status_preventivo'] == 4) { $colore = "class=\"tab_red\"";  }
			if($riga['status_preventivo'] == 5) { $colore = "class=\"tab_red\"";  }


			$nextAction = get_nextAction(71,$riga['id']);
			$nextAction = ($nextAction['id'] != NULL) ? '<i class="fa fa-share"></i>'.mydatetime($nextAction['data_aggiornamento']).' '.$nextAction['note'].'<br>' :  '<i class="fa fa-tags"></i>'.'Creato: '.mydate($riga['data_creazione']);

			
			$elimina = ($riga['status_preventivo'] > 0) ? "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>" : "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";
			$giorni = giorni($riga['data_scadenza']);
			$note = ($giorni > 0) ? '<span class="msg green">'.$giorni.' giorni</span>' : '<span class="msg red">SCADUTO</span>';	

			$scadenza = ($riga['data_scadenza'] == '0000-00-00') ? '-' :  mydate($riga['data_scadenza']);
			echo "<tr>";
			$nominativo = ($riga['cliente_id'] > 1) ? @$cliente_id[$riga['cliente_id']] : @$potential_id[$riga['potential_id']];		
			$followup = get_followup(2,$riga['id']);
			
			echo "<td $colore></td>"; 			
			echo "<td>".mydate($riga['data_apertura']).' '.$riga['marca'].' '.$riga['modello']." FPOWER-ID
			<span class=\"msg \" style=\"font-size: 100%; \"><strong>".$riga['id_fordpower']."</strong></span>";
			echo "<br>";
			echo "$note <span class=\"msg gray\" >".@$tipo_preventivo[$riga['tipo_preventivo']]."</span><span class=\"msg orange\" >".@$categoria_preventivo[$riga['categoria_preventivo']]."</span>
			</td>";
			/*echo "<td class=\"hideMobile\">".strip_tags(converti_txt($riga['note']))."
			<br>".$nextAction."</td>"; */
			echo "<td style=\"background: #E8FFE8;\"><strong>&euro; ".$riga['offerta']."</strong></td>"; 
			
 			echo "<td  class=\"strumenti\">";
			echo "<a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Dettagli\" data-fancybox-type=\"iframe\" class=\"fancybox_view\"> <i class=\"fa fa-search\"></i> </a>
			</td>";//<a href=\"mod_inserisci.php?copy_record&amp;id=".$riga['id']."\" title=\"Copia\"  target=\"_parent\"><i class=\"fa fa-files-o\"></i></a>
 			//$elimina

		    echo "</tr>"; 
			
	}

	echo "</table>";


?>

