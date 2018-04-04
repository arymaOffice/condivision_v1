
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo

//richiesta aggironamento km e posiszione
if (isset($_GET['q']) && isset($_GET['vdi'])) {

    require_once '../../fl_set/librerie/gt_alarm/index.php'; //richiesta servizio

    $errorMsg = array( //messagi d'errore
        0 => 'Aggiornato!',
        1 => 'User e/o Password non valida',
        2 => 'IdAlfanumerico non valido',
        3 => 'Formato DataInizio non valido',
        4 => 'Servizio sospeso',
        5 => 'Cliente non attivo',
        6 => 'Servizio Scaduto o cessato',
        100 => 'Errore generico',
    );

    $vdi = check($_GET['vdi']); //id univoco della macchina
    $id = check($_GET['id']); //id univoco della macchina
    $campoRichiesto = check($_GET['q']); //CAMPO CHE DEVO tornare
    $aggiorna = '';
    $valore = '';
    $esito = '';
    $richiestaDati = $macNilGps_soapClient->RequestRealTime($vdi);

    if ($richiestaDati['esito'] == 0) {

        $esito = $errorMsg[$richiestaDati['esito']];

        if ($campoRichiesto == 'km') {

            $aggiorna = 'chilometri_percorsi' . $id;
            $emplode = explode(',', $richiestaDati['chilometri_percorsi']);
            $valore = $emplode[0];

            //update campo
            $update = "UPDATE " . $tables[96] . " SET chilometri_percorsi = '$valore' WHERE vdi = '$vdi'";
            $query = mysql_query($update, CONNECT);

        } else if ($campoRichiesto == 'posizione') {

            $aggiorna = 'posizione';
            $valore = $richiestaDati['posizione'];

            //update campo
            $update = "UPDATE " . $tables[96] . " SET ultima_posizione = POINT($valore) WHERE vdi = '$vdi'";
            $query = mysql_query($update, CONNECT);
        }

    } else {

        $esito = @$errorMsg[@$richiestaDati['esito']];
    }

    echo json_encode(array('esito' => $esito, 'aggiorna' => $aggiorna, 'valore' => $valore), true);
    exit;

}

if (isset($_GET['eurotax']) && isset($_GET['targa'])) {

    require_once '../../fl_set/librerie/eurotax/index.php'; //richiesta servizio

    $targa = check($_GET['targa']); //targa per risalire alle versioni
    $id = check($_GET['id']);
    $versioni = ' <span onclick="$(\'#versioni\').css(\'display\',\'none\');" class="close" style="color: #aaaaaa;float: right;font-size: 28px;font-weight: bold;cursor:pointer">×</span> <h3>Scegli versione </h3>';
    $veicolo = $et->searchTarga($targa);
    $veicolo = json_decode($veicolo, true);

    if (isset($veicolo['telaio'])) {

        $update = "UPDATE " . $tables[96] . " SET telaio = '" . @$veicolo['telaio'] . "' WHERE id = '$id'";
        $query = mysql_query($update, CONNECT);

    }

    if(isset($veicolo['versioni'])){

        foreach (@$veicolo['versioni'] as $versione) { // Scorro le versioni e prendo la prima (o dividiamo il servizio e facciamo selezionare anche la versione da salvare in "allestimento")
            $query = http_build_query(array('myArray' => $versione));
            $url = urlencode($query);
            $versioni .= '<p style="cursor:pointer;"><a href="mod_opera.php?id=' . $id . '&eurotax&versione=' . $url . '"  class="ajaxLoad">' . $versione['versione'] . ' ' . $versione['codice_eurotax'] . '</a></p>';
        }

    }


    if (count(@$veicolo['versioni']) == 0) {
        $versioni .= '<p>Nessuna versione rilevata</p>';
    }

    echo json_encode(array('esito' => '<script>$(".modal-content").empty();</script> Scegli versione', 'aggiorna' => 'versione', 'valore' => $versioni));
    exit;

} //fine eutoax e targa

if (isset($_GET['eurotax']) && isset($_GET['versione'])) {

    require_once '../../fl_set/librerie/eurotax/index.php'; //richiesta servizio
    $aggiorna = '';
    $valore = '';
    $esito = '';
    parse_str($_GET['versione']);
    $codice_eurotax = check($myArray['codice_eurotax']);
    $motornet = check($myArray['codice_motornet']);
    $id = check($_GET['id']); //id nel db della macchina

    $select = "SELECT chilometri_percorsi,anno_immatricolazione FROM " . $tables[96] . " WHERE id = $id";
    $query = @mysql_query($select, CONNECT);
    $query = mysql_fetch_assoc($query);
    $km = ($query['chilometri_percorsi'] == '') ? 0 : $query['chilometri_percorsi'];
    $anno = $query['anno_immatricolazione'];

    if (isset($codice_eurotax) && isset($motornet) && isset($anno) && isset($km)) {

        //2 Recupero la quotazione con codice eurotax, anno, km, e motornet
        $valutazione = $et->getValutazione("auto", $anno, 12, $km, $motornet, $codice_eurotax, array(), $codiceOmologazione = "", $targa = "", $telaio = "", $costoHManMec = "", $costoHManCarr = "", $autocarro = 1, array(), $lavoryCarr = array(), $valutazioneDealer = "", $guidKey = "", $annoValutazione = "", $meseValutazione = "");
        $result = json_decode($valutazione, true);
        $valore = $result['valutazione']["quotazione_eurotax_giallo_km"];

        if (is_numeric($valore)) { //controlla se la risposta è nd
            //update campo
            $update = "UPDATE " . $tables[96] . " SET quotazione_attuale = '$valore', data_quotazione  = NOW() WHERE id = '$id'";
            $query = mysql_query($update, CONNECT);
            $esito = "Quotazione aggiornata : € " . $valore;

        } else {
            $update = "UPDATE " . $tables[96] . " SET quotazione_attuale = '0' WHERE id = '$id'";
            $query = mysql_query($update, CONNECT);
            $esito = "Quotazione rilevata N.D.";
        }
        $aggiorna = 'quotazione';

    } else {
        $aggiorna = 'niente';
        $valore = '';
        $esito = 'Quotazione non aggiornata';
    }

    echo json_encode(array('esito' => $esito, 'aggiorna' => $aggiorna, 'valore' => $valore, 'id' => $id));
    exit;

}

mysql_close(CONNECT);
//header("location: ".$_SERVER['HTTP_REFERER']);
exit;
?>

