<?php

// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo
include("../../fl_inc/headers.php");
require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');

ob_start(); 
require_once('note_date.php');

mysql_close(CONNECT);
 echo  $content = ob_get_clean();
exit;
try
{
  $html2pdf = new HTML2PDF('P', 'A4', 'it', true, 'UTF-8', array(0, 0, 0, 0));
  $html2pdf->pdf->SetDisplayMode('fullpage');
  $html2pdf->setDefaultFont('freesans');
  $html2pdf->writeHTML($content);
  $html2pdf->Output($filename);

  //header('Location: ./scarica.php?show&file='.$folder.$filename);



}
catch(HTML2PDF_exception $e) {
  echo "Problema nella creazione del documento".$e;
}


exit;

?>
