<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }
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
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
	//	<a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 


?>




<table class="dati" summary="Dati">
  <tr>
    <th></th>
    <th>Evento</th>
    <th>Descrizione del Menù</th>
    <th>Food Cost</th>
    <th>Prezzo Vendita</th>
    <th>Aggiornato al</th>
    <th>Riepilogo</th>
    <th>Fabbisogno</th>
    <th></th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$attivo = ($riga['confermato'] == 0) ? 'tab_orange' : 'tab_green';
	$confermato = ($riga['confermato'] == 0) ? '<span class="msg orange"><a href="mod_opera.php?conferma='.$riga['id'].'" onclick="return conferma(\'Confermare menù?\');"">Da Confermare</a></span>' : '<span class="msg green">Confermato</span>';
	$statusMenu = @$stato_menu_portate[$riga['stato_menu_portate']];
	$evento = GRD($tables[6],$riga['evento_id']);

			echo "<tr>"; 				
			echo "<td class=\"$attivo\"></td>";
			echo "<td>$confermato<br>$statusMenu</td>";	
			echo "<td><h2>".$evento['titolo_ricorrenza']."</h2> ".$riga['descrizione_menu']."</td>";	
			echo "<td>&euro; ".$riga['food_cost']."</td>";	
			echo "<td>&euro; ".$riga['prezzo_base']."</td>";
			echo "<td>".mydatetime($riga['data_aggiornamento'])."</td>";
			echo "<td><a style=\"color: $coloreEvento\" data-fancybox-type=\"iframe\" class=\"facyboxParent\" href=\"./mod_configura.php?preview&menuId=".$riga['evento_id']."&menuId=".$riga['id']."\" title=\"Ripilogo e Configurazione\" > <i class=\"fa fa fa-clipboard\" aria-hidden=\"true\"></i></a></td>";
			echo "<td><a style=\"color: $coloreEvento\" data-fancybox-type=\"iframe\" class=\"facyboxParent\" href=\"./mod_fabbisogno.php?evento_id=".$riga['evento_id']."&menuId=".$riga['id']."\" title=\"Calcola Fabbisogno\" > <i class=\"fa fa-truck\" aria-hidden=\"true\"></i></a></td>";

			echo "<td>
			<a href=\"".ROOT.$cp_admin."fl_app/MenuElegance/?menuId=".$riga['id']."\" title=\"Componi Menu\" ><i class=\"fa fa-cutlery\" aria-hidden=\"true\"></i></a>
			<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_stampa.php?menuId=".$riga['id']."\" title=\"Stampa Menu\" ><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>
			<a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
