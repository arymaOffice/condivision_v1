<?php
/*
 * modulo di esportazione in excel i leads presenti nella vista dal quale lo richiamano
 *    con i filtri della stessa
 *
 *   in mod_home.php tasto che la richiama
 */

require_once '../../fl_core/autentication.php';
include 'fl_settings.php';

$tipologia_main = str_replace('WHERE', '', $tipologia_main);
$tipologia_main = str_replace('tb1', 'pot', $tipologia_main);

//	$query = "SELECT $select FROM $tabella $tipologia_main ORDER BY $ordine LIMIT $start,$step;";


//prendi i leads con query filtrata
$leads = GQS('fl_potentials as pot LEFT JOIN fl_campagne_attivita ca ON ca.id = pot.campagna_id LEFT JOIN fl_veicoli v ON v.parent_id = pot.id ', '*,pot.id as poteID,ca.oggetto as caog', $tipologia_main);

$dati = array();

$data = date("d/m/Y");
$ora = date(" H:i");


foreach ($leads as $key => $value) {
    /*

    $value['status_potential'] = $status_potential[$value['status_potential']]; //Valori da array per alcuni campi
    $value['source_potential'] = $source_potential[$value['source_potential']]; //Valori da array per alcuni campi
    $value['campagna_id'] = $campagna_id[$value['campagna_id']]; //Valori da array per alcuni campi

    $dati[$value['id']] = array('id' => $value['id'], 'stato' => $value['status_potential'], 'sorgente' => $value['campagna_id'], 'attivita' => $value['source_potential'], 'nome' => ucfirst(strtolower($value['nome'])), 'cognome' => ucfirst(strtolower($value['cognome'])), 'citta' => $value['citta'], 'cellulare' => $value['telefono'], 'email' => strtolower($value['email']));*/

    $dati[$value['poteID']] = array('data' => $data, 'ora' => $ora,
        'vettura interesse' => $value['modello_interesse'],
        'id' => $value['poteID'],
        'nome' => ucfirst(strtolower($value['nome'])),
        'cognome' => ucfirst(strtolower($value['cognome'])),
        'indirizzo' => $value['indirizzo'], 
        'email' => strtolower($value['email']),
        'telefono' => strtolower($value['telefono']),
        'professione' => $value['job_title'],
        'marca' => $value['marca'],
        'modello' => $value['modello'],
        'anno' => $value['anno_immatricolazione'],
        'km' => $value['chilometri_percorsi'],
        'alimentazione' => $value['alimentazione'],
        'targa' => $value['targa'],
        'note' => $value['note'],
        'oggetto' => $value['caog'],
        'test_drive' => $value['test_drive'],
        'partita_iva' => $value['partita_iva'],
        'societa' => $value['ragione_sociale'],
        '(priorità) status' => $value['priorita_contatto'],
        'permuta' => $value['permuta'],
        'pagamento_vettura' => $value['pagamento_vettura']);

}
$campi = array('data', 'ora', 'vettura interesse', 'id', 'nome', 'cognome', 'indirizzo', 'email', 'telefono', 'professione', 'marca', 'modello', 'anno', 'km', 'alimentazione', 'targa', 'note', '(oggetto) attività campagna', 'test drive', 'p.iva', 'societa', '(priorità) status', 'permuta', 'pagamento');
mysql_close(CONNECT);

/* nuova implementazione*/
$name = 'aaa';
$return = 'source';

toSpreadSheet($name, $campi, $dati, $return);
