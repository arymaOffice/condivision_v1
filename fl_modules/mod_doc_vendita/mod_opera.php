<?php

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo




if(isset($_GET['pagato'])){

	$id = check($_GET['pagato']);

	$query = "UPDATE $tabella SET data_aggiornamento = '".date('Y-m-d H:i')."', pagato = 1, operatore = '".$_SESSION['number']."' WHERE id = '$id';";

	mysql_query($query,CONNECT);
}


if(isset($_GET['converti'])){

	$id = check($_GET['converti']);
	$nextNumber = get_next_number($_SESSION['anno_fiscale'],$defaultDocVendita);

	$query = "UPDATE $tabella SET data_aggiornamento = '".date('Y-m-d H:i')."', tipo_doc_vendita = $defaultDocVendita,numero_documento = $nextNumber, operatore = '".$_SESSION['number']."' WHERE id = '$id';";

	mysql_query($query,CONNECT);

}




if(isset($_POST['fattura_id'])) {

	$workflow_id = 1;
	$fattura_id = check($_POST['fattura_id']);
	$valuta = check($_POST['valuta']);
	$codice = check($_POST['codice']);
	$descrizione = (isset($_POST['descrizione'])) ? check($_POST['descrizione']) : 0;
	$quantita = check($_POST['quantita']);
	$importo = check(str_replace(',','.',$_POST['importo']));
	$aliquota = check(str_replace('.00','',$_POST['aliquota']));
	$subtotale = $importo*$quantita;


	

	if(defined('importi_lordi')){

		$new = '1.'.$aliquota;
		$totaleTemporaneo = round_up($subtotale/$new,2);
		$imposta = $subtotale - $totaleTemporaneo;
		$importo =  $totaleTemporaneo/$quantita;
		$subtotale = ($importo*$quantita) + $imposta;
		$importo = $totaleTemporaneo;

	}else{
		$new = '0.'.$aliquota;
		$imposta = $subtotale*$new;
		$subtotale += $imposta;
		$importo = $subtotale /$quantita;
		
		
	}



	/*
	$impostaCU = (defined('importi_lordi')) ?  ($importo*$aliquota)/(100+$aliquota) : ($importo*$aliquota)/100;
	if(defined('importi_lordi')) $importo = $importo-$impostaCU;


	$imposta = (defined('importi_lordi')) ?  ($totaleImponibile*$aliquota)/(100+$aliquota) : ($totaleImponibile*$aliquota)/100;
	$subtotale =(defined('importi_lordi')) ?   $totaleImponibile :   $totaleImponibile+$imposta;
	*/


	$query = "INSERT INTO `fl_doc_vendita_voci` (`id`, `fattura_id`, `codice`, `descrizione`, `quantita`, `valuta`,`importo`, `aliquota`, `imposta`, `subtotale`, `operatore`)
	VALUES (NULL, '$fattura_id', '$codice', '$descrizione', '$quantita','$valuta', '$importo', '$aliquota', '$imposta', '$subtotale', '".$_SESSION['number']."');";
	mysql_query($query,CONNECT);

}



if(isset($_GET['crea'])) {

	$workflow_id = check($_GET['crea']);//id tabella riferimento(evento)
	$tipo_doc_vendita = check($_GET['tipo_doc_vendita']);
	$refId = check($_GET['refId']);//id eventpo
	$anagraficaId = check($_GET['anagraficaId']);//cliente

	$nextNumber = get_next_number(date('Y'),$tipo_doc_vendita);//numero di documento successivo
	$evento = GRD($tables[$workflow_id],$refId);//info evento
	$categoria_doc_venditaID = $evento['tipo_evento'];
	$centro_di_ricavoID = $evento['centro_di_ricavo'];
	$oggetto = @$centro_di_ricavo[$evento['tipo_evento']].' '.$evento['titolo_ricorrenza'];

	/*Definizione dati del cliente / azienda */
	$cliente = GRD('fl_anagrafica',$anagraficaId);
	//forma giuridica 3 privato /altro soggetti giuridici
	$ragione_sociale = ($cliente['forma_giuridica'] != 3) ?  $cliente['ragione_sociale'] : $cliente['nome'].' '.$cliente['cognome'];
	$indirizzo = ($cliente['forma_giuridica'] != 3) ? $cliente['sede_legale'].' '.$cliente['comune_sede'].' '.$cliente['provincia_sede'].' '.$cliente['cap_sede'] : $cliente['indirizzo'].' '.$cliente['citta'].' '.$cliente['provincia'].' '.$cliente['cap'];
	$partita_iva = ($cliente['forma_giuridica'] != 3) ? $cliente['partita_iva'] : $cliente['codice_fiscale'];
	$codice_fiscale = ($cliente['forma_giuridica'] != 3) ? $cliente['codice_fiscale_legale'] : $cliente['codice_fiscale'] ;

	$creaFattura = "INSERT INTO `fl_doc_vendita` (`workflow_id`,`ref_id`, `proprietario`, `anno_di_competenza`, `tipo_doc_vendita`, `anagrafica_id`, `ragione_sociale`, `indirizzo`, `paese`, `partita_iva`, `codice_fiscale`, `data_documento`, `numero_documento`,  `oggetto_documento`, `categoria_doc_vendita`, `centro_di_ricavo`, `data_creazione`, `data_aggiornamento`, `operatore`)
	VALUES ('$workflow_id','$refId',".$_SESSION['number'].", ".date('Y').", $tipo_doc_vendita , $anagraficaId, '".$ragione_sociale."', '$indirizzo', '100000100', '$partita_iva', '$codice_fiscale', NOW(), '$nextNumber', '$oggetto', '$categoria_doc_venditaID', '$centro_di_ricavoID', NOW(), NOW(), ".$_SESSION['number'].");";

	if(mysql_query($creaFattura))  {

		$fattura_id = mysql_insert_id();

		// SE INVIO IMPORTO MI GENERA ACCONTO
		if(isset($_GET['importo'])){

			$codice = 'ACC';
			$descrizione = check($_GET['descrizione']);
			$quantita = 1;
			$importo = check($_GET['importo']);
			inserisci_voci_vendita($fattura_id,$codice,$descrizione,$importo,$quantita);
			mysql_close(CONNECT);
			header('Location: ./mod_inserisci.php?id='.$fattura_id);
			exit;

		}

		$extra = GQS('fl_registro_cassa','*',' conto_id = '.$refId);
		$totaleExtra = 0;

		foreach ($extra as $chiave => $valore) { 

			$codice = $valore['rif_id'];
			$descrizione = $valore['descrizione'];
			$quantita = $valore['quantita'];
			$importo = $valore['importo'];
			$aliquota = 22;
			$totaleExtra += $importo*$quantita;
		}



		if($evento['numero_adulti'] > 0) {
		
			$codice = 1;
			$descrizione = "Menù Adulto";
			$quantita = $evento['numero_adulti'];
			$importo = $evento['prezzo_base'];
			if(isset($_GET['accorpa_voci'])) $importo += $totaleExtra/$quantita;
			inserisci_voci_vendita($fattura_id,$codice,$descrizione,$importo,$quantita);

		}

		if($evento['numero_bambini'] > 0) {
			$codice = 2;
			$descrizione = "Menù Bambino";
			$quantita = $evento['numero_bambini'];
			$importo = $evento['prezzo_bambini'];
			inserisci_voci_vendita($fattura_id,$codice,$descrizione,$importo,$quantita);
		}


		if($evento['numero_operatori'] > 0) {
			$codice = 3;
			$prezzo_operatori = $evento['prezzo_operatori'];
			$descrizione = "Menù Operatore";
			$quantita = $evento['numero_operatori'];
			$importo = $prezzo_operatori;
			inserisci_voci_vendita($fattura_id,$codice,$descrizione,$importo,$quantita);
		}

		if($evento['numero_serali'] > 0) {
			$codice = 4;
			$descrizione = "Ospiti Serali/Taglio Torta";
			$quantita = $evento['numero_serali'];
			$importo = $evento['prezzo_serali'];
			inserisci_voci_vendita($fattura_id,$codice,$descrizione,$importo,$quantita);
		}

		if($evento['costi_siae'] > 0) {
			$codice = 4;
			$descrizione = "Anticipo costo SIAE";
			$quantita = 1;
			$importo = $evento['costi_siae'];
			inserisci_voci_vendita($fattura_id,$codice,$descrizione,$importo,$quantita);

		}

		if(!isset($_GET['accorpa_voci'])) {
			foreach ($extra as $chiave => $valore) { 

			$codice = $valore['rif_id'];
			$descrizione = $valore['descrizione'];
			$quantita = $valore['quantita'];
			$importo = $valore['importo'];
			$aliquota = 22;
			inserisci_voci_vendita($fattura_id,$codice,$descrizione,$importo,$quantita,0,$aliquota);
			}
		}

		/* VECCHIO CALCOLO EXTRA 

		$extra = GQS('fl_synapsy','*', "valore > 0 AND type2 = 23 AND type1 = $workflow_id AND id1 = $refId");

		// Inserisco Extra
		foreach ($extra as $chiave => $valore) {

			if($valore['qty'] < 1) $valore['qty'] = 1;
			$itemInfo = GRD($tables[$valore['type2']],$valore['id2']); // Prendo info elemento da tabella di riferimento
			//var_dump($itemInfo); //imparo cosa sta in questa tabella
			if($itemInfo['codice'] == 0) $itemInfo['codice'] = 'CV'.$itemInfo['id'];


			$codice = $itemInfo['codice'];
			$descrizione = $itemInfo['label'];
			$quantita = $valore['qty'];
			$importo = $valore['valore'];
			$aliquota = aliquota_default;
			$totaleImponibile = $importo*$quantita;
			$impostaCU = (defined('importi_lordi')) ?  ($importo*$aliquota)/(100+$aliquota) : ($importo*$aliquota)/100;
			if(defined('importi_lordi')) $importo = $importo-$impostaCU;
			$imposta = (defined('importi_lordi')) ?  ($totaleImponibile*$aliquota)/(100+$aliquota) : ($totaleImponibile*$aliquota)/100;
			$subtotale =(defined('importi_lordi')) ?   $totaleImponibile :  $totaleImponibile+$imposta;
			$valuta = 0;

			$query = "INSERT INTO `fl_doc_vendita_voci` (`id`, `fattura_id`, `codice`, `descrizione`, `quantita`, `valuta`,`importo`, `aliquota`, `imposta`, `subtotale`, `operatore`)
			VALUES (NULL, '$fattura_id', '$codice', '$descrizione', '$quantita','$valuta', '$importo', '$aliquota', '$imposta', '$subtotale', '".$_SESSION['number']."');";
			mysql_query($query,CONNECT);

		}//fine extra
		*/

		$documenti_acconto = GQS('fl_doc_vendita','*',' ref_id = '.$refId.' AND workflow_id = '.$workflow_id);
		foreach($documenti_acconto  as $key => $documento_vendita) {
	
		if($documento_vendita['id'] > 1){

			$acconti = GQD('fl_doc_vendita_voci','id,SUM(`subtotale`) AS totale','  `codice` = \'ACC\' AND `fattura_id` = '.$documento_vendita['id']);
			$tipoDoc =  ($documento_vendita['tipo_doc_vendita'] == 0) ? 'Fattura ' : 'Ricevuta ';

			if($acconti['totale'] > 0){

				$codice = 'DETACC';
				$descrizione = 'ACCONTO '.$tipoDoc.' no. '.$documento_vendita['numero_documento'].' del '.mydate($documento_vendita['data_documento']);
				$quantita = 1;
				$importo = '-'.$acconti['totale'];
				inserisci_voci_vendita($fattura_id,$codice,$descrizione,$importo,$quantita);

		}}				  
		}	
		


	}

	mysql_close(CONNECT);
	header('Location: ./mod_inserisci.php?id='.$fattura_id);
	exit;


}


if(isset($_GET['send'])){


	$doc_id = check($_GET['id']);
	$email = check($_GET['email']);
	$oggetto = check($_GET['oggetto']);
	$messaggio = converti_txt(check($_GET['messaggio']));
	$documento = GRD('fl_doc_vendita',$doc_id);
	$dati = GRD('fl_config',2); // Dati del cliente che fattura

	$destinato_a = pulisci($documento['ragione_sociale']);
	$filename = $documento['numero_documento']."-".$documento['data_documento'].'-'.$destinato_a.'.pdf';

	require_once('../../fl_set/librerie/html2pdf/html2pdf.class.php');

	// get the HTML
	include('../../fl_config/calderonimartini.condivision.biz/template/'.$documento['tipo_doc_vendita'].'_vendita.php');

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
		header("location: ./mod_send.php?esito=Invio Eseguito");
		exit;

	}
	catch(HTML2PDF_exception $e) {
		smail('michelefazio@aryma.it','Errore invio offerta su '.ROOT.$e);
		mysql_close(CONNECT);
		header("location: ./mod_send.php?esito=Errore: ".$e);
		exit;
	}

	mysql_close(CONNECT);
	exit;

}



mysql_close(CONNECT);
header("location: ".check($_SERVER['HTTP_REFERER'],1));
exit;


?>
