<?php 


// Controllo Login
require_once('../../fl_core/autentication.php');
require_once('../../fl_set/librerie/pclzip.lib.php');

$ref = check($_SERVER['HTTP_REFERER']);


include('fl_settings.php'); // Variabili Modulo   

$dir_files = DMS_ROOT.base64_decode(check($_GET['d']))."/";
$file = $dir_files.base64_decode(check($_GET['f']));
$archive = new PclZip($file); 
$list  =  $archive->extract(PCLZIP_OPT_PATH, $dir_files);

							   
if ($list == 0) { die("Errore: ".$archive->errorInfo(true)); } else {
	
echo $list[1]['filename'];
foreach($list as $chiave => $valore) {

$file = $valore['stored_filename'];
echo $titolo_file = $file;
//foreach($valore as $chiave3 => $valore3) { echo $chiave3." = ".$valore3."<br>"; }

/*
if(mysql_query($query, CONNECT)){
ridimensiona($file,$dir,$dir."tumb/",140);
} else {
echo mysql_error();
@mysql_close(CONNECT);
}*/

$priority++;


}

@mysql_close(CONNECT);
 header('Location: '.$_SESSION['POST_BACK_PAGE']);
exit;

}



function ridimensiona($file,$dir_sorgente,$dir_destinazione,$max_size){

if(!is_dir($dir_destinazione)) { @mkdir($dir_destinazione,0777); }

if(function_exists('imagecreatetruecolor')) {


$sorgente = $dir_sorgente.$file;
$destinazione = $dir_destinazione.$file;

$info = pathinfo($dir_sorgente.$file); 
$noext = 1;

foreach($info as $key => $valore){
if($key == "extension") unset($noext);
}

if(isset($noext)){
header("Location: $rct?action=9&ok=1&dir=$user&esito=Formato non valido. (File Non Valido)"); 
exit;
} 

$ext = $info["extension"];


if(!$max_size)  $max_size = 800;

list($width,$height) = getimagesize($sorgente);


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
	$big_im = @imagecreatefromjpeg($sorgente);
	break;
	case $ext=='png' or $ext=='PNG';
	$big_im = imagecreatefrompng($sorgente);
	break;
	case $ext=='gif' or $ext=='GIF';
	$big_im = @imagecreatefromgif($sorgente);
	break;

}

$thumb_im=imagecreatetruecolor($bigw,$bigh);
@imagecopyresampled($thumb_im,$big_im, 0, 0, 0, 0, $bigw, $bigh, $width, $height);
@imagedestroy($big_im);
$big_im=$thumb_im;

switch($ext){
	case $ext=='jpg' or $ext=='jpeg' or $ext=='JPG' or $ext=='JPEG';
	@imagejpeg($big_im,$destinazione,90);
	break;
	case $ext=='png' or $ext=='PNG';
	@imagepng($big_im,$destinazione,90);
	break;
	case $ext=='gif' or $ext=='GIF';
	@imagegif($big_im,$destinazione,90);
	break;
}
@imagedestroy($big_im);


} else {

copy($sorgente, $destinazione);

}
return TRUE;
}






