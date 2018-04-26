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

$host = "localhost";
$db = "authos";
$login = "authos";
$pwd = "v94uyg6589g98656859";


function connect($host, $login, $pwd, $db)
{
    $connect = @mysql_connect($host, $login, $pwd);
    if ($connect == false) {@session_start(); (isset($_SESSION['failure'])) ? $_SESSION['failure']++ : $_SESSION['failure'] = 1;
        echo "<div style=\"text-align: center; margin: 20% auto; font-family: Arial;\"><h1 style=\"color: #009900;\">Ops..!</h1><h2>Il server &egrave; momentaneamente non raggiungibile.</h2><p>Riprova tra pochi minuti. Ci scusiamo per il disagio.</p></div>";@smail(mail_admin, "Connessione fallita " . $_SESSION['failure'] . " volte per $db  su " . client, "User IP: " . $_SERVER['REMOTE_ADDR'], '');exit;}
//mysql_set_charset("UTF8", $connect);
    @mysql_select_db($db, $connect) or die("<h2>Errore nella selezione del database. Contattare l'amministratore.</h2>");
    return $connect;
}
//Connessione, per tutti, sempre.
define('CONNECT', connect($host, $login, $pwd, $db));

define('EUROTAX_USER','authos');
define('EUROTAX_PWD','torinoford');

set_time_limit(0);

require_once '../../fl_set/librerie/eurotax/index.php'; //richiesta servizio

for ($i = 2004; $i < 2019; $i++) {
    $anno = $i;
// $select = "SELECT id,codice FROm fl_marca_eurotax WHERE id IN(3,4,12,14,16,25,24,26,28,19,30,31,32,40,39,41,44,46,48,49,50,51,52,53,54,55,60,56,57,59,64,66,69,70,72,73,76,80,84,86,90,92,93,97,99,100,98,101,104,107,112) ";
    $select = "SELECT id,codice FROm fl_marca_eurotax WHERE id IN(3,4,12,14,16,25,24,26,28,19,30,31,32,40,39,41,44,46,48,49,50,51,52,53,54,55,60,56,57,59,64,66,69,70,72,73,76,80,84,86,90,92,93,97,99,100,98,101,104,107,112) ";

    $select = mysql_query($select, CONNECT);
    while ($value = mysql_fetch_assoc($select)) {

        echo  $value['codice'].''.$anno.'\n';

        $a1 = $et->getModelli('AUTO', $value['codice'], $anno);
        $oggetto1 = json_decode($a1, true);



        if (isset($oggetto1['modelli'])) {

            //echo $value['acronimo'];

            foreach ($oggetto1['modelli'] as $key1 => $value1) {

                //function getVersioni($tipoVeicolo, $modello="", $marca="", $anno="", $codiceCostruttore="", $porte="", $passo="", $libro=""){

                //print_r($value1); exit;

                $a2 = $et->getVersioni('AUTO', @$value1['cod_gamma_mod'], @$value['codice'], $anno);
                $oggetto2 = json_decode($a2, true);

                if (count($oggetto2['versioni']) > 0) {

                    foreach ($oggetto2['versioni'] as $key2 => $value2) {

                        $a3 = $et->getDettaglioAuto(@$value2['CodiceMotornet'], @$value2['CodiceEurotax']);
                        $oggetto3 = json_decode($a3, true);

                        $oggetto3 = @$oggetto3['modello'];

                        // print_r($oggetto3); exit;

                        $insert = "INSERT INTO `fl_modello_eurotax_new`(codice_modello,`id_marca_eurotax`, `id_segmento`, `modello`, `cilindrata`, `codice_alimentazione`,  `tipo_motore`, `desc_motore`, `hp`, `kw`, `cavalli_fiscali`, `euro`,codice_eurotax,codice_motornet,anno) VALUES ('" . @$value1['codice_modello'] . "','" . @$value['id'] . "',(SELECT id FROM fl_segmenti_eurotax WHERE segmento = '" . @$oggetto3['segmento'] . "'),'" . @$oggetto3['modello'] . "','" . @$oggetto3['cilindrata'] . "','" . @$oggetto3['codice_alimentazione'] . "','" . @$oggetto3['tipo_motore'] . "','" . $oggetto3['desc_motore'] . "','" . @$oggetto3['hp'] . "','" . $oggetto3['kw'] . "','" . @$oggetto3['cavalli_fiscali'] . "','" . @$oggetto3['euro'] . "','" . @$value2['CodiceMotornet'] . "', '" . @$value2['CodiceEurotax'] . "','" . $anno . "')";

                        mysql_query($insert,CONNECT);

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


?>
