<?php


ini_set('display_errors',1);
error_reporting(E_ALL);






function file_upload($source,$folder="",$return_name=FALSE,$file_newname="",$accepted_formats=array(),$denied_formats=array(),$sovrascrivi=0,$width=0,$height=0,$tumbnail=0) {

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

$esito = 0;

if($source != ""){

$info = pathinfo($source['name']); 

/* Check Estensione */
foreach($info as $key => $valore){
if($key == "extension") $ext = $info["extension"];
}
if(!isset($ext)){
return 3;
break;
} 
$formati = array('exe','EXE','src','scr','piff','php','php3','mdb','mdbx','sql');
if(is_array($denied_formats)) $formati = array_merge($formati,$denied_formats);
if(in_array(strtolower($ext),$formati)){
return 4; 
break;
} 
if(isset($accepted_formats[0])) { if(!in_array(strtolower($ext),$accepted_formats)){
return 3; 
break;
} }

/* Check Nome */
if(($file_newname != "")) { 
	$file_name = $file_newname; 
	} else {
	$file_originale = $source['name'];
	$file_name = explode(".",$file_originale);
	if(strstr($file_name[0],"'") || strstr($file_name[0]," ")){		
	$file_name = str_replace(" ","_",str_replace("'","_",$file_name[0]));
	} else { $file_name = $file_name[0]; }
}

/* Controllo Upload e sovrascrittura */		
if(is_uploaded_file($source['tmp_name'])) {
if(!@is_dir($folder)) {  if(!@mkdir($folder,0777)) { return $esiti[7];  break; } }
if(!is_writable($folder)) {  return $esiti[9]; break; }

if(file_exists($folder.$file_name.".".$ext) && $sovrascrivi == 0){
  $x = 2;
  while($x) { 
  if(!file_exists($folder.$file_name."_".$x.".".$ext)){
  $file_name = $file_name."_".$x;
  unset($x);
  break;
  } else { $x++; }
  }			 		
} 
$esito = (move_uploaded_file($source["tmp_name"],$folder.$file_name.".".$ext)) ? 1 : 9;
}
}

/* Controllo virus php */
//$exif = @exif_read_data($folder.$file_name.".".$ext,NULL,1,0);
/*@foreach(@$exif as $key => $section) {
    if(!is_array($section)) { 
	//echo $section; 
	}else{
	foreach ($section as $name => $val) {
     //echo "$key.$name: $val<br />\n";
	 if(strstr($val,'<?')) { unlink($folder.$file_name.".".$ext); $esito = 6; break;  } 
	 if(strstr($val,'mysql')) { unlink($folder.$file_name.".".$ext); $esito = 6; break;  } 
	 if(strstr($val,'php')) { unlink($folder.$file_name.".".$ext); $esito = 6; break;  } 
    }}
}

/* RITORNO VALORE */
if($return_name == TRUE) { 	if($esito == 1) { return $file_name.".".$ext; } } else { return $esiti[$esito]; }


} 


function ridimensiona($source){

if(function_exists('imagecreatetruecolor')) {

/* AZIONI RIDIMENSIONAMENTO */
list($width,$height) = @getimagesize($source['tmp_name']);


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
	$big_im = @imagecreatefromjpeg($source['tmp_name']);
	break;
	case $ext=='png' or $ext=='PNG';
	$big_im = imagecreatefrompng($source['tmp_name']);
	break;
	case $ext=='gif' or $ext=='GIF';
	$big_im = @imagecreatefromgif($source['tmp_name']);
	break;

}

$thumb_im=imagecreatetruecolor($bigw, $bigh);
@imagecopyresampled($thumb_im,$big_im, 0, 0, 0, 0, $bigw, $bigh, $width, $height);
@imagedestroy($big_im);
$big_im=$thumb_im;

switch($ext){
	case $ext=='jpg' or $ext=='jpeg' or $ext=='JPG' or $ext=='JPEG';
	@imagejpeg($big_im,$gallery.$user."/".$file_name.'.'.$ext,80);
	break;
	case $ext=='png' or $ext=='PNG';
	@imagepng($big_im,$gallery.$user."/".$file_name.'.'.$ext,80);
	break;
	case $ext=='gif' or $ext=='GIF';
	@imagegif($big_im,$gallery.$user."/".$file_name.'.'.$ext,80);
	break;
}
@imagedestroy($big_im);
if($width>$height){
$bigw=120;
$cal=120/$width;
$bigh=$height*$cal;
}
else if($width<$height){
$bigh=120;
$cal=120/$height;
$bigw=$width*$cal;
}
switch($ext){
	case $ext=='jpg' or $ext=='jpeg' or $ext=='JPG' or $ext=='JPEG';
	$big_im2 = @imagecreatefromjpeg($source['tmp_name']);
	break;
	case $ext=='png' or $ext=='PNG';
	$big_im2 = @imagecreatefrompng($source['tmp_name']);
	break;
	case $ext=='gif' or $ext=='GIF';
	$big_im2 = @imagecreatefromgif($source['tmp_name']);
	break;

	break;
}
$thumb_im2=@imagecreatetruecolor($bigw, $bigh);
imagecopyresampled($thumb_im2,$big_im2, 0, 0, 0, 0, $bigw, $bigh, $width, $height);
imagedestroy($big_im2);

$big_im2=$thumb_im2;
switch($ext){
	case $ext=='jpg' or $ext=='jpeg' or $ext=='JPG' or $ext=='JPEG';
	@imagejpeg($big_im2,$gallery.$user."/tumb/".$file_name.'.'.$ext,80);
	break;
	case $ext=='png' or $ext=='PNG';
	@imagepng($big_im2,$gallery.$user."/tumb/".$file_name.'.'.$ext,80);
	break;
	case $ext=='gif' or $ext=='GIF';
	@imagegif($big_im2,$gallery.$user."/tumb/".$file_name.'.'.$ext,80);
	break;
}
@imagedestroy($big_im2);


} else {

echo "Le funzioni di ridimensionamento non sono attive!";

}

}

?>