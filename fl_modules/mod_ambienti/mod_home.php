<?php

if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>



       

<div class="filtri" id="filtri"> 
<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  

<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>
<?php if(isset($_GET['start'])) echo '<input type="hidden" value="'.check($_GET['start']).'" name="start" />'; ?>

   <?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){
			
			

			if((select_type($chiave) == 2 || select_type($chiave) == 19 || select_type($chiave) == 9 || select_type($chiave) == 8 || select_type($chiave) == 12) && $chiave != 'id') {
				
								
				echo '<div class="filter_box">';
				echo '  <label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Non impostato</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
				echo '</div>';
			} else if( $chiave != 'id') { $valtxt = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; 
			echo '<div class="filter_box">';
			echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$valtxt.'" />'; echo '</div>';}

			
			
			} 
		
	}
	 ?>    

    
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

</div>

<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	

 		
	?>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <th style="width: 1%;"></th>
 <th><a href="./?ordine=1">Ambiente</a></th>
  <th>Location</th>
   <th>Misure</th>
   <th>Capienza</th>
  <th></th>
</tr>
<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$deleted = 0;
	$incomplete = 0;
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
			
			
			$elimina = "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";
			
			
				
			echo "<tr>"; 
			$sede = ucfirst($riga['nome_ambiente']);		
			echo "<td ><span class=\"Gletter\">".$riga['priority']."</span></td>"; 
			echo "<td><span class=\"color\"><h2>$sede</h2></span> ".$tipo_ambiente[$riga['tipo_ambiente']]."</td>";
			echo "<td class=\"hideMobile\">".$sede_id[$riga['sede_id']]."</td>"; 
			echo "<td class=\"hideMobile\">".$riga['larghezza_mt']." x ".$riga['profondita_mt']."</td>";
			echo "<td class=\"hideMobile\">".$riga['capienza_massima']."</td>"; 
			echo "<td  class=\"strumenti\">";
			echo "<a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Gestione Cliente \"> <i class=\"fa fa-search\"></i> </a>
			$elimina </td>";
			echo "<td><a href=\"../mod_tavoli/mod_tavoli_new.php?evento=0&ambiente_id=".$riga['id']."\" class=\"button fancybox fancybox-view\" data-fancybox-type=\"iframe\" title=\"Creazione Ambiente \"> Crea Template Tavoli</a>
			 </td>";		
		    echo "</tr>"; 
			}
		

	echo "</table>";
	

?>
