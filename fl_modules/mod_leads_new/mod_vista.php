<?php


// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
include('filtri.php');


	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
	$query = "SELECT $select FROM $tabella $tipologia_main ORDER BY $ordine LIMIT $start,$step;";

	$risultato = mysql_query($query, CONNECT);
	if($_SESSION['number'] == 1) echo $query.mysql_error();
	?>

<p style="clear: both; text-align: left;"> 


      <a href="#"  style=" padding: 8px 20px;
    font-size: 100%;" class="showcheckItems button">Seleziona contatti</a>
    <?php echo '<a style=" padding: 8px 20px;
    font-size: 100%;" class="button" href="./?'.$_SERVER['QUERY_STRING'].'&proprietario=-1"><i class="fa fa-users" aria-hidden="true"></i>  Mostra tutti</a>'; ?> 
  </p>


<div id="results" class="green"></div>
<form action="./mod_opera.php" id="print" class="ajaxForm" method="post" style="padding: 0; margin: 0;">
  <table class="dati" summary="Dati" style=" width: 100%;">
    <tr>
      <th></th>
            <th style="width: 1%;" class="checkItemTd"><input onclick="checkAllFields(1);" id="checkAll"  name="checkAll" type="checkbox"  />
        <label for="checkAll"><?php echo $checkRadioLabel; ?></label>
      </th>

<?php if($status_potential_id != 4){ ?>
      <th>Sorgente</th>
      <?php }  ?>
      <th>Nominativo</th>
      <th>Contatti</th>
      <th>Veicolo </th>
      <th>Interesse</th>
      <th>Test Drive </th>
      <th>Permuta</th>
     <th>Promo Pn. </th>
      <th>SMS/Email</th>
      <th> </th>
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
		if($_SESSION['usertype'] < 4) { 
		$checked = ($riga['status_potential']==0) ? '' : ''; //checked auto
		//$count += ($riga['status_potential']==0) ? 1 : 0;
		$input =  '<input onClick="countFields(1);" type="checkbox" name="leads[]" value="'.$riga['id'].'" id="'.$riga['id'].'"  '.$checked.' /><label for="'.$riga['id'].'">'.$checkRadioLabel.'</label>';
		}

		/* SMS*/
		$phone = phone_format($riga['telefono'],'39');	
		$website = www($riga['website']);		
	
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
		

		$new_contract = ($riga['is_customer'] > 1) ? '../mod_anagrafica/mod_inserisci.php?id='.$riga['is_customer'].'&meeting_id=0' : '../mod_anagrafica/mod_inserisci.php?id=1&meeting_id=0&j='.base64_decode($riga['id']).'&nominativo='.$riga['nome'].' '.$riga['cognome'];
		$color_contract = ($riga['is_customer'] > 1) ? 'c-green' : '';
		$testDrive = (@$riga['test_drive'] == 1) ? '<span class="msg green">SI</span>' : '<span class="msg red">NO</span>';
		$promo = (@$riga['promo_pneumatici'] == 1) ? '<span class="msg green">SI</span>' : '<span class="msg red">NO</span>';
		$permuta = (@$riga['permuta'] == 1) ? '<span class="msg green">SI</span>' : '<span class="msg red">NO</span>';
		$veicolo_lista = '';
		$veicolo = ($riga['is_customer'] > 1) ? get_veicolo(48,$riga['is_customer']) : get_veicolo($tab_id,$riga['id']);
		if($veicolo != NULL) $veicolo_lista = '<a href="../mod_veicoli/mod_inserisci.php?id='.$veicolo['id'].'">'.$veicolo['marca'].' '.$veicolo['modello'].'<br>'.$veicolo['anno_immatricolazione'].' Km. '.$veicolo['kilometraggio'].'</a><br>'.@$alimentazione[$veicolo['alimentazione']].' TARGA: '.$veicolo['targa'].'';
		$qualified = ($status_potential_id != 4 && $riga['nome'] != '' && $riga['telefono'] != '' && $riga['email'] != '') ? '<i class="fa fa-star" style="padding: 0; color: rgb(246, 205, 64); font-size: 80%;" aria-hidden="true"></i>' : '';
		$qualified .= ($status_potential_id != 4 && $veicolo != 0) ? '<i class="fa fa-star" style="padding: 0; color: rgb(246, 205, 64); font-size: 80%;" aria-hidden="true"></i>' : '';


		$recuperaSMS = get_lastActivity(16,$riga['id'],9);
		$smsinviati =  '<span class="msg " style="font-size: 125%;"><strong>'.$recuperaSMS['tot'].'</strong></span> '.mydatetime($recuperaSMS['data_creazionemax']).'';
		$recuperaMAIL = get_lastActivity(16,$riga['id'],1);
		$mailinviate = '<span class="msg " style="font-size: 125%;" ><strong>'.$recuperaMAIL['tot'].'</strong></span> '.mydatetime($recuperaMAIL['data_creazionemax']).'';

		
			if($status_potential_id != 4)  echo "<tr><td $colore><span class=\"Gletter\"></span></td>"; 
				echo "<td class=\"checkItemTd\">$input</td>";

		echo "<td><span class=\"msg blue\">".@$source_potential[$riga['source_potential']]."</span><br>".mydate($riga['data_creazione'])."</td>";
			echo "<td >
			<a class=\"mobile-buttons nominativo\" href=\"mod_inserisci.php?id=".$riga['id']."&potential_rel=".$riga['id']."\" title=\"Gestisci scheda ".ucfirst($riga['nome'])."\">
			".$riga['ragione_sociale']." <strong title=\"".strip_tags(converti_txt($riga['messaggio']))."\">".$riga['nome']." ".$riga['cognome']."</strong></a><strong></strong> $synapsy
			<br> ".$riga['industry']." <br> ".strip_tags(converti_txt($riga['note']))."
			 </td>";

			echo "<td>			$qualified <strong>".$status_potential[$riga['status_potential']]."</strong><br>

			<i class=\"fa fa-phone\" style=\"padding: 3px;\"></i>$phone <br><i class=\"fa fa-envelope-o\" style=\"padding: 3px;\"></i><a href=\"mailto:".$riga['email']."\"> ".$riga['email']."</a></td>";
			echo "<td>".$veicolo_lista."</td>";
			$interesse = ($status_potential_id != 4) ? $riga['marca_interesse'].' '.$riga['modello_interesse'].''.$riga['interessato_a'] : '';
			echo "<td>".$interesse."<br><span class=\"msg orange\">".@$tipo_interesse[@$riga['tipo_interesse']]."</span> </td>";
			echo "<td>$testDrive</td>";
			echo "<td>$permuta</td>";
			echo "<td>$promo</td>";
			echo "<td>$smsinviati<br/>$mailinviate</td>";
			echo "<td class=\"mobile-buttons\">"; 	
			if($riga['is_customer'] > 1) echo "<a href=\"$new_contract\"><i class=\"fa fa-user $color_contract\" ></i></a>"; 
			echo "</td>";

			
		    echo "</tr>";
		

			
			
	}
?>
  </table>
  <?php  $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);  ?>
  <div class="checkItemTd">
    <?php if($_SESSION['usertype'] < 4) {
 echo '<h3 style="text-align: left; clear: both;">
	<strong>Esegui su <span  id="counter"> '.$count.' lead</span> </strong> questa azione: 
	
	<select name="azione" id="action_list" class="select2" style="min-width: 250px;">
	<option value="-1">Seleziona Azione</option>
<!--	<option value="2">Cambia Status</option>
-->	<option value="3">Invia SMS</option>
	<option value="4">Invia Email</option>
	<option value="5">Esporta in MailUp</option>
	
	</select>



 
 <span id="action2" class="action_options">
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
