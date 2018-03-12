<?php


// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

?>




<br class="clear">
<div id="filtri" class="filtri">

  <h2>Filtri</h2>
  <form method="get" action="" id="fm_filtri">

  	<?php if(isset($_GET['action'])) echo '<input type="hidden" name="action" value="'.check($_GET['action']).'" >'; ?>
    <label> Qualificati </label>
    
    <input type="radio" name="qualificati" id="qualificati1" value="1" <?php if($qualificati_id == 1) echo 'checked'; ?>>
    <label for="qualificati1">SI</label>
   
    <input type="radio" name="qualificati" id="qualificati0" value="0"  <?php if($qualificati_id == 0) echo 'checked'; ?>>
    <label for="qualificati0">NO</label>
    
    <input type="radio" name="qualificati" id="qualificati2" value="2"  <?php if($qualificati_id == 2) echo 'checked'; ?>>
    <label for="qualificati2">SUPER</label>
    
    <input type="radio" name="qualificati" id="qualificati3" value="-1"  <?php if($qualificati_id == -1) echo 'checked'; ?>>
    <label for="qualificati3">Tutti</label>
    
   <?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){
			
			

			if((select_type($chiave) == 2 || select_type($chiave) == 19) && $chiave != 'id' || $chiave == 'status_potential' || $chiave == 'proprietario') {
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
 
  <table class="dati" summary="Dati" style=" width: 100%;">
    <tr>
      <th>Data</th>
      <th>Nominativo</th>
     
      <th>Contatti</th>
      <th></th>
    </tr>
   
    <?php 
	
	$i = 1;



	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">No records</td></tr>";		}
	$tot_res = $count = 0;
	
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
		
		$colore = "class=\"tab_blue\"";
		if($riga['priorita_contatto'] == 0) { $colore = "class=\"turquoise\"";  }
		if($riga['priorita_contatto'] == 1) { $colore = "class=\"orange\"";  }
		if($riga['priorita_contatto'] == 2) { $colore = "class=\"red\"";  }
		
	 	$data_principale = data_visita_lead($riga[$data_dafault]);
		

	
		$phone = phone_format($riga['telefono'],'39');	
		$website = www($riga['website']);		
		$new_contract = ($riga['is_customer'] > 1) ? '../mod_anagrafica/mod_inserisci.php?id='.$riga['is_customer'].'&meeting_id=0' : '../mod_anagrafica/mod_inserisci.php?id=1&meeting_id=0&j='.base64_decode($riga['id']).'&nominativo='.$riga['nome'].' '.$riga['cognome'];
		$color_contract = ($riga['is_customer'] > 1) ? 'c-green' : '';
		$qualified = ($status_potential_id != 4 && $riga['nome'] != '' && $riga['telefono'] != '' && $riga['email'] != '') ? '<i class="fa fa-star" style="padding: 0; color: rgb(246, 205, 64); font-size: 60%;" aria-hidden="true"></i>' : '';
		

			if($status_potential_id != 4)  echo "<tr><td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td><h3>$data_principale</h3><span class=\"msg blue\">".$status_potential[$riga['status_potential']]."</span></td>";

			echo "<td>
			<a class=\"mobile-buttons\" style=\"color: gray;\" href=\"mod_inserisci.php?id=".$riga['id']."&potential_rel=".$riga['id']."\" title=\"Gestisci scheda ".ucfirst($riga['nome'])."\">
			$qualified ".$riga['ragione_sociale']." <strong title=\"".strip_tags(converti_txt($riga['messaggio']))."\">".ucfirst($riga['nome'])." ".ucfirst($riga['cognome'])."</strong></a>    <br> ".strip_tags(converti_txt($riga['note']))."
			</td>";
			echo "<td><i class=\"fa fa-phone\" style=\"padding: 3px;\"> </i>  $phone <br><i class=\"fa fa-envelope-o\" style=\"padding: 3px;\"></i><a href=\"mailto:".$riga['email']."\"> ".$riga['email']."</a></td>";
			?>

			<td><a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_eventi/?lead_id=<?php echo $riga['id']; ?>" class="touch green_push setAction" style="width: 100%;" data-gtx="<?php echo base64_encode($tab_id); ?>" data-id="<?php echo base64_encode($riga['id']); ?>" data-azione="5"  data-esito="1"  data-note="Creato Evento"><i class="fa fa-calendar"></i> <br>Evento</a></td>

			<?php

			echo "</td>";
		    echo "</tr>";
		

			
			
	}
?>
  </table>
 
