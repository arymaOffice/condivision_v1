<?php

// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo
if(!isset($_GET['evento_id'])) die('Manca evento ID');

$evento_id = check($_GET['evento_id']);

$evento = GRD($tabella,$evento_id);
$menu = GQD('fl_menu_portate','id,descrizione_menu','evento_id = '.$evento_id);
$ricorrenza = GQD('fl_ricorrenze_matrimonio','*','evento_id = '.$evento_id);
$revisioni = GQD('fl_revisioni_hrc',"*",'evento_id = '.$evento_id." ORDER BY data_creazione DESC LIMIT 1");
$totale = mk_count('fl_revisioni_hrc','evento_id = '.$evento_id);
$ultima_revisione = ($revisioni['id'] > 1) ? 'Rev. n° '.$totale.' del '.mydatetime($revisioni['data_creazione']).' '.strip_tags(converti_txt($revisioni['note'])) : 'Nessuna Revisione';


$menuId = $menu['id'];
$prodotto_id = $data_set->data_retriever($tables[22],'label');

$filename = 'ordine-'.$evento_id.'.pdf';

require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');


// get the HTML
ob_start();

include(ordine_di_servizio);


    $content = ob_get_clean();
  	mysql_close(CONNECT);

    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'it', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->setDefaultFont('freesans');
	    $html2pdf->Output($filename);

		//header('Location: ./scarica.php?show&file='.$folder.$filename);



    }
    catch(HTML2PDF_exception $e) {
         echo "Problema nella creazione del documento".$e;
    }


	exit;

?>
