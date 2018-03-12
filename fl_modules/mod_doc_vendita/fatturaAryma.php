<?php

// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

$dati = GRD('fl_config',2);

$valuta = array('&euro;');
$doc_id = base64_decode(check($_GET['did']));
$documento = GRD('fl_doc_vendita',$doc_id);
$destinato_a = pulisci($documento['ragione_sociale']);

require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');

    // get the HTML
    ob_start(); ?>
<style type="text/css">
<!--
div#container {
	border: none;
	background: #FFFFFF;
	padding: 4mm;
	color: #4D4D4D;
	font-size: 18px;
	text-align: center;
	font-family: sans-serif;
}
body { font-size: 18px; color: #494949}
h1 {
	padding: 0;
	margin: 0;
	color: #2E89D0;
}
h5 { color: #B3B3B3; }
h2 {
	padding: 0;
	margin: 0;
	color: #4D4D4D;
	position: relative;
	font-size: 14pt;
}
.small {
	text-align: center;
	width: 100%;
}
-->
/* Table */

.dati, .dati2 {
	border-spacing: 2px;
	border-collapse: separate;
	caption-side: top;
	width: 700px;
	height: auto;
	margin: 10px 0;
	padding: 0px;
	vertical-align: middle;
	border: none;
}
.hometab {
	border-spacing: 2px;
	border-collapse: separate;
	caption-side: top;
	width: 45%;
	float: left;
	margin: 10px;
	height: auto;
	margin: 20px auto 20px 30px;
	padding: 0px;
	vertical-align: middle;
	border: none;
}
.hometab h2 {
	color: rgba(65,65,65,1);
}
.hometab td {
	vertical-align: middle;
	margin: 0px;
	padding: 1px;
	border: 1px solid #F4F4F4;
}
.hometab .bgcolor {
	background: #F2F2F2;
	padding: 0px 4px;
}
.dati th {
	text-align: left;
	max-width: 100px;
	vertical-align: top;
	color: rgba(65,65,65,1);
	padding: 5px;
	margin: 0px;
	font-weight: normal;
	font-size: 9pt;
}
.dati td {
	text-align: left;
	vertical-align: top;
	margin: 0px;
	padding: 4px 0px 4px 4px;
	min-height: 40px;
	font-size: 9pt;
}
#dati td {
	border: 1px solid #E3E0E0;
}

.dati tr {
	line-height: auto;
}
.dati tr:nth-child(even) {
	background: #F4F4F4;
}
.dati2 .alternate td {
	background: #F8F8F8;
}
.dati2 tr:hover, .dati2 .alternate:hover {
	background: #FFCCCC;
}
.dati2 td {
	vertical-align: middle;
	margin: 0px;
	padding: 4px 2px 4px 4px;
	border: none;
	color: rgba(65,65,65,1);
}
.info_fattura { line-height: 160%; font-weight: normal; font-size: 9pt; }

</style>
<page backtop="80mm" backbottom="5mm" backleft="4mm" backright="4mm" style="font-style: larger; ">
<page_footer>

<div style="margin: 12px; text-align: left; font-style: italic; font-size: 10px;"><?php echo converti_txt($dati['informazioni_fattura']); ?></div>
<div style="margin: 0 10px;   text-align: center; font-size: smaller;">
  <table style="width: 100%;">
    <tr>
      <td style="text-align: left; vertical-align: top; width: 70%; font-style: italic; font-size: 9px; "><p>Documento emesso con Condivision Cloud. Se puoi, evita di stamparlo.</p></td>
      <td style="text-align: right;  vertical-align: top;   width: 30%; font-style: italic; font-size: 9px;">Pagina [[page_cu]]/[[page_nb]]</td>
    </tr>
  </table>
</div>
</page_footer>
<page_header>
  <div style=" background: #4D4D4D; color: white; padding: 5px; text-align: center;"><strong><?php echo strtoupper($tipo_doc_vendita[$documento['tipo_doc_vendita']]); ?></strong></div>
  <table class="dati"  style=" margin: 20px 0; width: 100%; border: #E4E4E4 1px solid; border-collapse: collapse; font-style: larger;" align="left">
    <tr>
      <td scope="col" width="370" valign="top">
	   <?php if(defined('LOGO_FATTURA')) { ?><img src="<?php echo LOGO_FATTURA; ?>" alt="" style="max-width: 250px;" />       <?php } ?>
       <div style="margin: 0 15px;">
          <?php
         
		 $mittente =
		 '<h5>'.$dati['ragione_sociale'].'</h5>'
		 . '<p>'.$dati['sede_legale'].' '.$dati['cap'].' '.$dati['citta'].' '.@$provincia[$anagrafica['provincia']].' </p>'
		 . '<p>P. IVA '.@$dati['partita_iva'].'</p>';
		 if($dati['codice_fiscale'] != '') $mittente .= '<p>CF '.@$dati['codice_fiscale'].'</p>';
		 echo $mittente;
		  ?>
        </div></td>
      <th scope="col" width="370" valign="top" style="background: #F9F9F9"> <div style="margin: 0 10px;">
          <h2>DOC.NO. <?php echo $documento['numero_documento']; ?> del <?php echo mydate($documento['data_documento']); ?></h2>
   <p>&nbsp;</p>        
<br><br><br>
          <?php 


echo "<h5>DESTINATARIO: </h5>";
echo "<p>Codice Cliente: #".$documento['anagrafica_id'].'</p>';

echo $destinatario = "<p>".@$documento['ragione_sociale'].'</p>
<p>Sede Legale: '.$documento['indirizzo']."</p>
<p>P. Iva:  ".$documento['partita_iva']."</p>
<p>CF:  ".$documento['codice_fiscale']."</p>";
 ?>
        </div>
      </th>
    </tr>
  </table>
</page_header>
<table class="dati" id="dati">
  <tr>
    <th  style="width: 40px; text-align: left; background: #EBEBEB; ">Codice</th>
    <th style="width: 400px">Descrizione</th>
    <th style="width: 10px">Qtà</th>
    <th style="width: 40px">Importo</th>
    <th style="width: 40px">Aliquota</th>
    <th style="width: 40px">Imposta</th>
    <th style="width: 40px">Subtotale</th>
  </tr>


  <?php 

	
	$query = "SELECT * FROM `fl_doc_vendita_voci` WHERE id > 1 AND `fattura_id` = $doc_id";
	$risultato = mysql_query($query, CONNECT);
	$empty = 0;
	$tot_imponibile = 0;
	$tot_imposta = 0;
	$totale_documenti = 0;
	$righe = 0;
	
	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 
	$tot_imponibile +=  $riga['importo']*$riga['quantita'];
	$tot_imposta += $riga['imposta'];
	$totale_documenti += $riga['subtotale'];
	$empty++;
	?>
 	<tr>
    <td style="text-align: left; background: #EBEBEB; "><?php echo $riga['codice']; ?></td>
    <td style="width: 400px;"><?php echo $riga['descrizione']; ?></td>
    <td><?php echo $riga['quantita']; ?></td>
    <td><?php echo $valuta[$riga['valuta']]; ?> <?php echo numdec($riga['importo'],2); ?></td>
    <td><?php echo numdec($riga['aliquota'],0); ?></td>
    <td><?php echo $valuta[$riga['valuta']]; ?> <?php echo numdec($riga['imposta'],2); ?></td>
    <td><?php echo $valuta[$riga['valuta']]; ?> <?php echo numdec($riga['subtotale'],2); ?></td>
 	 </tr>
  	<?php  $righe++; 
     } 
	
	while($righe < 10) {
	echo '<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>'; 
	$righe++;
	}
?>
</table>


<table>
  <tr>
    <td scope="col" valign="top" width="482"  style=" min-height: 400px;   padding: 5px 0;"><p>NOTE: <?php echo converti_txt($documento['informazioni_cliente']); ?></p></td>
    <td scope="col"  valign="top" width="250" style="   background: #4D4D4D; color: white;   padding: 5px; margin: 0 5px;  text-align: right; ">
    <table width="255" border="0">
  <tr>
    <td width="150" style="text-align: left; vertical-align: middle;">IMPONIBILE</td>
    <td width="105"  style="text-align: right; vertical-align: middle;"><h3 style="font-size: 10pt;  margin: 8px 0;"><?php  echo $valuta[0]." ".numdec($tot_imponibile,2); ?></h3></td>
  </tr>
  <tr>
    <td width="150" style="text-align: left; vertical-align: middle;">IMPOSTA</td>
    <td width="105"  style="text-align: right; vertical-align: middle;"><h3 style="font-size: 10pt;  margin: 8px 0;"><?php  echo $valuta[0]." ".numdec($tot_imposta,2); ?></h3></td>
  </tr>
  <tr>
    <td width="150" style="text-align: left; vertical-align: middle;">TOTALE DOCUMENTO</td>
    <td width="105"  style="text-align: right; vertical-align: middle;"><h2 style="font-size: 12pt;  margin: 8px 0; color: white;">  <?php  echo $valuta[0]." ".numdec($totale_documenti,2); ?></h2></td>
  </tr>
</table>

    </td>
  </tr>
</table>



<div class="info_fattura"><?php echo html_entity_decode(converti_txt($dati['informazioni_pagamento'])); ?></div>
<div style="margin: 12px; text-align: center;"><img src="http://chart.apis.google.com/chart?cht=qr&chs=100x100&chl=<?php echo ROOT.'fl_api/digiDoc.php?'.base64_encode($doc_id); ?>" alt="Dati Fattura Digitale" /><br><p style="font-size: 6px;">Fattura Digitale</p></div>

</page>
<?php
    $content = ob_get_clean();
  	mysql_close(CONNECT);

    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'it', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $filename = $documento['numero_documento']."-".$documento['data_documento'].'-'.$destinato_a.'.pdf';
	    $html2pdf->Output($folder.$filename, 'F');

		header('Location: ./scarica.php?show&folder='.$folder.'&file='.$filename);

	
	
    }
    catch(HTML2PDF_exception $e) {
         echo "Problema nella creazione del documento".$e;
    }
	

	exit;

?>
