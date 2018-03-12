<?php


// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$anno = (isset($_GET['anno'])) ? check($_GET['anno']) : date('Y');
$filename = 'calendario.pdf';
require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');

ob_start(); 
include('calendario.php');
$content = ob_get_clean();

mysql_close(CONNECT);


    try
    {
        $html2pdf = new HTML2PDF('L', 'A4', 'it', true, 'UTF-8', array(0, 0, 0, 0));
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
