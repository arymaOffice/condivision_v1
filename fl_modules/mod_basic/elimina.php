<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

require_once('action_check.php');



if(isset($_GET['delFile'])) { 
$file= check($_GET['delFile']);
if(file_exists($file)){ @unlink($file); }
@mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit;
}

if(isset($_GET['unset'])) { 

if(!is_numeric($_GET['unset']) || !is_numeric($_GET['gtx'])) exit;

$id = $_GET['unset'];	
$tabella = $tables[check($_GET['gtx'])];
if(isset($_GET['tipologia'])) $tipologia = check($_GET['tipologia']);
$file = ($_GET['file'] != "" || $_GET['file'] != 0) ? check($_GET['file']) : "nofile";	
$parametri = "mod_".$tabella;

if(file_exists($file) && $tabella == "fl_files"){ @unlink($file); }

delete($tabella,$id,$$parametri,$cat_folder);


}



function delete($tabella,$id,$parametri,$cat_folder) {

$query = "DELETE FROM $tabella WHERE id = '$id' LIMIT 1";


if(mysql_query($query,CONNECT)) {

jorel_delete($tabella,$id,$parametri,$cat_folder);

@mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER']); 
exit;

} else {

@mysql_close(CONNECT);
header("Location: $referer&action=9&esito=Errore 1103: Errore cancellazione database!"); 
exit;
}

} // end delete function


function jorel_delete($tabella,$id,$parametri,$cat_folder) {


if(isset($parametri['cat_files'][0])) {
foreach($parametri['cat_files'] as $chiave => $valore) {
del_files(0,$valore,$id,$cat_folder[$valore]);
}}

}

function del_files($relation=0,$cat,$contenuto,$percorso) {



if($cat < 0) { echo "Devi fornire una categoria di files"; exit; }

$relation_check = ($relation != 0) ? "relation = $relation AND " : "";

$query ="SELECT * FROM fl_files WHERE $relation_check cat = $cat AND contenuto = $contenuto";

$risultato = mysql_query($query, CONNECT);
	while ($riga = mysql_fetch_array($risultato)) {
		if(unlink($percorso.$riga['file'])) {
		$del_query = "DELETE FROM fl_files WHERE $relation_check cat = $cat AND contenuto = $contenuto";
		
		if(!mysql_query($del_query, CONNECT)) { return false; }
		} else { echo "Non posso eliminare: ".$percorso.$riga['file']; exit; }
	}
	return true;
} // end del_files function


@mysql_close(CONNECT);

?>
