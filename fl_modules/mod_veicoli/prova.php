<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


// require_once '../../fl_core/autentication.php';
// include 'fl_settings.php'; // Variabili Modulo

// require_once '../../fl_set/librerie/eurotax/index.php'; //richiesta servizio
// //function getVersioni($tipoVeicolo, $modello="", $marca="", $anno="", $codiceCostruttore="", $porte="", $passo="", $libro="")
// $a = $et->getVersioni('AUTO','4C','Alfa Romeo','2015');


// print_r($a);


/*
dati in get dei dati basandosi sulla targa aggiorna la quotazione dell'auot al primo valore ritonrato 
su vari e versioni (testato come minore )
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo

require_once '../../fl_set/librerie/eurotax/index.php'; //richiesta servizio

$a = $et->getDettaglioAuto($_GET['mt'], $_GET['eurotax']);
echo'<pre>';
print_r(json_decode($a, true));
echo'</pre>';

exit;




if (isset($_GET['eurotax']) && isset($_GET['targa'])) {

    require_once '../../fl_set/librerie/eurotax/index.php'; //richiesta servizio

    $targa = check($_GET['targa']); //targa per risalire alle versioni
    $id = check($_GET['id']);

    if($targa == '' || $id == '' ){
        echo json_encode(array('esito' => 'dati non validi', 'aggiorna' => 'niente', 'valore' => 0 , 'id' => ''));
        exit;
    } 


    $veicolo = $et->searchTarga($targa);
    $veicolo = json_decode($veicolo, true);
    $codice_eurotax = '';
    $codice_motornet = '';

    $select = "SELECT chilometri_percorsi,anno_immatricolazione FROM " . $tables[96] . " WHERE id = $id";
    $query = @mysql_query($select, CONNECT);
    $query = mysql_fetch_assoc($query);
    $km = ($query['chilometri_percorsi'] == '') ? 0 : $query['chilometri_percorsi'];
    $anno = ($query['anno_immatricolazione'] == '') ? 0000 : $query['anno_immatricolazione'];

    if (isset($veicolo['versioni'])) {

            $valutazione = $et->getValutazione("auto", $anno, 12, $km, $veicolo['versioni'][0]['codice_motornet'], $veicolo['versioni'][0]['codice_eurotax'], array(), $codiceOmologazione = "", $targa = "", $telaio = "", $costoHManMec = "", $costoHManCarr = "", $autocarro = 1, array(), $lavoryCarr = array(), $valutazioneDealer = "", $guidKey = "", $annoValutazione = "", $meseValutazione = "");
            $result = json_decode($valutazione, true);

            echo '<pre>';

            print_r($result);

            echo '</pre>';
            


            $valute= $result['valutazione']["quotazione_eurotax_giallo_km"];
            $codice_eurotax = $veicolo['versioni'][0]['codice_eurotax'];
            $codice_motornet = $veicolo['versioni'][0]['codice_motornet'] ;
    }


    if (is_numeric($valute)) { //controlla se la risposta è nd
        //update campo
        $update = "UPDATE " . $tables[96] . " SET quotazione_attuale = '$valute', data_quotazione  = NOW(), codice_eurotax = '$codice_eurotax' , codice_motornet = '$codice_motornet'  WHERE id = '$id'";
        $query = mysql_query($update, CONNECT);
        $esito = "Quotazione aggiornata : € " . $valute;

    } else {
        $update = "UPDATE " . $tables[96] . " SET quotazione_attuale = '0' ,codice_eurotax = '$codice_eurotax' , codice_motornet = '$codice_motornet' , data_quotazione  = NOW() WHERE id = '$id'";
        $query = mysql_query($update, CONNECT);
        $esito = "Quotazione rilevata N.D.";
        $valute = 0;
    }
    $aggiorna = 'quotazione';

    echo json_encode(array('esito' => $esito, 'aggiorna' => $aggiorna, 'valore' => $valute, 'id' => $id));
    exit;

} //fine eutoax e targa

exit;

?>


?>