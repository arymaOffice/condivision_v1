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

set_time_limit(999999999999999999999999999999999999999999999999999);

require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo

require_once '../../fl_set/librerie/eurotax/index.php'; //richiesta servizio
$a = $et->getMarche('AUTO');
//$a = $et->getModelli('AUTO','ALF',2015);
//$a = $et->getVersioni('AUTO',"0040", 'ALF', 2015);
$oggetto = json_decode($a, true);
for ($i = 2002; $i < 2019; $i++) {
    $anno = $i;

    foreach ($oggetto['marche'] as $key => $value) {

        $a1 = $et->getModelli('AUTO', $value['acronimo'], $anno);
        $oggetto1 = json_decode($a1, true);

        if (count($oggetto1['modelli']) > 0) {

            //echo $value['acronimo'];

            foreach ($oggetto1['modelli'] as $key1 => $value1) {
                $a2 = $et->getVersioni('AUTO', $value1['codice_modello'], $value['acronimo'], $anno);
                $oggetto2 = json_decode($a2, true);

                if (count($oggetto2['versioni']) > 0) {

                    foreach ($oggetto2['versioni'] as $key2 => $value2) {

                        $a3 = $et->getDettaglioAuto($value2['CodiceMotornet'], $value2['CodiceEurotax']);
                        $oggetto3 = json_decode($a3, true);

                        $oggetto3 = $oggetto3['modello'];

                        // print_r($oggetto3); exit;

                        $insert = "INSERT INTO `fl_modello_eurotax_new`(codice_modello,`id_marca_eurotax`, `id_segmento`, `modello`, `cilindrata`, `codice_alimentazione`,  `tipo_motore`, `desc_motore`, `hp`, `kw`, `cavalli_fiscali`, `euro`,codice_eurotax,codice_motornet,anno) VALUES ('".$value1['codice_modello']."',(SELECT id FROM fl_marca_eurotax WHERE codice = '" . $value['acronimo'] . "'),(SELECT id FROM fl_segmenti_eurotax WHERE segmento = '" . $oggetto3['segmento'] . "'),'" . $oggetto3['modello'] . "','" . $oggetto3['cilindrata'] . "','" . $oggetto3['codice_alimentazione'] . "','" . $oggetto3['tipo_motore'] . "','" . $oggetto3['desc_motore'] . "','" . $oggetto3['hp'] . "','" . $oggetto3['kw'] . "','" . $oggetto3['cavalli_fiscali'] . "','" . $oggetto3['euro'] . "','" . $value2['CodiceMotornet'] . "', '" . $value2['CodiceEurotax'] . "','" . $anno . "')";
                        mysql_query($insert, CONNECT);

                        if (mysql_insert_id(CONNECT) < 1) {
                            print_r($oggetto3);

                        }
                    }
                }

            }

        }

    }
}

mysql_close(CONNECT);

// echo'<pre>';
// print_r(json_decode($a, true));
// echo'</pre>';

exit;

if (isset($_GET['eurotax']) && isset($_GET['targa'])) {

    require_once '../../fl_set/librerie/eurotax/index.php'; //richiesta servizio

    $targa = check($_GET['targa']); //targa per risalire alle versioni
    $id = check($_GET['id']);

    if ($targa == '' || $id == '') {
        echo json_encode(array('esito' => 'dati non validi', 'aggiorna' => 'niente', 'valore' => 0, 'id' => ''));
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

        $valute = $result['valutazione']["quotazione_eurotax_giallo_km"];
        $codice_eurotax = $veicolo['versioni'][0]['codice_eurotax'];
        $codice_motornet = $veicolo['versioni'][0]['codice_motornet'];
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
