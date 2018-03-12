<?php


// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];

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


<h1><strong>Leads <?php echo ' '.$new_button; ?></strong></h1>
<?php   if($status_potential_id != 4) include ("counters.php");?>


<br class="clear">
<div id="filtri" class="filtri">
  <h2>Filtri</h2>
  <form method="get" action="" id="fm_filtri">
   <?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>
    <label> Qualificati </label>
    
    <input type="radio" name="qualificati" id="qualificati1" value="1" <?php if($qualificati_id == 1) echo 'checked'; ?>>
    <label for="qualificati1">SI</label>
   
    <input type="radio" name="qualificati" id="qualificati0" value="0"  <?php if($qualificati_id == 0) echo 'checked'; ?>>
    <label for="qualificati0">NO</label>
    
    <input type="radio" name="qualificati" id="qualificati2" value="2"  <?php if($qualificati_id == 2) echo 'checked'; ?>>
    <label for="qualificati2">SUPER</label>
    
    <input type="radio" name="qualificati" id="qualificati3" value="-1"  <?php if($qualificati_id == -1) echo 'checked'; ?>>
    <label for="qualificati3">Tutti</label>
    
    
    <label> Campagna</label>
    <select name="campagna_id" id="campagna_id" >
      <option value="-1">Tutti</option>
      <?php 
              
		     foreach($campagna_id as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($campagna_id_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select>
    
      <label> Attività</label>
    <select name="source_potential" id="source_potential" >
      <option value="-1">Tutti</option>
      <?php 
              
		     foreach($source_potential as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($source_potential_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select>
    
    <label> TIPO INTERESSE</label>
    <select name="tipo_interesse" id="tipo_interesse">
      <option value="-1">Tutti</option>
      <?php 
              
		     foreach($tipo_interesse as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($tipo_interesse_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select>
    <?php $data_set->do_select('VALUES',$tabella,'interessato_a','interessato_a',$interessato_a_id); ?>
    
   
       <label> Promo Pneumatici </label>
    <select name="promo_pneumatici" id="promo_pneumatici">
      <option value="-1">Tutti</option>
      <?php 
              
		     foreach($promo_pneumatici as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($promo_pneumatici_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select>
       <label> Test Drive </label>
    <select name="test_drive" id="test_drive">
      <option value="-1">Tutti</option>
      <?php 
              
		     foreach($test_drive as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($test_drive_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select>
       <label> Permuta </label>
    <select name="permuta" id="permuta">
      <option value="-1">Tutti</option>
      <?php 
              
		     foreach($permuta as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($permuta_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select>

 
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


      <a href="#"  style=" padding: 8px 20px;
    font-size: 100%;" class="showcheckItems button">Seleziona contatti</a> </p>


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
      <?php if($status_potential_id != 4){ ?>
      <th></th>
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
		$qualified = ($riga['nome'] != '' && $riga['telefono'] != '' && $riga['email'] != '' && $veicolo != 0) ? '<i class="fa fa-star" style="color: rgb(246, 205, 64);" aria-hidden="true"></i>' : '';

		$recuperaSMS = get_lastActivity($tab_id,$riga['id'],9);
		$smsinviati =  '<span class="msg " style="font-size: 125%;"><strong>'.$recuperaSMS['tot'].'</strong></span> '.mydatetime($recuperaSMS['data_creazionemax']).'';
		$recuperaMAIL = get_lastActivity($tab_id,$riga['id'],1);
		$mailinviate = '<span class="msg " style="font-size: 125%;" ><strong>'.$recuperaMAIL['tot'].'</strong></span> '.mydatetime($recuperaMAIL['data_creazionemax']).'';

		
			if($status_potential_id != 4)  echo "<tr><td $colore><span class=\"Gletter\"></span></td>"; 
				echo "<td class=\"checkItemTd\">$input</td>";

		echo "<td><span class=\"msg blue\">".@$source_potential[$riga['source_potential']]."</span><br>".mydate($riga['data_creazione'])."</td>";
			if($status_potential_id != 4)  echo "<td style=\"text-align:center;\">$qualified</td>";
			echo "<td>
			<a class=\"mobile-buttons\" style=\"color: gray;\" href=\"mod_inserisci.php?id=".$riga['id']."&potential_rel=".$riga['id']."\" title=\"Gestisci scheda ".ucfirst($riga['nome'])."\">
			".$riga['ragione_sociale']." <strong title=\"".strip_tags(converti_txt($riga['messaggio']))."\">".$riga['nome']." ".$riga['cognome']."</strong></a> - <strong>".$riga['industry']."</strong> - <strong>".$riga['citta']."</strong> $synapsy <br> ".strip_tags(converti_txt($riga['note'])).'</td>';
			echo "<td><i class=\"fa fa-phone\" style=\"padding: 3px;\"></i>$phone <br><i class=\"fa fa-envelope-o\" style=\"padding: 3px;\"></i><a href=\"mailto:".$riga['email']."\"> ".$riga['email']."</a></td>";
			echo "<td>".$veicolo_lista."</td>";
			$interesse = ($status_potential_id != 4) ? $riga['interessato_a'] : '';
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
