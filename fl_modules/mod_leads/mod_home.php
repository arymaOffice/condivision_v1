<?php


// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

?>
<script type="text/javascript">
function checkAllFields(ref)
{
var chkAll = document.getElementById('checkAll');
var checks = document.getElementsByName('leads[]');
var boxLength = checks.length;
var allChecked = false;
var totalChecked = 0;
	if ( ref == 1 )
	{
		if ( chkAll.checked == true )
		{
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = true;
		}
		else
		{
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = false;
		}
	}
	else
	{
		for ( i=0; i < boxLength; i++ )
		{
			if ( checks[i].checked == true )
			{
			allChecked = true;
			continue;
			}
			else
			{
			allChecked = false;
			break;
			}
		}
		if ( allChecked == true )
		chkAll.checked = true;
		else
		chkAll.checked = false;
	}
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
	countFields(1);
}
  
  
  function countFields(ref)
{
var checks = document.getElementsByName('leads[]');
var boxLength = checks.length;
var totalChecked = 0;
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
	$('#counter').html('' + totalChecked + ' leads');
}
</script>


<?php  if(defined('qualificazioneLeads') && $status_potential_id != 4) include ("counters.php");
?>




<div id="filtri" class="filtri">
  <h2>Filtri</h2>
  <form method="get" action="" id="fm_filtri">
   

    <?php if(defined('qualificazioneLeads')) { ?>
    <label> Qualificati </label>
    
    <input type="radio" name="qualificati" id="qualificati1" value="1" <?php if($qualificati_id == 1) echo 'checked'; ?>>
    <label for="qualificati1">SI</label>
   
    <input type="radio" name="qualificati" id="qualificati0" value="0"  <?php if($qualificati_id == 0) echo 'checked'; ?>>
    <label for="qualificati0">NO</label>
    
    <input type="radio" name="qualificati" id="qualificati2" value="2"  <?php if($qualificati_id == 2) echo 'checked'; ?>>
    <label for="qualificati2">SUPER</label>
    
    <input type="radio" name="qualificati" id="qualificati3" value="-1"  <?php if($qualificati_id == -1) echo 'checked'; ?>>
    <label for="qualificati3">Tutti</label>
    <?php } ?>


   <?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){
			
			

			if((select_type($chiave) == 2 || select_type($chiave) == 19 || select_type($chiave) == 9) && $chiave != 'id' || $chiave == 'status_potential' || $chiave == 'proprietario') {
				echo '<div class="filter_box">';
				echo '  <label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Tutti</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
				echo '</div>';
			} else if( $chiave != 'id') { $cont = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; 
			echo '<div class="filter_box">';
			echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$cont.'" />'; echo '</div>';}

			
			
			} 
		
	}
	 ?>    

  
    <?php //if($_SESSION['usertype'] == 3 || $_SESSION['usertype'] == 0) $data_set->do_select('VALUES',$tabella,'in_use','operatore',$operatore_id,'','','','',$proprietario); ?>
    <div style="width: 50%; margin: 0; float: left;">
      <label> da</label>
      <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="8" />
    </div>
    <div style="width: 50%; margin: 0; float: left;">
      <label> a</label>
      <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="8" />
    </div>
    <input type="submit" value="<?php echo SHOW; ?>" class="button" />
  </form>
</div>
<?php


	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";

	$risultato = mysql_query($query, CONNECT);
	//if($_SESSION['number'] == 1) echo $query.mysql_error();
	?>
<p style="clear: both; text-align: left;"> 




      <a href="#"  style=" padding: 8px 20px;   font-size: 100%;" class="showcheckItems button">Seleziona contatti</a> </p>


<div id="results" class="green"></div>
<form action="./mod_opera.php?<?php echo $_SERVER['QUERY_STRING']; ?>" id="print" class="ajaxForm" method="post" style="padding: 0; margin: 0;">
  <table class="dati" summary="Dati" style=" width: 100%;">
    <tr>
      <th></th>
      <th style="width: 1%;" class="checkItemTd"><input onclick="checkAllFields(1);" id="checkAll"  name="checkAll" type="checkbox"  />
      <label for="checkAll"><?php echo $checkRadioLabel; ?></label>
      </th>
      <th>Primo Contatto</th>
      <th>Nominativo</th>
      <th>Interesse / Provenienza</th>
      <th>Scadenza </th>
      <th></th>
    </tr>
    <?php 
	
	$i = 1;



	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">No records</td></tr>";		}
	$tot_res = $count = 0;
	
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
		
		$colore = "class=\"tab_gray\"";
		if(defined('qualificazioneLeads')) { 
		if($riga['priorita_contatto'] == 0) { $colore = "class=\"turquoise\"";  }
		if($riga['priorita_contatto'] == 1) { $colore = "class=\"orange\"";  }
		if($riga['priorita_contatto'] == 2) { $colore = "class=\"red\"";  }
		}
		if($riga['status_potential']==4)  $colore = "class=\"tab_green\"";
	

		$input = '';
		if($_SESSION['usertype'] < 4) { 
		$checked = ($riga['status_potential']==0) ? '' : ''; //checked auto
		//$count += ($riga['status_potential']==0) ? 1 : 0;
		$input =  '<input onClick="countFields(1);" type="checkbox" name="leads[]" value="'.$riga['id'].'" id="'.$riga['id'].'"  '.$checked.' /><label for="'.$riga['id'].'">'.$checkRadioLabel.'</label>';
		}

		
	 	$data_principale = data_visita_lead($riga[$data_dafault]);
	 	$data_visita = data_visita_lead($riga['data_visita']);
		

		/*Gestione Assegnazion e scadenza */
		if($riga['data_scadenza'] != '0000-00-00' && $riga['data_scadenza'] != '') {
		$date = strtotime(@$riga['data_scadenza']);
		$giorniCount = giorni(date('Y-m-d',$date));
		$giorni =  '<span class="msg green">- '.$giorniCount.' giorni</span>';
		if($giorniCount == 0) $giorni =  '<span class="msg red">SCADE OGGI</span>';
		if($giorniCount == 1) $giorni =  '<span class="msg orange">Domani</span>';
		if($giorniCount < 0) $giorni = '<span class="msg red">SCADUTO</span>';	
		if($riga['status_potential'] == 4) $giorni = '';	
		} else { $giorni = '';	 }

		if($riga['data_scadenza_venditore'] != '0000-00-00' && $riga['data_scadenza_venditore'] != '') {
		$date = strtotime(@$riga['data_scadenza_venditore']);
		$giorniCount = giorni(date('Y-m-d',$date));
		$giorni2 =  '<span class="msg green">- '.$giorniCount.' giorni</span>';
		if($giorniCount == 0) $giorni2 =  '<span class="msg red">SCADE OGGI</span>';
		if($giorniCount == 1) $giorni2 =  '<span class="msg orange">Domani</span>';
		if($giorniCount < 0) $giorni2 = '<span class="msg red">SCADUTO</span>';	
		if($riga['status_potential'] > 2) $giorni2 = '';	
		} else { $giorni2 = '';	 }
		
		
		
		$bdcActivity = get_nextAction($tab_id,$riga['id'],0);		
		$bdcAction = ($bdcActivity != NULL ) ? '<span title="'.@$proprietario[$bdcActivity['operatore']].'"><strong>'.mydatetime($bdcActivity['data_aggiornamento']).' </strong></span><br><span title="'.$bdcActivity['note'].'">'.substr($bdcActivity['note'],0,85).' ...</span><br>' :  'Inserito il: '.mydate($riga['data_creazione']);

		/*$sellAction = get_nextAction($tab_id,$riga['id'],$riga['venditore']);
		$sellAction = ($riga['venditore'] > 0 && $riga['proprietario'] != $riga['venditore'] && $sellAction['id'] != NULL) ? '<span title="'.@$proprietario[$sellAction['operatore']].'"><strong>'.mydatetime($sellAction['data_aggiornamento']).'</strong> '.$sellAction['note'].'</span><br>' :  'Nessuna Azione';
		*/

	
		$phone = phone_format($riga['telefono'],'39');	
		$website = www($riga['website']);		
	
		$synapLead  = $synapsy = ''; // Gestione parentele, solo se attiva

		if(defined('synapLead')) {
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
		}
		
		$new_contract = ($riga['is_customer'] > 1) ? '../mod_anagrafica/mod_inserisci.php?id='.$riga['is_customer'].'&meeting_id=0' : '../mod_anagrafica/mod_inserisci.php?id=1&meeting_id=0&j='.base64_decode($riga['id']).'&nominativo='.checkValue($riga['nome']).' '.checkValue($riga['cognome']);
		$color_contract = ($riga['is_customer'] > 1) ? 'c-green' : '';
		$qualified = ($status_potential_id != 4 && $riga['nome'] != '' && $riga['telefono'] != '' && $riga['email'] != '') ? '<i class="fa fa-star" style="padding: 0; color: rgb(246, 205, 64); font-size: 60%;" aria-hidden="true"></i>' : '';
		$feedback = get_feedback($riga['id'] );
		$feedback = ($feedback != '0') ? '<i class="fa fa-smile-o '.$feedback.'" style aria-hidden="true"></i>' : '';
		

			echo "<tr><td $colore><span class=\"Gletter\"></span></td>"; 

			echo "<td class=\"checkItemTd\">$input</td>";
			echo "<td><h3>$data_principale $feedback</h3>
			<span class=\"msg blue\">".@$status_potential[$riga['status_potential']]."</span> ".@$proprietario[@$riga['proprietario']]."
			</td>";

			echo "<td>
			<h2><a class=\"mobile-buttons\" style=\"color: gray;\" href=\"mod_inserisci.php?id=".$riga['id']."&potential_rel=".$riga['id']."\" title=\"Gestisci scheda ".ucfirst(checkValue($riga['nome']))."\">
			$qualified ".$riga['ragione_sociale']." <strong title=\"".strip_tags(converti_txt($riga['messaggio']))."\">".ucfirst(checkValue($riga['nome']))." ".ucfirst(checkValue($riga['cognome']))."</strong></a> $synapsy    <br> ".strip_tags(converti_txt($riga['note']))."
			</h2>
			<i class=\"fa fa-phone\" style=\"padding: 3px;\"> </i>  $phone <i class=\"fa fa-envelope-o\" style=\"padding: 3px;\"></i><a href=\"mailto:".$riga['email']."\"> ".checkEmail($riga['email'])."</a>
			</td>";

			$interesse = ($status_potential_id != 4) ? @$interessato_a[$riga['interessato_a']].' '.$centro_di_ricavo[$riga['centro_di_ricavo']] : '';
			
			echo "<td>";
			if(defined('tab_prefix') && @tab_prefix == 'hrc') echo  "<span class=\"msg orange\">".@$anno_di_interesse[@$riga['anno_di_interesse']]."</span>";
			echo @$tipo_interesse[@$riga['tipo_interesse']]."  ".$interesse;
			if(defined('tab_prefix') && @tab_prefix == 'hrc') echo ' ('.$riga['numero_persone']." pax + ".$riga['numero_bambini']." B) ";
			
			echo '<br><span class="msg gray">'.@$campagna_id[$riga['campagna_id']]." > ".@$source_potential[$riga['source_potential']]."</span></td>";
		
			
			
	
			echo "<td>Visita: $data_visita <br> $bdcAction</td>";
			//echo "<td><strong>".@$proprietario[@$riga['venditore']]."</strong><br>".$giorni2."<br>$sellAction</td>";

			
			
				echo "<td>";
			echo $synapLead;
						
			if($riga['in_use'] == 0) {
			echo "<a class=\"mobile-buttons\" href=\"mod_inserisci.php?id=".$riga['id']."&potential_rel=".$riga['id']."\" title=\"Gestisci scheda ".ucfirst($riga['nome'])."\"> <i class=\"fa fa-search\"></i> </a>";
			} else {
			echo "<a class=\"mobile-buttons\" href=\"mod_inserisci.php?id=".$riga['id']."&potential_rel=".$riga['id']."&nominativo=".$riga['nome']."\" title=\"Bloccato: ".@$proprietario[$riga['in_use']]." sta chiamando il contatto\" style=\"color: red;\"> Gestito da ".@$proprietario[$riga['in_use']]."  </a>";
			}

			if($_SESSION['usertype'] == 0 ) echo " <a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Cancella\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>"; 

			echo "</td>";
			if($riga['is_customer'] > 1) echo "<td><a href=\"$new_contract\"><i class=\"fa fa-user $color_contract\" ></i></a></td>"; 

		    echo "</tr>";
		

			
			
	}
?>
  </table>
  <?php  $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);  ?>
  <div class="checkItemTd">
    <?php if($_SESSION['usertype'] == 3 || $_SESSION['usertype'] == 0) {
 echo '<h3 style="text-align: left; clear: both;">
	<strong>Esegui su <span  id="counter"> '.$count.' lead</span> </strong> questa azione: 
	
	<select name="azione" id="action_list" class="select2" style="min-width: 250px;">
	<option value="-1">Seleziona Azione</option>
	<option value="1">Assegna a Persona</option>
    <option value="6">Cambia Status</option>
    <option value="3">Invia SMS</option>
	<option value="4">Invia Email</option>
<!--	<option value="5">Esporta in MailUp</option>
-->	
	</select>


 <span id="action1" class="action_options">
 <select name="assegna_leads" id="assegna_leads" class="select2" style="min-width: 250px;">';
            
			echo '<optgroup label="Operatori BDC">';  
		     foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@$_SESSION['proprietario_id'] == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		
 echo '</optgroup></select>';
 echo ' giorni scadenza <input type="number" style="width: 50px;" name="scadenza" value="2" />
 </span>
 
  <span id="action2" class="action_options">
 <select name="assegna_venditore" id="assegna_venditore" class="select2" style="min-width: 250px;">';
            
		
			echo '<optgroup label="Venditori">';  
			    foreach($venditori as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@$_SESSION['proprietario_id'] == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		
 echo '</optgroup></select>';
 echo ' giorni scadenza <input type="number" style="width: 50px;" name="scadenza" value="2" />
 </span>
 
 <span id="action6" class="action_options" style="width: 150px;">
 <select name="status_potential" id="status_potential" class="select2" style="min-width: 250px;">';
            
			foreach($status_potential as $valores => $label){ // Recursione Indici di Categoria
			 echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; 
			}
		
 echo '</select>';
 echo '</span>
 
 
 
  <input type="submit" value="Esegui" class="button">
  
</h3>';
	} ?>
  </div>
</form>
