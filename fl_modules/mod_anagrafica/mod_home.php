<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>

<?php if(isset($_GET['id_sc'])) echo '<h2>Scadenze Documenti</h2>'; ?>
<?php if(isset($_GET['cn_sc'])) echo '<h2>Scadenze Contratti</h2>'; ?>
<div id="filtri" class="filtri"> 
<form method="get" action="" id="fm_filtri">
  <!--Partner: 
  
  <span style="position: relative;">
  <input type="text" id="operatore_text" name="operatore_text" value="<?php if(isset($_GET['operatore_text'])){ echo check($_GET['operatore_text']);} else { echo "Inserisci il Testo"; } ?>" onFocus="this.value=''; operatore.value=''" onkeydown=""  accesskey="a" tabindex="1"   onkeyup="return caricaProprietario(this.value,'contenuto-dinamico','operatore');" maxlength="200" class="txt_cerca" />
   <div id="contenuto-dinamico"><?php if(isset($_GET['operatore'])){ echo '<input type="hidden" name="operatore" value="'.$_GET['operatore'].'" />'; } ?> </div></span>
--> 
    
    Stato:
    <select name="status_anagrafica" id="status_anagrafica" onChange="form.submit();">
      <option value="-1">Mostra Tutti</option>
      <?php 
              
		     foreach($status_anagrafica as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_anagrafica_id == $valores) ? " selected=\"selected\"" : "";
			echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select>
    
    Stato account:
    <select name="status_account" id="status_account">
      <option value="-1">Tutti </option>
      <?php 
            $selected = (@$stato_account_id == 1) ? " selected=\"selected\"" : "";
		    echo "<option value=\"1\" $selected>Attivi</option>\r\n"; 
			$selected = (@$stato_account_id == 0) ? " selected=\"selected\"" : "";
			echo "<option value=\"0\" $selected>Sospesi</option>\r\n"; 
			
		 ?>
    </select> 
    
     Saldi  <select name="status_saldi" id="status_saldi">
      <option value="-1">Tutti </option>
      <?php 
            $selected = (@$status_saldi_id == 1) ? " selected=\"selected\"" : "";
		    echo "<option value=\"1\" $selected>Positivi</option>\r\n"; 
			$selected = (@$status_saldi_id == 0) ? " selected=\"selected\"" : "";
			echo "<option value=\"0\" $selected>Negativi</option>\r\n"; 
			
		 ?>
    </select> 
    creato tra il 
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
     e  il 
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    <input type="submit" value="Mostra" class="button" />
  
</form>

     
    </div>
    
    <div id="info_txt"></div>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query.mysql_error();

 		
	?>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <th style="width: 1%;"></th>
  <th>Account</th>
  <th><a href="./?ordine=1">Ragione Sociale</a></th>
  
  <th class="hideMobile">Indirizzo</th>
  <th>Contatti</th>
  
  <th>Saldo</th>
  <th></th>
  <th></th>
  <th  class="hideMobile"></th>
</tr>
<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$deleted = 0;
	$incomplete = 0;
	$status_colors = array('tab_gray','tab_orange','tab_fuxia','tab_green','tab_red');
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
			$query_doc = "SELECT * FROM fl_files WHERE relation = ".$riga['proprietario']." AND cat = $documentazione_auto AND contenuto = ".$riga['id']." ORDER BY titolo ASC";
 			$risultato2 = mysql_query($query_doc, CONNECT);	
			$documenti_count = mysql_affected_rows();
			
			$account = get_account($riga['id']); 
			$oggi = '';
			if($account['id'] > 0)  { 
			$oggi = '<br>Oggi: &euro; '.numdec(ricariche_oggi($account['id']),2);
			if($riga['user'] == '') mysql_query("UPDATE fl_anagrafica SET user = '".$account['user']."', nominativo = '".$account['nominativo']."' WHERE id = ".$riga['id'],CONNECT);
			$user_check = '<a title="Modifica Account" data-fancybox-type="iframe" class="fancybox" href="../mod_account/mod_password.php?external&id='.$account['id'].'&user='.$account['user'].'">'.$account['user'].'</a><br>'.$account['motivo_sospensione'];
			$user_ball = ($account['attivo'] == 1)  ? "<span class=\"c-green\"><i class=\"fa fa-user\"></i></span>" : "<span class=\"c-red\"><i class=\"fa fa-user\"></i></span>"; 
			$saldo = get_saldo($account['id']);
			$green = ($saldo >= 0) ?  'c-green' :  'c-red';	
			$saldo_txt = '<a data-fancybox-type="iframe" class="fancybox_view '.$green.'"  href="../mod_depositi/mod_estrattoconto.php?operatore_text='.$account['nominativo'].'&proprietario='.$account['id'].'"> &euro; '.numdec($saldo,2).'</a>';
			$data_scadenza = 'Scad.'.mydate(@$account['data_scadenza']).'<br>';
			} else {
			$user_check = "<a  href=\"../mod_account/mod_inserisci.php?external&anagrafica_id=".$riga['id']."&email=".$riga['email']."&nominativo=".$riga['ragione_sociale']."\">Attiva account</a>";
			$user_ball = '';
			$saldo = 0;
			$saldo_txt = 0;
			$data_scadenza = '';
			}
			$genitore_ball = ($riga['profilo_genitore'] > 1) ? "<span class=\"c-gray\">Father: ".$proprietario[$riga['profilo_genitore']]."</span>" : "" ;
			$colore = $status_colors[$riga['status_anagrafica']];
		    (@$riga['data_scadenza'] < date('Y-m-d')) ? $note = "<span title=\"Documento Scaduto\" class=\"c-red\"><i class=\"fa fa-exclamation-triangle fa-lg\"></i></span>" : $note = "";
			
			$tot_res++;
			
			if($stato_account_id != -1 && @$account['attivo'] != $stato_account_id) { 
			$tot_res--; 
			} else {
				
				if($status_saldi_id != -1 && ( ($status_saldi_id == 1 && $saldo >= 0) || ($status_saldi_id == 0 && $saldo < 0  ) ) ) { 
				$tot_res--; 
				} else {
				echo "<tr>"; 			
				echo "<td class=\"$colore\"><span class=\"Gletter\"></span></td>"; 
				echo "<td>$user_ball ".$user_check."<br><span class=\"c-gray\">Profilo ".$riga['profilo_commissione']."</span> <br> $genitore_ball </td>"; 		
				echo "<td><span class=\"color\">".ucfirst($riga['ragione_sociale'])."</span><br>$note P.iva ".$riga['partita_iva']."</td>";
				echo "<td class=\"hideMobile\">".$riga['comune_punto']." (".@$provincia[$riga['provincia_punto']].") ".$riga['cap_punto']."<br>".$riga['indirizzo_punto']."</td>"; 
				echo "<td><i class=\"fa fa-envelope-o\"></i> <a href=\"mailto:".$riga['email']."\">".$riga['email']."</a><br><i class=\"fa fa-phone\"> </i> ".$riga['telefono']." <i class=\"fa fa-mobile\"></i> ".$riga['cellulare']."</td>"; 
				echo "<td class=\"hideMobile\">".$saldo_txt." $oggi</td>";
				echo "<td class=\"hideMobile\"><a href=\"mod_inserisci.php?external&action=1&amp;id=".$riga['id']."\" title=\"Gestione Cliente ".ucfirst($riga['ragione_sociale'])."\"> <i class=\"fa fa-search\"></i> </a></td>";
				echo "<td class=\"azioni\"><a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?external&action=1&amp;id=".$riga['id']."\" title=\"Scheda di stampa ".ucfirst($riga['ragione_sociale'])."\"> <i class=\"fa fa-print\"></i> </a></td>";
				echo "<td  class=\"hideMobile\" style=\"font-size: smaller;\" title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]."\">Agg. ".$riga['data_aggiornamento']."<br />Creaz. ".$riga['data_creazione']."<br>$data_scadenza</td>";
				echo "</tr>"; 
				}
			
			
			} 
	
	
	}

	
	echo "</table>";
	

?>
<p><?php foreach($status_anagrafica as $chiave => $valore) {echo '<div style="display: inline-block; position: relative; padding-left: 15px; margin-right: 10px;"><span style="position: absolute; top: 4px; left: 0; width: 10px; height: 10px; margin: 0; display: block;" class="'.$status_colors[$chiave].'">&nbsp;</span> '.$valore.'</div>'; } ?></p>
<?php echo '<h2>Totale risultati: '.$tot_res.'</h2>'; ?> 