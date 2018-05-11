<?php 


require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 



if(isset($_GET['pagato'])){
	
	$id = check($_GET['pagato']);
	
	$query = "UPDATE $tabella SET data_aggiornamento = '".date('Y-m-d H:i')."', pagato = 1, operatore = '".$_SESSION['number']."' WHERE id = '$id';";
	
	mysql_query($query,CONNECT);
	
	}


if(isset($_POST['doc_acquisto_id'])) {

	$workflow_id = 1;
	$doc_acquisto_id = check($_POST['doc_acquisto_id']);
	$valuta = check($_POST['valuta']);
	$unita_di_misura = check($_POST['unita_di_misura']);
	$codice = check($_POST['codice']);
	$descrizione = (isset($_POST['descrizione'])) ? check($_POST['descrizione']) : 0;
	$quantita = check($_POST['quantita']);
	$imponibile = check(str_replace(',','.',$_POST['importo']));
	$aliquota = check(str_replace('.00','',$_POST['aliquota']));


	inserisci_voci_acquisto($doc_acquisto_id,$codice,$descrizione,$imponibile,$quantita,$valuta,$aliquota,$unita_di_misura);

}


if(isset($_POST['creaOrdiniFornitore'])){


	$_SESSION['fancyalertDone'] = NULL;
	$primo_fornitore = NULL;
	/* varibili in post 
		$_POST['id'] array con tutti gli id dei prodotti da ordinare
		$_POST[fornitore+ 'id'] fornitore per la materia
		$_POST[note+ 'id'] note per il fornitore
		$_POST[qta+ 'id'] quantità da ordinare della materia
	*/

	$ordini = 0;
	foreach($_POST['id'] as $key ) {

		if($_POST['fornitore'.$key] != $primo_fornitore ){
			//se sto lavorando su un fornitore diverso emetto ordine differente
			$primo_fornitore = $anagrafica_id = filter_var($_POST['fornitore'.$key],FILTER_SANITIZE_NUMBER_INT); 
			$oggetto = filter_var($_POST['oggetto'],FILTER_SANITIZE_STRING);

			$numero_doc = GQD('fl_doc_acquisto','(numero_documento + 1) as n_doc',' id > 1 AND `tipo_doc_acquisto` = 4 AND YEAR(data_documento) = '.date('Y').' ORDER BY numero_documento DESC LIMIT 1');

			$numero_doc['n_doc'] = ($numero_doc['n_doc'] == '') ? 1 : $numero_doc['n_doc'];

			$fornitore = GRD('fl_anagrafica',$anagrafica_id);
			//inserimento docuemnto di acquisto
			$insert_doc_acq = "INSERT INTO `fl_doc_acquisto` ( `anno_di_competenza`, `tipo_doc_acquisto`,`centro_di_costo`, `anagrafica_id`, `ragione_sociale`,`indirizzo`,`partita_iva`,`codice_fiscale`,`data_documento`, `numero_documento`, `oggetto_documento`,`data_creazione`, `data_aggiornamento`, `operatore`) 
			VALUES (NOW(),4,'".$fornitore['centro_di_costo']."','".$anagrafica_id."','".$fornitore['ragione_sociale']."','".$fornitore['indirizzo']."','".$fornitore['partita_iva']."','".$fornitore['codice_fiscale']."',NOW(),'".$numero_doc['n_doc']."' ,'".$oggetto."',NOW(),NOW(),'".$_SESSION['number']."');";
			$insert_doc_acq = mysql_query($insert_doc_acq,CONNECT);

			
			if(($parent_id = mysql_insert_id(CONNECT)) < 1){
				$parent_id = NULL;
			} else { $ordini++; }
		}

		$info_materia = GQD('fl_materieprime','`codice_articolo`, `descrizione`, `unita_di_misura`,ultimo_prezzo,valore_di_conversione','id = "'.$key.'"');

		$quantita = filter_var($_POST['qta'.$key],FILTER_SANITIZE_STRING);
		$note = filter_var($_POST['note'.$key],FILTER_SANITIZE_STRING);
		$costoPezzo = ($info_materia['ultimo_prezzo']/$info_materia['valore_di_conversione']);
		$importo = ($costoPezzo*$quantita);

		$insert_doc_voci = "INSERT INTO `fl_doc_acquisto_voci`( `parent_id`,  `codice`, `descrizione`,`note`, `unita_di_misura`, `quantita`, `valuta`, `importo`,`subtotale`, `data_creazione`, `operatore`) VALUES ('".$parent_id."','".$info_materia['codice_articolo']."','".$info_materia['descrizione']."','".$note."','".$info_materia['unita_di_misura']."','".$quantita."','EUR','".$costoPezzo."','".$importo."',NOW(),'".$_SESSION['number']."')";

		$insert_doc_voci = mysql_query($insert_doc_voci,CONNECT);
		
		$fabbisogno_id = filter_var($_POST['fabbisogni'.$key],FILTER_SANITIZE_NUMBER_INT);

		$update = "UPDATE fl_ricettario_fabbisogno set ordine_id = ".$parent_id." WHERE id IN ( ".$fabbisogno_id.")30";
		$update = mysql_query($update,CONNECT);
		
		
	}//fine foreach

	header("location: ./?tipo_doc_acquisto=4&ordiniCreati=".$ordini);
	mysql_close(CONNECT);
	exit;

}

mysql_close(CONNECT);
header("location: ".check($_SERVER['HTTP_REFERER']));
exit;

					
?>  
