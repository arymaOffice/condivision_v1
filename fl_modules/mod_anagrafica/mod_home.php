<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>

<h1><?php echo $module_title.' '.$new_button; ?></h1>

<div class="filtri" id="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2 class="filter_header"> Filtri</h2>  <!--Partner: 
  
  <span style="position: relative;">
  <input type="text" id="operatore_text" name="operatore_text" value="<?php if(isset($_GET['operatore_text'])){ echo check($_GET['operatore_text']);} else { echo "Inserisci il Testo"; } ?>" onFocus="this.value=''; operatore.value=''" onkeydown=""  accesskey="a" tabindex="1"   onkeyup="return caricaProprietario(this.value,'contenuto-dinamico','operatore');" maxlength="200" class="txt_cerca" />
   <div id="contenuto-dinamico"><?php if(isset($_GET['operatore'])){ echo '<input type="hidden" name="operatore" value="'.$_GET['operatore'].'" />'; } ?> </div></span>
--> 
    
  <!--  Stato:
    <select name="status_anagrafica" id="status_anagrafica">
      <option value="0">Mostra Tutti</option>
      <?php 
              
		     foreach($status_anagrafica as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_anagrafica_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
    </select>-->
    
    <label>  Stato account:</label>
    <select name="status_account" id="status_account">
      <option value="-1">Tutti </option>
      <?php 
            $selected = (@$stato_account_id == 1) ? " selected=\"selected\"" : "";
		    echo "<option value=\"1\" $selected>Attivi</option>\r\n"; 
			$selected = (@$stato_account_id == 0) ? " selected=\"selected\"" : "";
			echo "<option value=\"0\" $selected>Sospesi</option>\r\n"; 
			
		 ?>
    </select>
    <label> creato tra il</label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    <label> e il</label>
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	

 		
	?>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <th style="width: 1%;"></th>
  <?php if(ATTIVA_ACCOUNT_ANAGRAFICA == 1) { ?><th>Account</th><?php } ?>
  <th><a href="./?ordine=1">Nome e Cognome</a></th>
  
  <th class="hideMobile">Indirizzo</th>
  <th>Contatti</th>
   <th></th> <th></th>
  <?php if(ESTRATTO_CONTO_IN_ANAGRAFICA == 1) { ?><th>Saldo</th><?php } ?>
  <?php if(ALERT_DOCUMENTO_SCADUTO == 1) { ?><th></th><?php } ?>
  <th></th>
 
</tr>
<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$deleted = 0;
	$incomplete = 0;
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			$query_doc = "SELECT * FROM fl_files WHERE relation = ".$riga['proprietario']." AND cat = $documentazione_auto AND contenuto = ".$riga['id']." ORDER BY titolo ASC";
 			$risultato2 = mysql_query($query_doc, CONNECT);	
			$documenti_count = mysql_affected_rows();
			
			$account = get_account($riga['id']);
			if($account['id'] > 0)  { 
			$user_check = '<a data-fancybox-type="iframe" title="Modifica Account" class="fancybox" href="../mod_account/mod_visualizza.php?external&id='.$account['id'].'">'.$account['user'].'</a><br>'.$account['motivo_sospensione'];
			$user_ball = ($account['attivo'] == 1)  ? "<span class=\"c-green\"><i class=\"fa fa-user\"></i></span>" : "<span class=\"c-red\"><i class=\"fa fa-user\"></i></span>"; 
			$saldo = balance($account['id']);
			$saldo = '<a data-fancybox-type="iframe" class="fancybox_view"  href="../mod_depositi/mod_user.php?operatore_text='.$account['nominativo'].'&operatore='.$account['id'].'"> &euro; '.numdec($saldo,2).'</a>';
			} else {
			$user_check = "<a href=\"../mod_account/mod_inserisci.php?external&anagrafica_id=".$riga['id']."&email=".$riga['email']."&nominativo=".$riga['ragione_sociale']."\">Attiva account</a>";
			$user_ball = '';
			$saldo = 0;
			}
			
			if($riga['status_anagrafica'] == 3) { 
			$colore = "class=\"tab_green\"";  
			} else if($riga['status_anagrafica'] != 4) { $colore = "class=\"tab_orange\""; 
			} else { $colore = "class=\"tab_red\""; }
			
			$elimina = ($riga['status_anagrafica'] == 3) ? '' : "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";
		    ($riga['data_scadenza'] < date('Y-m-d')) ? $note = "<span title=\"Documento Scaduto\" class=\"c-red\"><i class=\"fa fa-exclamation-triangle fa-lg\"></i></span>" : $note = "<i class=\"fa fa-exclamation-triangle fa-lg\"></i>";
			
			if(1){
			
			$tot_res++;
			
			if($stato_account_id != -1 && $risultato3['attivo'] != $stato_account_id) { 
			
			$tot_res--; 
			
			} else {
			
				
			if($i==1){ $i=0; echo "<tr>"; } else { $i=1; echo "<tr>"; }	
			$nominativo = ($riga['ragione_sociale'] != '') ? ucfirst($riga['ragione_sociale']) : ucfirst($riga['nome']).' '.ucfirst($riga['cognome']);		
			$dettegli_utenza = get_tipo_utenza($riga['proprietario']);
			echo "<td $colore><span class=\"Gletter\"></span></td>"; 
			 if(ATTIVA_ACCOUNT_ANAGRAFICA == 1)  echo "<td  class=\"hideMobile\">$user_ball ".$user_check."</td>"; 		
			echo "<td><span class=\"color\">$nominativo</span><br><span class=\"msg orange\">".$tipo_profilo[$riga['tipo_profilo']]."</span> - P.iva ".$riga['partita_iva']."</td>";
			
			echo "<td class=\"hideMobile\">".$riga['comune_sede']." (".@$provincia[$riga['provincia_sede']].") ".$riga['cap_sede']."<br>".$riga['sede_legale']."</td>"; 
			echo "<td><i class=\"fa fa-envelope-o\"></i> <a href=\"mailto:".$riga['email']."\">".$riga['email']."</a></td>
			<td><i class=\"fa fa-phone\"> </i> ".$riga['telefono']."</td><td> <i class=\"fa fa-mobile\"></i> ".$riga['cellulare']."</td>"; 
		    if(ESTRATTO_CONTO_IN_ANAGRAFICA == 1)   echo "<td  class=\"hideMobile\">".$saldo."</td>";
			if(ALERT_DOCUMENTO_SCADUTO == 1)  echo "<td  class=\"hideMobile\">$note</td>";
			 
			//echo "<td><a href=\"../mod_documentazione/?operatore=".$riga['proprietario']."&amp;modulo=0&amp;cat=$documentazione_auto&amp;contenuto=".$riga['id']."\" title=\"Gestisci Files\" class=\"button\">Doc: ($documenti_count)</a></td>"; 
			echo "<td  class=\"strumenti\">";
			if(@PROFILO_ANAGRAFICA == 1)  echo '<a href="mod_inserisci.php?external&action=1&tBiD='.base64_encode('39').'&id='.$riga['id'].'"><i class="fa fa-user"></i>'.get_scan($riga['id']).'</a>';
			echo "<a href=\"mod_inserisci.php?id=".$riga['id']."&nominativo=".$riga['ragione_sociale']."\" title=\"Gestione Cliente ".ucfirst($riga['ragione_sociale'])." Agg. ".$riga['data_aggiornamento']."\"> <i class=\"fa fa-search\"></i> </a>
			<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?external&action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."&nominativo=".$riga['ragione_sociale']."\" title=\"Scheda di stampa ".ucfirst($riga['ragione_sociale'])."\"> <i class=\"fa fa-print\"></i> </a> $elimina </td>";

		
		    echo "</tr>"; }
			} else { $incomplete++; }
	}

	echo "</table>";
	

?>
<?php echo '<h2>Totale risultati: '.$tot_res.'</h2>'; ?> 