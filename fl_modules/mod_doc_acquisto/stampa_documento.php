<?php

// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
 ob_start();


$dati = GRD('fl_config',2); // Dati del cliente che fattura
$valuta = array('&euro;');
$doc_id = base64_decode(check($_GET['did']));
$documento = GRD('fl_doc_acquisto',$doc_id); // Tutti i dati della fattura, fl_doc_vendita_voci ha i prodotti collegati con fattura_id
$destinato_a = pulisci($documento['ragione_sociale']);

$formati = array('P','L');
$docFormato = $formati[$documento['tipo_doc_acquisto']];


require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');
include(template.$documento['tipo_doc_acquisto'].'_acquisto.php');
 mysql_close(CONNECT);
//echo $content; exit;

    try
    {
        $html2pdf = new HTML2PDF($docFormato, 'A4', 'it', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $filename = strtoupper(str_replace(' ' ,'-',$tipo_doc_acquisto[$documento['tipo_doc_acquisto']])).'_'.$documento['numero_documento']."-".$documento['data_documento'].'-'.$destinato_a.'.pdf';
	    $html2pdf->Output($filename);


		//header('Location: ./scarica.php?show&folder='.$folder.'&file='.$filename);

	
	
    }
    catch(HTML2PDF_exception $e) {
         echo "Problema nella creazione del documento".$e;
    }
	

	exit;

?>
