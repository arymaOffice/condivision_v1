<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 


if(isset($_POST['product_id'])) {

$product_id = check($_POST['product_id']);
$produttore = (isset($_POST['produttore'])) ? check($_POST['produttore']) : 1;
$value1 = (isset($_POST['value1'])) ? check($_POST['value1']) : '';
$value2 = (isset($_POST['value2'])) ? check($_POST['value2']) : '';
$value3 = (isset($_POST['value3'])) ? check($_POST['value3']) : '';
$value4 = (isset($_POST['value4'])) ? check($_POST['value4']) : '';
$value5 = (isset($_POST['value5'])) ? check($_POST['value5']) : '';
$value6 = (isset($_POST['value6'])) ? check($_POST['value6']) : '';
$value7 = (isset($_POST['value7'])) ? check($_POST['value7']) : '';
$value8 = (isset($_POST['value8'])) ? check($_POST['value8']) : '';
$value9 = (isset($_POST['value9'])) ? check($_POST['value9']) : '';
$value10 = (isset($_POST['value10'])) ? check($_POST['value10']) : '';


$query = "INSERT INTO `fl_prodotti_referenze` (`id`, `parent_id`,`produttore`, `attivo`, `value1`, `value2`, `value3`, `value4`, `value5`,`value6`, `value7`, `value8`, `value9`, `value10`,  `data_creazione`, `data_aggiornamento`) 
VALUES (NULL, '$product_id','$produttore', 1, '$value1', '$value2', '$value3', '$value4', '$value5','$value6','$value7','$value8','$value9','$value10', NOW(), NOW());";
mysql_query($query,CONNECT);	
mysql_close(CONNECT);
header("Location: ./mod_referenze_prodotto.php?product_id=$product_id"); 
exit;
		  
}

function error(){
	header( "HTTP/1.1 404 Error" ); 
	exit;
	}

$esiti = array();
$esiti[0] = "Inserire il file da caricare!";
$esiti[1] = "Caricamento avvenuto correttamente";
$esiti[2] = "Impossibile Caricare il file";
$esiti[3] = "Estensione del file non valida!";
$esiti[4] = "Formato file non valido.";
$esiti[5] = "File esistente";
$esiti[6] = "Il file contiente errori.";
$esiti[7] = "Impossibile creare cartella di destinazione.";
$esiti[8] = "Impossibile creare cartella per le anteprime.";
$esiti[9] = "Cartella di destinazione non scrivibile.";
$formati = array('exe','EXE','src','scr','piff','php','php3','mdb','mdbx','sql');


function ridimensiona2($source,$folder='./',$file_name='',$max_size=200){

if(function_exists('imagecreatetruecolor')) {

$info = getimagesize($source);
/* AZIONI RIDIMENSIONAMENTO */
list($width,$height) = @getimagesize($source);

$ext = image_type_to_extension($info[2]);


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
	$big_im = @imagecreatefromjpeg($source);
	break;
	case $ext=='png' or $ext=='PNG';
	$big_im = imagecreatefrompng($source);
	break;
	case $ext=='gif' or $ext=='GIF';
	$big_im = @imagecreatefromgif($source);
	break;

}

$thumb_im=imagecreatetruecolor($bigw, $bigh);
@imagecopyresampled($thumb_im,$big_im, 0, 0, 0, 0, $bigw, $bigh, $width, $height);
@imagedestroy($big_im);
$big_im=$thumb_im;

switch($ext){
	case $ext=='jpg' or $ext=='jpeg' or $ext=='JPG' or $ext=='JPEG';
	@imagejpeg($big_im,$folder.$file_name.'.'.$ext,80);
	break;
	case $ext=='png' or $ext=='PNG';
	@imagepng($big_im,$folder.$file_name.'.'.$ext,80);
	break;
	case $ext=='gif' or $ext=='GIF';
	@imagegif($big_im,$folder.$file_name.'.'.$ext,80);
	break;
}
@imagedestroy($big_im);



} else {

echo "Le funzioni di ridimensionamento non sono attive!";

}

}



if(isset($_POST['id'])){

$source = $_FILES['file'];
$id = check($_POST['id']);
if(isset($_POST['folder'])) $folder = check($_POST['folder']);

/* Check Estensione */
$info = pathinfo($source['name']); 
foreach($info as $key => $valore){ if($key == "extension") $ext = $info["extension"]; }
if(!isset($ext)) error();
if(in_array(strtolower($ext),$formati)){ error(); } 
$file_name = $id.'.'.$ext;


/*Check Dir*/
if(!@is_dir($folder)) {  if(!@mkdir($folder,0777)) { return $esiti[7]; mysql_close(CONNECT);  break; } }
if(!is_writable($folder)) {  return $esiti[9]; mysql_close(CONNECT); break; }


if(is_uploaded_file($source['tmp_name'])){
	if(move_uploaded_file($source['tmp_name'],$folder.'/'.$file_name)){
	
	} else {
	error();
	}
	
}}


mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;

?>
