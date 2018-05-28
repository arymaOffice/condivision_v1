<?php


// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
//include('filtri.php');


	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	//$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
	$start = 0;
	//$query = "SELECT $select FROM $tabella $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$query = "SELECT tb1.*,(SELECT GROUP_CONCAT(marca,modello) FROM fl_veicoli tb2 WHERE id IN(SELECT DISTINCT id FROM fl_veicoli WHERE parent_id = tb1.id)) FROM fl_potentials tb1 $tipologia_main LIMIT 0,100";
	$risultato = mysql_query($query, CONNECT);
	if($_SESSION['number'] == 1) echo $query.mysql_error();


	?>

<p style="clear: both; text-align: left;"> 

<!--<a href="mod_invia_sms.php<?php echo "?".$_SERVER['QUERY_STRING']; ?>" data-fancybox-type="iframe"  style=" padding: 8px 20px;
    font-size: 100%;" class="fancybox_view_small button">Invia SMS</a> <a href="mod_invia_mail.php<?php echo "?".$_SERVER['QUERY_STRING']; ?>" data-fancybox-type="iframe"  style=" padding: 8px 20px;
    font-size: 100%;" class="fancybox_view_small button">Invia Email</a> 
    
        <a href="mod_esporta.php<?php echo "?".$_SERVER['QUERY_STRING']; ?>" data-fancybox-type="iframe"  style=" padding: 8px 20px;
    font-size: 100%;" class="fancybox_view_small button">Esporta in MailUp</a> 
-->
      <a href="#"  style=" padding: 8px 20px;
    font-size: 100%;" class="showcheckItems button">Seleziona contatti</a>
    <?php echo '<a style=" padding: 8px 20px;
    font-size: 100%;" class="button" href="./?'.$_SERVER['QUERY_STRING'].'&proprietario=-1"><i class="fa fa-users" aria-hidden="true"></i>  Mostra tutti</a>'; ?> </p>
<form method="get">
      <select name="tipo_interesse" >
      	  <option value="-1">Interesse</option>
   <option value="-1">Tutti</option>
        
      <?php 
              
		     foreach($tipo_interesse as $valores => $label){ // Recursione Indici di Categoria
			$selected = (isset($_GET['tipo_interesse']) && check($_GET['tipo_interesse']) == $valores) ? " selected=\"selected\"" : "";
			echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select>


          <select name="categoria_interesse" >
          	 <option value="-1">Categoria Veicolo</option>
   <option value="-1">Tutti</option>
        
      <?php 
              
		     foreach($categoria_interesse as $valores => $label){ // Recursione Indici di Categoria
			$selected = (isset($_GET['categoria_interesse']) && check($_GET['categoria_interesse']) == $valores) ? " selected=\"selected\"" : "";
			echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select> 

          <select name="sede" >
          	  <option value="-1">Sede</option>
   <option value="-1">Tutti</option>
        
      <?php 
              
		     foreach($sede as $valores => $label){ // Recursione Indici di Categoria
			$selected = (isset($_GET['sede']) && check($_GET['sede']) == $valores) ? " selected=\"selected\"" : "";
			echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select> 
    <input type="submit" value="<?php echo SHOW; ?>" class="button" />

</form>
<div id="results" class="green"></div>
<form action="./mod_opera.php?<?php echo $_SERVER['QUERY_STRING']; ?>" id="print" class="ajaxForm" method="post" style="padding: 0; margin: 0;">
  <table class="dati" summary="Dati" style=" width: 100%;">
    <tr>
      <?php if($status_potential_id != 4){ ?>
      <th></th>
      <?php }  ?>
      <th style="width: 1%;" class="checkItemTd"><input onclick="checkAllFields(1);" id="checkAll"  name="checkAll" type="checkbox"  />
        <label for="checkAll"><?php echo $checkRadioLabel; ?></label>
      </th>
   
      <th>Nominativo</th>
      <th>Contatti</th>
      <th>Gestione  </th>
      <th>Cons. Vendita </th>
      <th></th>
    </tr>
    <?php 
	
	$i = 1;
	function www($url) {
	$num = '';
	if($url != '') $num = "http://".str_replace("http://","",str_replace(" ","",$url));
	return $num;
	}





	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">No records</td></tr>";		}
	$tot_res = $count = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
		$colore = "class=\"tab_blue\"";
		if($riga['priorita_contatto'] == 0) { $colore = "class=\"turquoise\"";  }
		if($riga['priorita_contatto'] == 1) { $colore = "class=\"orange\"";  }
		if($riga['priorita_contatto'] == 2) { $colore = "class=\"red\"";  }
		
		$input = '';
		//if($_SESSION['usertype'] < 4) { 
		$checked = ($riga['status_potential']==0) ? '' : ''; //checked auto
		//$count += ($riga['status_potential']==0) ? 1 : 0;
		$input =  '<input onClick="countFields(1);" type="checkbox" name="leads[]" value="'.$riga['id'].'" id="'.$riga['id'].'"  '.$checked.' /><label for="'.$riga['id'].'">'.$checkRadioLabel.'</label>';
		//}

		/*Gestione Assegnazion e scadenza */
		if($riga['data_scadenza'] != '0000-00-00 00:00:00' && $riga['data_scadenza'] != '') {
		$date = strtotime(@$riga['data_scadenza']);
		$giorniCount = giorni(date('Y-m-d',$date));
		$giorni =  '<span class="msg green">- '.$giorniCount.' giorni</span>';
		if($giorniCount == 0) $giorni =  '<span class="msg red">SCADE OGGI</span> alle '.date('H:i',$date);
		if($giorniCount == 1) $giorni =  '<span class="msg orange">Domani</span> alle '.date('H:i',$date);
		if($giorniCount < 0) $giorni = '<span class="msg red">SCADUTO</span>';	
	
		} else { $giorni = '';	 }

		if($riga['data_scadenza_venditore'] != '0000-00-00 00:00:00' && $riga['data_scadenza_venditore'] != '') {
		$date = strtotime(@$riga['data_scadenza_venditore']);
		$giorniCount = giorni(date('Y-m-d',$date));
		$giorni2 =  '<span class="msg green">- '.$giorniCount.' giorni</span>';
		if($giorniCount == 0) $giorni2 =  '<span class="msg red">SCADE OGGI</span> alle '.date('H:i',$date);
		if($giorniCount == 1) $giorni2 =  '<span class="msg orange">Domani</span> alle '.date('H:i',$date);
		if($giorniCount < 0) $giorni2 = '<span class="msg red">SCADUTO</span>';	

		} else { $giorni2 = '';	 }
	
		
		$gestore = $riga['proprietario'];
		//if(defined('assegnazione_automatica') && $gestore < 2 && function_exists('assegnazione_automatica')) $gestore = assegnazione_automatica($riga['id'],$riga['source_potential']);


		


		$bdcAction = get_nextAction(16,$riga['id'],$gestore);
		$bdcAction = ($gestore > 0 && $bdcAction['id'] != NULL) ? '<span title="'.@$proprietario[$bdcAction['operatore']].'"><strong>'.mydatetime($bdcAction['data_aggiornamento']).' </strong>'.$bdcAction['note'].'</span><br>' :  'Inserito il '.mydate($riga['data_creazione']);

		$sellAction = get_nextAction(16,$riga['id'],$riga['venditore']);
		$sellAction = ($riga['venditore'] > 0 && $gestore != $riga['venditore'] && $sellAction['id'] != NULL) ? '<span title="'.@$proprietario[$sellAction['operatore']].'"><strong>'.mydatetime($sellAction['data_aggiornamento']).'</strong> '.$sellAction['note'].'</span><br>' :  'Nessuna Azione';


		$query = 'SELECT * FROM `fl_meeting_agenda` WHERE potential_rel = '.$riga['id'].'';
		mysql_query($query);

		/* SMS*/
		$phone = phone_format($riga['telefono'],'39');	
		$website = www($riga['website']);	
		$valutazione = GQD('fl_surveys','*',' `workflow_id` = 16 AND `record_id` = '.$riga['id'].' ORDER BY data_creazione DESC LIMIT 1');
	  	$valutazioneBadge = ($valutazione['id'] > 0 ) ? '<span class="msg green" title="'.$valutazione['note'].'">Gradimento ('.numdec($valutazione['value'],2).')</span>' : '';
		

		$synapsy = '';
		
		$query = 'SELECT * FROM `fl_synapsy` WHERE (`type1` = '.$tab_id.' OR `type2` = '.$tab_id.') AND (`id1` = '.$riga['id'].' OR `id2` = '.$riga['id'].')';
		
		$parentele = mysql_query($query);
		if(mysql_affected_rows() > 0) {
			$synapsy = '<span class="msg"><i class="fa fa-link"></i></a>';
			while ($parente = mysql_fetch_array($parentele)){  	
			$record_rel = ($parente['id1'] == $riga['id']) ? $parente['id2'] : $parente['id1'];
			$nominativocorrelato = GRD($tabella,$record_rel);
			$synapsy .= ' <a href="../mod_leads/mod_inserisci.php?id='.$record_rel .'">'.$nominativocorrelato['nome'].' '.$nominativocorrelato['cognome'].'</a> <a href="mod_opera.php?disaccoppia='.$parente['id'].'" class="c-red">[x]</a>'; }
			$synapsy .= '</span>'; 
		}
		if(isset($_SESSION['synapsy'])) {
			 $synapLead = ($_SESSION['synapsy'] != $riga['id']) ? '<a href="mod_opera.php?connect='.$riga['id'].'" style="color: #E84B4E;"><i class="fa fa-link" aria-hidden="true"></i></a>' : '' ;
		} else {
			 $synapLead = '<a href="mod_opera.php?synapsy='.$riga['id'].'"><i class="fa fa-link" aria-hidden="true"></i></a>';
		}
		
		//$query = 'SELECT * FROM `fl_sms` WHERE `to` LIKE \''.$phone.'%\' ORDER BY `data_ricezione` DESC LIMIT 1';
		//$sms = mysql_fetch_array(mysql_query($query));
		//$send = '<a href="../mod_sms/mod_inserisci.php?action=1&id=1&to='.$phone.'&from='.crm_number.'" data-fancybox-type="iframe" class="fancybox_view"><i class="fa fa-envelope"></i></a> ';

		$smsbody =  (isset($sms['body']) && strlen($phone) > 4) ? '<br><span class="c-red"><strong>Ultimo SMS inviato:</strong> '.$sms['body'].'</span>' : '';
		$new_contract = ($riga['is_customer'] > 1) ? '../mod_anagrafica/mod_inserisci.php?id='.$riga['is_customer'].'&meeting_id=0' : '../mod_anagrafica/mod_inserisci.php?id=1&meeting_id=0&j='.base64_decode($riga['id']).'&nominativo='.$riga['nome'].' '.$riga['cognome'];
		$color_contract = ($riga['is_customer'] > 1) ? 'c-green' : '';
	
		$veicolo_lista = '';
		
		$veicoloUsato = get_veicolo(16,$riga['id']);		
		$veicoloNuovo = get_veicolo(48,$riga['id']);

		if($veicoloUsato != 0) $veicolo_lista .= '<span class="msg gray">PERMUTA</span> <a href="../mod_veicoli/mod_inserisci.php?id='.$veicoloUsato['id'].'">'.$veicoloUsato['marca'].' '.$veicoloUsato['modello'].' ';
		if(isset($veicoloNuovo) && $veicoloNuovo != 0) $veicolo_lista .= '<span class="msg blue">NUOVO</span> <a href="../mod_veicoli/mod_inserisci.php?id='.$veicoloNuovo['id'].'">'.$veicoloNuovo['marca'].' '.$veicoloNuovo['modello'].'';
	
		$qualified = ($status_potential_id != 4 && $riga['nome'] != '' && strlen($riga['telefono']) > 7) ? '<i class="fa fa-star" style="padding: 0; color: rgb(246, 205, 64); font-size: 80%;" aria-hidden="true"></i>' : '';
		$qualified .= ($status_potential_id != 4 && $riga['nome'] != ''   && strlen($riga['email']) > 5 && filter_var($riga['email'], FILTER_VALIDATE_EMAIL)) ? '<i class="fa fa-star" style="padding: 0; color: rgb(246, 205, 64); font-size: 80%;" aria-hidden="true"></i>' : '';
		$qualified .= ($status_potential_id != 4 && $veicoloUsato != 0) ? '<i class="fa fa-star" style="padding: 0; color: rgb(246, 205, 64); font-size: 80%;" aria-hidden="true"></i>' : '';
		$gestore = ($gestore > 0) ? $proprietario[@$gestore] : ''; 
		if($gestore == '') { 
				$attivitaInfo = GRD('fl_campagne_attivita',$riga['source_potential']);
				if($attivitaInfo['supervisor_id'] < 1) $gestore = '<a onclick="return conferma(\'Acquisire la gestione di questo lead?\');" href="mod_inserisci.php?id='.$riga['id'].'&aAt">Prendi in gestione</a>';
		}

		
		//Controllo esistenza venditore
		$venditoreAssegnato = (isset($proprietario[@$riga['venditore']])) ? $proprietario[@$riga['venditore']] : '<span class="c-red">'.$riga['venditore'].' Inserire venditore in Account!</span>';
		
		if(isset($proprietario[@$riga['venditore']]) && $riga['sede'] < 2) {  //Controllo se sede non assegnata e assegno sede di venditore
			$venditoreDetails = GRD('fl_account',$riga['venditore']); 
			$sedeUpdate = 'UPDATE fl_potentials SET  sede = '.$venditoreDetails['sede_principale'].' WHERE id = '.$riga['id'];
			if($venditoreDetails['sede_principale'] > 1 && mysql_query($sedeUpdate,CONNECT)) $venditoreAssegnato = $venditoreAssegnato . '<br> <span class="c-green">SEDE AGGIORNATA!</span>';
			if($venditoreDetails['sede_principale'] < 2) $venditoreAssegnato = $venditoreAssegnato .' <br><span class="c-red"> IL VENDITORE NON HA UNA SEDE ASSEGNATA!</span>';
			
		 }


			if($status_potential_id != 4)  echo "<tr><td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td class=\"checkItemTd\">$input</td>";

			//if($status_potential_id != 4)  echo "<td style=\"text-align:center;\"></td>";
			// <span class=\"msg $priocolor\">".$priorita_contatto[$riga['priorita_contatto']]."</span>
			echo "<td >
			<a class=\"mobile-buttons nominativo\" href=\"mod_inserisci.php?id=".$riga['id']."&potential_rel=".$riga['id']."\" title=\"Gestisci scheda ".ucfirst($riga['nome'])."\">
			".$riga['ragione_sociale']." <strong title=\"".strip_tags(converti_txt($riga['messaggio']))."\">".$riga['nome']." ".$riga['cognome']."</strong></a> (".@$categoria_interesse[$riga['categoria_interesse']]."/".@$tipo_interesse[$riga['tipo_interesse']].")
			<br><span class=\"msg blue\">".@$source_potential[$riga['source_potential']]."</span><span class=\"msg orange\">".@$sede[$riga['sede']]."</span>  $valutazioneBadge $synapsy
			<br>".$riga['industry']."<br> ".str_replace('Aggiornato automaticamente','',strip_tags(converti_txt($riga['note'])))."
			 </td>";
			
			echo "<td>
			$qualified <strong>".@$status_potential[$riga['status_potential']]."</strong> <br>
			<i class=\"fa fa-phone\" style=\"padding: 3px;\"> </i>  $phone <br><i class=\"fa fa-envelope-o\" style=\"padding: 3px;\"></i><a href=\"mailto:".$riga['email']."\"> ".$riga['email']."</a></td>";



			echo "<td><strong>".@$gestore."</strong><br>".$giorni."<br>$bdcAction</td>";
			echo "<td><strong>".$venditoreAssegnato."</strong><br>".$giorni2."<br>$sellAction</td>";


			echo "<td class=\"mobile-buttons\">
			"; 
			
			if($riga['is_customer'] > 1) echo "<a href=\"$new_contract\"><i class=\"fa fa-user $color_contract\" ></i></a>"; 
			echo $synapLead;
			
		if($_SESSION['usertype'] == 0 || @$_SESSION['profilo_funzione'] == 8) echo "<a href=\"./mod_opera.php?elimina=".$riga['id']."\" title=\"Cancella\" class=\"ajaxLink\"><i class=\"fa fa-trash-o\"></i></a>"; 
			echo "</td>";
		    echo "</tr>";
		

			
			
	}
?>
  </table>
  <div class="checkItemTd">
    <?php if($_SESSION['usertype'] > 0 || $_SESSION['usertype'] == 0) {
 echo '<h3 style="text-align: left; clear: both;" id="bottom-bar">
	<strong>Esegui su <span  id="counter"> '.$count.' lead</span> </strong> questa azione: 
	
	<select name="azione" id="action_list" class="select2" style="min-width: 250px;">
	<option value="-1">Seleziona Azione</option>
	<option value="1">Assegna Gestione</option>
	<option value="2">Assegna Venditore</option>
	<option value="6">Cambia Status</option>
	<option value="3">Invia SMS</option>
	<option value="4">Invia Email</option>
	<option value="5">Esporta in MailUp</option>
	<option value="7">Elimina</option>
	
	</select>


 <span id="action1" class="action_options">
 <select name="assegna_leads" id="assegna_leads" class="select2" style="min-width: 250px;">';
            
			echo '<optgroup label="Operatori BDC">';  
		     foreach($operatoribdc as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@$_SESSION['proprietario_id'] == $valores) ? " selected=\"selected\"" : "";
			if($valores > 1) echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}

			echo '<optgroup label="Digital">';  
		     foreach($operatoridgt as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@$_SESSION['proprietario_id'] == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}


		
 echo '</optgroup></select>';
 echo ' giorni scadenza <input type="number" style="width: 50px;" name="scadenza_bdc" value="2" />  ore <input type="number" style="width: 50px;"  name="ore_scadenza_bdc" value="2" />
 </span>
 
  <span id="action2" class="action_options">
 <select name="assegna_venditore" id="assegna_venditore" class="select2" style="min-width: 250px;">';
            
		
			echo '<optgroup label="Venditori">';  
			foreach($venditore as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@$_SESSION['proprietario_id'] == $valores) ? " selected=\"selected\"" : "";
			if($valores > 1)  echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}

			echo '<optgroup label="Digital">';  
		    foreach($operatoridgt as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@$_SESSION['proprietario_id'] == $valores) ? " selected=\"selected\"" : "";
			echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}

		
 echo '</optgroup></select>';
 echo ' giorni scadenza <input type="number" style="width: 50px;"  name="scadenza_venditore" value="2" />  ore <input type="number" style="width: 50px;"  name="ore_scadenza_venditore" value="2" />
 </span>
 
 <span id="action6" class="action_options">
 <select name="status_potential" id="status_potential" class="select2" style="min-width: 250px;">';
            
			foreach($status_potential as $valores => $label){ // Recursione Indici di Categoria
			 echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; 
			}
		
 echo '</select>';
 echo '</span>
 
 
 
  <input type="submit" value="Esegui" class="button" style="background: #050404;">
  </form>
</h3>';
	} ?>


  </div>
</form>

<div class="results" style="position: fixed; bottom: 20px; left: 0; width: 30%; z-index: 9999;"></div>


<?php 
if($_SERVER['HTTP_HOST'] == 'dev.bluemotive.it') echo ' <a class="c-red" href="../mod_leads/mod_opera.php?reset" onclick="return conferma(\'Sei sicuro di voler resettare tutto il database?\');"><i class="fa fa-user" aria-hidden="true"></i> Resetta tutti i leads (TEST)</a>';
?>

