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
			
			

			if((select_type($chiave) == 2 || select_type($chiave) == 19 || select_type($chiave) == 9) && $chiave != 'id') {
				echo '<div class="filter_box">';
				echo '  <label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Tutti</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
				echo '</div>';
			} else if( $chiave != 'id') { $cont = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; 
			echo '<div class="filter_box">';
			echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$cont.'" />'; echo '</div>';}

			
			
			} 
		
	}
	 ?>    

    <label> Periodo da</label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    <label> al </label>
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>
    
<?php
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
    $query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
?>

<table class="dati" summary="Dati">
  <tr>
   <th></th>
    <th>ID</th>
    <th>Oggetto</th>
    <th>Risposta</th>
    <th>Creata il / Da </th>
    <th></th>
    <th></th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$tipoColor = ($riga['tipo_faq'] == 0) ? 'purple' : 'turquoise';

			echo "<tr>"; 				
			echo "<td class=\"$tipoColor\"></td>"; 		
			echo "<td>".$riga['id']."</td>";
			echo "<td><h2><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\" >".$riga['oggetto']."</a></h2><span class=\"msg $tipoColor\">".$tipo_faq[$riga['tipo_faq']]."</span><span class=\"msg blue\">".$categoria_faq[$riga['categoria_faq']]."</span></td>";	
			echo "<td>".strip_tags(html_entity_decode(converti_txt($riga['risposta'])))."</td>";	
			echo "<td>".mydate($riga['data_creazione'])." / ".$proprietario[$riga['account_id']]."</td>";
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
