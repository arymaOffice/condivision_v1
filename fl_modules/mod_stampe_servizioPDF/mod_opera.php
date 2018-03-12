<?php
// $tables[128] tavoli_commensali
// $tables[125] tavoli
// da sostituire `fl_eventi_hrc`


$meeting_page = 1;
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo


function mysql_fetch_all ($result, $result_type = MYSQL_BOTH)
{
	if (!is_resource($result) || get_resource_type($result) != 'mysql result')
	{
		trigger_error(__FUNCTION__ . '(): supplied argument is not a valid MySQL result resource', E_USER_WARNING);
		return false;
	}
	if (!in_array($result_type, array(MYSQL_ASSOC, MYSQL_BOTH, MYSQL_NUM), true))
	{
		trigger_error(__FUNCTION__ . '(): result type should be MYSQL_NUM, MYSQL_ASSOC, or MYSQL_BOTH', E_USER_WARNING);
		return false;
	}
	$rows = array();
	while ($row = mysql_fetch_array($result, $result_type))
	{
		$rows[] = $row;
	}
	return $rows;
}



if(isset($_GET['eventId']) ){ //in base all'ebento recupero il numero di tavoli

$evento = check($_GET['eventId']);
$DatiIniziali = mysql_query($DatiIniziali,CONNECT);
$DatiIniziali = mysql_fetch_assoc($DatiIniziali);

//se ce ne sono già
$ciSonoGiaTavoli = "SELECT count(*) as ciSono FROM ".$tables[125]." WHERE evento_id = $evento";
$ciSonoGiaTavoli = mysql_query($ciSonoGiaTavoli,CONNECT);
$ciSonoGiaTavoli = mysql_fetch_assoc($ciSonoGiaTavoli);

if($ciSonoGiaTavoli['ciSono'] != 0){

	$Tavoli = "SELECT numero_tavolo FROM ".$tables[125]."  WHERE evento_id = $evento ORDER BY numero_tavolo DESC";
	$Tavoli = mysql_query($Tavoli,CONNECT);
	$Tavoli = mysql_fetch_all($Tavoli,MYSQL_NUM);
}


$DatiIniziali['ciSono'] = $ciSonoGiaTavoli['ciSono'];
$DatiIniziali['idTavoli'] = $Tavoli;
echo json_encode($DatiIniziali,true);
}



if (isset($_GET['insertTableId']) && isset($_GET['insertEventId'])) { //richiesta di inserire un tavolo
	$insertTableId = check($_GET['insertTableId']);//recupero e controllo variabili
	$insertEventId = check($_GET['insertEventId']);//recupero e controllo variabili
	$text = check($_GET['text']);//recupero e controllo variabili
	$x = check($_GET['x']);//recupero e controllo variabili
	$y = check($_GET['y']);//recupero e controllo variabili
	$type = check($_GET['type']);//recupero e controllo variabili
	//vedo se un tavolo è già stato inserito
	$contoTavoli = "SELECT count(*) AS quanti FROM ".$tables[125]." WHERE numero_tavolo = $insertTableId AND evento_id = $insertEventId";
	$contoTavoli = mysql_query($contoTavoli,CONNECT);
	$contoTavoli = mysql_fetch_array($contoTavoli);
	if($contoTavoli['quanti'] == 0) //se non ci sono tavoli con id ed vento uguale lo aggiungo
	{
		$inseriscoTavolo = "INSERT INTO ".$tables[125]." (layout_id,evento_id,tipo_tavolo_id,numero_tavolo,nome_tavolo,data_creazione,asse_x,asse_y) VALUES(3,$insertEventId,$type,$insertTableId,'$text',NOW(),$x,$y)";
		$inseriscoTavolo = mysql_query($inseriscoTavolo,CONNECT);
	}else{//sennò recupero i dati dello stesso
		$selezionoDatiTavolo = "SELECT id,numero_tavolo,nome_tavolo,asse_x,asse_y,tipo_tavolo_id,angolare FROM ".$tables[125]." WHERE evento_id = $insertEventId AND numero_tavolo = $insertTableId";
		$selezionoDatiTavolo = mysql_query($selezionoDatiTavolo,CONNECT);
		$selezionoDatiTavolo = mysql_fetch_assoc($selezionoDatiTavolo);
		$sommaCoperti = "SELECT sum(IF(tipo_commensale != 5,adulti,0)) a, sum(IF(tipo_commensale != 6 && tipo_commensale != 5,bambini,0)) b, sum(IF(tipo_commensale != 6 && tipo_commensale != 5,sedie,0)) s, sum(IF(tipo_commensale != 6 && tipo_commensale != 5,seggioloni,0)) as h,SUM(if(note_intolleranze != '',1,0)) as noteInt,sum(IF(tipo_commensale = 5,adulti + bambini,0)) as seraTot FROM ". $tables[128]." WHERE tavolo_id = ".$selezionoDatiTavolo['id'];
		$sommaCoperti = mysql_query($sommaCoperti,CONNECT);
		$sommaCoperti = mysql_fetch_assoc($sommaCoperti);
		echo json_encode(array_merge($selezionoDatiTavolo,$sommaCoperti),true);
	}

}

if(isset($_GET['tableName']) && isset($_GET['tableId']) && isset($_GET['evento'])){ //richiesta di cambiare un tavolo per i commensali
	$tableId = check($_GET['tableId']);
	$tableName = check($_GET['tableName']);
	$evento = check($_GET['evento']);
	//aggiorna il nome del tavolo
	$updateTavolo = "UPDATE ".$tables[125]." SET nome_tavolo = '$tableName' WHERE evento_id = $evento AND numero_tavolo = $tableId ";
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
						JOIN ".$tables[129]." ct ON ct.id = tc.tipo_commensale  
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
    $urlUploadImages = getcwd().'/img/';
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


mysql_close(CONNECT);
exit;


?>
