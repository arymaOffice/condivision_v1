<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>

<div class="filtri" id="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  
<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>

<?php if(ATTIVA_ACCOUNT_ANAGRAFICA == 1) { ?>

  <div class="filter_box">  
  <label>  Tipo account:</label>
    <select name="tipo_account" id="tipo_account">
      <option value="-1">Tutti </option>
  	<?php 
              
		     foreach($tipo as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($tipo_account_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
    </select>
    </div>
    

   
  <div class="filter_box">  
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
    </div>
    
<div class="filter_box">  
<label>  Account:</label>
<input type="text" id="account" name="account" value="<?php if(isset($_GET['account'])){ echo check($_GET['account']);} else {  } ?>" onFocus="this.value='';" onkeydown=""  accesskey="a" tabindex="1"   onkeyup="return caricaProprietario(this.value,'contenuto-dinamico','account');" maxlength="200" class="txt_cerca" />
<div id="contenuto-dinamico"></div>
</div>
<?php } ?>    
   <?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){
			
			echo '<div class="filter_box">';
			
			if(select_type($chiave) != 19 && select_type($chiave) != 5 && select_type($chiave) != 2 && $chiave != 'id') { $cont = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$cont.'" />'; }
			
			if((select_type($chiave) == 2 || select_type($chiave) == 19) && $chiave != 'id') {
				echo '  <label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Tutti</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
			}
			echo '</div>';
			
			} 
		
	}
	 ?>    


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
  <?php if(ATTIVA_ACCOUNT_ANAGRAFICA == 1) { ?><th><a href="./?ordine=2">Account</a></th><?php } ?>
  <th><a href="./?ordine=3">Ragione Sociale</a></th>
  
  <th class="hideMobile">Sede Legale</th>
  <th>Contatti</th>
  <?php if(ESTRATTO_CONTO_IN_ANAGRAFICA == 1) { ?><th>Saldo</th><?php } ?>
  <?php if(ALERT_DOCUMENTO_SCADUTO == 1) { ?><th></th><?php } ?>
  <th><a href="./?ordine=0">Recenti</a> | <a href="./?ordine=1">Meno recenti</a></th>
 
</tr>
<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$deleted = 0;
	$incomplete = 0;
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			$show = 1;
			$account = get_account($riga['id']);
			if($stato_account_id != -1 && (!isset($account['attivo'] ) || @$account['attivo'] != $stato_account_id))   $show = 0;
			if($tipo_account_id != -1 && (!isset($account['tipo'] ) || @$account['tipo'] != $tipo_account_id))   $show = 0;
			
			
			if($show==1) {
				
				
			if($account['id'] > 0)  { 
			$user_check = '<a title="Modifica Account"  href="../mod_account2/mod_visualizza.php?id='.$account['id'].'">'.$account['user'].'</a><br>'.$account['motivo_sospensione'];
			$user_ball = ($account['attivo'] == 1)  ? "<span class=\"c-green\"><i class=\"fa fa-user\"></i></span>" : "<span class=\"c-red\"><i class=\"fa fa-user\"></i></span>"; 
			$saldo = balance($account['id']);
			$saldo = '<a data-fancybox-type="iframe" class="fancybox_view"  href="../mod_depositi/mod_user.php?operatore_text='.$account['nominativo'].'&operatore='.$account['id'].'"> &euro; '.numdec($saldo,2).'</a>';
			$tipo_profilo = $tipo[$account['tipo']];
			
			if($riga['account'] == "") mysql_query("UPDATE $tabella SET account = '".$account['user']."' WHERE id = ".$riga['id']." LIMIT 1");
			} else {
			$user_check = "<a href=\"../mod_account2/mod_inserisci.php?anagrafica_id=".$riga['id']."&email=".$riga['email']."&nominativo=".$riga['ragione_sociale']."\">Crea account</a>";
			$user_ball = '';
			$saldo = 0;
			$tipo_profilo = '';
			}
			
			if($riga['status_anagrafica'] == 3) { 
			$colore = "class=\"tab_green\"";  
			} else if($riga['status_anagrafica'] != 4) { $colore = "class=\"tab_orange\""; 
			} else { $colore = "class=\"tab_red\""; }
			$elimina =  "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>" ;
		    (@$riga['data_scadenza'] < date('Y-m-d')) ? $note = "<span title=\"Documento Scaduto\" class=\"c-red\"><i class=\"fa fa-exclamation-triangle fa-lg\"></i></span>" : $note = "<i class=\"fa fa-exclamation-triangle fa-lg\"></i>";
			$concessione = (defined('AFFILIAZIONI') && AFFILIAZIONI == 1)  ? " ".$riga['numero_concessione'] : '';
			$tot_res++;
			
					echo "<tr>"; 
					$nominativo = ($riga['ragione_sociale'] != '') ? ucfirst($riga['ragione_sociale']) : ucfirst($riga['nome']).' '.ucfirst($riga['cognome']);		
					echo "<td $colore><span class=\"Gletter\"></span></td>"; 
					if(ATTIVA_ACCOUNT_ANAGRAFICA == 1)  echo "<td  class=\"hideMobile\">$user_ball ".$user_check."</td>"; 		
					echo "<td><span class=\"color\"><strong>".$riga['id']."</strong> $nominativo</span><br>P. iva ".$riga['partita_iva'].'<br>';
					if(defined('MULTI_BRAND') && MULTI_BRAND == 1)  echo "<span class=\"msg blue\">".$marchio[$riga['marchio']]."</span>";
					echo "<span class=\"msg orange\">".$tipo_profilo." $concessione </span></td>";
					echo "<td class=\"hideMobile\">".$riga['comune_sede']." (".@$riga['provincia_sede'].") ".$riga['cap_sede']."<br>".$riga['sede_legale']."</td>"; 
					echo "<td><i class=\"fa fa-envelope-o\"></i> <a href=\"mailto:".$riga['email']."\">".$riga['email']."</a>
					<br><i class=\"fa fa-phone\" style=\"padding: 5px 10px;\"></i>".$riga['telefono']." - ".$riga['cellulare']."</td>"; 
					if(ESTRATTO_CONTO_IN_ANAGRAFICA == 1)   echo "<td  class=\"hideMobile\">".$saldo."</td>";
					if(ALERT_DOCUMENTO_SCADUTO == 1)  echo "<td  class=\"hideMobile\">$note</td>";
					echo "<td  class=\"strumenti\">";
					if(@PROFILO_ANAGRAFICA == 1)  echo '<a href="mod_inserisci.php?external&action=1&tBiD='.base64_encode('39').'&id='.$riga['id'].'"><i class="fa fa-user"></i>'.get_scan($riga['id']).'</a>';
					if(@PANORAMICA_ANAGRAFICA == 1)  echo '<a href="mod_panoramica_contatto.php?id='.$riga['id'].'"><i class="fa fa-television" aria-hidden="true"></i></a>';
					echo "<a href=\"mod_inserisci.php?id=".$riga['id']."&nominativo=".$riga['ragione_sociale']."\" title=\"Gestione Cliente ".ucfirst($riga['ragione_sociale'])." Agg. ".$riga['data_aggiornamento']."\"> <i class=\"fa fa-search\"></i> </a>
					<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?external&action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."&nominativo=".$riga['ragione_sociale']."\" title=\"Scheda di stampa ".ucfirst($riga['ragione_sociale'])."\"> <i class=\"fa fa-print\"></i> </a> $elimina </td>";
					echo "</tr>"; 
				
				}

	}

	echo "</table>";
	

?>
<?php echo '<h2>Totale risultati: '.$tot_res.'</h2>'; ?> 