<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
//if(!isset($_GET['associa_evento'])) $_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
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
			echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
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
			
			

			if((select_type($chiave) == 2 || select_type($chiave) == 19) && $chiave != 'id') {
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


<p><a href="?<?php echo $_SERVER['QUERY_STRING']; ?>&action=25">Ricerca Avanzata</a></p>
<input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>
 
 <?php if(isset($_GET['associa_evento'])) echo '<h2>Inserisci una o più anagrafiche e poi <a class="button" style=" padding: 10px; background: red; color: white; " href="../mod_eventi/mod_inserisci.php?id='.check($_GET['associa_evento']).'&t='.base64_encode(1).'">torna all\'evento</a></h2>'; ?>   
    
<script type="text/javascript">

/*Avvio*/
$(document).ready(function() {
	
$('.sede_operativa').click(function () {
$(".info_sede_legale").hide();
$(".info_sede_operativa").show();
$(".sede_legale").css('font-weight','normal');
$(".sede_operativa").css('font-weight','bold');
});

$('.sede_legale').click(function () {
$(".info_sede_legale").show();
$(".info_sede_operativa").hide();
$(".sede_legale").css('font-weight','bold');
$(".sede_operativa").css('font-weight','normal');

});

});

</script>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
    $query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	echo mysql_error();

 		
	?>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <?php if(ATTIVA_ACCOUNT_ANAGRAFICA == 1) { ?><th class="noprint"><a href="./?ordine=2&<?php echo $_SERVER['QUERY_STRING']; ?>">Account</a></th><?php } ?>
  <th  class="desktop"><a href="./?ordine=3&<?php echo $_SERVER['QUERY_STRING']; ?>">Ragione Sociale</a></th>
  
  <th class="desktop"><a href="#" class="sede_legale">Sede Legale</a><?php if(!defined('ANAGRAFICA_SEMPLICE')){ ?>/<a href="#" class="sede_operativa">Sede Operativa</a><?php } ?> </th>
  <th>Contatti</th>
  <?php if(ESTRATTO_CONTO_IN_ANAGRAFICA == 1) { ?><th>Saldo</th><?php } ?>
  <?php if(ALERT_DOCUMENTO_SCADUTO == 1) { ?><th></th><?php } ?>
  <th class="noprint"><a href="./?ordine=0&<?php echo $_SERVER['QUERY_STRING']; ?>">Recenti</a> | <a href="./?ordine=1&<?php echo $_SERVER['QUERY_STRING']; ?>">Meno recenti</a></th>
 
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
			
			if(ATTIVA_ACCOUNT_ANAGRAFICA == 1){
			$account = get_account($riga['id']);
			if(!isset($_GET['cerca']) && $stato_account_id != -1 && (isset($account['attivo'] ) && @$account['attivo'] != $stato_account_id))   $show = 0;
			if(!isset($_GET['cerca']) && $tipo_account_id != -1 && (isset($account['tipo'] ) && @$account['tipo'] != $tipo_account_id))   $show = 0;
			//if(!isset($_GET['cerca']) && $tipo_account_id == -1 && (isset($account['tipo'] ) && @$account['tipo'] < 2))   $show = 0;
			} 
			
			/*if($riga['email'] != ''){

				$query = "INSERT INTO `fl_account` (`attivo`, `anagrafica`, `tipo`, `email`, `nominativo`, `user`, `password`, `data_creazione`, `data_scadenza`, `data_aggiornamento`, `operatore`, `aggiornamento_password`) 
				VALUES ('1', '".$riga['id']."', 3,  '".$riga['email']."',  '".$riga['ragione_sociale']."', '".$riga['email']."', '6e656502f31d70a7f0dfbca2fdc5dc6a', NOW(),'2100-31-12',NOW(),1,NOW());";
				mysql_query($query);
			}*/
				
				
			if(ATTIVA_ACCOUNT_ANAGRAFICA == 1 && @ $account['id'] > 0 && $show == 1)  { 
			$user_check = '<a data-fancybox-type="iframe" title="Modifica Account" class="fancybox" href="../mod_account/mod_visualizza.php?external&id='.$account['id'].'">'.$account['user'].'</a><br>'.$account['motivo_sospensione'];
			$user_ball = ($account['attivo'] == 1)  ? "<span class=\"c-green\"><i class=\"fa fa-user\"></i></span>" : "<span class=\"c-red\"><i class=\"fa fa-user\"></i></span>"; 
			$saldo = balance($account['id']);
			$saldo = '<a data-fancybox-type="iframe" class="fancybox_view"  href="../mod_depositi/mod_user.php?operatore_text='.$account['nominativo'].'&operatore='.$account['id'].'"> &euro; '.numdec($saldo,2).'</a>';
			$tipo_profilo_label = $tipo[$account['tipo']];
			if(isset($riga['account']) && @ $riga['account'] != $account['user']) mysql_query("UPDATE $tabella SET account = '".$account['user']."' WHERE id = ".$riga['id']." LIMIT 1");
			$notifica_icon = '<a data-fancybox-type="iframe" title="Invia Notifica Account" class="fancybox_view_small" href="../mod_notifiche/mod_invia.php?destinatario[]='.@$account['id'].'"><i class="fa fa-bell" aria-hidden="true"></i></a>';
			} else {
			$user_check = "<a href=\"../mod_account/mod_inserisci.php?external&anagrafica_id=".$riga['id']."&email=".$riga['email']."&nominativo=".$riga['ragione_sociale']."\">Crea account</a>";
			$user_ball = '';
			$saldo = 0;
			$tipo_profilo_label = '';
			$notifica_icon = '';
			}
			if(!defined('TIPO_DA_ACCOUNT') || TIPO_DA_ACCOUNT == 0) $tipo_profilo_label = $tipo_profilo[$riga['tipo_profilo']];
			
			
			if($show==1) {
			
			
			if(isset($account['attivo']) && $account['attivo'] == 1) { 
				$colore = "b_green";  
			} else if(isset($account['attivo']) &&  $account['attivo'] == 0) { 
				$colore = "b_red"; 
			} else { 
				$colore = "b_orange"; 
			}

			$elimina = (defined('ELIMINA_ANAGRAFICA')) ? "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>" : '';
		    (@$riga['data_scadenza'] < date('Y-m-d')) ? $note = "<span title=\"Documento Scaduto\" class=\"c-red\"><i class=\"fa fa-exclamation-triangle fa-lg\"></i></span>" : $note = "<i class=\"fa fa-exclamation-triangle fa-lg\"></i>";
			$concessione = (defined('AFFILIAZIONI') && isset($riga['numero_concessione']))  ? $riga['id'].".".$riga['numero_concessione'] : '';
			$tot_res++;
					
						
					$nominativo = ($riga['ragione_sociale'] != '') ? ucfirst(checkValue($riga['ragione_sociale'])) : ucfirst(checkValue(@$riga['nome'])).' '.ucfirst(checkValue(@$riga['cognome']));		
					$sede_punto = (!isset($riga['comune_punto'])) ? '' : $riga['comune_punto']." (".@$riga['provincia_punto'].") ".$riga['cap_punto']."<br>".$riga['indirizzo_punto'];
					echo '<tr>';
					
					if(ATTIVA_ACCOUNT_ANAGRAFICA == 1)  echo "<td class=\"desktop $colore\">$user_ball ".$user_check."</td>"; 		
					echo "<td><a href=\"mod_panoramica_contatto.php?id=".$riga['id']."\"><span class=\"color\"><strong>".$riga['id']."</strong> $nominativo</span><br>P. iva ".$riga['partita_iva'].'<br>';
					if(defined('MULTI_BRAND'))  echo "<span class=\"msg blue\">".$marchio[$riga['marchio']]."</span> ";
					echo " <span class=\"msg orange\">".$tipo_profilo_label." $concessione </span></a></td>";
					echo "
					<td class=\"desktop info_sede_legale\">".$riga['comune_sede']." (".@$riga['provincia_sede'].") ".$riga['cap_sede']."<br>".$riga['sede_legale']."</td>
					<td class=\"desktop info_sede_operativa\" >".$sede_punto."</td>"; 
					echo "<td class=\"desktop\"><i class=\"fa fa-envelope-o\"></i> <a href=\"mailto:".checkEmail($riga['email'])."\">".checkEmail($riga['email'])."</a>
					<br><i class=\"fa fa-phone\" style=\"padding: 5px 10px;\"></i>".phone_format($riga['telefono'])." - ".phone_format($riga['cellulare'])."</td>"; 
					if(ESTRATTO_CONTO_IN_ANAGRAFICA == 1)   echo "<td  class=\"hideMobile\">".$saldo."</td>";
					if(ALERT_DOCUMENTO_SCADUTO == 1)  echo "<td  class=\"hideMobile\">$note</td>";
					echo "<td  class=\"strumenti\">";
					if(@PROFILO_ANAGRAFICA == 1)  echo '<a href="mod_inserisci.php?external&action=1&tBiD='.base64_encode('39').'&id='.$riga['id'].'"><i class="fa fa-user"></i>'.get_scan($riga['id']).'</a>';
					
					if(@PANORAMICA_ANAGRAFICA == 1)  { 
					echo '<a href="mod_panoramica_contatto.php?id='.$riga['id'].'"><i class="fa fa-television" aria-hidden="true"></i></a>'; 
					} else {
					echo "<a href=\"mod_inserisci.php?id=".$riga['id']."&nominativo=".$riga['ragione_sociale']."\" title=\"Gestione Cliente ".ucfirst($riga['ragione_sociale'])." Agg. ".$riga['data_aggiornamento']."\"> <i class=\"fa fa-search\"></i> </a>
					<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?external&action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."&nominativo=".$riga['ragione_sociale']."\" title=\"Scheda di stampa ".ucfirst($riga['ragione_sociale'])."\"> <i class=\"fa fa-print\"></i> </a>"; 
					}
					
					echo "$notifica_icon  $elimina </td>";
					echo "</tr>"; 
				
				}

	}

	echo "</table>";
	

?>
<?php echo '<h2>Totale risultati: '.$tot_res.'</h2>'; ?> 