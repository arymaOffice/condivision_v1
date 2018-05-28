<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

 
include("../../fl_inc/headers.php");?>

<?php include('../../fl_inc/testata.php'); ?>
<?php include('../../fl_inc/menu.php'); ?>
<?php include('../../fl_inc/module_menu.php'); ?>

<h1>Potential Import</h1>   
 <div class="filtri">
      
       
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$query = "SELECT * FROM status_potential;";
	
	$risultato = mysql_query($query, CONNECT);
	echo "<h2>".mysql_affected_rows()."</h2>";
	while ($riga = mysql_fetch_array($risultato)) 
	{
	

				
	$query = "INSERT INTO `fl_potentials` (`id`, `in_use`, `status_potential`, `marchio`, `source_potential`, `data_aggiornamento`, `is_customer`, `paese`, `nome`, `cognome`, `email`, `telefono`, `mansione`, `messaggio`, `note`, `operatore`, `proprietario`, `data_creazione`, `ip`) 
	VALUES (".$riga['ID'].", '0', '0', '0', '0', NOW(), '0', '0', '".$riga['Name']."', '', '".$riga['email']."', '".$riga['phone']."', '0', '".str_replace("'"," ",stripslashes($riga['cover']))."', '"."Nationality: ".$riga['nationality']."<br > Experience: ".$riga['job']."', '1', '1', '2014-10-15', '');";	
	if(	$riga['phone'] != '') {
	if(mysql_query($query,CONNECT)) {
		echo "Iserito: ".$riga['email']."<br>";
		//mysql_query("DELETE FROM status_potential WHERE id = ".$riga['ID']." LIMIT 1;",CONNECT);
	} else { echo mysql_error(); }
	}
			
	}
	

?>
