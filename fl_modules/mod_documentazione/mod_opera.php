<?php 


// Controllo Login
require_once('../../fl_core/autentication.php');

$ref = $_SERVER['HTTP_REFERER'];
$baseref = explode('?', $ref);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];


include('fl_settings.php'); // Variabili Modulo   



//Inserisci Aggiorna
if(isset($_POST['titolo'])) { 

$relation = check($_POST['relation']);
$cat = check($_POST['cat']);
$modulo = check($_POST['modulo']);
$contenuto = check($_POST['contenuto']);



$obbligatorio = array();
foreach($_POST as $chiave => $valore){

if(in_array($chiave,$obbligatorio)) {

if($valore == ""){
@mysql_close(CONNECT);
$chiave = ucfirst($chiave);
header("Location: $rct?$val&action=9&ok=1&cat=$cat&contenuto=$relation&esito=Inserire valore per il campo $chiave");
exit;
exit;}}}
$mese_id = "";
$anno_id = "";
$titolo = check(@$_POST['titolo']);

if(isset($_POST['anno'])) {
$mese_id = check($_POST['titolo']);
$anno_id = check($_POST['anno']);
$titolo = $mesi[$mese_id]." ".check($_POST['anno']);
}

$versione = check(@$_POST['versione']);
$protocollo = check(@$_POST['protocollo']);
$revisionato = check(@$_POST['revisionato']);
$status = check(@$_POST['status']);
$lang = check(@$_POST['lang']);
$data = time();



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
$target_url = 'http://cdn.goservizi.it/file_receive.php';
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

/*
if(is_uploaded_file($_FILES['upfile']['tmp_name'])) {


move_uploaded_file($_FILES['upfile']['tmp_name'], $dir_documenti.$doc_dir."/".$file_name.'.'.$ext); 


} else {

header("Location: $rct?action=9&ok=1&cat=$cat&contenuto=$relation&contenuto=$contenuto&modulo=$modulo&esito=Errore 1103: Impossibile caricare il file, contatta il webmaster."); 
exit;

}*/


} else {

header("Location: $rct?action=9&ok=1&cat=$cat&contenuto=$relation&esito=Errore 1104: Selezionare il file da caricare."); 
exit;

} 



$query = "INSERT INTO `fl_files` ( `id` , `status` , `modulo` ,`contenuto` ,`relation` , `cat` , `titolo` , `protocollo` , `versione` , `revisionato` , `lang` , `file` , `data` ,`mese` ,`anno` , `download` )
			VALUES ('', '$status','$modulo','$contenuto', '$relation', '$cat', '$titolo', '$protocollo', '$versione', '$revisionato', '$lang', '$file_name', '$data','$mese_id','$anno_id', '0');";

if(mysql_query($query, CONNECT)){

@mysql_close(CONNECT);
header("Location: $rct?action=9&ok=1&cat=$cat&contenuto=$relation&contenuto=$contenuto&modulo=$modulo&esito=CARICATO CORRETTAMENTE: $file_name.$ext");
exit;	

} else {

echo mysql_error(CONNECT).$tabella;
@mysql_close(CONNECT);
header("Location: $rct?action=9&ok=1&cat=$cat&contenuto=$relation&contenuto=$contenuto&modulo=$modulo&esito=ERROR 1101: Errore di Inserimento in database!");
exit;}
}
?> 
