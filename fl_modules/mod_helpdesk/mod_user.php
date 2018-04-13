<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];


?>


<br class="clear" />

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
			
	
			
			if((select_type($chiave) == 2 || select_type($chiave) == 19 || select_type($chiave) == 9)) {
				echo '<label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Tutti</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
			} else { 
			$cont = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; 
			echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$cont.'" />'; 
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
    <th># Id</th>
    <th>Oggetto</th>
    <th>Riferimenti Ticket</th>
    <th>Data Apertura</th>
    <th></th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "</table><h3>Nessun Elemento</h3><table>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$azione = (0) ? '' : '';
	$colors = array('gray','green','orange');
	$color = $colors[$riga['stato_hd']];
	
	$operatore = ($riga['operatore'] < 1 || $riga['operatore'] == $riga['account_id']) ?  'Da prendere in carico' : $stato_hd[$riga['stato_hd']].' e preso in carico da: '.$proprietario[$riga['operatore']];
	if($riga['stato_hd'] == 0) $operatore = "Chiuso alle ".mydatetime($riga['data_chiusura']);
			
			echo "<tr>";
			echo "<td class=\"$color\"></td>";
			echo "<td>#".$riga['id']."</td>";
			echo "<td><h2><a href=\"mod_visualizza.php?id=".$riga['id']."\" title=\"".strip_tags(html_entity_decode(converti_txt($riga['messaggio'])))."\" >".$riga['oggetto']."</a></h2>
			$operatore  <span class=\"msg blue\">".@$tipologia_hd[$riga['tipologia_hd']]."</span><span class=\"msg orange\">".@$priorita[$riga['priorita']]."</span></td>";	
			
				echo "<td>".ucfirst($riga['nominativo'])." (".$riga['email_contatto'].") / (".$riga['telefono_contatto'].")<br /><span style=\"color: #cc0000;\">Username: ".ucfirst($riga['username'])."</span>"."</td>";		
echo "<td>".mydatetime($riga['data_creazione'])."</td>";
			echo "<td><a href=\"mod_visualizza.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
