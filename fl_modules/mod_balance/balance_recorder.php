<?php

if($_SERVER["REMOTE_ADDR"] === $_SERVER['REMOTE_ADDR']) {

session_start();		
include('../../fl_core/settings.php');
include('../../fl_core/data_manager/array_statiche.php');
include('../../fl_core/category/proprietario.php');
$saldi = '';
$data_oggi = date('Y-m-d');

	
$saldi = '<h3  style="font-family: sans-serif;">Registrazione Saldi Inizio Giornata ('.date('H:i').')</h3>';	

foreach($affiliato as $valores => $label){ 

mysql_query('SELECT * FROM `fl_balance_recorder` WHERE data = \''.$data_oggi.'\' AND proprietario = '.$valores.';',CONNECT);

if(mysql_affected_rows(CONNECT) < 1) {
$query = "INSERT INTO `fl_balance_recorder` (`id`, `marchio`, `proprietario`, `data`, `saldo_iniziale`, `saldo_finale`) 
VALUES (NULL, '0', '$valores', NOW(), '".get_saldo($valores)."', '0');";
$saldi .= '<p  style="font-family: sans-serif;">'.$affiliato[$valores].' = '.get_saldo($valores).' EURO</p>';
if(!mysql_query($query,CONNECT)) mail(mail_admin,'Errore Registrazione Saldo per '.$label.' in data '.date('d/m/Y'),'Da '.$_SERVER['REMOTE_ADDR'].mysql_error());
} else { $saldi .= '<p  style="font-family: sans-serif;">'.$affiliato[$valores].' = '.get_saldo($valores).' EURO [<span style="color: red;">SALDO GIA PRESENTE</span>]</p>';
}


}


$query = "SELECT *, COUNT(*) as tot, SUM(dare) as tot_dare, SUM(avere) as tot_avere FROM `fl_pagamenti` WHERE causale != 85 AND data_creazione BETWEEN '$data_oggi 00:00:00' AND '$data_oggi 23:59:59' AND status_pagamento = 1 GROUP BY causale;";
$risultato = mysql_query($query, CONNECT);
while ($riga = mysql_fetch_array($risultato)) 
	{
		
		$query2 = "INSERT INTO `fl_operazioni_contabili` (`id`, `marchio`, `data_creazione`, `causale`, `operazione`,`totale`, `dare`, `avere`) 
		VALUES (NULL, '0', '$data_oggi', '".$riga['causale']."', '".$riga['rif_operazione']."','".$riga['tot']."','".$riga['tot_dare']."', '".$riga['tot_avere']."');";	
 		mysql_query($query2, CONNECT);
	

	}
	

$query = "SELECT *, COUNT(*) as tot, SUM(dare) as tot_dare, SUM(avere) as tot_avere FROM `fl_pagamenti` WHERE causale = 85 AND data_creazione BETWEEN '$data_oggi 00:00:00' AND '$data_oggi 23:59:59' AND status_pagamento = 1 GROUP BY rif_operazione;";
$risultato = mysql_query($query, CONNECT);
while ($riga = mysql_fetch_array($risultato)) 
	{
		
		$query3 = "INSERT INTO `fl_operazioni_contabili` (`id`, `marchio`, `data_creazione`, `causale`, `operazione`,`totale`, `dare`, `avere`) 
		VALUES (NULL, '0', '$data_oggi', '".$riga['causale']."', '".$riga['rif_operazione']."','".$riga['tot']."', '".$riga['tot_dare']."', '".$riga['tot_avere']."');";	
 		mysql_query($query3, CONNECT);
		

	}

	
mysql_close(CONNECT);	
mail(mail_admin,'Registrazione Saldi data '.date('d/m/Y'),$saldi,intestazioni);
mail('fersinoandrea@gmail.com','Registrazione Saldi Data '.date('d/m/Y'),$saldi,intestazioni);
echo $saldi;
}




?>
