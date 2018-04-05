<?php
/*
* modulo di esportazione in excel i leads presenti nella vista dal quale lo richiamano
*    con i filtri della stessa
*
*   in mod_home.php tasto che la richiama
*/

require_once '../../fl_core/autentication.php';
include 'fl_settings.php';

//prendi i leads con query filtrata
$leads = GQS($tabella, '*', str_replace('WHERE','',$tipologia_main));
$dati = array();

foreach ($leads as $key => $value) {
    /*

    $value['status_potential'] = $status_potential[$value['status_potential']]; //Valori da array per alcuni campi
    $value['source_potential'] = $source_potential[$value['source_potential']]; //Valori da array per alcuni campi
    $value['campagna_id'] = $campagna_id[$value['campagna_id']]; //Valori da array per alcuni campi


    $dati[$value['id']] = array('id' => $value['id'], 'stato' => $value['status_potential'], 'sorgente' => $value['campagna_id'], 'attivita' => $value['source_potential'], 'nome' => ucfirst(strtolower($value['nome'])), 'cognome' => ucfirst(strtolower($value['cognome'])), 'citta' => $value['citta'], 'cellulare' => $value['telefono'], 'email' => strtolower($value['email']));*/

    $dati[$value['id']] = array('id' => $value['id'], 'nome' => ucfirst(strtolower($value['nome'])), 'cognome' => ucfirst(strtolower($value['cognome'])), 'citta' => $value['citta'], 'cellulare' => $value['telefono'], 'email' => strtolower($value['email']));

}
$campi = array('id', 'nome', 'cognome', 'citta', 'cellulare', 'email');
mysql_close(CONNECT);

/* nuova implementazione*/
$name = 'aaa';
$return = 'source';


toSpreadSheet($name,$campi,$dati,$return);