<?php

// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$id = check($_GET['id']);
$preventivo = GRD('fl_preventivi',$id); 
$persona = ($preventivo['cliente_id'] > 1) ? GRD('fl_anagrafica',$preventivo['cliente_id']) : GRD($tables[106],$preventivo['potential_id']); 
$dati = GRD('fl_config',2); // Dati del cliente che fattura

$numero_offerta = "P".substr($preventivo['data_creazione'],2,2)."-".str_pad($preventivo['id'],3,0,STR_PAD_LEFT)."-".@strtoupper(substr(trim($tipo_preventivo[$preventivo['tipo_preventivo']]),0,2));
$filename = $numero_offerta.'.pdf';

require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');

// get the HTML
ob_start(); 

include(preventivo_template);


    $content = ob_get_clean();
  	mysql_close(CONNECT);

    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'it', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
	    $html2pdf->Output($filename);

		//header('Location: ./scarica.php?show&file='.$folder.$filename);

	
	
    }
    catch(HTML2PDF_exception $e) {
         echo "Problema nella creazione del documento".$e;
    }
	

	exit;

?>
