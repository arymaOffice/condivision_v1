<?php
//conterrà lechiamate all'SDK, a titolo esemplificativo

require_once 'class/Intodb.php';
require_once 'class/ParseMyJson.php';

$et = new Eurotax(EUROTAX_USER, EUROTAX_PWD); //oggetto ti tipo 'Eurotax'(SDK principale)

/*---------------------- ESEMPI DI UTILIZZO DELLA CLASSE INTODB CHE PRELEVA DATI DALL'SDK(QUINDI DAL WS E LI INSERISC IN TABELLAELOCALI) ------------------------*/
/*
// 1 Recupero dati dal servizio a partire dalla targa
$veicolo = $et->searchTarga("BM094RG");
$veicolo = json_decode($veicolo,true);

foreach($veicolo['versioni'] as $versione) { // Scorro le versioni e prendo la prima (o dividiamo il servizio e facciamo selezionare anche la versione da salvare in "allestimento")
	foreach($versione as $label => $valore) {  echo '<p>'.$label.' => '.$valore; }
	echo '<p>'.$versione['versione'].$versione['codice_eurotax'];
};


//2 Recupero la quotazione con codice eurotax, anno, km, e motornet 
$valutazione = $et->getValutazione("auto", "2000", "1", $km = "130000", $codiceMotornet="TOY1811", $codiceEurotax="1015730", array(), $codiceOmologazione="", $targa="", $telaio="", $costoHManMec="", $costoHManCarr="", $autocarro=1, array(), $lavoryCarr=array(), $valutazioneDealer="", $guidKey="", $annoValutazione="", $meseValutazione="");
$result = json_decode($valutazione,true);
echo "Valutazione Vettura: ".$result['valutazione']["quotazione_eurotax_giallo_km"];

*/
?>