<?php

// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
 ob_start();
$dati = GRD('fl_config',2);

$valuta = array('&euro;');
$doc_id = base64_decode(check($_GET['did']));
$documento = GRD('fl_doc_vendita',$doc_id);
$destinato_a = pulisci($documento['ragione_sociale']);

require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');

    // get the HTML
    ?>
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
h2 {
	padding: 0;
	margin: 0;

	position: relative;
	font-size: 15pt
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

#mittente p { font-size:  8pt; line-height:  2pt; margin: 5px 0; color: #999; }
</style>
<page backtop="68mm" backbottom="5mm" backleft="4mm" backright="4mm" style="font-style: larger; ">










<page_footer>


<div style="margin: 12px; text-align: left; font-style: italic; font-size: 10px;"><?php echo html_entity_decode(converti_txt($dati['informazioni_pagamento'])); ?></div>
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


  <table class="dati"  style=" margin: 20px 0; width: 100%; border: #E4E4E4 1px solid; border-collapse: collapse; font-style: larger;" align="left">
    <tr>
      <td scope="col" width="390" valign="top">
	   <?php if(defined('LOGO_FATTURA')) { ?><img src="<?php echo LOGO_FATTURA; ?>" alt="" style="max-width: 250px;" />       <?php } ?>
      

       <div style="margin: 0 15px;" id="mittente">
          <?php
         
		 $mittente =
		 '<h5>'.$dati['ragione_sociale'].'</h5>'
		 . '<p>'.$dati['sede_legale'].' '.$dati['cap'].' '.$dati['citta'].' '.@$provincia[$anagrafica['provincia']].' </p>'
		 . '<p>P. IVA '.@$dati['partita_iva'].'</p>';
		 if($dati['codice_fiscale'] != '') $mittente .= '<p>CF '.@$dati['codice_fiscale'].'</p>';
		 if($dati['telefono'] != '') $mittente .= '<p>'.@$dati['telefono'].'</p>';
		 if($dati['riferimenti_web'] != '') $mittente .= '<p>'.@$dati['riferimenti_web'].'</p>';
		 
		 echo $mittente;
		  ?>

        </div></td>
      <th scope="col" width="360" valign="top" style="background: #F9F9F9"> 

      <div style="margin: 90px 10px;">
       

   <p>&nbsp;</p>        
<br><br><br>
          <?php 


echo $destinatario = "<p>Spett. le: </p><p><strong>".@$documento['ragione_sociale'].'</strong></p>
<p>Sede Legale: '.$documento['indirizzo']."</p>
<p>P. Iva:  ".$documento['partita_iva']."</p>
<p>CF:  ".$documento['codice_fiscale']."</p>";
 ?>
        </div>
      </th>
    </tr>
  </table>
</page_header>




<h4 style="margin: 54px 0 10px 0;"><?php echo strtoupper($tipo_doc_vendita[$documento['tipo_doc_vendita']]); ?> <?php echo $documento['numero_documento']; ?> del <?php echo mydate($documento['data_documento']); ?></h4>


<h2 style="font-size: 10pt;  margin: 10px 0;">Descrizione</h2>
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
 	
<div style=" margin-bottom:  10px auto; padding: 0px; width: 680px;  ">

    <p style="padding-right: 50px;"><?php echo  html_entity_decode(converti_txt($riga['descrizione'])); ?></p> 
</div>



<div style="text-align: right; background: #ccc; margin: 10px auto; padding: 3px 10px 6px 10px ; width: 748px;">
	Totale voce <?php echo $valuta[$riga['valuta']]; ?> <?php echo numdec($riga['importo'],2); ?>
</div>

 	

 	
 	
  	<?php  $righe++; 
     } 
	
	while($righe < 10) {
	$righe++;
	}
?>



<table>
  <tr>
    <td scope="col" valign="top" width="482"  style=" min-height: 400px;   padding: 5px 0;">
   </td>
    <td scope="col"  valign="top" width="250" style="     padding: 5px; margin: 0 5px;  text-align: right; ">
    <table width="255" border="0">
  <tr>
    <td width="150" style="text-align: left; vertical-align: middle;">Imponibile</td>
    <td width="105"  style="text-align: right; vertical-align: middle;"><h2 style="font-size: 10pt;  margin: 8px 0;"><?php  echo $valuta[0]." ".numdec($tot_imponibile,2); ?></h2></td>
  </tr>
  <tr>
    <td width="150" style="text-align: left; vertical-align: middle;">Iva 22%</td>
    <td width="105"  style="text-align: right; vertical-align: middle;"><h2 style="font-size: 10pt;  margin: 8px 0;"><?php  echo $valuta[0]." ".numdec($tot_imposta,2); ?></h2></td>
  </tr>
  <tr>
    <td width="150" style="text-align: left; vertical-align: middle;">TOTALE</td>
    <td width="105"  style="text-align: right; vertical-align: middle;"><h2 style="font-size: 10pt;  margin: 8px 0; ">  <?php  echo $valuta[0]." ".numdec($totale_documenti,2); ?></h2></td>
  </tr>
</table>

    </td>
  </tr>
</table>

<h2 style="font-size: 10pt;  margin: 8px 0;"><?php echo html_entity_decode(converti_txt($documento['informazioni_cliente'])); ?></h2>
<h2 style="font-size: 10pt;  margin: 8px 0;"><?php echo html_entity_decode(converti_txt($dati['informazioni_fattura'])); ?></h2>


</page>



<?php
    $content = ob_get_clean();
  	mysql_close(CONNECT);

    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'it', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $filename = strtoupper(str_replace(' ' ,'-',$tipo_doc_vendita[$documento['tipo_doc_vendita']])).'_'.$documento['numero_documento']."-".$documento['data_documento'].'-'.$destinato_a.'.pdf';
	    $html2pdf->Output($folder.$filename, 'F');

		header('Location: ./scarica.php?show&folder='.$folder.'&file='.$filename);

	
	
    }
    catch(HTML2PDF_exception $e) {
         echo "Problema nella creazione del documento".$e;
    }
	

	exit;

?>
