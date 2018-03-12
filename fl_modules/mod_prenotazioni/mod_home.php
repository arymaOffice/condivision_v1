<?php 
if($_SESSION['usertype'] < 2) { ?>

<form method="get">
  <?php $data_set->do_select('TABLE','fl_anagrafica','ragione_sociale','ANiD',@$_SESSION['anagrafica']); ?>
</form>
<?php } ?>


<div class="filtri" id="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  
<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>

   <?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){
			
			echo '<div class="filter_box">';
			
			if(select_type($chiave) != 19 && select_type($chiave) != 5 && select_type($chiave) != 2 && $chiave != 'id') { $cont = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$cont.'" />'; }
			
			if((select_type($chiave) == 2 || select_type($chiave) == 19) && $chiave != 'id') {
				echo '  <label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Tutti</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
			}
			echo '</div>';
			
			} 
		
	}
	 ?>    

    <label> Data da</label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    <label> al </label>
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
<input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>
<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
			
	?>
<table class="dati" summary="Dati">
  <tr>
    <th></th>
    <th>ID</th>
    <th>Orario,Data, Tipo di prenotazione</th>
    <th>Cliente</th>
    <th>Rif.</th>
     <th>Ricevuto</th>
    <?php if(!isset($_GET['parent_id'])) echo  '<th>Affiliato</th>'; ?>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php 
	
	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$attivo = ($riga['stato_prenotazione'] == 1) ? "class=\"tab_green\"" : "class=\"tab_red\" ";
	$attivo2 = ($riga['stato_prenotazione'] == 1) ? "class=\"msg green\"" : "class=\"msg red\" ";
	$azione = ($riga['stato_prenotazione'] > 0) ? '<span '.$attivo2.'>'.$stato_prenotazione[$riga['stato_prenotazione']].'</span>' : '<a class="button green" href="mod_opera.php?stato_prenotazione=1&id='.$riga['id'].'">Conferma</a><a class="button gray" href="mod_opera.php?stato_prenotazione=2&id='.$riga['id'].'">Rifiuta</a>';

			echo "<tr>"; 				
			echo "<td $attivo></td>";
			echo "<td>".$riga['id']."</td>";
			echo "<td><h1>".substr($riga['orario'],0,5)."</h1>".mydate($riga['data_prenotazione'])."<br>".ucfirst($tipo_prenotazione[$riga['tipo_prenotazione']])."</a></td>";	
			echo "<td><strong>".ucfirst($riga['nominativo'])."</strong> <a href=\"mailto:".$riga['email_nominativo']."\">".$riga['email_nominativo']."</a>  ".$riga['telefono_nominativo']."<br>".strip_tags(converti_txt($riga['commento']))."</a></td>";	
			echo "<td>".$riga['riferimento']."</td>";
			echo "<td>".mydatetime($riga['data_creazione'])."</td>";
			echo "<td>".$anagrafica_id[$riga['anagrafica_id']]."</td>";
			echo "<td>$azione</td>";
			echo "<td><a href=\"mod_inserisci.php?$type&item_rel=0&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		    echo "</tr>";
	}

	
	

?>
</table>
<p>NB: Il riferimento può essere il numero di camera, il codice coupon o un altro identificativo impostato in fase di prenotazione.</p>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
