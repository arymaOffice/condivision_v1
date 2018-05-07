<?php

// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

$dati = GRD('fl_config',2);
$proprietario_id = check($_GET['proprietario']);
$periodo_inizio = check($_GET['periodo_inizio']);
$periodo_fine = check($_GET['periodo_fine']);

$data_documento = check($_GET['data_documento']);
$numero_documento = check($_GET['numero_documento']);
$informazioni_cliente = check($_GET['informazioni_cliente']);
require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');

    // get the HTML
    ob_start(); ?>

    <style type="text/css">
<!--
div#container {
	border: none;
	background: #FFFFFF;
	padding: 3mm;
	font-size: 3.7mm;
	text-align: center;
}
h1 {
	padding: 0;
	margin: 0;
	color: #2E89D0;
	font-size: 7mm;
}
h2 {
	padding: 0;
	margin: 0;
	color: #222222;
	font-size: 5mm;
	position: relative;
}
.small {
	font-size: 3.0mm;
	text-align: center;
	width: 100%;
}
-->

/* Table */

.dati,.dati2 { 	
	border-spacing: 2px; 
	border-collapse: separate; 
	caption-side: top; 
	width: 774px; 
	height: auto; 
	margin: 20px auto 20px auto;
	padding: 0px; 
	 
	vertical-align: middle; 
	font-size: 10px; 
	border: none;
	}
.hometab { border-spacing: 2px; 
	border-collapse: separate; 
	caption-side: top;  
	width: 45%; float: left; margin: 10px;
	height: auto; 
	margin: 20px auto 20px 30px;
	padding: 0px;
	 
	vertical-align: middle; 
	font-size: 12px;  
	border: none; }
.hometab h2 { color: rgba(65,65,65,1); }
.hometab td { vertical-align: middle;  margin: 0px; padding: 1px;  border: 1px solid #F4F4F4;}
.hometab .bgcolor { background: #F2F2F2; padding: 0px 4px; }

.dati th { background: #B3B3B3; 	font-size: 10px;  text-align: left; max-width: 100px; vertical-align: top; color: rgba(65,65,65,1); padding: 5px; margin: 0px;  }
.dati td { text-align: left;  vertical-align: top;  margin: 0px; padding: 4px 2px 4px 4px; min-height: 20px;  }
.dati tr { line-height: auto; }
.dati tr:nth-child(even) { background:  #F4F4F4; }

.dati2 .alternate td { background: #F8F8F8; }
.dati2 tr:hover,.dati2 .alternate:hover{ background: #FFCCCC; }
.dati2 td { vertical-align: middle;  margin: 0px; padding: 4px 2px 4px 4px;  border: none; color: rgba(65,65,65,1); }

</style>

<page backtop="58mm" backbottom="12mm" backleft="3mm" backright="3mm" style="font-size: smaller;"> 
 
 <page_footer> 
   <div style="margin: 10px; font-size: 9px; text-align: center;">
       <table style="width: 100%;">
            <tr>
                <td style="text-align: left; vertical-align: top;    width: 50%">
				<?php echo '<p>'.$dati['ragione_sociale'].' '.$dati['sede_legale'].' '.$dati['cap'].' '.$dati['citta'].' '.@$provincia[$anagrafica['provincia']].' - P. IVA '.$dati['p_iva_cf'].'</p>'; ?>
				</td>
                <td style="text-align: right;  vertical-align: top;   width: 50%">Pagina [[page_cu]]/[[page_nb]]</td>
            </tr>
        </table>

         
</div>
 </page_footer>

 <page_header> 




    <table class="dati"  style=" margin: 20px 0; width: 100%; border: #E4E4E4 1px solid; border-collapse: collapse" align="left">
       <tr>
         <td scope="col" width="350">
         <div style="margin: 10px;"><img src="../../fl_set/lay/logo.jpg" style="width:  100px; height: auto;" ><?php
         
		 $mittente =
		 '<h3>'.$dati['ragione_sociale'].'</h3>'
		 . '<p>'.$dati['sede_legale'].'</p>'
		 . '<p>'.$dati['cap'].' '.$dati['citta'].' '.@$provincia[$anagrafica['provincia']].' </p>'
		 . '<p>P. IVA '.$dati['p_iva_cf'].'</p>';
		 echo $mittente;
		  ?>
</div></td>
         
          <th scope="col" width="390" style="background: #ECECEC;">
<div style="margin: 10px;">
<?php 
echo "Codice Cliente: ".$proprietario_id;

$account_ref = get_anagrafica_account($proprietario_id);
@$anagrafica = get_anagrafica(@$account_ref['anagrafica']);

echo "<h4>Destinatario Fattura: </h4>";

if(isset($anagrafica['ragione_sociale'])){
$destinatario = "<p>".@$anagrafica['ragione_sociale'].'</p>
<p>Sede Legale: '.$anagrafica['sede_legale'].", ".$anagrafica['cap_sede']." ".$anagrafica['comune_sede']." (".@$provincia[$anagrafica['provincia_sede']].") </p>
<p>Punto Vendita ".$anagrafica['indirizzo_punto'].", ".$anagrafica['cap_punto']." ".$anagrafica['comune_punto']." (".@$provincia[$anagrafica['provincia_punto']].") </p>
<p>P. Iva:  ".$anagrafica['partita_iva']."</p>";
$agente = $anagrafica['profilo_genitore'];
echo $destinatario.'<p>Agente: '.$proprietario[$agente].'</p>';
} else { echo $destinatario = "<h2>Nessuna Ragione Sociale Trovata</h2>"; $agente = 0; } ?> </div> 
</th>
  </tr></table>

</page_header> 

<p>Fattura no.  <?php echo $numero_documento; ?> del <?php echo $data_documento; ?><br>
Periodo di competenza dal <?php echo mydate($periodo_inizio); ?> al <?php echo mydate($periodo_fine); ?></p>
<?php
$query = "SELECT *, COUNT(*) as tot, SUM(commissione) as tot_commissione, SUM(imponibile) as tot_imponibile FROM fl_commissioni WHERE proprietario = $proprietario_id AND status_fatturazione = 0 AND data_operazione BETWEEN '$periodo_inizio' AND '$periodo_fine' GROUP BY prodotto_ref ";
$risultato = mysql_query($query, CONNECT);
$aliquote = array();
$imponibili_iva = array();
?>
    
	 <table class="dati" summary="Dati" width="700" style="width: 778mm; border: #E4E4E4 1px solid; margin-top: 0;">
        <tr>
    
       <th width="3%" width="170" scope="col" >Servizio</th>
       <th width="5%" scope="col">Qta</th>
       <th width="7%" scope="col">Valore Facciale</th>
       <th width="5%" scope="col">Valore Compl. Trans.</th>
       <th width="35%" scope="col">Aggio U.</th>
       <th width="11%" scope="col">Aggio Tot</th>
       <th width="12%" scope="col">Sconti/Magg.</th>
       <th width="15%" scope="col">Imp. netto dovuto</th>
       <th width="6%" scope="col">C.I.</th>
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
		
			echo "<tr>"; 
			echo "<td>".$riga['prodotto_desc']."</td>";	
			//echo "<td>".mydate($riga['data_operazione'])."</td>"; 
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
			echo "<tr>"; 
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

      <table class="dati"  style=" margin: 20px 0; width: 100%; border: #E4E4E4 1px solid; border-collapse: collapse" align="center">
       <tr>
         <td scope="col" width="330">
         
            <table class="dati" style=" margin: 20px 0; width: 100%; border: #E4E4E4 1px solid; border-collapse: collapse" align="right">
       <tr>
       <th scope="col" width="330">Informazioni Pagamento</th>
       </tr>
       
       </table>
       <div style="font-size: 9px;"><?php echo converti_txt($dati['informazioni_pagamento']); ?></div>	

       </td>
         <td scope="col" width="320">
         
   <table class="dati" style=" margin: 20px 0; width: 100%; border: #E4E4E4 1px solid; border-collapse: collapse" align="right">
       <tr>
       <th scope="col" width="200">Cod. IVA</th>
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
       <td scope="col"><?php echo $chiave; ?></td>
       <td scope="col"><?php echo numdec($imponibili_iva[$chiave],2); ?></td>
       <td scope="col"><?php echo numdec($valore,2); ?></td>
       <td scope="col"><?php echo numdec($imponibili_iva[$chiave]*$valore/100,2); ?></td>
       </tr>
	  <?php } ?>
             <tr>
       <td scope="col" colspan="4"></td>
       </tr>

 
       <tr>
       <td scope="col">Totale</td>
       <td scope="col"><?php echo numdec($tot_imponibile,2); ?></td>
       <td scope="col"></td>
       <td scope="col"><?php echo numdec($tot_imposta,2); ?></td>
       </tr>

            <tr>
       <td scope="col" colspan="4"></td>
       </tr>
      <tr>
       <td scope="col">Totale Fattura</td>
        <td scope="col"><h2>&euro; <?php  $totale_fattura = $tot_imponibile+$tot_imposta; echo numdec($totale_fattura,2); ?></h2></td>
       <td scope="col"></td>
       <td scope="col"></td>
      
       </tr>


</table>
</td>

</tr></table>
<p><?php echo converti_txt($informazioni_cliente); ?></p>
</page>
    
	<?php
    $content = ob_get_clean();
    
  
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', 0);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $filename = $numero_documento."-".date('Y').'.pdf';
	    $html2pdf->Output($folder_fatture.$filename, 'F');
		
		$query = "INSERT INTO `fl_fatture` (`id`, `pagato`, `marchio`, `data_documento`, `numero_documento`, `mittente`, `proprietario`, `destinatario`, `partita_iva`, `pagamento`, `agente`, `rif_contratto`,`periodo_competenze_inizio`, `periodo_competenze_fine`,`totale_prodotti`, `imponibile`, `iva`, `totale_fattura`, `commissione`, `metodo_di_pagamento`, `informazioni_cliente`,`filename`,`data_creazione`, `data_aggiornamento`, `operatore`) 
		VALUES (NULL, '1', '0', '".convert_data($data_documento,1)."', '$numero_documento', '$mittente', $proprietario_id, '$destinatario', '".@$anagrafica['partita_iva']."', '".$dati['informazioni_pagamento']."',  $agente, 0,'".$periodo_inizio."','".$periodo_fine."', $totale, $tot_imponibile, $tot_imposta, $totale_fattura, $totale_commissioni,'6', '$informazioni_cliente','$filename', NOW(), NOW(), ".$_SESSION['number'].");";
		
		if(mysql_query($query,CONNECT)) { 
		$id_fattura = mysql_insert_id(CONNECT);
		$query = "UPDATE fl_commissioni SET status_fatturazione = 1, rif_fattura = $id_fattura WHERE proprietario = $proprietario_id AND status_fatturazione = 0 AND data_operazione BETWEEN '$periodo_inizio' AND '$periodo_fine'";
		$risultato = mysql_query($query, CONNECT);

		$esito = insert_credito($proprietario_id,$periodo_inizio,$periodo_fine,$id_fattura,$numero_documento,$data_documento,$totale_commissioni,$_SESSION['number']);
		} else { $esito = "Problema nella creazione della fattura".mysql_error(); }	
		
	
    }
    catch(HTML2PDF_exception $e) {
         $esito = "Problema nella creazione della fattura".$e;
    }
	
	header('Location: ./?action=9&esito='.$esito);
	mysql_close(CONNECT);
	exit;

?>

