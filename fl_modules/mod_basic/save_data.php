<?php 

require_once('action_check.php');
include('../../fl_core/dataset/array_statiche.php');

// Campi Obbligatori
$obbligatorio = array('importo','estremi_del_pagamento','titolo','fornitore','entrate','nome','nome_e_cognome','oggetto','rfq','marca','modello');

$dir_upfile = $dir_testate;

if(isset($_POST['dir_upfile']) && @$_POST['dir_upfile'] != "") $dir_upfile = DMS_ROOT.$_POST['dir_upfile']."/";

$sezione = "";
if(isset($_POST['relation'])) $sezione = "&sezione=".check($_POST['relation']);
if(isset($_POST['item_rel'])) $sezione = "&item_rel=".check($_POST['item_rel']);
if(isset($_GET['sezione'])) $sezione = "&sezione=".check($_GET['sezione']);
if(isset($_GET['tipo_segnalazione'])) $sezione = "&tipo_segnalazione=".check($_GET['tipo_segnalazione']);
if(isset($_POST['mode']))  $sezione .= "&mode=".check($_POST['mode']);
//Aggiorna

function not_doing($who){
$not_in = array('meeting_rel_id','status_auto','function','potential_rel_id','reload','copy_record','base_price',"info","gtx","id","old_file","del_file","dir_upfile","mandatory","mode","external","data_creazione",'goto');
if(!is_numeric($who) && !in_array($who,$not_in)) return true;	
}

if(isset($_POST['id'])) { 

$id = check($_POST['id']);
$tab_id = check($_POST['gtx']);
$tabella = @$tables[$tab_id];
$query = "";


if($tabella != 'fl_doc_vendita' && isset($_POST['codice_fiscale']) && $_POST['codice_fiscale'] != ''){
include_once ("../../fl_core/class/CodiceFiscale.class.php");
//$patterncod= "^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$";	
$cf = new CodiceFiscale();
$cf ->SetCF(check(@$_POST['codice_fiscale'])); 
if ($cf->GetCodiceValido()) {
} else {
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>$cf->GetErrore()));
exit;
}
}

/*
if (isset($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)    ) {
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire una mail corretta"));
exit;
}

if (isset($_POST['link']) && !filter_var($_POST['link'], FILTER_VALIDATE_URL)    ) {
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire un link corretto"));
exit;
}

if (isset($_POST['codice_fiscale_legale'])) {
$valore = check($_POST['codice_fiscale_legale']);
if($valore == '' || @!ctype_alnum($valore)){	
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Codice fiscale azienda non valido"));
exit;
}
if(check_record($tabella,'codice_fiscale_legale',$id,check($_POST['codice_fiscale_legale'])) == TRUE){	
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Codice fiscale azienda esistente"));
exit;
}}

if (isset($_POST['rfq'])) {
if(check_record($tabella,'rfq',$id,check($_POST['rfq'])) == TRUE){	
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Rfq esistente"));
exit;
}}*/

if (isset($_POST['user']) && $tabella == 'fl_account') {
$exist = check_record($tabella,'user',$id,check($_POST['user']));
if($exist == TRUE || strlen(check($_POST['user'])) < 8){	
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Username esistente o non valido"));
exit;
}}

if (isset($_POST['numero_progressivo'])) {
if(check_record($tabella,'numero_progressivo',$id,check($_POST['numero_progressivo']),'AND anno_fiscale = '.check($_POST['anno_fiscale'])) == TRUE){	
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"numero progressivo esistente"));
exit;
}}

if(isset($_POST['dare']) || isset($_POST['avere'])) {
if(check($_POST['dare'])+check($_POST['avere']) == 0){
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire un importo"));
exit;
}}
/*
if(isset($_POST['telefono']) || isset($_POST['cellulare'])) {
if(strlen(check(@$_POST['telefono']).check(@$_POST['cellulare'])) < 6){
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire almeno un numero di telefono"));
exit;
}}*/


foreach($_POST as $chiave => $valore){




if(not_doing($chiave)){

if(isset($_POST['mandatory']) && $chiave != 'note' && (strstr(@$_POST['mandatory'],$chiave) || @strtolower($_POST['mandatory']) == "all" ) ) array_push($obbligatorio,$chiave);

if(isset($_POST['importo']) && $_POST['importo'] == 0.000){
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire un importo"));
exit;

 }


if(in_array($chiave,$obbligatorio)) {

if(trim($valore) == ""){
@mysql_close(CONNECT);
$chiave = ucfirst($chiave);
$query .= "status = 0 WHERE `id` = $id LIMIT 1;";
@mysql_query($query,CONNECT);
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire valore per il campo $chiave $sezione"));
exit;}}

$etichette = array('mesi_di_interesse','gruppo','giorni_lavorativi','sedi_id');
$campi_date = array('data_contatto','data_acquisto','data_consegna','anno_immatricolazione','data_saldo','data_vendita','data_incasso','data_attivazione','data_contatto','data_test_drive','periodo_cambio_vettura','scadenza_obiettivo','data_revisione','data_evento','meeting_date','data_arrived','data_fattura','data_corrispettivo','data_versamento','data_preventivo','data_intervento','data_pagamento','data_rielaborazione','data_scadenza_contratto','data_avvio','data_conclusione','data_documento','data_operazione','data_emissione','data_nascita','data_apertura','data_chiusura','data_inizio','data_fine');
$campi_datetime = array('data_scadenza','data_acquisto_vettura','start_date','end_date','start_meeting','end_meeting');
$moneta = array('dare','avere','importo');

$valore_inserito = (!is_array($valore)) ?  check($valore) : '';

if(in_array($chiave,$campi_datetime)){ // Inserimento datetime
$datetime = explode(" ",$valore);
$data = explode("/",$datetime[0]);
@$valore_inserito = $data[2].'-'.$data[1].'-'.$data[0].' '.$datetime[1].':00';
@$valore_inserito = str_replace('--','',$valore_inserito);
}

if(in_array($chiave,$campi_date)){ // Inserimento date
$data = explode("/",$valore);
@$valore_inserito = $data[2].'-'.$data[1].'-'.$data[0];
@$valore_inserito = str_replace('--','',$valore_inserito);
}


if(in_array($chiave,$moneta)){ // Conversione virgole
@$valore_inserito = str_replace(',','.',$valore);
}


if($chiave == 'data_aggiornamento') { $valore_inserito = date('Y').'-'.date('m').'-'.date('d').' '.date('H').':'.date('i').':'.date('s'); }

if(in_array($chiave,$etichette)){ // Inserimento etichette
$elementi = '';
$n = 0;
if(is_array($_POST[$chiave])){
foreach(@$_POST[$chiave] as $key => $value){
if($n > 0) $elementi .= ",";
$elementi .= '['.$value.']'; 
$n++;
} 
$valore_inserito = $elementi;
}
} 

$valore_inserito = str_replace("'","\'",$valore_inserito);

if($valore_inserito == '0000-00-00 00:00:00') $valore_inserito = 'NULL';
if($valore_inserito == '0000-00-00') $valore_inserito = 'NULL';
if($valore_inserito != 'NULL') $valore_inserito = " '".$valore_inserito."' ";
$query .= ",`$chiave` = ".$valore_inserito; // Set chiave

}}



}

//echo $query; exit;
if($id == 1 || isset($_POST['copy_record'])) { 
$id = new_inserimento($tabella,0);
@mysql_query("UPDATE $tabella SET data_creazione = NOW()  WHERE id = $id",CONNECT);
if(isset($_POST['base_price'])) insert_prezzo($id,0,check($_POST['base_price']));
if(isset($_POST['potential_rel_id'])) mysql_query("UPDATE fl_potentials SET status_potential = 4, is_customer = '$id' WHERE id = ".check($_POST['potential_rel_id']),CONNECT);
if(isset($_POST['meeting_rel_id'])) mysql_query("UPDATE fl_appuntamenti SET issue = 2 WHERE id = ".check($_POST['meeting_rel_id']),CONNECT);

action_record('new',$tabella,$id,'New Empty Element'); 
$val = "external&id=$id"; 
}

$query = "UPDATE `$tabella` SET id = $id ".$query." WHERE `id` = $id LIMIT 1;";

if(@mysql_query($query,CONNECT)){
action_record('modify',$tabella,$id,base64_encode($query)); //Da far diventare metodo della classe data_manager;

if(isset($_POST['function'])) do_action(check($_POST['function']),$id); //Da far diventare metodo della classe data_manager;

if(trim(check(@$_POST['old_file'])) != "") $old_file = check(@$_POST['old_file']);

// Cancella il file attuale se impostato
if(isset($_POST['del_file'])) {
$query_a =  "UPDATE `$tabella` SET `upfile` = '' WHERE `id` = $id LIMIT 1;";;
mysql_query($query_a,CONNECT);
}

if(trim(@$_FILES['upfile']['name']) != ""){


$info = pathinfo($_FILES['upfile']['name']); 
$noext = 1;

foreach($info as $key => $valore){
if($key == "extension") unset($noext);
}

if(isset($noext)){
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>'File non integro. Riprova')); 
exit;
} 

$ext = strtolower($info["extension"]);
$formati = array('php','php3','exe','src','piff','dll','inc','sql');


if(in_array($ext,$formati)){
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>'Formato non valido')); 
exit;
} 
$file_name = $dir_upfile.$_FILES["upfile"]["name"];

if(is_uploaded_file($_FILES["upfile"]["tmp_name"])) {

if(move_uploaded_file($_FILES["upfile"]["tmp_name"],$file_name )) {	
$query_p =  "UPDATE `$tabella` SET `upfile` = '$file_name' WHERE `id` = $id LIMIT 1;";
mysql_query($query_p,CONNECT);
}
}
/*
//Salvataggio su CDN
$target_url = DMS_ROOT.'file_receive.php';
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
if($result == false) { echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>$result));   exit; } else {
$query_p =  "UPDATE `$tabella` SET `upfile` = '$file_name' WHERE `id` = $id LIMIT 1;";;
mysql_query($query_p,CONNECT);
}*/

} 



@mysql_close(CONNECT);
if(isset($_SESSION['POST_BACK_PAGE']) && !isset($_POST['goto'])  && !isset($_POST['info']) && !isset($_POST['reload'])) {
$rct = json_encode(array('action'=>'goto','class'=>'green','url'=>$_SESSION['POST_BACK_PAGE'],'esito'=>"Salvato Correttamente!")); 
} else if(isset($_POST['goto'])){
$rct = json_encode(array('action'=>'goto','class'=>'green','url'=>check($_POST['goto']),'esito'=>"Salvato Correttamente!")); 
} else if(isset($_POST['reload'])){
$rct = json_encode(array('action'=>'goto','class'=>'green','url'=>check($_POST['reload']).$id,'esito'=>"Salvato Correttamente!")); 
} else {
$rct = json_encode(array('action'=>'info','class'=>'green','url'=>'','esito'=>"Salvataggio scheda alle ".date('H:i:s - d/m/Y'))); 
}
//if(isset($_POST['function'])) $rct = json_encode(array('action'=>'realoadParent','class'=>'green','url'=>$_SESSION['POST_BACK_PAGE'],'esito'=>"Attendi Caricamento!")); 


echo $rct;
exit;

} else { 

$error = mysql_error();

//echo $query;
action_record('modify-error',$tabella,$id,base64_encode($query).$error);

//if(file_exists($allegati.$file_name)){unlink( $allegati.$file_name);}
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Errore 1101: Errore inserimento in database!<br />".$query.mysql_error(CONNECT))); 
@mysql_close(CONNECT);
exit;

}	

@mysql_close(CONNECT);


?>  
