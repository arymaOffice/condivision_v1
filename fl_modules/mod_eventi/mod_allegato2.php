<?php

// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
if(!isset($_GET['evento_id'])) die('Manca evento ID');

$evento_id = check($_GET['evento_id']);

$docName = 'ALLEGATO CONTRATTO';
$azienda = GRD('fl_config',2);
$evento = GRD($tabella,$evento_id);
$location = GRD('fl_sedi',$evento['location_evento']); // Dati del cliente che fattura
$citta_location = $location['citta'];
$menu = GQD('fl_menu_portate','id,descrizione_menu','evento_id = '.$evento_id);
$ricorrenza = GQD('fl_ricorrenze_matrimonio','*','evento_id = '.$evento_id);
$menuId = $menu['id'];
$persona = ($evento['anagrafica_cliente'] > 1) ? GRD('fl_anagrafica',$evento['anagrafica_cliente']) : GRD($tables[106],$evento['lead_id']); 
if($evento['anagrafica_cliente2'] > 1) $persona2 = GRD('fl_anagrafica',$evento['anagrafica_cliente2']); 

$filename = 'allegato-'.$evento_id.'.pdf';

require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');


// get the HTML
ob_start(); 

include(allegato_allestimenti); 

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
