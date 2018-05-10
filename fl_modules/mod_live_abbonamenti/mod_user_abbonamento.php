<?php 

// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');


$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
$data_set =  '';
include 'fl_settings.php';
$new_button = '';
include "../../fl_inc/headers.php";

?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/testata.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/menu.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/module_menu.php'); ?>
    
<?php
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
	$query = "SELECT *,DATE_FORMAT(data_avvio,'%d/%m/%Y %H:%i:%s ') as data_format_start,DATE_FORMAT(data_fine,'%d/%m/%Y %H:%i:%s ') as data_format_end  FROM fl_abb_user  abb_us JOIN fl_abbonamenti abb ON abb.id = abb_us.id_abb RIGHT JOIN fl_account acc ON acc.id = abb_us.id_user  LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
?>
<table class="dati" summary="Dati">
  <tr>
    <th>User</th>
    <th>Data Inizio</th>
    <th>Data Fine</th>
    <th>Nome abbonamnento</th>
    <th></th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
			echo "<tr>"; 
			echo "<td>".$riga['user']."</td>";
			echo "<td>".$riga['data_format_start']."</td>";	
			echo "<td>".$riga['data_format_end']."</td>";	
			echo "<td>".$riga['nome']."</td>";	
            echo "<td><a href=''>Gestisci Abbonamento</a></td>";
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); ?>
