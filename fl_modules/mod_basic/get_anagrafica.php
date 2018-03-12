<?php
@require_once('../../fl_core/autentication.php');
if(!strstr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])) { echo "Non si accettano richieste da remoto"; exit; }

$anagrafica_id = check($_POST['id']); //cliente
$info = GRD('fl_anagrafica',$anagrafica_id); //ritorna valori 

$fill = 0;// inizzializzo se non trovo il cliente

$riga_datornare = array();

if($info['id'] != 0){

	$fill = 1;//cliente trovato

	//basandomi sul tipo di azienza o privato decido cosa ritornare
	if($info['forma_giuridica'] != 3){
		//quindi un azienda
		$riga_datornare['indirizzo'] = $info['sede_legale'].' '.$info['cap_sede'].' '.$info['comune_sede'].' '.$info['frazione_sede'];
		$riga_datornare['codice_fiscale'] = $info['codice_fiscale_legale'];
		$riga_datornare['partita_iva'] = $info['partita_iva'];
		$riga_datornare['ragione_sociale'] = $info['ragione_sociale'];



	}else{
		//quindi un privato
		$riga_datornare['indirizzo'] = $info['indirizzo'].' '.$info['cap'].' '.$info['citta'];
		$riga_datornare['codice_fiscale'] = $info['codice_fiscale'];
		$riga_datornare['partita_iva'] = $info['codice_fiscale'];
		$riga_datornare['ragione_sociale'] = $info['cognome'].' '.$info['nome'];

	}

	echo json_encode($riga_datornare);

}



if($fill == 0) echo json_encode(array('ragione_sociale'=>"Nessun cliente trovato"));

mysql_close(CONNECT);
exit;

?>