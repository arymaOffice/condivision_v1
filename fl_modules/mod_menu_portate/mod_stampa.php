<?php

// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
if(!isset($_GET['menuId'])) die('Manca Menu ID');

$menuId = check($_GET['menuId']);
$filename = 'menu-'.$menuId.'.pdf';

require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');


// get the HTML
ob_start(); 


include(menu_ricorrenze);


    $content = ob_get_clean();
  	mysql_close(CONNECT);


    if(isset($_GET['preview']))  { 

        echo '<style type="text/css">page_footer,page_header { display: none; }</style>';
        echo $content; exit; 

    }
    

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

