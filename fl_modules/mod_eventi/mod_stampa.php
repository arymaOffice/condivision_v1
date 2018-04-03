<?php




// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');

ob_start(); 

include('fl_settings.php'); // Variabili Modulo 
$id = check($_GET['id']);
$evento = GRD($tabella,$id); 
$acconto = $versamento_caparra = '';

$documento_vendita = GQD('fl_doc_vendita','*',' workflow_id = 6 AND ref_id = '.$evento['id']);
if($documento_vendita['id'] > 1){
$totale = GQD('fl_doc_vendita_voci','id,SUM(`subtotale`) AS totale','  `codice` = \'ACC\' AND `fattura_id` = '.$documento_vendita['id']);
$tipoDoc =  ($documento_vendita['tipo_doc_vendita'] == 0) ? 'Fattura ' : 'Ricevuta ';
if($totale['totale'] > 0) $acconto = 'Versamento Acconto: '.$tipoDoc.' no. '.$documento_vendita['numero_documento'].' del '.mydate($documento_vendita['data_documento']).' - Importo: EURO '.numdec($totale['totale'],2);
}

if(defined('GESTIONE_CAPARRA')) {
$registro_caparre = GQS('fl_registro_caparre','*','movimento_caparra = 0 AND evento_id = '.$id);
    foreach ($registro_caparre as $key => $value) {
        $versamento_caparra  = $value['importo'];
    }
}

$id_contratto = (isset($_GET['contratto'])) ? check($_GET['contratto']) : 11;
$dati = GRD('fl_config',2); // Dati del cliente che fattura
$contratto = GRD('fl_modelli',$id_contratto); // Dati del cliente che fattura
$location = GRD('fl_sedi',$evento['location_evento']); // Dati del cliente che fattura
$citta_location = $location['citta'];
$prezzo_base = $evento['prezzo_base'];
$prezzo_bambini = $evento['prezzo_bambini'];
$prezzo_serali = $evento['prezzo_serali'];
$prezzo_operatori = $evento['prezzo_operatori'];
$costo_siae = $evento['costo_siae'];

$persona = ($evento['anagrafica_cliente'] > 1) ? GRD('fl_anagrafica',$evento['anagrafica_cliente']) : GRD($tables[106],$evento['lead_id']); 

if($evento['anagrafica_cliente2'] > 1) $persona2 = GRD('fl_anagrafica',$evento['anagrafica_cliente2']); 


$filename = "C".substr($evento['data_creazione'],2,2)."-".str_pad($evento['id'],3,0,STR_PAD_LEFT)."-".@trim(strtoupper(substr($tipo_evento[$evento['tipo_evento']],0,4))).'.pdf';

require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');



if(!isset($_GET['modello'])) { include(contratto_template); } else  {  include(contratto_template2);  }


    $content = ob_get_clean();
  	mysql_close(CONNECT);

    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'it', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->setDefaultFont('freesans');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
	    $html2pdf->Output($filename);

		//header('Location: ./scarica.php?show&file='.$folder.$filename);

	
	
    }
    catch(HTML2PDF_exception $e) {
         echo "Problema nella creazione del documento".$e;
    }
	

	exit;

?>
