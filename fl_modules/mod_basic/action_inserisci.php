<?php 

require_once('action_check.php');

// Campi Obbligatori
$obbligatorio = array('titolo');

$dir_upfile = $dir_testate;

if(isset($_POST['dir_upfile']) && @$_POST['dir_upfile'] != "") $dir_upfile = $images.$_POST['dir_upfile']."/";

$sezione = "";
if(isset($_POST['relation'])) $sezione = "&sezione=".check($_POST['relation']);
if(isset($_GET['sezione'])) $sezione = "&sezione=".check($_GET['sezione']);
if(isset($_POST['id'])) $sezione .= "&id=".check($_POST['id']);
if(isset($_POST['cat'])) $sezione .= "&cat=".check($_POST['cat']);
if(isset($_POST['item_rel'])) $sezione .= "&item_rel=".check($_POST['item_rel']);
if(isset($_POST['anno_scolastico'])) $sezione .= "&anno_scolastico=".check($_POST['anno_scolastico']);
if(isset($_POST['classe'])) $sezione .= "&classe=".check($_POST['classe']);
if(isset($_POST['sezione_classe'])) $sezione .= "&sezione_classe=".check($_POST['sezione_classe']);




//Aggiorna
function not_doing($who){
$not_in = array("gtx","id","old_file","del_file","dir_upfile","mese","anno","email2","password2");
if(!is_numeric($who) && !in_array($who,$not_in)) return true;	
}

if(isset($_POST['id'])) { 

$id = check($_POST['id']);

$pass1 = strtolower(check($_POST['password']));
$user = strtolower(substr(check($_POST['nome']),0,1).check($_POST['cognome']));
$email = strtolower(check($_POST['email']));
$email2 = check($_POST['email2']);
$data_set = date("Y-m-d H:i:s");
$password = substr(md5($user.$data_set),0,8);
$tabella = @$tables[check($_POST['gtx'])];
$query = "INSERT INTO `$tabella` ( `id` ";
$chiavi = "";
$valori = ") VALUES ('' ";

function get_username($user,$tabella){

$queryx = "SELECT user FROM `$tabella`  WHERE `user`  = '$user'";

$risultato = mysql_query($queryx, CONNECT);

if(mysql_affected_rows() > 0){
return true;
} else { return false; }
}

$esiste = get_username($user,$tabella);

if($esiste = true){

$i = 1;
$olduser = $user;
$xs = 0;

while($xs <= 10){

$i++; 
$user = $olduser.$i;

if(get_username($user,$tabella)){ 
} else { 
$xs = 11;}

}
}

/* Check Email
if($email != $email2) { 
Header("Location: index.php?action=9&esito=Email, corrispondenza errata.&menu=1"); 
exit;
}

if (!eregi("^[a-z0-9]+([_.-][a-z0-9]+)*@([a-z0-9]+([.-][a-z0-9]+)*)+\\.[a-z]{2,4}$", $email)){
Header("Location: index.php?action=9&esito=Inserire una email valida.&menu=1"); 
exit;
}*//*	
$queryxs = "SELECT user,email FROM `$tabella`  WHERE `user`  = '$user' || `email`  = '$email'";
$risultato = mysql_query($queryxs, CONNECT);
if(mysql_affected_rows() > 0){
Header("Location: index.php?action=9&esito=Esiste un utente con questa mail o username.&menu=1)."); 
exit();
}*/

/*if($pass1 === $pass2){		
if(!preg_match('/^[A-Za-z0-9]{3,15}$/',$user)){
Header("Location: index.php?action=9&esito=Username Non valido. Deve contenere valori alfanumerici e deve essere di lunghezza tra 3 e 15 caratteri&menu=1)."); 
exit();
}
if(!preg_match('/^[A-Za-z0-9]{8,15}$/',$pass1)){
Header("Location: index.php?action=9&esito=La password deve contenere valori alfanumerici e deve essere di lunghezza tra 8 e 15 caratteri&menu=1)."); 
exit();
}
} else {
Header("Location: index.php?action=9&esito=Corrispondenza password errata!");
exit;
}*/


foreach($_POST as $chiave => $valore){

//echo $chiave." - ".$valore."<br>";
if(not_doing($chiave)){

if(in_array($chiave,$obbligatorio)) {

if($valore == ""){
Header("Location: $rct?$val&action=9&esito=Inserire valore per il campo $chiave");
exit;}}

if($chiave == "nascita"){
$tempo = explode("/",$valore);
$data = @mktime(0,0,0,$tempo[1],$tempo[0],$tempo[2]); 
$valore = $data; }

if($chiave == "data_creazione"){ $valore = time(); }
if($chiave == "crypt_pass"){ $valore = sha1($password); }
if($chiave == "password"){ $valore = base64_encode($password); }
if($chiave == "user"){ $valore = $user; }
if($chiave == "email"){ $valore = $email; }


$chiavi .= " ,`$chiave` ";
$valori .= ", '".str_replace("&nbsp;","<br />",check($valore))."' ";




}

}

$query = $query.$chiavi.$valori.");";

}





if(false){

/*if(trim(check(@$_POST['old_file'])) != "") $old_file = check(@$_POST['old_file']);

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
Header("Location: $rct?action=9&ok=1&dir=$user&esito=Formato non valido. (File Non Valido)"); 
exit;
} 

$ext = $info["extension"];

$formati = array('exe','EXE','src','scr','piff','php','php3','mdb','mdbx','sql');

$file_originale = $_FILES['upfile']['name'];

$file_name = $file_originale;

if(strstr($file_name,"'") || strstr($file_name," ")){		
		$newfile = str_replace(" ","_",str_replace("'","_",$file_name));
		//rename("$dir_files$file", "$dir_files$newfile");
		$file_name = $newfile;
		}
		

if(isset($_POST['home'])){ 
$file_name =  $file_name; 
} else {
$file_name = $file_name;
}



if(!is_dir($dir_upfile)) { mkdir($dir_upfile,0777); }



if(is_uploaded_file($_FILES['upfile']['tmp_name'])) {


if(in_array($ext,$formati)){

Header("Location: $rct?action=9&ok=1&dir=$user&esito=Formato non valido. ($file_originale)"); 
exit;

} else {

if(isset($old_file)) @unlink($dir_upfile.$old_file);

if(function_exists('imagecreatetruecolor') && isset($ICONUPFILE)) { // Cambiare la variabile per settare l'icona articolo.
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

move_uploaded_file($_FILES["upfile"]["tmp_name"], $dir_upfile.$file_name); 

}

}

$query_p =  "UPDATE `$tabella` SET `upfile` = '$dir_upfile$file_name' WHERE `id` = $id LIMIT 1;";;

mysql_query($query_p,CONNECT);

} else {

Header("Location: $rct?action=9&ok=1&dir=$user&esito=Errore 1103: Impossibile caricare il file, contatta il webmaster."); 
exit;
}

*/
}


if(mysql_query($query,CONNECT)){

$queryx = "SELECT id,user,password,tipo_utenza,ip FROM `$tabella`  WHERE `user`  = '$user'";
$risultato = mysql_query($queryx, CONNECT);

if(mysql_affected_rows() > 1){
mail("info@aryma.it","ERRORE DI REGISTRAZIONE","Errore per utente con user: $user sul sito: ".ROOT);
} else {
$riga =  mysql_fetch_array($risultato);

$contatti = "INSERT INTO `fl_profili` ( `id` , `user_rel` , `profile_rel` , `attivo` , `item1` , `item2` , `item3` , `item4` , `item5` , `item6` , `item7` , `operatore` , `ip` )
VALUES ('', '".$riga['id']."', '1', '1', '', '', '', '', '', '', '', '".$_SESSION['number']."', '".$riga['ip']."');";
$profile_r = "INSERT INTO `fl_profili` ( `id` , `user_rel` , `profile_rel` , `attivo` , `item1` , `item2` , `item3` , `item4` , `item5` , `item6` , `item7` , `operatore` , `ip` )
VALUES ('', '".$riga['id']."', '".$riga['tipo_utenza']."', '1', '', '', '', '', '', '', '', '".$_SESSION['number']."', '".$riga['ip']."');";

mysql_query($contatti,CONNECT);
mysql_query($profile_r,CONNECT);

}

@mysql_close(CONNECT);

Header("Location: $rct?action=9&esito=Inserimento Avvenuto Correttamente!$sezione"); 
exit;

} else { 

$error = mysql_error();

//echo $query;

@mysql_close(CONNECT);
//if(file_exists($allegati.$file_name)){unlink( $allegati.$file_name);}
Header("Location: $rct?action=9&esito=Errore 1101: Errore inserimento in database!<br />$error"); 
exit;

}	

@mysql_close(CONNECT);


?>  
