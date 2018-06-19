<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>

<?php if($_SESSION['usertype'] < 2) { // Serve solo se il modulo funziona in relazione all'anagrafica ?>
<form method="get">
  <?php $data_set->do_select('TABLE','fl_anagrafica','ragione_sociale','ANiD',@$_SESSION['anagrafica']); ?>
</form>
<?php } ?>

<h1><?php echo $module_title.' '.$new_button; ?></h1>


<div class="filtri" id="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  

<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>
<?php if(isset($_GET['start'])) echo '<input type="hidden" value="'.check($_GET['start']).'" name="start" />'; ?>

<?php 

	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){// Se sono contemplati nei filtri di base li gestisce
			
			echo '<div class="filter_box">';
			
			if(select_type($chiave) == 1 || select_type($chiave) == 3 ) { 
			$cont = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; 
			echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$cont.'" />'; 
			}
			
			if((select_type($chiave) == 2 || select_type($chiave) == 19)) {
				echo '<label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Tutti</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
			}
			
			echo '</div>';
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
    <th>Id</th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$azione = (0) ? '' : '';

			echo "<tr>"; 				
			echo "<td></td>";
			echo "<td>".$riga['id']."</td>";
			echo "<td>".$riga[$basic_filters[0]]."</td>";	
			echo "<td>".$riga[$basic_filters[1]]."</td>";	
			echo "<td>".mydatetime($riga['data_creazione'])."</td>";
			echo "<td>$azione</td>";
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
