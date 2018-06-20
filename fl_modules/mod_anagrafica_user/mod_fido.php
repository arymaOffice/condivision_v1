<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];;
?>

<div id="filtri" class="filtri"> 
<form method="get" action="" id="fm_filtri">
   <input type="hidden" value="22" name="action" />
 Filtri    <input type="submit" value="Mostra" class="button" />
  
</form>

     
    </div>
    
    <div id="info_txt"></div>
    
         <script type="text/javascript">
function checkAllFields(ref)
{
var chkAll = document.getElementById('checkAll');
var checks = document.getElementsByName('destinatario[]');
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
var checks = document.getElementsByName('destinatario[]');
var boxLength = checks.length;
var totalChecked = 0;
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
	$('#counter').val('Scrivi a ' + totalChecked + ' mail');
}

$(function() {

    $("#scrivi").submit(function() {

        $form = $(this);

        $.fancybox({
                'title': "Invio notifica",
                'href': $form.attr("action") + "?" + $form.serialize(),
                'type': 'iframe'
        });

        return false;

    });


});

    </script>
<form action="../mod_notifiche/mod_invia.php" id="scrivi" method="get">



<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	$tipologia_main .= " AND importo_fido > 0 ";
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query.mysql_error();

 		
	?>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <th>Account</th>
  <th><a href="./?ordine=1">Ragione Sociale</a></th>
  <th>Fido Concesso</th>
  <th>Saldo</th>
  <th>Residuo</th>
  <th>Garanzie</th>
  <th>Azioni</th>
    <th></th>

</tr>
<?php 
	  // <th><input onclick="checkAllFields(1);" id="checkAll" style="display: block;"  name="checkAll" type="checkbox" checked /> </th>

	$i = 1;
	$count = 0;

	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$deleted = 0;
	$incomplete = 0;
	$status_colors = array('tab_gray','tab_orange','tab_fuxia','tab_green','tab_red');
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			$messaggio = '';
			$account = get_account($riga['id']); 
			if($account['id'] > 0)  { 
			if($riga['user'] == '') mysql_query("UPDATE fl_anagrafica SET user = '".$account['user']."', nominativo = '".$account['nominativo']."' WHERE id = ".$riga['id'],CONNECT);
			$user_check = '<a title="Modifica Account" href="../mod_account/mod_visualizza.php?id='.$account['id'].'&user='.$account['user'].'">'.$account['user'].'</a><br>'.$account['motivo_sospensione'];
			$user_ball = ($account['attivo'] == 1)  ? "<span class=\"c-green\"><i class=\"fa fa-user\"></i></span>" : "<span class=\"c-red\"><i class=\"fa fa-user\"></i></span>"; 
			$saldo = get_saldo($account['id']);
			$green = ($saldo >= 0) ?  'c-green' :  'c-red';	
			$saldo_txt = '<a data-fancybox-type="iframe" class="fancybox_view '.$green.'"  href="../mod_depositi/mod_estrattoconto.php?operatore_text='.$account['nominativo'].'&proprietario='.$account['id'].'"> &euro; '.numdec($saldo,2).'</a>';
			$data_scadenza = 'Scad.'.mydate(@$account['data_scadenza']).'<br>';
			$messaggio = '<i class="fa fa-bell" aria-hidden="true"></i>';
			} else {
			$user_check = "<a  href=\"../mod_account/mod_inserisci.php?anagrafica_id=".$riga['id']."&email=".$riga['email']."&nominativo=".$riga['ragione_sociale']."\">Attiva account</a>";
			$user_ball = '';
			$saldo = 0;
			$saldo_txt = 0;
			$data_scadenza = '';
			}
			$genitore_ball = ($riga['profilo_genitore'] > 1) ? "<span class=\"c-gray\">Father: ".$proprietario[$riga['profilo_genitore']]."</span>" : "" ;
			$colore = $status_colors[$riga['status_anagrafica']];
	
			$blocca = ($riga['concessione_fido'] == 1) ? '<a href="mod_opera.php?blocco=0&id='.$riga['id'].'" title="Revoca concessione" class="c-green"><i class="fa fa-toggle-on"></i></a>' : '<a href="mod_opera.php?blocco=1&id='.$riga['id'].'" title="Abilita concessione" class="c-gray"><i class="fa fa-toggle-off"></i></a>';	
			$bloccatxt = ($riga['concessione_fido'] == 1) ? 'Attivo' : 'Disattivo';
			$tot_res++;
			
			$saldo_tot = $saldo+$riga['importo_fido'];
			$green = ($saldo_tot >= 0) ?  'c-green' :  'c-red';	
			$saldo_tot = '<a class="fancybox_view '.$green.'" > &euro; '.numdec($saldo_tot,2).'</a>';
			$input = ($account['id'] > 0 && filter_var($riga['email'], FILTER_VALIDATE_EMAIL)) ? '<input style="display: block;"  onClick="countFields(1);" type="checkbox" name="destinatario[]" value="'.$account['id'].'" checked="checked" />' : '';
			($account['id'] > 0 && filter_var($riga['email'], FILTER_VALIDATE_EMAIL)) ? $count++ : 0;

			
			if($stato_account_id != -1 && @$account['attivo'] != $stato_account_id) { 
			$tot_res--; 
			} else {
				
				if($status_saldi_id != -1 && ( ($status_saldi_id == 1 && $saldo < 0) || ($status_saldi_id == 0 && $saldo > 0) ) ) { 
				$tot_res--; 
				} else {
					
				
				echo "<tr>"; 
					echo "<td>$user_ball ".$user_check."<br><span class=\"c-gray\">Profilo ".$riga['profilo_commissione']."</span> <br> $genitore_ball </td>"; 		
				echo "<td><span class=\"color\">".ucfirst($riga['ragione_sociale'])."</span> - ".ucfirst($riga['nome'])." ".ucfirst($riga['cognome'])."<br> P.iva ".$riga['partita_iva']."</td>";
				echo "<td>&euro; ".numdec($riga['importo_fido'],2)." ($bloccatxt)</td>"; 
				echo "<td>".$saldo_txt."</td>";
				echo "<td>".$saldo_tot."</td>";
				echo "<td>".$garanzia_fido[$riga['garanzia_fido']]."</td>"; 
				echo "<td>$blocca</td>"; 
				echo "<td>
				
				<a data-fancybox-type=\"iframe\" class=\"fancybox_view_small\" href=\"../mod_notifiche/mod_invia.php?destinatario[]=".$account['id']."\">$messaggio</a></td>"; 
				//echo "<td>$input</td>";
				echo "</tr>"; 
				}
			
			
			} 
	
	
	}

	
	echo "</table>";

$_SESSION['send'] = 1;
//echo '<p style="text-align: right; padding: 20px 0;"><input type="submit" id="counter" value="Scrivi a '.$count.' mail " class="button"></p></form>'; 


?>
<?php echo '<h2>Totale risultati: '.$tot_res.'</h2>'; ?> 