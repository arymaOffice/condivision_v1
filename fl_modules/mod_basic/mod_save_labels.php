<?php 

session_start(); 
require_once('../../fl_core/settings.php'); 


$ref =  str_replace('new','',@$_SERVER['HTTP_REFERER']);
$baseref = @explode('?', @$ref);
$rct = $baseref[0]; 
$val = (count($baseref) > 1) ? $baseref[1] : "";
$valb = explode('#',$val);
$vars = $valb[0];


//Funzione Connect 
$connect = connect($host, $login, $pwd, $db);
$tabella = 'fl_labels';

$campi = array();
$labels = array();
$tipi = array();
$richiesto = array();
$headers = array();
$help = array();

//Aggiorna
function not_doing($who){
$not_in = array("gtx","id");
if(!is_numeric($who) && !in_array($who,$not_in)) return true;	
}




foreach($_POST as $chiave => $valore){

if(not_doing($chiave)){

if(strstr($chiave,"fl_campo_")){ array_push($campi,$valore); }
if(strstr($chiave,"fl_label_")){ array_push($labels,$valore); }
if(strstr($chiave,"fl_tipo_")){ array_push($tipi,$valore); }
if(strstr($chiave,"fl_richiesto_")){ array_push($richiesto,$valore); }
if(strstr($chiave,"fl_header_")){ array_push($headers,$valore); }
if(strstr($chiave,"fl_help_")){ array_push($help,$valore); }
}

}
$ic = count($labels);
for($i=0;$i<$ic;$i++){
$query = "SELECT * FROM `$tabella` WHERE campo = '".$campi[$i]."' LIMIT 1;";
$rus = mysql_query($query, $connect);
if(mysql_affected_rows() <= 0){

$query = "INSERT INTO `$tabella` ( `id` , `campo` , `label` , `tipo` , `richiesto` ,`header`, `help` ) 
						  VALUES ('', '".$campi[$i]."', '".str_replace("</p>","",str_replace("<p>","",$labels[$i]))."', '".$tipi[$i]."', '".$richiesto[$i]."', '".$headers[$i]."', '".$help[$i]."');";
if(mysql_query($query, $connect)) echo "<p>Inserita label per: ".$campi[$i]." - Etichetta: ".$labels[$i]."</p>\r\n";

} else {

$query = "UPDATE `$tabella` SET `label` = '".str_replace("</p>","",str_replace("<p>","",$labels[$i]))."',`tipo` = '".$tipi[$i]."',`richiesto` = '".$richiesto[$i]."', `header` = '".$headers[$i]."' ,`help` = '".$help[$i]."' WHERE `campo` = '".$campi[$i]."' LIMIT 1 ;";
if(mysql_query($query, $connect)) echo "<p>Etichetta per ".$campi[$i]." Aggiornata!</p>\r\n";}

}



@mysql_close($connect);


?>  
