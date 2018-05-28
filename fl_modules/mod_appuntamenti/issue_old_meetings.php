<?php 



ini_set('display_errors',1);
error_reporting(E_ALL);

require_once('../../fl_core/settings.php');
include('fl_settings.php'); // Variabili Modulo 



$query = "SELECT * FROM `fl_meeting_agenda` WHERE `meeting_date` <  '".date('Y-m-d')."' AND (`issue` = 1 OR `issue` = 6);";
$risultato = mysql_query($query, CONNECT);

	while ($riga = mysql_fetch_array($risultato)) 
	{
	$update = "UPDATE `fl_potentials` SET `status_potential`= 3 , data_aggiornamento ='".date('Y-m-d H:i:00')."', `in_use` = '0' WHERE `id` = ".$riga['potential_rel'].";";	
	mysql_query($update, CONNECT);
		echo mysql_error();
	}

$update = "UPDATE `fl_meeting_agenda` SET `issue` = 4 WHERE `meeting_date` < '".date('Y-m-d')."' AND (`issue` = 1 OR `issue` = 6);";	
mysql_query($update, CONNECT);
	echo mysql_error();

$query = "SELECT * FROM `fl_meeting_agenda` WHERE `meeting_date` <  '".date('Y-m-d')."' AND `issue` = 0;";
$risultato = mysql_query($query, CONNECT);

while ($riga = mysql_fetch_array($risultato)) 
	{
	$update = "UPDATE `fl_potentials` SET `status_potential`= 1 , data_aggiornamento ='".date('Y-m-d H:i:00')."', `in_use` = '0' WHERE `id` = ".$riga['potential_rel'].";";	
	mysql_query($update, CONNECT);
	echo mysql_error();
	}

$update = "UPDATE `fl_meeting_agenda` SET `issue` = 3 WHERE `meeting_date` < '".date('Y-m-d')."' AND `issue` = 0;";	
mysql_query($update, CONNECT);
	echo mysql_error();



mysql_close(CONNECT);
exit;




?>
