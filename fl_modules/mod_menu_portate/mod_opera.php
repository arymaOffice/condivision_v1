<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 


// Modifica Stato se è settata $stato	
if(isset($_GET['stato_menu_portata'])) { 
$stato_menu_portata = check($_GET['stato_menu_portata']);
$id = check($_GET['id']);
$query1 = "UPDATE $tabella SET stato_menu_portata = $$stato_menu_portata WHERE `id` = $id";
mysql_query($query1,CONNECT);	
}


if(isset($_GET['creaVarianteMenu'])) { 

$menuId = check($_GET['creaVarianteMenu']);
$idPiatto = check($_GET['idPiatto']);
$synId = check($_GET['synId']);

// Creo copia e aggiorno il piatto
$newPiatto = copy_record('fl_ricettario',$idPiatto);
$aggiornaPiatto = "UPDATE fl_ricettario SET variante = $idPiatto WHERE `id` = $newPiatto";
mysql_query($aggiornaPiatto,CONNECT);	

//Aggiorno l'associazione piatto/menu
$query1 = "UPDATE fl_synapsy SET id2 = $newPiatto WHERE `id` = $synId";
mysql_query($query1,CONNECT);	

}




mysql_close(CONNECT);
header("Location: ".$_SERVER['HTTP_REFERER'].'#p'.$newPiatto); 
exit;

?>
