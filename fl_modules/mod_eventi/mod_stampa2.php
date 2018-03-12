<?php

// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');

ob_start(); 

include('fl_settings.php'); // Variabili Modulo 
$id = check($_GET['id']);
$evento = GRD($tabella,$id); 
$persona = ($evento['anagrafica_cliente'] > 1) ? GRD('fl_anagrafica',$evento['anagrafica_cliente']) : GRD($tables[106],$evento['lead_id']); 

if($evento['anagrafica_cliente2'] > 1){
    $persona2 = GRD('fl_anagrafica',$evento['anagrafica_cliente2']); 
}

$filename = "P".substr($evento['data_creazione'],2,2)."-".str_pad($evento['id'],3,0,STR_PAD_LEFT)."-".@substr($tipo_evento[$evento['tipo_evento']],0,1).'.pdf';

require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');

include(contratto_template2);

    $content = ob_get_clean();
  	mysql_close(CONNECT);
    
    /*********************************************************************************************/
    /*                                                                                           */
    /*                              MODIFICHE SANTE 29/03/2017                                   */
    /*                                                                                           */
    /*********************************************************************************************/
    //---------------------Labels persona e persona2
    $personaLbls = array(
                            "/{{nome}}/",
                            "/{{cognome}}/",
                            "/{{numero_documento}}/",
                            "/{{telefono}}/",
                            "/{{email}}/",
                            "/{{comune_residenza}}/",
                            "/{{provincia_residenza}}/"
                            );

    //------------------------Labels intestazioni persona2
    $persona2IntestLbl = array(
                                    "/{{Cliente:}}/",
                                    "/{{Doc. Identità no.:}}/",
                                    "/{{Tel:}}/",
                                    "/{{Email:}}/",
                                    "/{{Città:}}/"
                                );
    
    //------------------------ Labels evento
    $eventoLbls = array(
                            "/{{data_evento}}/",
                            "/{{tipo_evento}}/",
                            "/{{numero_adulti}}/",
                            "/{{location_evento}}/",
                            "/{{condizioni_aggiuntive}}/",
                            "/{{contratto}}/"
                        );
    
    //------------------------ campi persona
    $persona1Campi = array(
                            $persona['nome'],
                            $persona['cognome'],
                            $persona['numero_documento'],
                            phone_format($persona['telefono'],'39'),
                            $persona['emai'],
                            $persona['comune_residenza'],
                            "(" . $persona['provincia_residenza'] . ")"
                            );

    //-----------------------intestazioni persona2 
    $persona2IntestCampi = array(
                                    "Cliente:",
                                    "Doc. Identità no.:",
                                    "Tel:",
                                    "Email:",
                                    "Città:",
                                );
    
    //----------------------- campi persona2
    $persona2Campi = array(
                            $persona2['nome'],
                            $persona2['cognome'],
                            $persona2['numero_documento'],
                            phone_format($persona2['telefono'],'39'),
                            $persona2['emai'],
                            $persona2['comune_residenza'],
                            $persona2['provincia_residenza']
                            );

    //----------------------- campi evento
    $tipo_contratto = "C".substr($evento['data_creazione'],2,2)."-".str_pad($evento['id'],3,0,STR_PAD_LEFT)."-".@substr($tipo_evento[$evento['tipo_evento']],0,1);//stringa contenente il tipo di contratto(nel footer)
    
    $eventoCampi = array(
                             mydate($evento['data_evento']),
                            $tipo_evento[$evento['tipo_evento']],
                            $evento['numero_adulti'],
                            $location_evento[$evento['location_evento']],
                            $evento['condizioni_aggiuntive'],
                            $tipo_contratto
                        );

    $content = preg_replace($personaLbls, $persona1Campi, $content, 1);                     //sostituisco le labels con i campi della persona, effettuo dolo 1 sostituzione, perchè poi eseguo l'altra per la persona2

    if(count($persona2) > 0){                                                               //se persona2 ha elementi allora faccio la sostituzione delle labels
        $content = preg_replace($persona2IntestLbl, $persona2IntestCampi, $content, 1);     //stampo le intestazioni per la persona2
        $content = preg_replace($personaLbls, $persona2Campi, $content, 1);
    }else{
        $content = preg_replace($persona2IntestLbl, "", $content, 1);                       //stampo stringhe vuote per le intestazioni della persona 2
        $content = preg_replace($personaLbls, "", $content, 1);                             //se la persona2 non c'è sostituisco i label con stringhe vuote altrimenti resterebbero i label visibili
    }

    $content = preg_replace($eventoLbls, $eventoCampi, $content);                           //sostituisco i label per l0evento 

    /*--------------------------------------------- FINE MODIFICHE SANTE ----------------------------------*/

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
