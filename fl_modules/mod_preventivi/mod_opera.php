<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

if(isset($_POST['tipo_richiesta'])) {

$workflow_id = 71;
$tipo_richiesta = check($_POST['tipo_richiesta']);
$note = (check($_POST['interlocutore']) != '') ? '[Interlocutore: '.check($_POST['interlocutore']).'] - '.check($_POST['note']) : check($_POST['note']);
$anagrafca_id = (isset($_POST['anagrafica_id'])) ? check($_POST['anagrafica_id']) : 0;
$data_scadenza = convert_data(check($_POST['data_scadenza']),1);
$parent_id = check($_POST['parent_id']);


$query = "INSERT INTO `fl_richieste` (`id`, `marchio`,`workflow_id`,`parent_id`, `anagrafica_rel`, `tipo_richiesta`, `data_apertura`, `data_chiusura`, `data_scadenza`, `note`, `operatore`, `data_creazione`, `data_aggiornamento`) 
VALUES (NULL, '0','$workflow_id', '$parent_id', '$anagrafca_id', '$tipo_richiesta', NOW(), '0000-00-00', '$data_scadenza', '$note', '".$_SESSION['number']."', NOW(), NOW());";
mysql_query($query,CONNECT);
}

if(isset($_POST['status_preventivo'])) {
$status = check($_POST['status_preventivo']);
$query = "UPDATE `$tabella` SET `status_preventivo` = '$status', `operatore` =  '".$_SESSION['number']."' WHERE id = '$parent_id';";
mysql_query($query,CONNECT);
}



if(isset($_GET['id'])){


	$id = check($_GET['id']);
	$email = check($_GET['email']);
	$oggetto = check($_GET['oggetto']);
	$messaggio = converti_txt(check($_GET['messaggio']));
	$preventivo = GRD('fl_preventivi',$id); 

	$filename = "P".substr($preventivo['data_creazione'],2,2)."-".str_pad($preventivo['id'],3,0,STR_PAD_LEFT)."-".@substr(trim($tipo_preventivo[$preventivo['tipo_preventivo']]),0,2).'.pdf';

	require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');

	// get the HTML
	ob_start(); 
	include(preventivo_template);
    $content = ob_get_clean();

    try
    {

        $html2pdf = new HTML2PDF('P', 'A4', 'it', true, 'UTF-8', 0);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
		$content_PDF = $html2pdf->Output('','S'); 
		
		$mail_txt = str_replace("[*CORPO*]",$messaggio,mail_template);

		$esito = smail($email,$oggetto,$mail_txt,'','',$content_PDF,$filename);
		smail(mail_admin,$email.' INVIATA  '.$esito.' OBJ: '.$oggetto,$mail_txt,'','',$content_PDF,$filename);

		mysql_close(CONNECT);
		header("location: ./mod_send.php?customer_rel=0&esito=Invio Eseguito");
		exit;

    }
    catch(HTML2PDF_exception $e) {
       smail('michelefazio@aryma.it','Errore invio offerta su '.ROOT.$e);
       mysql_close(CONNECT);
		header("location: ./mod_send.php?customer_rel=0&esito=Errore: ".$e);
		exit;
    }

mysql_close(CONNECT);
exit;

}



mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER'].'&esito=Operazione Registrata!'); 
exit;

?>  
