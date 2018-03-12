<?php 

require_once('action_check.php');
include('../../fl_core/dataset/array_statiche.php');

// Campi Obbligatori
$obbligatorio = array('importo','estremi_del_pagamento','titolo','fornitore','entrate','nome','nome_e_cognome','oggetto','email');

$dir_upfile = $dir_testate;

if(isset($_POST['dir_upfile']) && @$_POST['dir_upfile'] != "") $dir_upfile = $images.$_POST['dir_upfile']."/";

$sezione = "";
if(isset($_POST['relation'])) $sezione = "&sezione=".check($_POST['relation']);
if(isset($_POST['item_rel'])) $sezione = "&item_rel=".check($_POST['item_rel']);
if(isset($_GET['sezione'])) $sezione = "&sezione=".check($_GET['sezione']);
if(isset($_GET['tipo_segnalazione'])) $sezione = "&tipo_segnalazione=".check($_GET['tipo_segnalazione']);
if(isset($_POST['mode']))  $sezione .= "&mode=".check($_POST['mode']);
//Aggiorna

function not_doing($who){
$not_in = array("gtx","id","old_file","del_file","dir_upfile","mese","anno","mandatory","mode","external","data_creazione",'goto');
if(!is_numeric($who) && !in_array($who,$not_in)) return true;	
}


if(isset($_POST['id'])) { 

$id = check($_POST['id']);
$tabella = @$tables[check($_POST['gtx'])];
if($id == 1) { $id = new_inserimento($tabella,0); action_record('new',$tabella,$id,'New Empty Element');  $val = "external&id=$id"; }
$query = "UPDATE `$tabella` SET `id` = '$id'";


foreach($_POST as $chiave => $valore){

if(not_doing($chiave)){

if(isset($_POST['mandatory']) && $chiave != 'note' && (strstr(@$_POST['mandatory'],$chiave) || @strtolower($_POST['mandatory']) == "all" ) ) array_push($obbligatorio,$chiave);

$patterncod= "^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$";
if(isset($_POST['importo']) && $_POST['importo'] == 0.000){
header("Location: $rct?$val&error=1&action=9&esito=Inserire un importo");
exit;

 }
if(isset($_POST['codice_fiscale'])){
/*	
if ($id != 1 && isset($_POST['codice_fiscale']) && @!ereg($patterncod,check(@$_POST['codice_fiscale']))) {
header("Location: $rct?$val&error=1&action=9&esito=Inserire un codice fiscale corretto!$sezione");
exit;
}*/
/*
include_once ("../../fl_core/class/CodiceFiscale.class.php");

$cf = new CodiceFiscale();*/
/*
$cf ->SetCF(check(@$_POST['codice_fiscale'])); 
if ($cf->GetCodiceValido()) {
 /*echo  "Codice VALIDO - Data di nascita:" . 
  $cf->GetGGNascita() . 
  "-" . 
  $cf->GetMMNascita() . 
  "-" . 
  $cf->GetAANascita() . 
  " Sesso:" . 
  $cf->GetSesso() . 
  " Comune di nascita:". 
  $cf->GetComuneNascita();
  exit;
} else {
header("Location: $rct?$val&error=1&action=9&esito=".$cf->GetErrore()."$sezione");
exit;
}*/

}

if ($id != 1 && isset($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)    ) {
header("Location: $rct?$val&error=1&action=9&esito=Inserire una mail corretta!$sezione");
exit;
}

if(isset($_POST['dare']) || isset($_POST['avere'])) {
$importo = check($_POST['dare'])+check($_POST['avere']);
if(!is_numeric($importo) || $importo == 0){
header("Location: $rct?$val&error=1&action=9&esito=Inserisci un importo in cifre Es: 1.50");
exit;
}}

if(in_array($chiave,$obbligatorio) && $id != 1) {

if(trim($valore) == ""){
@mysql_close(CONNECT);
$chiave = ucfirst($chiave);
$query .= "status = 0 WHERE `id` = $id LIMIT 1;";
@mysql_query($query,CONNECT);
Header("Location: $rct?$val&error=1&action=9&esito=Inserire valore per il campo $chiave $sezione");
exit;}}

$etichette = array('gruppo','giorni_lavorativi');
$campi_date = array('data_scadenza_contratto','data_documento','data_operazione','data_emissione','data_scadenza','data_nascita','aggiornato','schedulazione');
$date_timestamp = array('data_modifica','data','data_evento','data_inizio','data_fine','datac_inizio','datac_fine', "data_pagamento" ,"data_intervento", "data_chiusura" ,"data_versamento" ,"data_corrispettivo" , "data_fattura" ,"data_inserimento","data_richiesta","data_preventivo");

$valore_inserito = check($valore);

if(in_array($chiave,$campi_date)){ // Inserimento date
$data = explode("/",$valore);
$valore_inserito = $data[2].'-'.$data[1].'-'.$data[0];
}

if($chiave == 'data_aggiornamento') { $valore_inserito = date('Y').'-'.date('m').'-'.date('d').' '.date('H').':'.date('i').':'.date('s'); }

if(in_array($chiave,$etichette)){ // Inserimento etichette
$contentnum = "";

foreach($$chiave as $key => $value){
if($key > 0) $contentnum .= ",";
if(isset($_POST[$chiave][$key])){
$contentnum .= 1;
} else { $contentnum .= 0; }
} 

$valore_inserito = $contentnum;
} 
if($chiave == "status_assistenza" && @$valore == 0 || $chiave == "status_pagamento" && @$valore == 0 && trim($_POST['user']) != ""){ $valore_inserito = 1; }

$query .= ",`$chiave` = '".$valore_inserito."'"; // Set chiave

}}


$query .= " WHERE `id` = $id LIMIT 1;";

}

//echo $query; exit;



if(@mysql_query($query,CONNECT)){
action_record('modify',$tabella,$id,base64_encode($query));
if(trim(check(@$_POST['old_file'])) != "") $old_file = check(@$_POST['old_file']);

// Cancella il file attuale se impostato
if(isset($_POST['del_file'])) {
$query_a =  "UPDATE `$tabella` SET `upfile` = '' WHERE `id` = $id LIMIT 1;";;
mysql_query($query_a,CONNECT);
}

if(trim($_FILES['upfile']['name']) != ""){

$info = pathinfo($_FILES['upfile']['name']); 
$noext = 1;

foreach($info as $key => $valore){
if($key == "extension") unset($noext);
}

if(isset($noext)){
header("Location: $rct?action=9&ok=1&cat=$cat&contenuto=$relation&esito=Formato non valido. (File Corrotto)"); 
exit;
} 

$ext = strtolower($info["extension"]);
$formati = array('php','php3','exe','src','piff','dll','inc','sql');


if(in_array($ext,$formati)){
header("Location: $rct?action=9&ok=1&cat=$cat&contenuto=$relation&esito=Formato non valido."); 
exit;
} 


//Salvataggio su CDN
$target_url = 'http://www.condivision.org/file_receive.php';
$file_name_with_full_path = $_FILES['upfile']['tmp_name'];
$file_name =  $info['basename']."_".time().".".$ext;
$post = array('basename' => $file_name,'file_contents'=>'@'.$file_name_with_full_path);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
$result=curl_exec ($ch);
curl_close ($ch);

$query_p =  "UPDATE `$tabella` SET `upfile` = '$file_name' WHERE `id` = $id LIMIT 1;";;
mysql_query($query_p,CONNECT);


} 









@mysql_close(CONNECT);
if(isset($_SESSION['POST_BACK_PAGE']) && !isset($_POST['goto'])) {
header("Location: ".$_SESSION['POST_BACK_PAGE']); 
} else if(isset($_POST['goto'])){
header("Location: ".check($_POST['goto'])); 
} else {
header("Location: $rct?id=$id&action=9&success&esito=Salvataggio scheda alle ".date('H:i:s - d/m/Y')."$sezione"); 
}
exit;

} else { 

$error = mysql_error();

//echo $query;
action_record('modify-error',$tabella,$id,base64_encode($query).$error);
@mysql_close(CONNECT);
//if(file_exists($allegati.$file_name)){unlink( $allegati.$file_name);}
Header("Location: $rct?error=1&action=9&esito=Errore 1101: Errore inserimento in database!<br />$error"); 
exit;

}	

@mysql_close(CONNECT);


?>  
