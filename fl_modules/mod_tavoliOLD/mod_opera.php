<?php
// $tables[128] tavoli_commensali
// $tables[125] tavoli
// da sostituire `fl_eventi_hrc`

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$meeting_page = 1;
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo


function mysql_fetch_all_mia ($result, $result_type = MYSQL_BOTH){
	$rows = array();
	while ($row = mysql_fetch_array($result, $result_type))
	{
		$rows[] = $row;
	}
	return $rows;
}



if(isset($_GET['eventId']) ){ //in base all'ebento recupero il numero di tavoli

$evento = false;
$evento_principale = check($_GET['eventId']);
$ambiente_id = check($_GET['ambiente_id']);
$DatiIniziali = mysql_query($DatiIniziali,CONNECT);
$DatiIniziali = mysql_fetch_assoc($DatiIniziali);

$evento_hrc = GQS('fl_eventi_hrc',' multievento, data_evento ' ,'ambienti = '.$ambiente_id.'  AND id = '.$evento_principale);

if(isset($evento_hrc[0]['multievento']) && $evento_hrc[0]['multievento'] == 1){
	$data_evento = substr($evento_hrc[0]['data_evento'], 0,10);
	$eventi_coinvolti = GQS('fl_eventi_hrc','id','ambienti = '.$ambiente_id.' AND DATE(data_evento) = "'.$data_evento.'"' );

	$evento = implode(',',array_column($eventi_coinvolti,'id'));
}

	//se ce ne sono già
$ciSonoGiaTavoli = "SELECT count(*) as ciSono FROM ".$tables[125]." WHERE evento_id = $evento_principale";
$ciSonoGiaTavoli = mysql_query($ciSonoGiaTavoli,CONNECT);
$ciSonoGiaTavoli = mysql_fetch_assoc($ciSonoGiaTavoli);

if($ciSonoGiaTavoli['ciSono'] != 0){

	$Tavoli = "SELECT numero_tavolo FROM ".$tables[125]."  WHERE evento_id = $evento_principale ORDER BY numero_tavolo DESC";
	$Tavoli = mysql_query($Tavoli,CONNECT);
	$Tavoli = mysql_fetch_all_mia($Tavoli,MYSQL_NUM);
}

if($evento){
	$daTogliere = array($evento_principale.',',$evento_principale,','.$evento_principale);
	$evento = str_replace($daTogliere, '', $evento);
	$evento = trim($evento,',');
 $Tavoli_opachi = "SELECT evento_id,numero_tavolo FROM ".$tables[125]."  WHERE evento_id IN($evento) ORDER BY numero_tavolo DESC";
	$Tavoli_opachi = mysql_query($Tavoli_opachi,CONNECT);
	$Tavoli_opachi = mysql_fetch_all_mia($Tavoli_opachi,MYSQL_NUM);

}

$DatiIniziali['ciSono'] = $ciSonoGiaTavoli['ciSono'];
$DatiIniziali['idTavoli'] = $Tavoli;
$DatiIniziali['idTavoliOpachi'] = $Tavoli_opachi;


echo json_encode($DatiIniziali,true);
}



if (isset($_GET['insertTableId']) && isset($_GET['insertEventId'])) { //richiesta di inserire un tavolo
	$insertTableId = $_GET['insertTableId'];//recupero e controllo variabili
	$insertEventId =  $_GET['insertEventId'];//recupero e controllo variabili
	$text = check($_GET['text']);//recupero e controllo variabili
	$x = check($_GET['x']);//recupero e controllo variabili
	$y = check($_GET['y']);//recupero e controllo variabili
	$type = check($_GET['type']);//recupero e controllo variabili
	$categoria = check($_GET['categoria']);//recupero e controllo variabili
	$numero = check($_GET['numero']);//recupero e controllo variabili

	if($_GET['diverso'] != 'false'){ $insertEventId = check($_GET['diverso']);}
	//vedo se un tavolo è già stato inserito
	$contoTavoli = "SELECT count(*) AS quanti FROM ".$tables[125]." WHERE numero_tavolo = $insertTableId AND evento_id = $insertEventId";
	$contoTavoli = mysql_query($contoTavoli,CONNECT);
	$contoTavoli = mysql_fetch_array($contoTavoli);
	if($contoTavoli['quanti'] == 0) //se non ci sono tavoli con id ed vento uguale lo aggiungo
	{
		$inseriscoTavolo = "INSERT INTO ".$tables[125]." (layout_id,evento_id,tipo_tavolo_id,numero_tavolo,nome_tavolo,numero_tavolo_utente,nome_tavolo_utente,data_creazione,asse_x,asse_y) VALUES(3,$insertEventId,$type,$insertTableId,'$categoria','$numero','$text',NOW(),$x,$y)";
		$inseriscoTavolo = mysql_query($inseriscoTavolo,CONNECT);
	}else{//sennò recupero i dati dello stesso
		if($_GET['diverso'] == 'false'){
		$selezionoDatiTavolo = "SELECT id,numero_tavolo,nome_tavolo,numero_tavolo_utente,nome_tavolo_utente,asse_x,asse_y,tipo_tavolo_id,angolare FROM ".$tables[125]." WHERE evento_id = $insertEventId AND numero_tavolo = $insertTableId";

		$selezionoDatiTavolo = mysql_query($selezionoDatiTavolo,CONNECT);
		$selezionoDatiTavolo = mysql_fetch_assoc($selezionoDatiTavolo);
		$sommaCoperti = "SELECT sum(IF(tipo_commensale != 5,adulti,0)) a, sum(IF(tipo_commensale != 6 && tipo_commensale != 5,bambini,0)) b, sum(IF(tipo_commensale != 6 && tipo_commensale != 5,sedie,0)) s, sum(IF(tipo_commensale != 6 && tipo_commensale != 5,seggioloni,0)) as h,SUM(if(note_intolleranze != '',1,0)) as noteInt,sum(IF(tipo_commensale = 5,adulti + bambini,0)) as seraTot FROM ". $tables[128]." WHERE tavolo_id = ".$selezionoDatiTavolo['id'];
		$sommaCoperti = mysql_query($sommaCoperti,CONNECT);
		$sommaCoperti = mysql_fetch_assoc($sommaCoperti);
	}else{ $selezionoDatiTavolo = "SELECT id,numero_tavolo,nome_tavolo,numero_tavolo_utente,nome_tavolo_utente,asse_x,asse_y,tipo_tavolo_id,angolare FROM ".$tables[125]." WHERE evento_id = $insertEventId AND numero_tavolo = $insertTableId";

		$selezionoDatiTavolo = mysql_query($selezionoDatiTavolo,CONNECT);
		$selezionoDatiTavolo = mysql_fetch_assoc($selezionoDatiTavolo);
		$sommaCoperti = array();
		 }
		echo json_encode(array_merge($selezionoDatiTavolo,$sommaCoperti),true);
	}

}

if(isset($_GET['tableNameUser']) && isset($_GET['tableId']) && isset($_GET['evento'])){ //richiesta di cambiare un tavolo per i commensali
	$tableId = check($_GET['tableId']);
	$tableNameUser = check($_GET['tableNameUser']);
	$evento = check($_GET['evento']);
	//aggiorna il nome del tavolo scritto dall'utente
	$updateTavolo = "UPDATE ".$tables[125]." SET nome_tavolo_utente = '$tableNameUser' WHERE evento_id = $evento AND numero_tavolo = $tableId ";
	$updateTavolo = mysql_query($updateTavolo,CONNECT);
}
if(isset($_GET['categoria']) && isset($_GET['tableId']) && isset($_GET['evento'])){ //richiesta di cambiare un tavolo per i commensali
	$tableId = check($_GET['tableId']);
	$categoria = check($_GET['categoria']);
	$evento = check($_GET['evento']);
	//aggiorna il nome del tavolo di categoria
	$updateTavolo = "UPDATE ".$tables[125]." SET nome_tavolo = '$categoria' WHERE evento_id = $evento AND numero_tavolo = $tableId ";
	$updateTavolo = mysql_query($updateTavolo,CONNECT);
}
if(isset($_GET['numero']) && isset($_GET['tableId']) && isset($_GET['evento'])){ //richiesta di cambiare un tavolo per i commensali
	$tableId = check($_GET['tableId']);
	$numero = check($_GET['numero']);
	$evento = check($_GET['evento']);
	//aggiorna il numero del tavolo
	$updateTavolo = "UPDATE ".$tables[125]." SET numero_tavolo_utente = '$numero' WHERE evento_id = $evento AND numero_tavolo = $tableId ";
	$updateTavolo = mysql_query($updateTavolo,CONNECT);
}
if(isset($_GET['cognome']) && isset($_GET['tableId'])){//ritorna i commensali

	$cognome =check($_GET['cognome']);
	$nome = check($_GET['nome']);
	$intolleranze = check($_GET['intolleranze']);
	$a = check($_GET['a']);//adulti
	$b = check($_GET['b']);//bambini
	$s = check($_GET['s']);//sedie
	$h = check($_GET['h']);//sedioloni
	$tipo_commensale = check($_GET['tipo_commensale']);//tipo_commensale
	$tableId = check($_GET['tableId']);//id del tavolo nel canvas
	$eventoId = check($_GET['eventoId']);//id dell'evento

	$insertCommensale = "INSERT INTO ".$tables[128]." (id,tavolo_id,cognome,nome,adulti,bambini,sedie,seggioloni,note_intolleranze,tipo_commensale,data_creazione,data_aggiornamento) VALUES(NULL,(SELECT id FROM fl_tavoli WHERE evento_id = $eventoId AND numero_tavolo = $tableId),'$cognome','$nome','$a','$b','$s','$h','$intolleranze','$tipo_commensale',CURDATE(),NOW())";
	$insertCommensale = mysql_query($insertCommensale,CONNECT);
}

if (isset($_GET['chiedoOspiti']) && $_GET['chiedoOspiti'] == 1) {

	$table = check($_GET['table']);
	$evento_id = check($_GET['evento_id']);
	$idPersone = '';

	//recupero commensali del tavolo
	$selezionOspiti =	"SELECT	tc.id as tcId,cognome,nome,note_intolleranze,adulti,
	bambini,sedie,seggioloni,ct.tipo_commensale  as tipo_commensale
	FROM ".$tables[128]." tc
	JOIN ".$tables[125]." t ON t.id = tc.tavolo_id
	JOIN fl_commensali_tipo ct ON ct.id = tc.tipo_commensale
	WHERE t.evento_id = $evento_id AND t.numero_tavolo = $table ORDER BY tc.id DESC";

	$selezionOspiti = mysql_query($selezionOspiti,CONNECT);

	$result['result'] = '';										//verrà riempito con i commensali
	$result['resultTOT'] = '';									//verrà riempito con i totali di adulti,bambini e sedie
	$arrayAppogio = array();									//variabile d'appoggio per il push

	while ($row = mysql_fetch_assoc($selezionOspiti)) { //riempio il vettore con i risultati della query
		array_push($arrayAppogio, $row);
		$idPersone .= $row['tcId'].',';
	}

	$result['result'] = $arrayAppogio; //reimpio result con tutti i commensali
	$idPersone = rtrim($idPersone,','); //rimuovo l'ultima vigola dalla stringa

	//recupero totali del tavolo
	$sumOspitiTavolo = " SELECT sum(IF(tipo_commensale != 6 && tipo_commensale != 5,adulti,0)) aTot, sum(IF(tipo_commensale != 6 && tipo_commensale != 5,bambini,0)) bTot, sum(IF(tipo_commensale != 6 && tipo_commensale != 5,sedie,0)) sTot, sum(IF(tipo_commensale != 6 && tipo_commensale != 5,seggioloni,0)) as hTot,sum(IF(tipo_commensale = 5, adulti + bambini,0)) as seraTot,sum(IF(tipo_commensale = 6, adulti + bambini,0)) as opTot,SUM(if(note_intolleranze != '',1,0)) as noteInt FROM ".$tables[128]." WHERE id IN($idPersone)";
	$sumOspitiTavolo = mysql_query($sumOspitiTavolo,CONNECT);
	$sumOspitiTavolo = mysql_fetch_assoc($sumOspitiTavolo);
	$result['resultTOT'] = $sumOspitiTavolo;

	echo json_encode($result,true);
}

if(isset($_GET['idCommensale'])){

	$idCommensale = check($_GET['idCommensale']);
	$nomeCampo = check($_GET['nomeCampo']);
	$valoreCampo = check($_GET['valoreCampo']);

	$updateCampo = "UPDATE ".$tables[128]." SET $nomeCampo = '$valoreCampo' WHERE id = $idCommensale";
	$updateCampo = mysql_query($updateCampo,CONNECT);

}

if(isset($_GET['delete'])){//cancella un commensale

	$idCommensale = check($_GET['commensaleId']);
	$updateCampo = "DELETE FROM ".$tables[128]." WHERE id = $idCommensale";
	$updateCampo = mysql_query($updateCampo,CONNECT);

}

if(isset($_GET['deleteTable'])){//cancella il tavolo

	$delTable = check($_GET['delTable']);
	$evento_id = check($_GET['evento_id']);
	$updateCampo = "DELETE FROM ".$tables[125]." WHERE evento_id = $evento_id AND numero_tavolo = $delTable";
	$updateCampo = mysql_query($updateCampo,CONNECT);

}


if(isset($_GET['updateTable'])){//update coords tavolo

	$idTable = check($_GET['TableId']);
	$idEvento = check($_GET['eventoId']);
	$x = check($_GET['x']);
	$y = check($_GET['y']);
	$angle = check($_GET['angle']);

	$updateCampo = "UPDATE ".$tables[125]." SET asse_x = '$x',asse_y = '$y',angolare = '$angle' WHERE evento_id = $idEvento AND numero_tavolo = $idTable";
	$updateCampo = mysql_query($updateCampo,CONNECT);
}

if(isset($_POST["img"])){// crea l'immagine

$encodedData=explode(',', $_POST["img"]);
$data = base64_decode($encodedData[1]);
$urlUploadImages = DMS_PUBLIC.'tableau/';
$nameImage = check($_POST["evento_id"]).'.png';
$img = imagecreatefromstring($data);
if($img) {
	imagepng($img, $urlUploadImages.$nameImage, 0);
	imagedestroy($img);
	$url['esito'] = 1;
	echo json_encode($url);
}
else {
	$url['esito'] = 0;
	echo json_encode($url);
}
}


//crea il template dell'evento
if(isset($_GET['template_id']) && isset($_GET['evento'])) {

	//mi aspetto anche un parametro ambiente

	$evento_id = check($_GET['evento']);
	$template_id = check($_GET['template_id']);
 	$tavoli = GQD('fl_tavoli','id,evento_id',' evento_id = '.$evento_id);

	$evento_hrc = GQS('fl_eventi_hrc','multievento,data_evento','id = '.$evento_id);

if($tavoli['id'] < 1) {  // SOLO SE NON ESISTONO TAVOLI CARICO IL TEMPLATE!!!


	if($evento_hrc[0]['multievento']){
		$tavoli = GQS('fl_eventi_hrc','*','ambiente =  AND data_evento = '.$evento_hrc[0]['data_evento']); //mi ritorna la tipologia di commensale
	}else{
		$tavoli = GQS('fl_tavoli','*',' id > 1 AND evento_id = 1'); //mi ritorna la tipologia di commensale
	}
	foreach($tavoli AS $chiave => $valore) {

	$query = "INSERT INTO `fl_tavoli` (`layout_id`, `evento_id`, `tipo_tavolo_id`, `numero_tavolo`, `nome_tavolo`, `data_creazione`, `asse_x`, `asse_y`, `angolare`,`numero_tavolo_utente`)
	VALUES ( '".$valore['layout_id']."', '".$evento_id."', '".$valore['tipo_tavolo_id']."', '".$valore['numero_tavolo']."', '".$valore['nome_tavolo']."', NOW(),  '".$valore['asse_x']."', '".$valore['asse_y']."', '".$valore['angolare']."','".$valore['numero_tavolo_utente']."');";
	mysql_query($query,CONNECT);

	}

}


mysql_close(CONNECT);
header('Location: mod_layout.php?layout=1&evento='.$evento_id);
exit;
}




//crea i tavoli dato l'ambiente e l'evento
if(isset($_GET['ambiente_id']) && isset($_GET['evento'])) {

	$evento_id = check($_GET['evento']);
	$ambiente_id = check($_GET['ambiente_id']);

	$tavoli_template = "SELECT layout_id,tipo_tavolo_id,numero_tavolo,nome_tavolo,asse_x,asse_y,angolare FROM fl_tavoli_template tt JOIN fl_tavoli_layout tl ON tl.id= tt.layout_id WHERE tl.ambiente_id = $ambiente_id ";
	$tavoli_template = mysql_query($tavoli_template,CONNECT);



	while($valore = mysql_fetch_assoc($tavoli_template)) {

		$query = "INSERT INTO `fl_tavoli` (`layout_id`, `evento_id`, `tipo_tavolo_id`, `numero_tavolo`, `nome_tavolo`, `data_creazione`, `asse_x`, `asse_y`, `angolare`,`numero_tavolo_utente`)
		VALUES ( '".$valore['layout_id']."', '".$evento_id."', '".$valore['tipo_tavolo_id']."', '".$valore['numero_tavolo']."', '".$valore['nome_tavolo']."', NOW(),  '".$valore['asse_x']."', '".$valore['asse_y']."', '".$valore['angolare']."','0');";
		mysql_query($query,CONNECT);

	}
	mysql_close(CONNECT);
	header('Location: mod_layout.php?layout=1&evento='.$evento_id.'&ambiente_id='.$ambiente_id);
	exit;
}


mysql_close(CONNECT);
exit;


?>
