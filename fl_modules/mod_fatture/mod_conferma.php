<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['last_referrer'] = ROOT.$_SERVER['PHP_SELF'];
$dati = GRD('fl_config',2);
$periodo_inizio = convert_data(check($_GET['periodo_inizio']),1);
$periodo_fine = convert_data(check($_GET['periodo_fine']),1);
?>


<form method="get" action="fattura.php" id="conferma">

<div style="float: left; width: 30%; background: #EBEBEB; padding: 20px; margin: 0 10px 0 0; ">
<?php 

$account_ref = get_anagrafica_account($proprietario_id);
@$anagrafica = get_anagrafica(@$account_ref['anagrafica']);
echo "<h1>Destinatario Fattura: <h1>";

if(isset($anagrafica['ragione_sociale'])){
echo "<h2>".@$anagrafica['ragione_sociale'].'<br>'.$anagrafica['sede_legale'].", ".$anagrafica['cap_sede']." ".$anagrafica['comune_sede']." (".$provincia[$anagrafica['provincia_sede']].") <br>P. Iva:  ".$anagrafica['partita_iva']."</h2>";
echo '<p>Agente: '.$proprietario[$anagrafica['profilo_genitore']].'</p>';
} else { echo "<h2>Nessuna Ragione Sociale Trovata</h2>"; } ?>
<br>

<div class=""><p class="input_text">
<label for="data_documento">Data</label>
<input type="text" name="data_documento" id="data_documento" value="<?php echo date('d/m/Y'); ?>" size="30" class="calendar" /></p></div>

<div class=""><p class="input_text"><label for="numero_documento">Numero </label>
<input   class="" type="text" name="numero_documento" id="numero_documento"  value="<?php echo get_next_fattura(); ?>"  /></p></div>

<h3>Informazioni in fattura</h3>
<textarea name="informazioni_cliente"></textarea>
<input type="hidden" name="set" value="1">
<input type="hidden" name="proprietario" value="<?php echo check($proprietario_id); ?>">
<input type="hidden" name="periodo_inizio" value="<?php echo $periodo_inizio; ?>">
<input type="hidden" name="periodo_fine" value="<?php echo $periodo_fine; ?>">

<br><br><a href="#" onclick="$('#conferma').submit();" class="button"> Crea Fattura </a>

</div>

<div style="float: left; width: 60%;">
<h1>Riepilogo Importi non fatturati</h1>
<p>Periodo di fatturazione dal <?php echo check($_GET['periodo_inizio']); ?> al <?php echo check($_GET['periodo_fine']); ?></p>
<?php
$query = "SELECT *, COUNT(*) as tot, SUM(commissione) as tot_commissione, SUM(imponibile) as tot_imponibile FROM fl_commissioni WHERE proprietario = $proprietario_id AND status_fatturazione = 0 AND data_operazione BETWEEN '$periodo_inizio' AND '$periodo_fine' GROUP BY prodotto_ref ";
$risultato = mysql_query($query, CONNECT);
$aliquote = array();
$imponibili_iva = array();
?>
    
	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
  <th style="width: 1%;"></th>
       <th scope="col">Servizio</th>
       <th scope="col">Qta</th>
       <th scope="col">Valore Facciale</th>
       <th scope="col">Valore Compl. Transazione</th>
       <th scope="col">Aggio U.</th>
       <th scope="col">Aggio Tot.</th>
       <th scope="col">Sconti/Magg.</th>
       <th scope="col">Importo netto dovuto</th>
       <th scope="col">C.I.</th>
       </tr>
	<?php 
	
	$i = 1;
	$totale = 0;
	$totale_valore = 0;
	$totale_commissioni = 0;
	$totale_dovuto = 0;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Importo in questo periodo</td></tr>";		}
	$tot_res = 0;
	$incomplete = 0;
	while ($riga = @mysql_fetch_array($risultato)) 
	{
			
			if(!isset($aliquote[$riga['codice_iva']])) {  $aliquote[$riga['codice_iva']] = $riga['aliquota_iva']; }
			if(!isset($imponibili_iva[$riga['codice_iva']])) { $imponibili_iva[$riga['codice_iva']] = 0; }
			if($riga['status_fatturazione'] == 0){
			$colore = "style=\" background: #FF3A17; color: #FFF;\"";
			} else if($riga['status_fatturazione'] == 1){
			$colore = "style=\" background: #009900; color: #FFF;\"";
			} else {
			$colore = "class=\"gray\"";
			}
			
			$importo_netto_dovuto = $riga['tot_imponibile']-$riga['tot_commissione'];
			$totale += $riga['tot'];
			
			$totale_valore += $riga['tot_imponibile'];
			$totale_commissioni += $riga['tot_commissione'];
			$totale_dovuto += $riga['tot_imponibile']-$riga['tot_commissione'];
			$imponibili_iva[$riga['codice_iva']] += $importo_netto_dovuto;

		
			echo "<tr><td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td>".$riga['prodotto_desc']."</td>";	
			echo "<td>".$riga['tot']."</td>";	
			echo "<td> ".numdec($riga['imponibile'],2)."</td>"; 
			echo "<td> ".numdec($riga['tot_imponibile'],2)."</td>"; 
			echo "<td> ".numdec($riga['commissione'],3)."</td>"; 
			echo "<td> ".numdec($riga['tot_commissione'],3)."</td>"; 
			echo "<td> </td>"; 
			echo "<td> ".numdec($importo_netto_dovuto,2)."</td>"; 
			echo "<td> ".$riga['codice_iva']."</td>"; 

		    echo "</tr>";
			
			
			
	}
			echo "<tr><td></td>"; 
			echo "<td>TOTALE</td>";	
			echo "<td>".$totale."</td>";	
			echo "<td> </td>"; 
			echo "<td> ".numdec($totale_valore,2)."</td>"; 
			echo "<td> </td>"; 
			echo "<td> ".numdec($totale_commissioni,3)."</td>"; 
			echo "<td> </td>"; 
			echo "<td> ".numdec($totale_dovuto,2)."</td>"; 
			echo "<td> </td>"; 

		    echo "</tr>";

	
?></table>

<div style="font-size: 9px;"><?php echo converti_txt($dati['informazioni_fattura']); ?></div>

<table class="dati" summary="Dati" style=" width: 100%;">
       <tr>
       <th scope="col">Cod. IVA</th>
       <th scope="col">Imponibile IVA</th>
       <th scope="col">% IVA</th>
       <th scope="col">Imposta EURO</th>
       </tr>
       <?php 
	   $tot_imponibile = 0;
	   $tot_imposta = 0;
	   
	   foreach($aliquote as $chiave => $valore) { 
	   $tot_imponibile += $imponibili_iva[$chiave];
	   $tot_imposta += $imponibili_iva[$chiave]*$valore/100;
	   ?>
       <tr>
       <th scope="col"><?php echo $chiave; ?></th>
       <th scope="col"><?php echo numdec($imponibili_iva[$chiave],2); ?></th>
       <th scope="col"><?php echo numdec($valore,2); ?></th>
       <th scope="col"><?php echo numdec($imponibili_iva[$chiave]*$valore/100,2); ?></th>
       </tr>
	  <?php } ?>
             <tr>
       <th scope="col" colspan="4"></th>
       </tr>

 
       <tr>
       <th scope="col">Totale</th>
       <th scope="col"><?php echo numdec($tot_imponibile,2); ?></th>
       <th scope="col"></th>
       <th scope="col"><?php echo numdec($tot_imposta,2); ?></th>
       </tr>

            <tr>
       <th scope="col" colspan="4"></th>
       </tr>
      <tr>
       <th scope="col">Totale Fattura</th>
       <th scope="col"></th>
       <th scope="col"></th>
       <th scope="col"><h2>&euro; <?php echo numdec($tot_imponibile+$tot_imposta,2); ?></h2></th>
       </tr>


</table>

</div>
</form>

