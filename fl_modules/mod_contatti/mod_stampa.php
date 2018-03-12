<?php




// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');

ob_start(); 

include('fl_settings.php'); // Variabili Modulo 
$id = check($_GET['id']);
$evento = GRD($tabella,$id); 
$persona = ($evento['anagrafica_cliente'] > 1) ? GRD('fl_anagrafica',$evento['anagrafica_cliente']) : GRD($tables[106],$evento['lead_id']); 
if($evento['anagrafica_cliente2'] > 1) $persona2 = GRD('fl_anagrafica',$evento['anagrafica_cliente2']); 


$filename = "P".substr($evento['data_creazione'],2,2)."-".str_pad($evento['id'],3,0,STR_PAD_LEFT)."-".@substr($tipo_evento[$evento['tipo_evento']],0,1).'.pdf';

require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');



include(contratto_template);


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
