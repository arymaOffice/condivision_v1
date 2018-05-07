<?php 

require_once('action_check.php');
include('../../fl_core/data_manager/array_statiche.php');

// Campi Obbligatori
$obbligatorio = array('importo','estremi_del_pagamento','titolo','fornitore','entrate','nome','codice_fiscale','nome_e_cognome','oggetto','email','numero_documento');

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
$not_in = array("info","gtx","id","old_file","del_file","dir_upfile","mese","anno","mandatory","mode","external","nominativo","data_creazione",'goto');
if(!is_numeric($who) && !in_array($who,$not_in)) return true;	
}


if(isset($_POST['id'])) { 

$id = check($_POST['id']);
$tabella = @$tables[check($_POST['gtx'])];
$query = "";


if(isset($_POST['codice_fiscale'])){
/*	
if ($id != 1 && isset($_POST['codice_fiscale']) && @!ereg($patterncod,check(@$_POST['codice_fiscale']))) {
header("Location: $rct?$val&error=1&action=9&esito=Inserire un codice fiscale corretto!$sezione");
exit;
}*/

include_once ("../../fl_core/data_manager/CodiceFiscale.class.php");
$patterncod= "^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$";

$cf = new CodiceFiscale();
$cf ->SetCF(check(@$_POST['codice_fiscale'])); 
if ($cf->GetCodiceValido()) {
} else {
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>$cf->GetErrore()));
exit;
}

}

if ($id != 1 && isset($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)    ) {
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire una mail corretta"));
exit;
}

if(isset($_POST['dare']) || isset($_POST['avere'])) {
if(check($_POST['dare'])+check($_POST['avere']) == 0){
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire un importo"));
exit;
}}

if(isset($_POST['telefono']) || isset($_POST['cellulare'])) {
if(strlen(check($_POST['telefono']).check($_POST['cellulare'])) < 6){
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Inserire almeno un numero di telefono"));
exit;
}}


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

$etichette = array('gruppo','giorni_lavorativi');
$campi_date = array('data_documento','data_operazione','data_emissione','data_scadenza','data_nascita','aggiornato','schedulazione');
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
if($chiave == "status_assistenza" && @$valore == 0 || $chiave == "status_pagamento" && @$valore == 0 && trim(@$_POST['user']) != ""){ $valore_inserito = 1; }

$query .= ",`$chiave` = '".$valore_inserito."'"; // Set chiave

}}



}

//echo $query; exit;
if($id == 1) { $id = new_inserimento($tabella,0); action_record('new',$tabella,$id,'New Empty Element');  $val = "external&id=$id"; }
$query = "UPDATE `$tabella` SET id = $id ".$query." WHERE `id` = $id LIMIT 1;";


if(@mysql_query($query,CONNECT)){
action_record('modify',$tabella,$id,base64_encode($query));
if(trim(check(@$_POST['old_file'])) != "") $old_file = check(@$_POST['old_file']);

// Cancella il file attuale se impostato
if(isset($_POST['del_file'])) {
@unlink($dir_upfile.$old_file);
$query_a =  "UPDATE `$tabella` SET `upfile` = '' WHERE `id` = $id LIMIT 1;";;
mysql_query($query_a,CONNECT);
}

//Compatibilita per Upload Files
if(!isset($_FILES)) $_FILES = $HTTP_POST_FILES;
if(!isset($_SERVER)) $_SERVER = $HTTP_SERVER_VARS;

$user = check($_POST['id']);


// Controllo ALLEGATO
if(trim(@$_FILES['upfile']['name']) != ""){




$info = pathinfo($_FILES['upfile']['name']); 
$noext = 1;

foreach($info as $key => $valore){
if($key == "extension") unset($noext);
}

if(isset($noext)){
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Formato non valido. (File Non Valido)$sezione")); 
exit;
} 

$ext = $info["extension"];

$formati = array('exe','EXE','src','scr','piff','php','php3','mdb','mdbx','sql');

$file_originale = $_FILES['upfile']['name'];

$file_name = explode(".",$file_originale);

if(strstr($file_name[0],"'") || strstr($file_name[0]," ")){		
		$newfile = str_replace(" ","_",str_replace("'","_",$file_name[0]));
		//rename("$dir_files$file", "$dir_files$newfile");
		$file_name = $newfile;
		}
		

if(isset($_POST['home'])){ 
$file_name = "File_".$user."_homeitem"; 
} else {
$file_name = $file_name[0]."_".$user;
}



if(!is_dir($dir_upfile)) { mkdir($dir_upfile,0777); }



if(is_uploaded_file($_FILES['upfile']['tmp_name'])) {


if(in_array($ext,$formati)){

echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Formato non valido. ($file_originale) ")); 
exit;

} else {

if(isset($old_file)) @unlink($dir_upfile.$old_file);

if(function_exists('imagecreatetruecolor')) {
$max_size = 80;
list($width,$height) = @getimagesize($_FILES['upfile']['tmp_name']);


if($width > $max_size || $height > $max_size) {

if($width>$height){
$bigw=$max_size;
$cal=$max_size/$width;
$bigh=$height*$cal;
}
else if($width<$height){
$bigh=$max_size;
$cal=$max_size/$height;
$bigw=$width*$cal;
}

} else { 
$bigw= $width;
$bigh= $height;
}



switch($ext){
	case $ext=='jpg' or $ext=='jpeg' or $ext=='JPG' or $ext=='JPEG';
	$big_im = @imagecreatefromjpeg($_FILES['upfile']['tmp_name']);
	break;
	case $ext=='png' or $ext=='PNG';
	$big_im = @imagecreatefrompng($_FILES['upfile']['tmp_name']);
	break;
	case $ext=='gif' or $ext=='GIF';
	$big_im = @imagecreatefromgif($_FILES['upfile']['tmp_name']);
	break;
		
}

$thumb_im=imagecreatetruecolor($bigw, $bigh);
@imagecopyresampled($thumb_im,$big_im, 0, 0, 0, 0, $bigw, $bigh, $width, $height);
@imagedestroy($big_im);
$big_im=$thumb_im;

switch($ext){
	case $ext=='jpg' or $ext=='jpeg' or $ext=='JPG' or $ext=='JPEG';
	@imagejpeg($big_im,$dir_upfile.$file_name.".".$ext,100);
	break;
	case $ext=='png' or $ext=='PNG';
	@imagepng($big_im,$dir_upfile.$file_name.".".$ext,100);
	break;
	case $ext=='gif' or $ext=='GIF';
	@imagegif($big_im,$dir_upfile.$file_name.".".$ext,100);
	break;
}
@imagedestroy($big_im);


} else {


move_uploaded_file($_FILES["upfile"]["tmp_name"], $dir_upfile.$file_name.".".$ext); 

}

}

$query_p =  "UPDATE `$tabella` SET `upfile` = '$dir_upfile$file_name.$ext' WHERE `id` = $id LIMIT 1;";;

mysql_query($query_p,CONNECT);

} else {

echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Errore 1103: Impossibile caricare il file, contatta il webmaster.")); 
exit;
}


} 





@mysql_close(CONNECT);
if(isset($_SESSION['POST_BACK_PAGE']) && !isset($_POST['goto'])  && !isset($_POST['info'])) {
echo json_encode(array('action'=>'goto','class'=>'green','url'=>$_SESSION['POST_BACK_PAGE'],'esito'=>"Salvato Correttamente!")); 
} else if(isset($_POST['goto'])){
echo json_encode(array('action'=>'goto','class'=>'green','url'=>check($_POST['goto']),'esito'=>"Salvato Correttamente!")); 
} else {
echo json_encode(array('action'=>'info','class'=>'green','url'=>'','esito'=>"Salvataggio scheda alle ".date('H:i:s - d/m/Y'))); 
}
exit;

} else { 

$error = mysql_error();

//echo $query;
action_record('modify-error',$tabella,$id,base64_encode($query).$error);
@mysql_close(CONNECT);
//if(file_exists($allegati.$file_name)){unlink( $allegati.$file_name);}
echo json_encode(array('action'=>'info','class'=>'red','url'=>'','esito'=>"Errore 1101: Errore inserimento in database!<br />$error")); 
exit;

}	

@mysql_close(CONNECT);


?>  
