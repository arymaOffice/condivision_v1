<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");


 ?>


<body style=" background: #FFFFFF;">
<div id="container" >
<div id="content_scheda">


<h1>Crea da modello</h1>


<?php	
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine";
	$risultato = mysql_query($query, CONNECT);


	if(mysql_affected_rows() == 0) { echo "<p>Nessun Elemento</p>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
			$colore = ($riga['attivo'] == 0) ? "class=\"tab_red\"" : "class=\"tab_green\"";  
			

			echo "<div class=\"col-sm-3 deco\">"; 				
			echo "<span $colore></span>"; 		
			echo "<h2>".$riga['titolo']."</h2>";	
			echo "<span class=\"msg blue\">".$tipo_modello[$riga['tipo_modello']]."</span> <span class=\"msg gray\">".$categoria_modello[$riga['categoria_modello']]."</span>";	
			echo "<p>Rev. ".mydatetime($riga['data_aggiornamento'])."</p>";
			echo "<p>Valore: <strong>&euro; ".numdec($riga['valore_base'],2)."</strong></p>";
			echo "<p><a href=\"../mod_preventivi/mod_inserisci.php?MoD=".base64_encode($riga['id'])."&POiD=".check($_GET['POiD'])."&id=1\" title=\"Seleziona\"> Crea da modello </a></p>"; 
		    echo "</div>";
	}

	
		    echo "<div class=\"col-sm-3 deco\">"; 				
			echo "<span></span>"; 		
			echo "<h2>Vuoto</h2>";	
			echo "<span class=\"msg blue\">Crea da zero</span>";	
			echo "<p></p>";
			echo "<p></p>";
			echo "<p><a href=\"../mod_preventivi/mod_inserisci.php?POiD=".check($_GET['POiD'])."&id=1\" title=\"Seleziona\"> Crea nuovo </a></p>"; 
		    echo "</div>";


?>



</div>
</div>
</body></html>
