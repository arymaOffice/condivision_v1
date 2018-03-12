<h1>Corsi Formazione</h1>
<div class="filtri" style="height: auto;"> </div>


<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
			
	?>
		  
	<?php 
	
	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	

	$ecm = ($riga['is_ecm'] == 1) ? "<span class=\"msg blue\">ECM</span>" : '';
	$destinatari = ($riga['is_ecm'] == 1) ?  ucfirst($riga['members']).'<br>ECM: '.$destinatari_ecm[$riga['destinatari_ecm']].' (cr. '.$riga['crediti_studente_ecm'].')' :  ucfirst($riga['members']); 
	$photo = ($riga['photo'] != '') ? '<img src="'.$riga['photo'] .'" alt="Photo" style="max-width: 350px;">' : '<i class="fa fa-camera"></i>';
	
	echo "<div class=\"dashboard_div\">"; 	
	 
			echo '<p class="folder_icon"><a href="../mod_didattica/?cId='.base64_encode($riga['id']).'">'.$photo.'</a></p>';
			echo '<h1><strong>'.ucfirst($riga['title']).'</strong></h1>
			<br>'.$course_category[$riga['course_category']].' '.$ecm.'			
			<a href="../mod_didattica/?cId='.base64_encode($riga['id']).'"><i class="fa fa-folder-open"></i></a>'; 
			
	 echo '</div>';
	}

	

?>	


<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
