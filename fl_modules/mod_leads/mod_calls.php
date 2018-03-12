<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$id = check(@$_GET['id']);
$tab_id = 74;

include("../../fl_inc/headers.php"); 
 ?>

<body style=" background: #FFFFFF;">

<h1>Log Attivit&agrave;</h1>   
 
  <p><a href="javascript:location.reload();" class="button">Aggiorna e Rivedi</a>
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
     <th scope="col"></th>
       <th scope="col">Data</th>
       <th scope="col">Azione</th>
       <th scope="col">Esito</th>
          <th scope="col">Note</th>
       <th scope="col">Account</th>
      </tr>
	<?php 
	
		$query = "SELECT * FROM `fl_calls_log` WHERE `tab_id` = $tab_parent_id AND `item_id` = $id ORDER BY data_creazione DESC;";
		$risultato = mysql_query($query, CONNECT);


while ($riga = mysql_fetch_array($risultato)) 
	{
		if($riga['issue'] == 0) { $colore = "class=\"tab_blue\"";  }
		if($riga['issue'] == 1) { $colore = "class=\"tab_green\"";  }
		if($riga['issue'] == 2) { $colore = "class=\"tab_blue\"";  } 
		if($riga['issue'] == 3)  { $colore = "class=\"tab_orange\"";  }
		if($riga['issue'] == 4) { $colore = "class=\"tab_red\"";  }
		if($riga['issue'] == 5) { $colore = "class=\"tab_green\"";  }
		if($riga['issue'] == 6) { $colore = "class=\"tab_green\"";  }

		
			
    
	
			echo "<tr><td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td>".mydatetime($riga['data_creazione'])."</td>"; 
			echo "<td>".@$item_action[$riga['item_action']]."</td>";	
			echo "<td>".@$item_issue[$riga['issue']]."</td>";	
			echo "<td><input type=\"text\" value=\"".$riga['note']."\" name=\"note\" class=\"updateField\" data-rel=\"".$riga['id']."\" /></td>";	
			echo "<td>".@$proprietario[$riga['operatore']]."</td>";	
		    echo "</tr>";
		
	
			
	}

	echo "</table>";
	
?>
