<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$id = check($_GET['id']);
include("../../fl_inc/headers.php"); 
?>





<h1>Conferme di Lettura <a href="#" class="noprint" onClick=" window.print();"><i class="fa fa-print"></i></a></h1>

<?php
	


						
	$query = "SELECT * FROM `fl_conferme` WHERE modulo = 1 AND contenuto = $id ORDER BY data_apertura DESC;";

	$risultato = mysql_query($query, CONNECT);
 
		
	?>

  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
        <th style="width: 1%;"></th>
        <th scope="col" class="titolo">Account</th>
        <th scope="col" class="home">Data Apertura</th>
        <th scope="col" class="home">Conferma</th>
      </tr>
	  
	<?php 
	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessuna apertura</td></tr>";		}
	while ($riga = mysql_fetch_array($risultato)) 
	{
		   $colore = ($riga['conferma'] == 1) ? 'green' : 'red';
	   	   $obi = ($riga['conferma'] == 1) ? 'Si' : 'No';

			echo "<tr>"; 	
			echo "<td class=\"$colore\"><span class=\"Gletter\"></span></td>"; 
			echo "<td>".ucfirst($proprietario[$riga['proprietario']])."</td>";		
			echo "<td>".mydatetime($riga['data_apertura'])."</td>"; 
		    echo "<td>$obi</td>";
			echo "</tr>";
			
	}

	

?>	

</table>


