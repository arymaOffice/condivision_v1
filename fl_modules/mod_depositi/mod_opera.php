<?php 


require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 




if (isset($_GET['id'])) 
{

$var = check($_GET['id']);
$data = '';

if(isset($_GET['data_valuta'])) {
$data = explode("/",check($_GET['data_valuta']));

$valore_inserito = $data[2].'-'.$data[1].'-'.$data[0];
if ( !preg_match( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/",$valore_inserito ) )
{ 
header("location: ./?action=9&esito=Data non valida!");
exit;
}
$data =  ', data_valuta = \''.$valore_inserito.'\'';

}
$query = "UPDATE $tabella SET status_pagamento = 1 $data WHERE id = $var";
mysql_query($query,CONNECT);

action_record('CONFERMA',$tabella,$var,base64_encode($query));

if(check($_GET['causale']) == 88){
$info = GRD($tabella,$var);
$query = "INSERT INTO `fl_commissioni` (`id`, `tipo_operazione`, `status_fatturazione`, `rif_fattura`, `proprietario`, `marchio`, `data_operazione`, `imponibile`, `aliquota_iva`, `commissione`, `rif_operazione`, `prodotto_ref`, `data_creazione`, `data_aggiornamento`) 
VALUES (NULL, '2', '0', '0', '".$info['proprietario']."', '".$info['marchio']."', '".$info['data_operazione']."', '0', '0', '".$info['dare']."', '".$var."', 'BONUS', NOW(),  NOW());";
if(!mysql_query($query,CONNECT)){
header("location: ./?action=9&esito=Confermata ma non inserita in commissioni.".mysql_error());
exit;
}
}


}



mysql_close(CONNECT);
header("location: ".$_SESSION['last_referrer']);
exit;




?>
