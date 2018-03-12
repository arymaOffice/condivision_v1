<?php

// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
if(!isset($_GET['evento_id'])) die('Manca evento ID');

$evento_id = check($_GET['evento_id']);

$docName = 'ALLEGATO 1';
$azienda = GRD('fl_config',2);
$evento = GRD($tabella,$evento_id);
$menu = GQD('fl_menu_portate','id,descrizione_menu','evento_id = '.$evento_id);
$ricorrenza = GQD('fl_ricorrenze_matrimonio','*','evento_id = '.$evento_id);
$menuId = $menu['id'];

$filename = 'allegato-'.$evento_id.'.pdf';

require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');


// get the HTML
ob_start(); 

include(allegato1);


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

