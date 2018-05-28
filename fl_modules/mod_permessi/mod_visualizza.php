<?php

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$_SESSION['account_manage'] = check($_GET['account']);
include("../../fl_inc/headers.php");

?>

<h1>Permessi</h1>

     

<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	
			
	?>

	 <table class="dati" summary="Dati">
      <tr>
      <th>Account</th>
 	  <th>Modulo</th>
      <th>Livello Accesso</th>
      </tr>
	  
	<?php 
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	$accesso = '';
	

	
	foreach($livello_accesso as $chiave => $valore){
	$class = ($riga['livello_accesso'] == $chiave) ? 'tab_green' : 'tab_gray';	
	$accesso .= '<a class="'.$class.'" style="color: white; padding: 5px; margin: 3px; display: inline-block;" href="mod_opera.php?modulo='.$riga['modulo_id'].'&valore='.$chiave.'">'.$valore.'</a>';
	}
	
	$modulo_name = (isset($modulo_id[$riga['modulo_id']])) ? $modulo_id[$riga['modulo_id']] : '<span class="msg red">Modulo non attivo</span>';

	echo "<tr>";	
			
			echo "<td>".@$account_id[$riga['account_id']]."</td>"; 
			echo "<td>".@$modulo_name."</td>"; 
			echo "<td>$accesso</td>"; 
			
				
		    echo "</tr>";
	}

	

?>	

</table>

<?php paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main); ?>