<?php


ini_set('display_errors',1);
error_reporting(E_ALL);

session_start();		
include('../../fl_core/settings.php');
require('../../fl_core/class/ARY_dataInterface.class.php');
$data_set = new ARY_dataInterface();
$causale = $data_set->data_get_items(82);


$data_oggi = date('Y-m-d');
if(isset($_GET['data'])) $data_oggi = check($_GET['data']);
$stringa  = '';


$query = "SELECT *, COUNT(*) as tot, SUM(dare) as tot_dare, SUM(avere) as tot_avere FROM `fl_pagamenti` WHERE  DATE(data_creazione) BETWEEN '$data_oggi' AND '$data_oggi' AND status_pagamento = 1 GROUP BY causale;";
$risultato = mysql_query($query, CONNECT);
while ($riga = mysql_fetch_array($risultato)) 
	{
		
		$query2 = "INSERT INTO `fl_operazioni_contabili` (`id`, `marchio`, `data_creazione`, `causale`, `operazione`,`totale`, `dare`, `avere`) 
		VALUES (NULL, '0', '$data_oggi', '".$riga['causale']."', '".$causale[$riga['causale']]."','".$riga['tot']."','".$riga['tot_dare']."', '".$riga['tot_avere']."');";	
 		mysql_query($query2, CONNECT);

 		$stringa .= "<p>".$data_oggi." - (".$riga['causale'].") ".$causale[$riga['causale']]." OPERAZIONI: ".$riga['tot']." DARE: ".$riga['tot_dare'].", AVERE: ".$riga['tot_avere']."</p>".mysql_error();
	

	}
	



mysql_close(CONNECT);	
echo $stringa; 
smail('michelefazio@aryma.it','Registrazione Operazioni data '.$data_oggi,$stringa);
smail('fersinoandrea@gmail.com','DEMO Registrazione Operazioni  Data '.$data_oggi,$stringa);


?>
